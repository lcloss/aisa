<?php
/**
 * Title: Cabeçalho AISA
 * Slug: aisa/header
 * Categories: header
 * Inserter: no
 */
defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"className":"site-header__inner","layout":{"type":"default"}} -->
<div class="wp-block-group site-header__inner">
<!-- wp:html -->
<a class="site-header__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/img/logo.svg' ) ); ?>" width="382" height="189" alt="<?php esc_attr_e( 'AISA — início', 'aisa' ); ?>"></a>
<!-- /wp:html -->
<!-- wp:html -->
<a class="btn btn-secondary site-header__search" href="<?php echo esc_url( home_url( '/?s=' ) ); ?>" aria-label="<?php esc_attr_e( 'Pesquisar no site', 'aisa' ); ?>"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true" focusable="false"><circle cx="11" cy="11" r="7"/><path d="M20 20l-4-4"/></svg></a>
<a class="btn btn-accent site-header__cta" href="<?php echo esc_url( aisa_page_url( 'torne-se-socio' ) ); ?>"><?php esc_html_e( 'Torne-se sócio', 'aisa' ); ?></a>
<!-- /wp:html -->
<!-- wp:html -->
<button class="btn btn-secondary site-header__menu" aria-expanded="false" aria-controls="aisa-menu-panel" hidden><?php esc_html_e( 'Menu', 'aisa' ); ?></button>
<div id="aisa-menu-panel" class="aisa-menu-panel">
<div class="aisa-menu-toolbar"><!-- /wp:html -->
<!-- wp:aisa/telefone {"variante":"botao"} /-->
<!-- wp:html --><button class="btn btn-secondary aisa-menu-close" type="button"><?php esc_html_e( 'Fechar menu', 'aisa' ); ?></button></div>
<!-- /wp:html -->
<!-- wp:navigation {"overlayMenu":"never","openSubmenusOnClick":true,"ariaLabel":"Menu principal","className":"site-nav","layout":{"type":"flex","justifyContent":"left"}} -->
<?php
$ageing = aisa_nav_link( __( 'Centro de Dia', 'aisa' ), 'centro-de-dia' ) . aisa_nav_link( __( 'Apoio Domiciliário', 'aisa' ), 'servico-apoio-domiciliario' ) . aisa_nav_link( __( 'Novas instalações', 'aisa' ), 'novas-instalacoes' );
$children = aisa_nav_link( __( 'Actividades de Animação e de Apoio à Família (AAAF)', 'aisa' ), 'atividades-de-animacao-e-de-apoio-a-familia' ) . aisa_nav_link( __( 'Componente de Apoio à Família (CAF)', 'aisa' ), 'componente-de-apoio-a-familia' ) . aisa_nav_link( __( 'Actividades de Enriquecimento Curricular (AEC)', 'aisa' ), 'atividades-de-enriquecimento-curricular' );
$services = aisa_nav_group( __( 'Envelhecimento', 'aisa' ), $ageing, 'aisa-nav-group aisa-icon-centro-dia' );
$services .= aisa_nav_group( __( 'Infância e Juventude', 'aisa' ), $children, 'aisa-nav-group aisa-icon-infancia' );
$services .= aisa_nav_link( __( 'Saúde e Reabilitação', 'aisa' ), 'saude-e-reabilitacao', 'aisa-nav-solo aisa-icon-generico' );
$services .= aisa_nav_group( __( 'Como aceder', 'aisa' ), aisa_nav_link( __( 'Candidaturas', 'aisa' ), 'candidaturas' ) . aisa_nav_link( __( 'Protocolos', 'aisa' ), 'protocolos' ), 'aisa-nav-group aisa-icon-generico' );
$services .= aisa_nav_link( __( 'Ver todos os serviços', 'aisa' ), 'servicos', 'aisa-nav-all' );
echo aisa_nav_group( __( 'Serviços', 'aisa' ), $services, 'aisa-services' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Serialized native blocks.
$about = '';
foreach ( array( 'quem-somos' => __( 'Quem Somos', 'aisa' ), 'equipa-tecnica' => __( 'Equipa Técnica', 'aisa' ), 'orgaos-sociais' => __( 'Órgãos Sociais', 'aisa' ), 'parcerias' => __( 'Parcerias', 'aisa' ), 'documentos' => __( 'Documentos', 'aisa' ) ) as $slug => $label ) {
	$about .= aisa_nav_link( $label, $slug );
}
echo aisa_nav_group( __( 'A AISA', 'aisa' ), $about ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo aisa_nav_group( __( 'Participar', 'aisa' ), aisa_nav_link( __( 'Torne-se Sócio', 'aisa' ), 'torne-se-socio' ) . aisa_nav_link( __( 'Apoie a Nossa Causa', 'aisa' ), 'apoie-a-nossa-causa' ) . aisa_nav_link( __( 'Voluntariado', 'aisa' ), 'voluntariado' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo aisa_nav_link( __( 'Notícias', 'aisa' ), 'noticias' ) . aisa_nav_link( __( 'Agenda', 'aisa' ), 'agenda' ) . aisa_nav_link( __( 'Contactos', 'aisa' ), 'contactos' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
?>
<!-- /wp:navigation -->
<!-- wp:html --></div><!-- /wp:html -->
</div>
<!-- /wp:group -->
<!-- wp:html -->
<div id="aisa-search-panel" class="site-search" hidden><div class="site-search__inner">
<!-- /wp:html -->
<!-- wp:search {"label":"Pesquisar no site","placeholder":"Ex.: Centro de Dia, voluntariado, regulamento…","buttonText":"Pesquisar","className":"site-search__form"} /-->
<!-- wp:html -->
</div></div>
<!-- /wp:html -->
