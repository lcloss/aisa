<?php
defined( 'ABSPATH' ) || exit;
$slides = array_values( array_filter( $attributes['slides'] ?? array(), static function ( $slide ) { return ! empty( $slide['id'] ) && wp_attachment_is_image( (int) $slide['id'] ); } ) );
if ( ! $slides ) { return; }
$id = wp_unique_id( 'aisa-slides-' );
?>
<section <?php echo get_block_wrapper_attributes( array( 'class' => 'slideshow' ) ); ?> aria-roledescription="<?php esc_attr_e( 'carrossel', 'aisa' ); ?>" aria-label="<?php echo esc_attr( $attributes['label'] ?? __( 'Fotografias da AISA', 'aisa' ) ); ?>" data-aisa-slideshow>
<div class="slideshow__track" id="<?php echo esc_attr( $id ); ?>">
<?php foreach ( $slides as $index => $slide ) :
	// Legenda do bloco; se vazia, a legenda definida na Biblioteca de Média.
	$caption = trim( sanitize_text_field( $slide['caption'] ?? '' ) );
	if ( '' === $caption ) { $caption = trim( wp_strip_all_tags( (string) wp_get_attachment_caption( (int) $slide['id'] ) ) ); }
	?>
<figure class="slideshow__slide"><?php echo wp_get_attachment_image( (int) $slide['id'], 'large', false, array( 'alt' => sanitize_text_field( $slide['alt'] ?? '' ), 'loading' => 0 === $index ? 'eager' : 'lazy', 'fetchpriority' => 0 === $index ? 'high' : 'auto' ) ); ?><?php if ( '' !== $caption ) : ?><figcaption class="slideshow__caption"><?php echo esc_html( $caption ); ?></figcaption><?php endif; ?></figure>
<?php endforeach; ?></div>
<div class="slideshow__controls" hidden>
<button type="button" class="slideshow__btn" data-prev aria-controls="<?php echo esc_attr( $id ); ?>" aria-label="<?php esc_attr_e( 'Fotografia anterior', 'aisa' ); ?>"><svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true" focusable="false"><path d="M15 5l-7 7 7 7" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></button>
<span class="slideshow__status" aria-live="polite" aria-atomic="true" data-status data-format="<?php esc_attr_e( '%1$d de %2$d', 'aisa' ); ?>"><?php echo esc_html( sprintf( __( '%1$d de %2$d', 'aisa' ), 1, count( $slides ) ) ); ?></span>
<button type="button" class="slideshow__btn" data-next aria-controls="<?php echo esc_attr( $id ); ?>" aria-label="<?php esc_attr_e( 'Fotografia seguinte', 'aisa' ); ?>"><svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true" focusable="false"><path d="M9 5l7 7-7 7" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></button>
<button type="button" class="slideshow__btn slideshow__btn--play" data-play data-start="<?php esc_attr_e( 'Reproduzir', 'aisa' ); ?>" data-stop="<?php esc_attr_e( 'Pausar', 'aisa' ); ?>" aria-label="<?php esc_attr_e( 'Reproduzir', 'aisa' ); ?>"><svg class="slideshow__icon-play" viewBox="0 0 24 24" width="18" height="18" aria-hidden="true" focusable="false"><path d="M8 5.5v13l10-6.5z" fill="currentColor"/></svg><svg class="slideshow__icon-pause" viewBox="0 0 24 24" width="18" height="18" aria-hidden="true" focusable="false"><path d="M7 5h3.5v14H7zM13.5 5H17v14h-3.5z" fill="currentColor"/></svg></button>
</div></section>
