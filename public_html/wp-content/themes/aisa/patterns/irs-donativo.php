<?php
/**
 * Title: Consignação do IRS e donativos
 * Slug: aisa/irs-donativo
 * Categories: aisa
 */
defined( 'ABSPATH' ) || exit;
echo aisa_page_group(
	aisa_page_heading( __( 'Consignação do IRS', 'aisa' ) ) .
	aisa_page_text( __( 'Pode consignar 1% do IRS liquidado à AISA, sem custo para si. O valor sai da parte do Estado e não do seu reembolso.', 'aisa' ) ) .
	aisa_page_text( __( 'Indique o NIF da AISA no Quadro 11 do Modelo 3 da sua declaração de IRS.', 'aisa' ) ) .
	aisa_page_text( '<strong class="aisa-nif">' . esc_html__( 'NIF: 502 369 345', 'aisa' ) . '</strong>' ) .
	aisa_page_heading( __( 'Donativos', 'aisa' ) ) .
	aisa_page_text( __( 'O seu apoio através de contribuições, donativos ou voluntariado é importante para a continuidade das nossas atividades.', 'aisa' ) ) .
	aisa_page_text( __( 'Para saber como fazer um donativo, contacte-nos. A AISA emite um recibo de donativo a pedido, com o NIF do doador.', 'aisa' ) ) .
	'<!-- wp:aisa/telefone /-->', 'section--muted aisa-service-callout'
); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
