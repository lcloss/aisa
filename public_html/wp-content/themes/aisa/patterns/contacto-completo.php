<?php
/**
 * Title: Contactos completos e mapa local
 * Slug: aisa/contacto-completo
 * Categories: aisa
 */
defined( 'ABSPATH' ) || exit;
echo '<!-- wp:aisa/telefone {"variante":"grande"} /-->';
echo aisa_page_text( __( 'Chamada para a rede fixa nacional.', 'aisa' ) );
echo aisa_page_text( '<a href="mailto:geral@aisaipss.pt">geral@aisaipss.pt</a>' );
echo aisa_page_heading( __( 'Onde estamos', 'aisa' ) );
echo aisa_page_text( __( 'Rua de Cascais, n.º 877, Malveira da Serra, 2755-162 Alcabideche', 'aisa' ) );
echo aisa_page_text( __( 'Horário de atendimento: das 08h30 às 11h30 e das 13h00 às 18h00.', 'aisa' ) );
echo aisa_page_image( get_theme_file_uri( 'assets/img/mapa-aisa.png' ), __( 'Mapa: AISA, Rua de Cascais, n.º 877, Malveira da Serra — localização esquemática, sem escala', 'aisa' ) );
echo aisa_page_text( '<a href="https://www.google.com/maps/search/?api=1&amp;query=AISA%20Rua%20de%20Cascais%20877%20Malveira%20da%20Serra" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Abrir no Google Maps', 'aisa' ) . '</a> (' . esc_html__( 'abre noutra janela', 'aisa' ) . ')' );
echo aisa_page_heading( __( 'Envie-nos uma mensagem', 'aisa' ) );
echo aisa_page_text( __( 'Prefere falar? Ligue', 'aisa' ) . ' <a href="tel:+351214857700">214 857 700</a>.' );
echo aisa_contact_form_block( 'contacto' );
echo aisa_page_heading( __( 'Informação do prestador', 'aisa' ) );
echo aisa_page_text( __( 'AISA — Associação de Apoio Social Nossa Senhora da Assunção', 'aisa' ) );
echo aisa_page_text( __( 'NIPC: [A CONFIRMAR — BQ2]', 'aisa' ) );
// Livro de Reclamações: aguarda A9; no invented destination.
