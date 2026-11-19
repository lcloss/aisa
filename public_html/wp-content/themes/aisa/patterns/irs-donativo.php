<?php
/**
 * Title: Formas de ajudar (donativos, IRS, voluntariado)
 * Slug: aisa/irs-donativo
 * Categories: aisa
 *
 * Três formas de ajudar, com âncoras estáveis para o menu e o botão "Apoie a nossa causa":
 * #donativos, #consignacao-irs, #voluntariado.
 */
defined( 'ABSPATH' ) || exit;
echo aisa_page_group(
	aisa_page_heading( __( 'Donativos', 'aisa' ), 2, 'donativos' ) .
	aisa_page_text( __( 'O seu apoio através de contribuições, donativos ou voluntariado é importante para a continuidade das nossas atividades.', 'aisa' ) ) .
	aisa_page_text( __( 'Para saber como fazer um donativo, contacte-nos. A AISA emite um recibo de donativo a pedido, com o NIF do doador.', 'aisa' ) ) .
	'<!-- wp:aisa/telefone /-->', 'section--muted aisa-service-callout'
) . aisa_page_group(
	aisa_page_heading( __( 'Consignação do IRS', 'aisa' ), 2, 'consignacao-irs' ) .
	aisa_page_text( __( 'Pode consignar 1% do IRS liquidado à AISA, sem custo para si. O valor sai da parte do Estado e não do seu reembolso.', 'aisa' ) ) .
	aisa_page_text( __( 'Indique o NIF da AISA no Quadro 11 do Modelo 3 da sua declaração de IRS.', 'aisa' ) ) .
	aisa_page_text( '<strong class="aisa-nif">' . esc_html__( 'NIF: 502 369 345', 'aisa' ) . '</strong>' ),
	'section--muted aisa-service-callout'
) . aisa_page_group(
	aisa_page_heading( __( 'Voluntariado', 'aisa' ), 2, 'voluntariado' ) .
	aisa_page_text( __( 'Também pode ajudar com o seu tempo. Conheça o voluntariado na AISA e diga-nos como gostaria de participar.', 'aisa' ) ) .
	aisa_page_text( aisa_page_link( 'voluntariado', __( 'Quero ser voluntário', 'aisa' ) ) ),
	'section--muted aisa-service-callout'
); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
