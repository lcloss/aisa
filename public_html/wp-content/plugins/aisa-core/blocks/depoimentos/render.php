<?php
defined( 'ABSPATH' ) || exit;
if ( (int) ( wp_count_posts( 'aisa_depoimento' )->publish ?? 0 ) < 3 ) { return; }
$items = get_posts( array( 'post_type' => 'aisa_depoimento', 'post_status' => 'publish', 'posts_per_page' => max( 1, min( 3, (int) ( $attributes['quantidade'] ?? 1 ) ) ), 'no_found_rows' => true, 'tax_query' => \Aisa\Core\Blocks::valencia_query( $attributes['valencia'] ?? '' ), 'orderby' => 'aleatoria' === ( $attributes['ordem'] ?? '' ) ? 'rand' : 'date', 'order' => 'DESC' ) );
if ( ! $items ) { return; }
?>
<section <?php echo get_block_wrapper_attributes(); ?>>
<?php if ( ! empty( $attributes['titulo'] ) ) : ?><h2><?php echo esc_html( $attributes['titulo'] ); ?></h2><?php endif; ?>
<?php foreach ( $items as $item ) : $valencia = aisa_get_valencia( $item ); ?>
<figure class="testimonial" data-valencia="<?php echo esc_attr( $valencia ? $valencia->slug : 'generico' ); ?>"><blockquote class="testimonial__quote"><p><?php echo esc_html( wp_strip_all_tags( strip_shortcodes( $item->post_content ) ) ); ?></p></blockquote><figcaption class="testimonial__who"><?php echo esc_html( get_post_meta( $item->ID, '_aisa_dep_relacao', true ) . ( $valencia ? ' · ' . $valencia->name : '' ) ); ?></figcaption></figure>
<?php endforeach; ?></section>
