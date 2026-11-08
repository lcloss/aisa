<?php
/** php public_html/wp-content/plugins/aisa-core/tests/forms.php */
if ( 'cli' !== PHP_SAPI ) {
	exit;
}
require dirname( __DIR__, 4 ) . '/wp-load.php';
if ( 'local' !== wp_get_environment_type() ) {
	exit( "Use only on the local development site.\n" );
}

use Aisa\Core\Forms;

$checks = 0;
$assert = static function ( $condition, $message ) use ( &$checks ) {
	if ( ! $condition ) {
		throw new RuntimeException( $message );
	}
	++$checks;
	echo 'PASS ' . $message . PHP_EOL;
};
foreach ( array( 'voluntariado', 'contacto' ) as $kind ) {
	$post = get_page_by_path( 'aisa-' . $kind, OBJECT, 'wpcf7_contact_form' );
	$form = wpcf7_contact_form( $post->ID );
	$assert( $kind === Forms::kind( $form ), $kind . ': custom validation scope' );
	$assert( ! $form->prop( 'mail_2' )['active'], $kind . ': no autoresponder' );
	$assert( $form->is_true( 'do_not_store' ), $kind . ': storage explicitly disabled' );
	$html = do_shortcode( '[contact-form-7 id="' . $post->ID . '" html_class="form-' . $kind . '"]' );
	$tags = new WP_HTML_Tag_Processor( $html );
	$found = false;
	while ( $tags->next_tag( 'INPUT' ) ) {
		if ( 'consentimento' === $tags->get_attribute( 'name' ) ) {
			$found = true;
			$assert( null === $tags->get_attribute( 'checked' ), $kind . ': consent unchecked' );
			$assert( 'true' === $tags->get_attribute( 'aria-required' ), $kind . ': consent required' );
		}
	}
	$assert( $found, $kind . ': consent exists' );
	$assert( str_contains( $html, 'cf-turnstile' ) && str_contains( $html, 'aria-live="polite"' ), $kind . ': native Turnstile and live response' );
	$assert( AISA_FORMS_RECIPIENT === $form->prop( 'mail' )['recipient'], $kind . ': configured recipient' );
	$assert( $form->pref( 'acceptance_as_validation' ) === 'on', $kind . ': consent has field error' );
}
global $wpdb;
$table = $wpdb->prefix . 'wpmailsmtp_debug_events';
foreach ( array( "INSERT INTO `$table` (content) VALUES ('synthetic diagnostic')", "UPDATE `$table` SET content = 'synthetic diagnostic'", "REPLACE INTO `$table` (content) VALUES ('synthetic diagnostic')" ) as $query ) {
	$assert( '' === apply_filters( 'query', $query ), 'SMTP diagnostic write blocked' );
}
$query = 'SELECT COUNT(*) FROM `' . $table . '`';
$assert( $query === apply_filters( 'query', $query ), 'Other SQL unaffected' );
foreach ( array( 'wp_mail_smtp_debug', 'wp_mail_smtp_email_sending_debug' ) as $option ) {
	$assert( array() === apply_filters( 'pre_update_option_' . $option, array( 'synthetic diagnostic' ) ), 'SMTP diagnostic option blocked' );
}
$assert( ! defined( 'WPCF7_LOAD_CSS' ) || ! WPCF7_LOAD_CSS, 'CF7 CSS disabled' );
$assert( ! WPCF7_AUTOP, 'CF7 automatic paragraphs disabled' );
$assert( ! post_type_exists( 'flamingo_inbound' ), 'No submission storage post type' );
$assert( ! $wpdb->get_var( "SHOW TABLES LIKE '%flamingo%'" ), 'No Flamingo tables' );
echo $checks . " configuration/privacy checks passed.\n";
