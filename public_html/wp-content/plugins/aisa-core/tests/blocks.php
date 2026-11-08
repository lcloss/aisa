<?php
/** Integration and temporary HTTP fixtures: wp --path=public_html eval-file <file>. */
defined( 'ABSPATH' ) || exit;
function aisa_block_check( $ok, $message ) {
 if ( ! $ok ) { WP_CLI::error( $message ); }
 WP_CLI::log( 'PASS ' . $message );
}
function aisa_test_block( $name, $attrs = array() ) {
 return do_blocks( '<!-- wp:aisa/' . $name . ' ' . wp_json_encode( (object) $attrs ) . ' /-->' );
}
$registry = WP_Block_Type_Registry::get_instance();
foreach ( array( 'telefone', 'provas', 'slideshow', 'documentos', 'regulamentos', 'depoimentos', 'proximos-eventos' ) as $name ) {
 $type = $registry->get_registered( 'aisa/' . $name );
 aisa_block_check( $type && 3 === $type->api_version && $type->is_dynamic(), 'Registado aisa/' . $name );
 aisa_block_check( (bool) wp_scripts()->registered[ $type->editor_script_handles[0] ]->deps, 'Dependências editor ' . $name );
}
$ids = array();
register_shutdown_function( static function () use ( &$ids ) { if ( ! is_file( AISA_CORE_PATH . 'tests/blocks-fixtures.json' ) ) { foreach ( array_reverse( $ids ) as $id ) { if ( 'attachment' === get_post_type( $id ) ) { wp_delete_attachment( $id, true ); } else { wp_delete_post( $id, true ); } } } } );
wp_set_current_user( get_users( array( 'role' => 'administrator', 'number' => 1 ) )[0]->ID );
// Isolate the threshold fixture from any real testimonials, without editing them.
$existing = (int) wp_count_posts( 'aisa_depoimento' )->publish;
aisa_block_check( 0 === $existing, 'Base de desenvolvimento sem depoimentos publicados (teste 2/3 isolado)' );
for ( $i = 0; $i < 2; $i++ ) {
 $ids[] = wp_insert_post( array( 'post_type' => 'aisa_depoimento', 'post_status' => 'publish', 'post_title' => 'NOME INTERNO NUNCA PUBLICAR', 'post_content' => 'AISA depoimento de teste ' . $i, 'meta_input' => array( '_aisa_dep_relacao' => 'Familiar de utente' ) ) );
 wp_set_object_terms( end( $ids ), 'sad', 'aisa_valencia' );
}
aisa_block_check( '' === aisa_test_block( 'depoimentos', array( 'titulo' => 'Título também escondido' ) ), 'Com 2 depoimentos não sai HTML nem título' );
$ids[] = wp_insert_post( array( 'post_type' => 'aisa_depoimento', 'post_status' => 'publish', 'post_title' => 'NOME INTERNO NUNCA PUBLICAR', 'post_content' => 'AISA terceiro depoimento', 'meta_input' => array( '_aisa_dep_relacao' => 'Familiar de utente' ) ) );
$html = aisa_test_block( 'depoimentos', array( 'titulo' => 'Testemunhos', 'quantidade' => 3 ) );
aisa_block_check( 3 === substr_count( $html, '<figure' ) && str_contains( $html, 'Testemunhos' ) && ! str_contains( $html, 'NOME INTERNO' ), 'Com 3 aparecem depoimentos e título, sem nomes internos' );
aisa_block_check( 2 === substr_count( aisa_test_block( 'depoimentos', array( 'valencia' => 'sad', 'quantidade' => 3 ) ), '<figure' ), 'Filtro de valência não altera limiar global' );
foreach ( array( 'past' => '-3 days', 'future' => '+3 days', 'later' => '+5 days' ) as $name => $offset ) {
 $events[ $name ] = wp_insert_post( array( 'post_type' => 'aisa_evento', 'post_status' => 'publish', 'post_title' => 'AISA evento ' . $name, 'meta_input' => array( '_aisa_evento_inicio' => wp_date( 'Y-m-d', strtotime( $offset ) ) . 'T14:00', '_aisa_evento_local' => 'Sala de teste' ) ) );
 $ids[] = $events[ $name ];
}
$html = aisa_test_block( 'proximos-eventos' );
aisa_block_check( ! str_contains( $html, 'AISA evento past' ) && str_contains( $html, 'AISA evento future' ) && strpos( $html, 'AISA evento future' ) < strpos( $html, 'AISA evento later' ), 'Só eventos futuros, ordenados pelo início' );
aisa_block_check( str_contains( aisa_test_block( 'proximos-eventos', array( 'valencia' => 'infancia' ) ), '/noticias/' ), 'Estado vazio com ligação para notícias' );
$upload = wp_upload_bits( 'aisa-block-test.pdf', null, "%PDF-1.4\n%%EOF\n" );
$pdf = wp_insert_attachment( array( 'post_title' => 'Documento de teste', 'post_mime_type' => 'application/pdf', 'post_status' => 'inherit' ), $upload['file'] );
update_attached_file( $pdf, $upload['file'] ); $ids[] = $pdf;
foreach ( array( 'sad' => 2026, 'generico' => 2025, 'centro-dia' => 2024 ) as $slug => $year ) {
 $id = wp_insert_post( array( 'post_type' => 'aisa_documento', 'post_status' => 'publish', 'post_title' => 'AISA regulamento ' . $slug, 'meta_input' => array( '_aisa_doc_ficheiro' => $pdf, '_aisa_doc_ano' => $year ) ) );
 $ids[] = $id; wp_set_object_terms( $id, $slug, 'aisa_valencia' ); wp_set_object_terms( $id, 'regulamentos', 'aisa_tipo_documento' );
}
$html = aisa_test_block( 'documentos' );
aisa_block_check( 3 === substr_count( $html, 'class="aisa-download"' ) && str_contains( $html, 'PDF, 15 B' ) && str_contains( $html, 'aria-describedby=' ), 'Downloads com formato/tamanho real e descrição acessível' );
aisa_block_check( strpos( $html, 'AISA regulamento sad' ) < strpos( $html, 'AISA regulamento generico' ), 'Documentos por ano descendente' );
aisa_block_check( ! str_contains( aisa_test_block( 'documentos', array( 'tipos' => array( 'estatutos' ) ) ), 'aisa-download' ), 'Filtro por tipo' );
$image_upload = wp_upload_bits( 'aisa-block-test.jpg', null, file_get_contents( get_theme_file_path( 'assets/img/default.jpg' ) ) );
$image = wp_insert_attachment( array( 'post_title' => 'Imagem de teste', 'post_mime_type' => 'image/jpeg', 'post_status' => 'inherit' ), $image_upload['file'] );
update_attached_file( $image, $image_upload['file'] ); $ids[] = $image;
$slides = array( array( 'id' => $image, 'alt' => 'Fotografia de teste um' ), array( 'id' => $image, 'alt' => 'Fotografia de teste dois' ) );
$html = aisa_test_block( 'slideshow', array( 'slides' => $slides ) );
aisa_block_check( 2 === substr_count( $html, '<figure' ) && str_contains( $html, 'fetchpriority="high"' ) && str_contains( $html, 'loading="eager"' ) && str_contains( $html, 'loading="lazy"' ) && ! str_contains( $html, '<figure hidden' ), 'Slideshow: prioridade inicial, lazy e todas as imagens sem JS' );
aisa_block_check( str_contains( aisa_test_block( 'telefone' ), 'class="aisa-tel ' ) && str_contains( aisa_test_block( 'telefone' ), 'tel:+351214857700' ), 'Telefone com classe de medição' );
$filter = static function () { return '+351219999999'; }; add_filter( 'aisa_telefone', $filter );
aisa_block_check( str_contains( aisa_test_block( 'telefone' ), 'tel:+351219999999' ), 'Telefone filtrável' ); remove_filter( 'aisa_telefone', $filter );
$a = array( 'items' => array( array( 'numero' => '30', 'legenda' => 'anos de teste' ) ), 'ano' => 'Dados de 2025' );
aisa_block_check( str_contains( aisa_test_block( 'provas', $a ), 'Dados de 2025' ) && '' === aisa_test_block( 'provas', array( 'items' => $a['items'] ) ), 'Provas sempre datadas' );
$content = '';
foreach ( array( 'slideshow' => array( 'slides' => $slides ), 'provas' => $a, 'depoimentos' => array( 'titulo' => 'Testemunhos de teste', 'quantidade' => 3 ), 'proximos-eventos' => array(), 'documentos' => array(), 'regulamentos' => array(), 'telefone' => array() ) as $name => $attrs ) {
 $content .= '<!-- wp:aisa/' . $name . ' ' . wp_json_encode( (object) $attrs ) . ' /-->';
}
$page = wp_insert_post( array( 'post_type' => 'page', 'post_status' => 'draft', 'post_title' => 'Verificação dos blocos AISA (teste)', 'post_content' => $content ) );
wp_set_object_terms( $page, 'sad', 'aisa_valencia' ); $GLOBALS['post'] = get_post( $page );
$html = aisa_test_block( 'regulamentos' );
aisa_block_check( str_contains( $html, 'AISA regulamento sad' ) && str_contains( $html, 'AISA regulamento generico' ) && ! str_contains( $html, 'AISA regulamento centro-dia' ), 'Regulamentos: valência da página mais Geral' );
$ids[] = $page;
// Keep draft as requested; authenticate HTTP previews with a disposable administrator session.
$admin = wp_get_current_user(); $token = WP_Session_Tokens::get_instance( $admin->ID )->create( time() + HOUR_IN_SECONDS );
$cookie = wp_generate_auth_cookie( $admin->ID, time() + HOUR_IN_SECONDS, 'logged_in', $token );
file_put_contents( AISA_CORE_PATH . 'tests/blocks-fixtures.json', wp_json_encode( array( 'ids' => $ids, 'page' => $page, 'url' => home_url( '/?page_id=' . $page . '&preview=true' ), 'cookie_name' => LOGGED_IN_COOKIE, 'cookie' => $cookie, 'user' => $admin->ID, 'token' => $token ) ) );
WP_CLI::success( 'Integração dos blocos concluída; rascunho ' . $page . '. Limpar com blocks-cleanup.php.' );
