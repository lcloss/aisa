<?php
/**
 * Title: Texto legal em validação
 * Slug: aisa/legal-reservado
 * Categories: aisa
 */
defined( 'ABSPATH' ) || exit;
echo aisa_page_group( aisa_page_text( '<strong>' . esc_html__( 'Texto em validação pela AISA', 'aisa' ) . '</strong>' ), 'aisa-legal-notice' );
$headings = array(
	__( 'Identificação e contacto do responsável', 'aisa' ),
	__( 'Encarregado de Proteção de Dados', 'aisa' ),
	__( 'Finalidades e fundamentos do tratamento', 'aisa' ),
	__( 'Prazos de conservação', 'aisa' ),
	__( 'Subcontratantes e transferências de dados', 'aisa' ),
	__( 'Direitos e retirada do consentimento', 'aisa' ),
	__( 'Reclamação à CNPD', 'aisa' ),
	__( 'Propriedade dos conteúdos e imagens', 'aisa' ),
	__( 'Ligações externas', 'aisa' ),
	__( 'Responsabilidade pela informação e comparticipações', 'aisa' ),
	__( 'Lei aplicável e resolução alternativa de litígios', 'aisa' ),
	__( 'Data da última atualização', 'aisa' ),
);
foreach ( $headings as $heading ) {
	echo aisa_page_heading( $heading ) . aisa_page_text( __( 'Texto em validação pela AISA.', 'aisa' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
