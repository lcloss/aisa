<?php
/** Run on the local development site with wp eval-file. Restores the option. */
defined( 'ABSPATH' ) || exit;

use Aisa\Core\GTM;

$original = get_option( 'aisa_gtm_id', null );
$user_id  = get_current_user_id();
$query    = $GLOBALS['wp_query'];
$checks   = 0;
$temporary_users = array();
$failure = null;
$assert   = static function ( $condition, $message ) use ( &$checks ) {
	if ( ! $condition ) {
		throw new RuntimeException( $message );
	}
	++$checks;
	WP_CLI::log( 'PASS ' . $message );
};
$capture  = static function ( $method ) {
	ob_start();
	GTM::$method();
	return ob_get_clean();
};

try {
	GTM::register_settings();
	wp_set_current_user( 0 );
	$GLOBALS['wp_query'] = new WP_Query();
	$assert( 1 === has_action( 'wp_head', array( GTM::class, 'render_consent' ) ), 'Consent priority 1' );
	$assert( 5 === has_action( 'wp_head', array( GTM::class, 'render_container' ) ), 'GTM priority 5' );
	foreach ( array( 'gtm-ABC', 'GTM-A<script>', "GTM-ABC\n", array( 'GTM-ABC' ) ) as $invalid ) {
		$assert( '' === GTM::sanitize_id( $invalid ), 'Invalid ID rejected' );
	}
	delete_option( 'aisa_gtm_id' );
	foreach ( array( 'render_consent', 'render_container', 'render_noscript' ) as $method ) {
		$assert( '' === $capture( $method ), 'No ID: no output from ' . $method );
	}
	update_option( 'aisa_gtm_id', 'GTM-XXXXXXX' );
	$assert( GTM::enabled(), 'Anonymous request enabled' );
	$consent = $capture( 'render_consent' );
	$assert( 6 === substr_count( $consent, "'denied'" ) && str_contains( $consent, "security_storage:'granted'" ), 'Consent defaults: six denied, security granted' );
	$assert( str_contains( $capture( 'render_noscript' ), 'ns.html?id=GTM-XXXXXXX' ), 'Noscript container' );
	$response = wp_remote_get( home_url( '/' ) );
	$assert( ! is_wp_error( $response ) && 200 === wp_remote_retrieve_response_code( $response ), 'HTTP home responds 200' );
	$html = wp_remote_retrieve_body( $response );
	$assert( false !== strpos( $html, "consent','default'" ) && strpos( $html, "consent','default'" ) < strpos( $html, 'googletagmanager.com/gtm.js' ), 'HTTP consent precedes GTM' );
	$assert( str_contains( $html, 'ns.html?id=GTM-XXXXXXX' ), 'HTTP body includes noscript' );
	$assert( ! str_contains( $html, 'gtm-forms.js' ), 'CF7 inactive: no listener asset' );
	foreach ( array( 'administrator', 'editor', 'subscriber' ) as $role ) {
		$users = get_users( array( 'role' => $role, 'number' => 1 ) );
		if ( empty( $users ) ) {
			$id = wp_insert_user( array( 'user_login' => 'aisa-gtm-' . wp_generate_uuid4(), 'user_pass' => wp_generate_password(), 'role' => $role ) );
			$assert( ! is_wp_error( $id ), 'Temporary account for ' . $role );
			$temporary_users[] = $id;
		} else {
			$id = $users[0]->ID;
		}
		wp_set_current_user( $id );
		$assert( 'subscriber' === $role ? GTM::enabled() : ! GTM::enabled() && '' === $capture( 'render_container' ), $role . ' tracking eligibility' );
	}
	wp_set_current_user( 0 );
	$GLOBALS['wp_query']->is_preview = true;
	$assert( ! GTM::enabled(), 'Preview excluded' );
	$GLOBALS['wp_query']->is_preview = false;
	add_filter( 'aisa_gtm_enabled', '__return_false' );
	$assert( ! GTM::enabled(), 'Filter disables tracking' );
	remove_filter( 'aisa_gtm_enabled', '__return_false' );
	delete_option( 'aisa_gtm_id' );
	$html = wp_remote_retrieve_body( wp_remote_get( home_url( '/' ) ) );
	$assert( ! str_contains( $html, 'googletagmanager' ) && ! str_contains( $html, 'aisa-consent-default' ), 'HTTP without ID: zero GTM and consent scripts' );
	define( 'AISA_GTM_ID', 'GTM-CONSTANT' );
	update_option( 'aisa_gtm_id', 'GTM-OPTION' );
	$assert( 'GTM-CONSTANT' === GTM::container_id(), 'Constant overrides option' );
	define( 'WPCF7_VERSION', 'test' );
	GTM::enqueue_forms();
	$assert( wp_script_is( 'aisa-gtm-forms', 'enqueued' ) && 1 === wp_scripts()->get_data( 'aisa-gtm-forms', 'group' ), 'CF7 active: listener enqueued in footer' );
} catch ( Throwable $error ) {
	$failure = $error->getMessage();
} finally {
	null === $original ? delete_option( 'aisa_gtm_id' ) : update_option( 'aisa_gtm_id', $original );
	wp_set_current_user( $user_id );
	$GLOBALS['wp_query'] = $query;
	require_once ABSPATH . 'wp-admin/includes/user.php';
	foreach ( $temporary_users as $id ) {
		wp_delete_user( $id );
	}
}
if ( $failure ) {
	WP_CLI::error( $failure );
}
WP_CLI::success( $checks . ' GTM checks passed; original option restored.' );
