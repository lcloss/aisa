<?php
/**
 * Title: Barra de topo AISA
 * Slug: aisa/topbar
 * Categories: header
 * Inserter: no
 *
 * Telefone, e-mail, morada e redes sociais. O botão de destaque do
 * cabeçalho é "Torne-se sócio" (patterns/header.php).
 */
defined( 'ABSPATH' ) || exit;
$aisa_contacts_url = aisa_page_url( 'contactos' );
// Same source and format as the aisa/telefone block (filter aisa_telefone).
$aisa_tel        = preg_replace( '/[^+0-9]/', '', (string) apply_filters( 'aisa_telefone', defined( 'AISA_TELEFONE' ) ? AISA_TELEFONE : '+351214857700' ) );
$aisa_tel_digits = preg_replace( '/\D/', '', $aisa_tel );
$aisa_tel_show   = implode( ' ', str_split( str_starts_with( $aisa_tel_digits, '351' ) ? substr( $aisa_tel_digits, 3 ) : $aisa_tel_digits, 3 ) );
?>
<!-- wp:html -->
<div class="topbar__inner">
<ul class="topbar__contacts">
<li class="topbar__tel"><a class="aisa-tel" href="<?php echo esc_url( 'tel:' . $aisa_tel ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Ligar para a AISA, %s', 'aisa' ), $aisa_tel_show ) ); ?>"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z"/></svg><?php echo esc_html( $aisa_tel_show ); ?></a></li>
<li class="topbar__email"><a href="mailto:geral@aisaipss.pt"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/></svg>geral@aisaipss.pt</a></li>
<?php if ( '#' !== $aisa_contacts_url ) : ?>
<li class="topbar__place"><a href="<?php echo esc_url( $aisa_contacts_url ); ?>"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M12 21s-7-6.2-7-11.5a7 7 0 0 1 14 0C19 14.8 12 21 12 21z"/><circle cx="12" cy="9.5" r="2.5"/></svg><?php esc_html_e( 'Rua de Cascais 877, Malveira da Serra', 'aisa' ); ?></a></li>
<?php endif; ?>
</ul>
<ul class="topbar__social">
<li class="topbar__social-label"><?php esc_html_e( 'Siga-nos', 'aisa' ); ?></li>
<li><a href="https://www.facebook.com/AISA.IPSS" aria-label="<?php esc_attr_e( 'AISA no Facebook', 'aisa' ); ?>"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M13.5 22v-8.5H16l.5-3.5h-3V7.7c0-1 .3-1.7 1.7-1.7h1.4V2.8c-.6-.1-1.6-.2-2.7-.2-2.5 0-4.1 1.5-4.1 4.3V10H7v3.5h2.8V22h3.7z"/></svg></a></li>
<li><a href="https://www.instagram.com/aisa_ipss/" aria-label="<?php esc_attr_e( 'AISA no Instagram', 'aisa' ); ?>"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true" focusable="false"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor"/></svg></a></li>
</ul>
</div>
<!-- /wp:html -->
