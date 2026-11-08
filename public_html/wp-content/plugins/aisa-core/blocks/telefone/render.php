<?php
/** Telephone link; the filter accepts an international telephone number. */
defined( 'ABSPATH' ) || exit;
$number = preg_replace( '/[^+0-9]/', '', (string) apply_filters( 'aisa_telefone', AISA_TELEFONE ) );
$digits = preg_replace( '/\D/', '', $number );
$local = str_starts_with( $digits, '351' ) ? substr( $digits, 3 ) : $digits;
$display = implode( ' ', str_split( $local, 3 ) );
$classes = array( 'botao' => 'btn btn-accent', 'grande' => 'contact-block__tel', 'texto' => '' );
$variant = $attributes['variante'] ?? 'botao';
$compact = in_array( 'site-header__tel', explode( ' ', $attributes['className'] ?? '' ), true );
?>
<a <?php echo get_block_wrapper_attributes( array( 'class' => 'aisa-tel ' . ( $classes[ $variant ] ?? $classes['botao'] ) ) ); ?> href="<?php echo esc_url( 'tel:' . $number ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Ligar para a AISA, %s', 'aisa' ), $display ) ); ?>"><span class="aisa-tel-number"><?php echo esc_html( $display ); ?></span><?php if ( $compact ) : ?><span class="aisa-tel-short"><?php esc_html_e( 'Ligar', 'aisa' ); ?></span><?php endif; ?></a>
