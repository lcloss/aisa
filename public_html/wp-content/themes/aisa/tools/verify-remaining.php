<?php
/** Local page matrix, exact taxonomy/template assignments and idempotence. No external requests. */
require dirname( __DIR__, 4 ) . '/wp-load.php';
if ( 'local' !== wp_get_environment_type() ) { exit; }
$pages = require __DIR__ . '/remaining-content.php';
$checks = 0;
$check = static function ( $condition, $label ) use ( &$checks ) {
	if ( ! $condition ) { throw new RuntimeException( $label ); }
	++$checks;
};
foreach ( $pages as [ $key, $title, $slug, $template, $valencia ] ) {
	$page = get_page_by_path( $slug );
	$check( $page && $title === $page->post_title, $key . ' title/slug' );
	$check( $template === get_post_meta( $page->ID, '_wp_page_template', true ), $key . ' template' );
	$terms = wp_get_object_terms( $page->ID, 'aisa_valencia', array( 'fields' => 'slugs' ) );
	$check( $terms === ( $valencia ? array( $valencia ) : array() ), $key . ' valência' );
	$check( 1 === count( get_posts( array( 'post_type' => 'page', 'post_status' => 'any', 'name' => $slug ) ) ), $key . ' unique slug' );
	$check( ! str_contains( $page->post_content, '<!-- wp:pattern ' ), $key . ' expanded editor blocks' );
}
foreach ( array( 'servicos-grupos', 'indice-ancoras', 'pessoas', 'logotipos', 'irs-donativo', 'contacto-completo', 'legal-reservado', 'pagina-vazia-404' ) as $slug ) {
	$check( WP_Block_Patterns_Registry::get_instance()->is_registered( 'aisa/' . $slug ), 'Registered ' . $slug );
}
$check( 89 === get_page_by_path( 'contactos' )->ID, 'Contactos ID reused' );
$check( 87 === get_page_by_path( 'voluntariado' )->ID, 'Voluntariado ID reused' );
$check( 3 === get_page_by_path( 'politica-de-privacidade' )->ID, 'Privacy draft ID reused' );
echo $checks . " checks passed.\n";
