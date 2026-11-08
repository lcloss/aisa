<?php
/** Run with wp --path=public_html eval-file specs/scripts/prepare-cookie-policy.php. */
defined( 'ABSPATH' ) || exit;

// Never replace an existing legal page or publish an unconfigured declaration.
WP_CLI::log( 'A verificar a página P27 existente…' );
$existing = get_page_by_path( 'politica-de-cookies' );
if ( $existing ) {
	WP_CLI::success( 'P27 já existe: ID ' . $existing->ID . '. Conteúdo preservado.' );
	return;
}
WP_CLI::log( 'A criar o rascunho da Política de Cookies…' );
$id = wp_insert_post(
	array(
		'post_type'    => 'page',
		'post_status'  => 'draft',
		'post_name'    => 'politica-de-cookies',
		'post_title'   => 'Política de Cookies',
		'post_content' => '<!-- wp:paragraph --><p>Os cookies necessários permitem o funcionamento deste site. Os cookies de estatísticas e de marketing só são utilizados com o teu consentimento. Podes aceitar, rejeitar ou alterar as tuas preferências a qualquer momento.</p><!-- /wp:paragraph -->' . "\n" . '<!-- wp:shortcode -->[cookie_declaration]<!-- /wp:shortcode -->' . "\n" . '<!-- wp:paragraph --><p><a class="aisa-cookie-prefs" href="/politica-de-cookies/">Alterar preferências</a></p><!-- /wp:paragraph -->',
	),
	true
);
if ( is_wp_error( $id ) ) {
	WP_CLI::error( $id->get_error_message() );
}
WP_CLI::success( 'P27 preparada como rascunho: ID ' . $id . '. Publicar só após validar a declaração Cookiebot.' );
