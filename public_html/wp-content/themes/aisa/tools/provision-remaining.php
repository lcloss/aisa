<?php
/** Repeatable LOCAL beta provisioning: wp --quiet --path=public_html eval-file .../tools/provision-remaining.php */
defined( 'ABSPATH' ) || exit;
if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) { exit; }
if ( 'local' !== wp_get_environment_type() || ! str_ends_with( wp_parse_url( home_url(), PHP_URL_HOST ), '.test' ) || get_option( 'blog_public' ) ) {
	WP_CLI::error( 'Apenas beta local .test não indexável: A10 e os restantes gates de lançamento continuam pendentes.' );
}
if ( 'aisa' !== get_stylesheet() || ! taxonomy_exists( 'aisa_valencia' ) ) { WP_CLI::error( 'Ativa aisa e aisa-core primeiro.' ); }
$pages = require __DIR__ . '/remaining-content.php';
$manifest = get_option( 'aisa_remaining_manifest', array( 'before' => array(), 'pages' => array() ) );
$aliases = array( 'saude-e-reabilitacao' => 'qualidade-de-vida', 'novas-instalacoes' => 'novos-projectos', 'politica-de-privacidade' => 'politica-privacidade' );
foreach ( $pages as [ $key, $title, $slug, $template, $valencia, $content, $source ] ) {
	$page = get_page_by_path( $slug );
	if ( ! $page && isset( $aliases[ $slug ] ) ) { $page = get_page_by_path( $aliases[ $slug ] ); }
	if ( $page && ! isset( $manifest['before'][ $page->ID ] ) ) {
		$manifest['before'][ $page->ID ] = array( 'post' => $page->to_array(), 'meta' => get_post_meta( $page->ID ), 'valencia' => wp_get_object_terms( $page->ID, 'aisa_valencia', array( 'fields' => 'slugs' ) ) );
		update_option( 'aisa_remaining_manifest', $manifest, false );
	}
	$old_hash = $page ? get_post_meta( $page->ID, '_aisa_remaining_hash', true ) : '';
	$owned = $page && $old_hash && hash_equals( $old_hash, hash( 'sha256', $page->post_content ) );
	// Keep imported/editorial text and IDs. Replace only our unchanged generated text or the forms-only seed.
	$form_seed = $page && in_array( $slug, array( 'contactos', 'voluntariado' ), true ) && strlen( wp_strip_all_tags( strip_shortcodes( $page->post_content ) ) ) < 120;
	$wp_privacy_seed = $page && 'politica-privacidade' === $page->post_name && 'draft' === $page->post_status && str_contains( $page->post_content, 'Texto sugerido' );
	$preserve = $page && trim( $page->post_content ) && ! $owned && ! $form_seed && ! $wp_privacy_seed;
	if ( $preserve && ! in_array( $key, array( 'P14', 'P26', 'P28' ), true ) ) {
		$content = $page->post_content;
		WP_CLI::log( 'Texto editorial preservado: ' . $slug );
	} elseif ( $preserve && in_array( $key, array( 'P26', 'P28' ), true ) ) {
		WP_CLI::warning( 'Texto legal anterior guardado no manifesto; beta mostra apenas o espaço reservado.' );
	}
	if ( 'page-servico' === $template ) {
		if ( ! str_contains( $content, 'O valor depende do rendimento' ) ) { $content .= aisa_page_text( 'O valor depende do rendimento do agregado familiar, segundo as regras da Segurança Social. Ligue-nos e fazemos as contas consigo.' ); }
	}
	foreach ( array( 'P02' => 'servicos-grupos', 'P13' => 'provas', 'P14' => 'pessoas', 'P16' => 'logotipos', 'P18' => 'contacto', 'P19' => 'irs-donativo' ) as $pattern_key => $pattern_slug ) {
		if ( $key === $pattern_key && ! str_contains( $content, 'aisa/' . $pattern_slug ) ) { $content .= '<!-- wp:pattern {"slug":"aisa/' . $pattern_slug . '"} /-->'; }
	}
	if ( 'P17' === $key && ! str_contains( $content, 'wp:aisa/documentos' ) ) { $content .= '<!-- wp:aisa/documentos /-->'; }
	foreach ( array( 'P24' => 'voluntariado', 'P25' => 'contacto' ) as $form_key => $kind ) {
		if ( $key === $form_key && ! str_contains( $content, 'form-' . $kind ) && ! str_contains( $content, 'aisa/contacto-completo' ) ) { $content .= aisa_contact_form_block( $kind ); }
	}
	// In post_content, insert actual editable blocks, as Gutenberg does when inserting a pattern.
	$content = serialize_blocks( resolve_pattern_blocks( parse_blocks( $content ) ) );
	$data = array( 'post_type' => 'page', 'post_title' => $title, 'post_name' => $slug, 'post_status' => 'publish', 'post_content' => $content );
	if ( $page ) { $data['ID'] = $page->ID; }
	$id = wp_insert_post( wp_slash( $data ), true );
	if ( is_wp_error( $id ) ) { WP_CLI::error( $id->get_error_message() ); }
	update_post_meta( $id, '_wp_page_template', $template );
	update_post_meta( $id, '_aisa_remaining_hash', hash( 'sha256', $content ) );
	update_post_meta( $id, '_aisa_remaining_spec', 'wp-04-02-04' );
	if ( $source ) { update_post_meta( $id, '_aisa_content_source', 'https://www.aisaipss.pt/' . $source . '/' ); }
	wp_set_object_terms( $id, $valencia ? array( $valencia ) : array(), 'aisa_valencia' );
	if ( in_array( $key, array( 'P26', 'P28', 'P29' ), true ) ) { update_post_meta( $id, '_aisa_launch_validation_required', true ); }
	// Dedicated source image for the building, reused on subsequent runs.
	if ( 'P06' === $key && ! get_post_thumbnail_id( $id ) ) {
		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';
		require_once ABSPATH . 'wp-admin/includes/file.php';
		$file = wp_upload_bits( 'aisa-novas-instalacoes.jpg', null, file_get_contents( dirname( ABSPATH ) . '/assets/capa_facebook.jpg' ) );
		if ( $file['error'] ) { WP_CLI::error( $file['error'] ); }
		$attachment = wp_insert_attachment( array( 'post_mime_type' => 'image/jpeg', 'post_title' => 'Novas instalações da AISA', 'post_status' => 'inherit' ), $file['file'], $id, true );
		if ( is_wp_error( $attachment ) ) { WP_CLI::error( $attachment->get_error_message() ); }
		wp_update_attachment_metadata( $attachment, wp_generate_attachment_metadata( $attachment, $file['file'] ) );
		update_post_meta( $attachment, '_wp_attachment_image_alt', 'Obra das novas instalações da AISA' );
		set_post_thumbnail( $id, $attachment );
	}
	$manifest['pages'][ $key ] = $id;
	update_option( 'aisa_remaining_manifest', $manifest, false );
	WP_CLI::success( $key . ': ' . $id . ' /' . $slug . '/ · ' . $template . ' · ' . ( $valencia ?: 'sem valência' ) );
}
// P27 stays pending 02-02. Its existing clearly labelled local placeholder must be reachable from the footer.
$cookies = get_page_by_path( 'politica-de-cookies' );
if ( $cookies && 'draft' === $cookies->post_status ) {
	$manifest['before'][ $cookies->ID ] = array( 'post' => $cookies->to_array(), 'meta' => get_post_meta( $cookies->ID ) );
	wp_update_post( array( 'ID' => $cookies->ID, 'post_status' => 'publish' ) );
}
update_option( 'aisa_remaining_manifest', $manifest, false );
wp_get_theme()->delete_pattern_cache();
flush_rewrite_rules( false );
WP_CLI::success( 'Beta local pronta. Não constitui aprovação de A10 nem dos outros gates de lançamento.' );
