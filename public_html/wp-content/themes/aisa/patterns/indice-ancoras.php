<?php
/**
 * Title: Índice de terapias
 * Slug: aisa/indice-ancoras
 * Categories: aisa
 */
defined( 'ABSPATH' ) || exit;
$links = array();
foreach ( aisa_therapy_sections() as $anchor => $label ) { $links[] = '<a href="#' . esc_attr( $anchor ) . '">' . esc_html( $label ) . '</a>'; }
echo aisa_page_group( aisa_page_heading( __( 'Conheça os nossos serviços', 'aisa' ) ) . aisa_page_list( $links ), 'aisa-anchor-index' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
