<?php
/** AISA block theme. */
defined( 'ABSPATH' ) || exit;

function aisa_setup() {
	load_theme_textdomain( 'aisa', get_template_directory() . '/languages' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'post-thumbnails' );
	add_editor_style( array( 'assets/css/base.css', 'assets/css/components.css', 'assets/css/theme.css' ) );
	remove_theme_support( 'core-block-patterns' );
	register_nav_menus( array( 'principal' => __( 'Principal', 'aisa' ), 'rodape' => __( 'Rodapé', 'aisa' ) ) );
}
add_action( 'after_setup_theme', 'aisa_setup' );

function aisa_enqueue_assets() {
	$version = wp_get_theme()->get( 'Version' );
	wp_enqueue_style( 'aisa-base', get_theme_file_uri( 'assets/css/base.css' ), array(), $version );
	wp_enqueue_style( 'aisa-components', get_theme_file_uri( 'assets/css/components.css' ), array( 'aisa-base' ), $version );
	wp_enqueue_style( 'aisa-theme', get_theme_file_uri( 'assets/css/theme.css' ), array( 'aisa-components' ), $version );
	if ( defined( 'WPCF7_VERSION' ) ) {
		wp_enqueue_style( 'aisa-forms', get_theme_file_uri( 'assets/css/forms-cf7.css' ), array( 'aisa-theme' ), $version );
	}
	wp_enqueue_script( 'aisa-nav', get_theme_file_uri( 'assets/js/nav.js' ), array(), $version, array( 'strategy' => 'defer', 'in_footer' => true ) );
	wp_enqueue_script( 'aisa-search', get_theme_file_uri( 'assets/js/search.js' ), array(), $version, array( 'strategy' => 'defer', 'in_footer' => true ) );
	wp_enqueue_script( 'aisa-cookie-preferences', get_theme_file_uri( 'assets/js/cookie-preferences.js' ), array(), $version, array( 'strategy' => 'defer', 'in_footer' => true ) );
}
add_action( 'wp_enqueue_scripts', 'aisa_enqueue_assets' );

function aisa_register_styles() {
	foreach ( array( 'primary' => __( 'Primário', 'aisa' ), 'secondary' => __( 'Secundário', 'aisa' ), 'accent' => __( 'Acento', 'aisa' ), 'inverse' => __( 'Invertido', 'aisa' ) ) as $name => $label ) {
		register_block_style( 'core/button', array( 'name' => $name, 'label' => $label, 'is_default' => 'primary' === $name ) );
	}
	foreach ( array( 'plain' => __( 'Fundo', 'aisa' ), 'surface' => __( 'Superfície', 'aisa' ), 'muted' => __( 'Suave', 'aisa' ) ) as $name => $label ) {
		register_block_style( 'core/group', array( 'name' => 'section-' . $name, 'label' => $label ) );
	}
	register_block_style( 'core/image', array( 'name' => 'media', 'label' => __( 'Fotografia', 'aisa' ) ) );
}
add_action( 'init', 'aisa_register_styles' );

/** Filterable by aisa-core when defaults per service become available. */
function aisa_header_image_url( $post_id = 0 ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$url = get_the_post_thumbnail_url( $post_id, 'full' );
	return apply_filters( 'aisa_header_image_url', $url ? $url : get_theme_file_uri( 'assets/img/default.jpg' ), $post_id );
}

/** Preserve the native featured-image block, including the empty-image case. */
function aisa_render_header_image( $content, $block ) {
	if ( false === strpos( $block['attrs']['className'] ?? '', 'aisa-header-image' ) ) {
		return $content;
	}
	return '<figure class="wp-block-post-featured-image aisa-header-image page-header__media"><img src="' . esc_url( aisa_header_image_url() ) . '" alt="" width="1600" height="900" fetchpriority="high"></figure>';
}
add_filter( 'render_block_core/post-featured-image', 'aisa_render_header_image', 10, 2 );

function aisa_skip_link() {
	echo '<a class="aisa-skip-link" href="#conteudo">' . esc_html__( 'Saltar para o conteúdo', 'aisa' ) . '</a>';
}
add_action( 'wp_body_open', 'aisa_skip_link', 0 );
// The theme supplies the first focusable link and a consistent target in every template.
remove_action( 'wp_footer', 'the_block_template_skip_link' );

/** Resolve existing pages, retaining a stable destination during migration. */
function aisa_page_url( $slug ) {
	$page = get_page_by_path( $slug );
	return $page ? get_permalink( $page ) : home_url( '/' . sanitize_title( $slug ) . '/' );
}

/** Native block serialization keeps menus editable in the Site Editor. */
function aisa_nav_link( $label, $slug ) {
	$url = 'agenda' === $slug ? get_post_type_archive_link( 'aisa_evento' ) : aisa_page_url( $slug );
	if ( ! $url || '#' === $url ) { return ''; }
	return serialize_block( array( 'blockName' => 'core/navigation-link', 'attrs' => array( 'label' => $label, 'url' => $url, 'kind' => 'custom' ), 'innerBlocks' => array(), 'innerHTML' => '', 'innerContent' => array() ) );
}

function aisa_nav_group( $label, $children, $class = '' ) {
	if ( ! trim( $children ) ) { return ''; }
	return '<!-- wp:navigation-submenu ' . wp_json_encode( array( 'label' => $label, 'openSubmenusOnClick' => true, 'className' => $class ) ) . ' -->' . $children . '<!-- /wp:navigation-submenu -->';
}

/** nav.js owns disclosure state; avoid two controllers writing aria-expanded. */
function aisa_navigation_controller( $content, $block ) {
	if ( false === strpos( $block['attrs']['className'] ?? '', 'site-nav' ) ) {
		return $content;
	}
	$processor = new WP_HTML_Tag_Processor( $content );
	while ( $processor->next_tag() ) {
		foreach ( $processor->get_attribute_names_with_prefix( 'data-wp-' ) ?? array() as $attribute ) {
			$processor->remove_attribute( $attribute );
		}
	}
	return $processor->get_updated_html();
}
add_filter( 'render_block_core/navigation', 'aisa_navigation_controller', 10, 2 );

require_once get_template_directory() . '/includes/demo-templates.php';
require_once get_template_directory() . '/includes/page-patterns.php';
