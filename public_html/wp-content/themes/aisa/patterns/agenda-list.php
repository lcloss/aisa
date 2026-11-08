<?php
/**
 * Title: Lista de eventos
 * Slug: aisa/agenda-list
 * Categories: aisa
 */
defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"className":"aisa-section__inner","layout":{"type":"default"}} --><div class="wp-block-group aisa-section__inner"><!-- wp:query {"query":{"inherit":true}} --><div class="wp-block-query"><!-- wp:post-template --><!-- wp:group {"className":"event aisa-valencia-scope","layout":{"type":"default"},"tagName":"article"} --><article class="wp-block-group event aisa-valencia-scope"><!-- wp:aisa/event-details /--><!-- wp:group {"className":"","layout":{"type":"default"}} --><div class="wp-block-group "><!-- wp:post-terms {"term":"aisa_valencia","className":"valencia-tag"} /--><!-- wp:post-title {"level":2,"isLink":true,"className":"event__title"} /--></div><!-- /wp:group --></article><!-- /wp:group --><!-- /wp:post-template --><!-- wp:query-no-results --><!-- wp:paragraph --><p>Em breve, novas datas. <a href="/noticias/">Veja as últimas notícias.</a></p><!-- /wp:paragraph --><!-- /wp:query-no-results --><!-- wp:query-pagination {"layout":{"type":"flex"}} --><div class="wp-block-query-pagination"><!-- wp:query-pagination-previous /--><!-- wp:query-pagination-numbers /--><!-- wp:query-pagination-next /--></div><!-- /wp:query-pagination --></div><!-- /wp:query --></div><!-- /wp:group -->
