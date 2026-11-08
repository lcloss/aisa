<?php
defined( 'ABSPATH' ) || exit;
$slides = array_values( array_filter( $attributes['slides'] ?? array(), static function ( $slide ) { return ! empty( $slide['id'] ) && wp_attachment_is_image( (int) $slide['id'] ); } ) );
if ( ! $slides ) { return; }
$id = wp_unique_id( 'aisa-slides-' );
?>
<section <?php echo get_block_wrapper_attributes( array( 'class' => 'slideshow' ) ); ?> aria-roledescription="<?php esc_attr_e( 'carrossel', 'aisa' ); ?>" aria-label="<?php echo esc_attr( $attributes['label'] ?? __( 'Fotografias da AISA', 'aisa' ) ); ?>" data-aisa-slideshow>
<div id="<?php echo esc_attr( $id ); ?>">
<?php foreach ( $slides as $index => $slide ) : ?>
<figure class="slideshow__slide"><?php echo wp_get_attachment_image( (int) $slide['id'], 'large', false, array( 'alt' => sanitize_text_field( $slide['alt'] ?? '' ), 'loading' => 0 === $index ? 'eager' : 'lazy', 'fetchpriority' => 0 === $index ? 'high' : 'auto' ) ); ?></figure>
<?php endforeach; ?></div>
<div class="slideshow__controls" hidden>
<div class="slideshow__btns"><button type="button" class="btn btn-secondary" data-prev aria-controls="<?php echo esc_attr( $id ); ?>"><?php esc_html_e( 'Anterior', 'aisa' ); ?></button><button type="button" class="btn btn-secondary" data-next aria-controls="<?php echo esc_attr( $id ); ?>"><?php esc_html_e( 'Seguinte', 'aisa' ); ?></button><button type="button" class="btn btn-secondary" data-play data-start="<?php esc_attr_e( 'Reproduzir', 'aisa' ); ?>" data-stop="<?php esc_attr_e( 'Pausar', 'aisa' ); ?>" aria-label="<?php esc_attr_e( 'Reproduzir', 'aisa' ); ?>"><?php esc_html_e( 'Reproduzir', 'aisa' ); ?></button></div>
<span class="slideshow__caption" aria-live="polite" aria-atomic="true" data-status data-format="<?php esc_attr_e( '%1$d de %2$d', 'aisa' ); ?>"><?php echo esc_html( sprintf( __( '%1$d de %2$d', 'aisa' ), 1, count( $slides ) ) ); ?></span>
</div></section>
