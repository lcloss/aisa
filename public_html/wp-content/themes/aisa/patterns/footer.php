<?php
/**
 * Title: Rodapé AISA
 * Slug: aisa/footer
 * Categories: footer
 * Inserter: no
 */
defined( 'ABSPATH' ) || exit;
?>
<!-- wp:html -->
<div class="site-footer__grid">
<div><h2 class="site-footer__title">AISA</h2><p><?php esc_html_e( 'Associação de Apoio Social de Nossa Senhora da Assunção', 'aisa' ); ?></p><p><?php esc_html_e( 'Morada: [A CONFIRMAR — BQ2]', 'aisa' ); ?><br><?php esc_html_e( 'NIPC: [A CONFIRMAR — BQ2]', 'aisa' ); ?></p><p><a class="aisa-tel" href="tel:+351214857700">214 857 700</a><br><a href="mailto:geral@aisaipss.pt">geral@aisaipss.pt</a></p><p><a href="https://www.facebook.com/AISA.IPSS">Facebook</a> · <a href="https://www.instagram.com/aisa_ipss/">Instagram</a></p></div>
<?php
$columns = array(
	__( 'Serviços', 'aisa' ) => array( 'servicos' => __( 'Ver todos os serviços', 'aisa' ), 'centro-de-dia' => __( 'Centro de Dia', 'aisa' ), 'servico-apoio-domiciliario' => __( 'Apoio Domiciliário', 'aisa' ), 'atividades-de-animacao-e-de-apoio-a-familia' => __( 'Infância e Juventude', 'aisa' ), 'saude-e-reabilitacao' => __( 'Saúde e Reabilitação', 'aisa' ) ),
	__( 'A AISA e Documentos', 'aisa' ) => array( 'quem-somos' => __( 'Quem Somos', 'aisa' ), 'equipa-tecnica' => __( 'Equipa Técnica', 'aisa' ), 'orgaos-sociais' => __( 'Órgãos Sociais', 'aisa' ), 'documentos' => __( 'Documentos', 'aisa' ), 'contactos' => __( 'Contactos', 'aisa' ) ),
	__( 'Legais', 'aisa' ) => array( 'politica-de-privacidade' => __( 'Privacidade', 'aisa' ), 'politica-de-cookies' => __( 'Cookies', 'aisa' ), 'termos' => __( 'Termos', 'aisa' ), 'declaracao-de-acessibilidade' => __( 'Declaração de Acessibilidade', 'aisa' ) ),
);
foreach ( $columns as $heading => $links ) : ?>
<div><h2 class="site-footer__title"><?php echo esc_html( $heading ); ?></h2><ul>
<?php foreach ( $links as $slug => $label ) : ?>
<li><a href="<?php echo esc_url( aisa_page_url( $slug ) ); ?>"><?php echo esc_html( $label ); ?></a></li>
<?php endforeach; ?>
<?php if ( __( 'Legais', 'aisa' ) === $heading ) : ?>
<li><a href="<?php echo esc_url( home_url( '/politica-de-cookies/' ) ); ?>" class="aisa-cookie-prefs"><?php esc_html_e( 'Preferências de cookies', 'aisa' ); ?></a></li>
<!-- Livro de Reclamações: aguarda confirmação A9. -->
<?php endif; ?>
</ul></div>
<?php endforeach; ?>
</div>
<div class="site-footer__legal">© <?php echo esc_html( wp_date( 'Y' ) ); ?> AISA IPSS</div>
<!-- /wp:html -->
