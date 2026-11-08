<?php
/**
 * Title: Todos os serviços por fase da vida
 * Slug: aisa/servicos-grupos
 * Categories: aisa
 */
defined( 'ABSPATH' ) || exit;
$groups = array(
	array( 'centro-dia', __( 'Envelhecimento', 'aisa' ), __( 'Perto de casa, com companhia e cuidado.', 'aisa' ), array(
		array( 'centro-de-dia', __( 'Centro de Dia', 'aisa' ), 'centro-dia', 'centro-dia-sala' ),
		array( 'servico-apoio-domiciliario', __( 'Serviço de Apoio Domiciliário', 'aisa' ), 'sad', '' ),
		array( 'novas-instalacoes', __( 'Novas instalações: Lar e Centro de Dia · em construção', 'aisa' ), 'generico', 'construction' ),
	) ),
	array( 'infancia', __( 'Infância e Juventude', 'aisa' ), __( 'Espaço para crescer, descobrir e brincar.', 'aisa' ), array(
		array( 'atividades-de-animacao-e-de-apoio-a-familia', __( 'Actividades de Animação e de Apoio à Família (AAAF)', 'aisa' ), 'infancia', '' ),
		array( 'componente-de-apoio-a-familia', __( 'Componente de Apoio à Família (CAF)', 'aisa' ), 'infancia', '' ),
		array( 'atividades-de-enriquecimento-curricular', __( 'Actividades de Enriquecimento Curricular (AEC)', 'aisa' ), 'infancia', '' ),
	) ),
	array( 'generico', __( 'Saúde e Reabilitação', 'aisa' ), __( 'Mais autonomia, em cada dia.', 'aisa' ), array(
		array( 'saude-e-reabilitacao', __( 'Saúde e Reabilitação', 'aisa' ), 'generico', '' ),
	) ),
	array( 'generico', __( 'Como aceder', 'aisa' ), __( 'Fale connosco para conhecer as condições de acesso.', 'aisa' ), array(
		array( 'candidaturas', __( 'Candidaturas', 'aisa' ), 'generico', '' ),
		array( 'protocolos', __( 'Protocolos', 'aisa' ), 'generico', '' ),
	) ),
);
foreach ( $groups as [ $icon, $title, $intro, $services ] ) {
	$heading = '<!-- wp:html --><img class="valencia-icon" src="' . esc_url( get_theme_file_uri( 'assets/icons/' . $icon . '.svg' ) ) . '" alt="" width="48" height="48"><!-- /wp:html -->';
	$heading .= aisa_page_heading( $title ) . aisa_page_text( esc_html( $intro ) );
	$cards = '';
	foreach ( $services as [ $slug, $label, $valencia, $photo ] ) {
		$page = get_page_by_path( $slug );
		$url = $page ? get_the_post_thumbnail_url( $page->ID, 'large' ) : false;
		if ( 'construction' === $photo ) { $url = get_theme_file_uri( 'assets/img/capa_facebook.jpg' ); }
		if ( ! $url && $photo ) { $url = wp_get_attachment_image_url( get_option( 'aisa_demo_media', array() )[ $photo ] ?? 0, 'large' ); }
		$url = $url ?: get_theme_file_uri( 'assets/img/default.jpg' );
		$card = aisa_page_image( $url, '', 'service-card__media' );
		$card .= '<!-- wp:html --><div class="service-card__stripe" data-valencia="' . esc_attr( $valencia ) . '"></div><!-- /wp:html -->';
		$card .= '<!-- wp:heading {"level":3} --><h3 class="wp-block-heading">' . aisa_page_link( $slug, $label ) . '</h3><!-- /wp:heading -->';
		$cards .= aisa_page_group( $card, 'service-card' );
	}
	echo aisa_page_group( $heading . aisa_page_group( $cards, 'aisa-service-cards' ), 'aisa-service-group', 'section' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped block helpers.
}
echo aisa_page_group( aisa_page_heading( __( 'Não sabe qual escolher? Ligue-nos', 'aisa' ) ) . '<!-- wp:aisa/telefone {"variante":"grande"} /-->', 'is-style-section-strong-blue aisa-service-callout' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
