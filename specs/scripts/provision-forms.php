<?php
/** wp --path=public_html eval-file specs/scripts/provision-forms.php */
defined( 'ABSPATH' ) || exit;
if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
	exit;
}
foreach ( array( 'contact-form-7/wp-contact-form-7.php', 'wp-mail-smtp/wp_mail_smtp.php', 'honeypot/wp-armour.php' ) as $plugin ) {
	if ( ! is_plugin_active( $plugin ) ) {
		WP_CLI::error( 'Plugin obrigatório inactivo: ' . $plugin );
	}
}
if ( ! defined( 'AISA_FORMS_RECIPIENT' ) || ! is_email( AISA_FORMS_RECIPIENT ) || ! defined( 'WPMS_MAIL_FROM' ) ) {
	WP_CLI::error( 'Configura AISA_FORMS_RECIPIENT e WPMS_* no wp-config.php, fora do Git.' );
}

// No email content, debug transcript, tracking, summaries or deferred mail queue.
$smtp = get_option( 'wp_mail_smtp', array() );
$smtp['logs'] = array( 'enabled' => false, 'log_email_content' => false );
$smtp['debug_events'] = array( 'email_debug' => false );
$smtp['general']['summary_report_email_disabled'] = true;
$smtp['general']['optimized_email_sending_enabled'] = false;
update_option( 'wp_mail_smtp', $smtp );
update_option( 'wpa_error_message', 'Não foi possível validar o envio. Ative o JavaScript e tente novamente.' );
update_option( 'wpa_disable_test_widget', 'yes' );

foreach ( array( 'voluntariado' => 'Voluntariado', 'contacto' => 'Contacto' ) as $kind => $title ) {
	$existing = get_page_by_path( 'aisa-' . $kind, OBJECT, 'wpcf7_contact_form' );
	$form = $existing ? wpcf7_contact_form( $existing->ID ) : WPCF7_ContactForm::get_template( array( 'title' => $title, 'locale' => 'pt_PT' ) );
	$form->set_title( $title );
	$properties = $form->get_properties();
	$properties['form'] = file_get_contents( AISA_CORE_PATH . 'forms/' . $kind . '.html' );
	$subject = 'voluntariado' === $kind ? 'Candidatura a voluntário — [nome]' : 'Contacto pelo site — [nome]';
	$body = "Nome: [nome]\nTelefone: [telefone]\nE-mail: [email]\n";
	if ( 'voluntariado' === $kind ) {
		$body .= "Disponibilidade: [disponibilidade]\nÁrea de interesse: [area]\nTem 18 anos ou mais?: [maior18]\n";
	}
	$body .= "Mensagem: [mensagem]\nConsentimento: [consentimento]\n";
	$properties['mail'] = array(
		'active' => true,
		'recipient' => AISA_FORMS_RECIPIENT,
		'sender' => 'Site AISA <' . WPMS_MAIL_FROM . '>',
		'subject' => $subject,
		'body' => $body,
		'additional_headers' => '', // Supplied conditionally by Forms::mail_components().
		'attachments' => '',
		'use_html' => false,
		'exclude_blank' => false,
	);
	$properties['mail_2']['active'] = false;
	$properties['additional_settings'] = "aisa_form: $kind\nacceptance_as_validation: on\nskip_mail: off\ndo_not_store: true";
	$properties['messages'] = array_merge( $properties['messages'], array(
		'mail_sent_ok' => 'voluntariado' === $kind ? 'Obrigado! Recebemos a sua candidatura. Vamos entrar em contacto em breve, por telefone ou e-mail.' : 'Obrigado! Recebemos a sua mensagem. Vamos entrar em contacto em breve, por telefone ou e-mail.',
		'mail_sent_ng' => 'Não foi possível enviar. Tente novamente ou ligue 214 857 700.',
		'validation_error' => 'Verifique os campos assinalados e tente novamente.',
		'spam' => 'Não foi possível validar o envio. Tente novamente ou ligue 214 857 700.',
		'accept_terms' => 'Aceite o contacto da AISA para podermos responder ao seu pedido.',
		'invalid_required' => 'Preencha este campo.',
		'invalid_email' => 'Indique um endereço de e-mail válido.',
		'invalid_tel' => 'Indique um número de telefone válido.',
	) );
	$form->set_properties( $properties );
	$form->save();
	wp_update_post( array( 'ID' => $form->id(), 'post_name' => 'aisa-' . $kind ) );
	$slug = 'voluntariado' === $kind ? 'voluntariado' : 'contactos';
	$shortcode = sprintf( '[contact-form-7 id="%d" title="%s" html_title="%s" html_class="form-%s"]', $form->id(), $title, $title, $kind );
	$block = '<!-- wp:shortcode -->' . $shortcode . '<!-- /wp:shortcode -->';
	$page = get_page_by_path( $slug );
	if ( ! $page ) {
		$content = $block;
		if ( 'contacto' === $kind ) {
			$content = '<!-- wp:paragraph --><p>Prefere falar? Ligue <a href="tel:+351214857700">214 857 700</a></p><!-- /wp:paragraph -->' . "\n" . $content;
		}
		$id = wp_insert_post( array( 'post_type' => 'page', 'post_status' => 'draft', 'post_title' => 'contacto' === $kind ? 'Contactos' : $title, 'post_name' => $slug, 'post_content' => $content ), true );
		if ( is_wp_error( $id ) ) {
			WP_CLI::error( $id->get_error_message() );
		}
	} elseif ( false === strpos( $page->post_content, 'form-' . $kind ) ) {
		WP_CLI::error( 'Página existente preservada: insere manualmente ' . $shortcode );
	} else {
		// Update only our existing shortcode; preserve the page and its editorial text.
		$content = preg_replace( '/\[contact-form-7\b[^\]]*html_class="form-' . $kind . '"[^\]]*\]/', $shortcode, $page->post_content );
		if ( $content !== $page->post_content ) {
			wp_update_post( array( 'ID' => $page->ID, 'post_content' => $content ) );
		}
	}
	WP_CLI::success( $title . ': formulário ' . $form->id() . '; página /' . $slug . '/ em rascunho ou preservada.' );
}
