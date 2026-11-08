<?php
/**
 * Title: Pessoas e funções
 * Slug: aisa/pessoas
 * Categories: aisa
 */
defined( 'ABSPATH' ) || exit;
echo aisa_people_blocks( array(
	array( 'role' => __( 'Coordenação dos Serviços Sociais', 'aisa' ) ),
	array( 'role' => __( 'Coordenação de Infância e Juventude', 'aisa' ) ),
	array( 'role' => __( 'Coordenação de Saúde e Reabilitação', 'aisa' ) ),
	array( 'role' => __( 'Enfermagem', 'aisa' ) ),
) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
