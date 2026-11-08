<?php
/**
 * Plugin Name: AISA Core
 * Description: Conteúdos e regras editoriais da AISA.
 * Version: 0.1.0
 * Requires at least: 6.2
 * Requires PHP: 8.1
 * Text Domain: aisa
 */
namespace Aisa\Core;

defined( 'ABSPATH' ) || exit;
define( 'AISA_CORE_VERSION', '0.1.0' );
define( 'AISA_CORE_PATH', plugin_dir_path( __FILE__ ) );
define( 'AISA_CORE_URL', plugin_dir_url( __FILE__ ) );
foreach ( array( 'post-types', 'taxonomies', 'meta', 'valencia', 'featured-required', 'schema-event', 'blocks' ) as $aisa_file ) {
	require_once AISA_CORE_PATH . 'includes/class-' . $aisa_file . '.php';
}
require_once AISA_CORE_PATH . 'includes/helpers.php';
Post_Types::boot();
Taxonomies::boot();
Meta::boot();
Valencia::boot();
Featured_Required::boot();
Schema_Event::boot();
Blocks::boot();
register_activation_hook( __FILE__, static function () {
	Post_Types::register();
	Taxonomies::register();
	Taxonomies::seed();
	flush_rewrite_rules();
} );
register_deactivation_hook( __FILE__, static function () {
	foreach ( array( 'aisa_evento', 'aisa_depoimento', 'aisa_documento' ) as $aisa_type ) {
		unregister_post_type( $aisa_type );
	}
	flush_rewrite_rules();
} );
