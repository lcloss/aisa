<?php
/**
 * Title: Perto de si — hero
 * Slug: aisa/hero
 * Categories: aisa
 */
defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"className":"hero is-style-section-plain","layout":{"type":"default"},"align":"full"} --><div class="wp-block-group alignfull hero is-style-section-plain"><!-- wp:group {"className":"hero__text","layout":{"type":"default"}} --><div class="wp-block-group hero__text"><!-- wp:paragraph {"className":"aisa-eyebrow"} --><p class="aisa-eyebrow"><?php esc_html_e( 'Malveira da Serra · Janes', 'aisa' ); ?></p><!-- /wp:paragraph --><!-- wp:heading {"level":1,"className":"hero__title"} --><h1 class="wp-block-heading hero__title"><?php esc_html_e( 'Perto de si,', 'aisa' ); ?> <em><?php esc_html_e( 'a cuidar dos seus.', 'aisa' ); ?></em></h1><!-- /wp:heading --><!-- wp:paragraph {"className":"hero__lead"} --><p class="hero__lead"><?php esc_html_e( 'Promovemos a qualidade de vida das crianças, jovens, idosos e pessoas com dependência. Da infância ao envelhecimento.', 'aisa' ); ?></p><!-- /wp:paragraph --><!-- wp:group {"className":"hero__actions","layout":{"type":"default"}} --><div class="wp-block-group hero__actions"><!-- wp:aisa/telefone {"variante":"botao"} /--><!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button {"className":"is-style-secondary"} --><div class="wp-block-button is-style-secondary"><a class="wp-block-button__link wp-element-button" href="#servicos"><?php esc_html_e( 'Ver serviços', 'aisa' ); ?></a></div><!-- /wp:button --></div><!-- /wp:buttons --></div><!-- /wp:group --></div><!-- /wp:group --><?php echo '<!-- wp:aisa/slideshow ' . wp_json_encode( array( 'slides' => get_option( 'aisa_demo_slides', array() ) ) ) . ' /-->'; ?></div><!-- /wp:group -->
