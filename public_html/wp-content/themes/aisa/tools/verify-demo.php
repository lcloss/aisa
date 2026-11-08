<?php
/** WordPress integration checks; no persistent fixtures. Run with wp eval-file. */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) { exit; }
function aisa_demo_check( $condition, $message ) {
	if ( ! $condition ) { throw new RuntimeException( $message ); }
	echo 'PASS ' . $message . PHP_EOL;
}
$manifest = get_option( 'aisa_demo_manifest', array() );
$ids = $manifest['posts'] ?? array();
aisa_demo_check( 'page' === get_option( 'show_on_front' ) && (int) get_option( 'page_on_front' ) === $ids['inicio'] && (int) get_option( 'page_for_posts' ) === $ids['noticias'], 'Definições de leitura da demo' );
aisa_demo_check( 12 === (int) get_option( 'posts_per_page' ), '12 notícias por página' );
foreach ( array( 'front-page', 'page-servico', 'home', 'single', 'archive-aisa_evento', 'single-aisa_evento' ) as $name ) {
	$template = get_block_template( 'aisa//' . $name );
	aisa_demo_check( $template && ! empty( $template->content ), 'Template nativo ' . $name );
}
aisa_demo_check( 'page-servico' === get_page_template_slug( $ids['centro-dia'] ) && 'centro-dia' === aisa_get_valencia( $ids['centro-dia'] )->slug, 'Centro de Dia: template e valência' );
aisa_demo_check( 4 === count( get_option( 'aisa_demo_slides' ) ), 'Quatro slides locais com alternativa' );
foreach ( get_option( 'aisa_demo_slides' ) as $slide ) {
	aisa_demo_check( wp_attachment_is_image( $slide['id'] ) && ! empty( $slide['alt'] ), 'Slide válido ' . $slide['id'] );
}
foreach ( array( 'evento-aniversario-aisa', 'evento-festa-natal', 'evento-atelier-natal' ) as $key ) {
	aisa_demo_check( aisa_evento_is_future( $ids[ $key ] ) && str_contains( get_the_title( $ids[ $key ] ), '[DEMO]' ), 'Evento futuro identificado ' . $key );
}
// Exercise fallback in actual Query Loop context, without changing a saved post.
global $post;
$post = get_post( $ids['inicio'] );
setup_postdata( $post );
$fallback = render_block( array( 'blockName' => 'core/post-featured-image', 'attrs' => array( 'className' => 'news-card__media' ), 'innerBlocks' => array(), 'innerHTML' => '', 'innerContent' => array() ) );
aisa_demo_check( str_contains( $fallback, 'default.jpg' ) && str_contains( $fallback, 'alt=""' ), 'Imagem por defeito decorativa no cartão sem fotografia' );
$post = get_post( $ids['centro-dia'] );
setup_postdata( $post );
$content = do_blocks( $post->post_content );
foreach ( array( 'Para quem é', 'O que inclui', 'Horário e condições', 'Como aceder', 'rendimentos' ) as $heading ) {
	if ( 'rendimentos' === $heading ) { $heading = 'rendimento do agregado familiar'; }
	aisa_demo_check( str_contains( $content, $heading ), 'Secção ou frase Q2: ' . $heading );
}
wp_reset_postdata();
echo 'Integração M0 concluída.' . PHP_EOL;
