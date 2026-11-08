<?php
/**
 * Title: Página não encontrada
 * Slug: aisa/not-found
 * Inserter: no
 */
defined( 'ABSPATH' ) || exit;
?>
<!-- wp:heading {"level":1} --><h1 class="wp-block-heading"><?php esc_html_e( 'Não encontrámos esta página', 'aisa' ); ?></h1><!-- /wp:heading -->
<!-- wp:paragraph --><p><?php esc_html_e( 'Podes pesquisar ou seguir uma destas ligações. Se precisares de ajuda, liga-nos.', 'aisa' ); ?></p><!-- /wp:paragraph -->
<!-- wp:search {"label":"Pesquisar no site","buttonText":"Pesquisar"} /-->
<!-- wp:html -->
<p><a href="<?php echo esc_url( aisa_page_url( 'servicos' ) ); ?>"><?php esc_html_e( 'Serviços', 'aisa' ); ?></a> · <a href="<?php echo esc_url( aisa_page_url( 'noticias' ) ); ?>"><?php esc_html_e( 'Notícias', 'aisa' ); ?></a> · <a href="<?php echo esc_url( aisa_page_url( 'contactos' ) ); ?>"><?php esc_html_e( 'Contactos', 'aisa' ); ?></a></p>
<p><a class="aisa-tel btn btn-accent" href="tel:+351214857700">214 857 700</a></p>
<!-- /wp:html -->
