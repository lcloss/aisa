<?php
/**
 * Title: Ajuda em páginas vazias e pesquisa
 * Slug: aisa/pagina-vazia-404
 * Categories: aisa
 * Inserter: no
 */
defined( 'ABSPATH' ) || exit;
if ( ! is_search() ) { echo aisa_page_heading( __( 'Não encontrámos esta página', 'aisa' ), 1 ); }
echo aisa_page_text( __( 'Pode pesquisar ou seguir uma destas ligações. Se precisar de ajuda, ligue-nos.', 'aisa' ) );
?>
<!-- wp:search {"label":"Pesquisar no site","buttonText":"Pesquisar"} /-->
<?php
echo aisa_page_list( array( aisa_page_link( 'servicos', __( 'Serviços', 'aisa' ) ), aisa_page_link( 'noticias', __( 'Notícias', 'aisa' ) ), aisa_page_link( 'contactos', __( 'Contactos', 'aisa' ) ) ) );
echo '<!-- wp:aisa/telefone {"variante":"grande"} /-->';
