<?php
/** Run with wp --path=public_html eval-file <this file>. Fixtures retained for HTTP checks. */
defined( 'ABSPATH' ) || exit;
$aisa_ids = array();
function aisa_check( $condition, $message ) {
	if ( ! $condition ) {
		WP_CLI::error( $message );
	}
	WP_CLI::log( 'PASS ' . $message );
}
$admin = get_users( array( 'role' => 'administrator', 'number' => 1 ) )[0];
wp_set_current_user( $admin->ID );
$future = wp_date( 'Y-m-d', strtotime( '+3 days' ) ) . 'T14:00';
$past = wp_date( 'Y-m-d', strtotime( '-3 days' ) ) . 'T14:00';
foreach ( array( 'future' => $future, 'later' => wp_date( 'Y-m-d', strtotime( '+5 days' ) ) . 'T14:00', 'past' => $past ) as $name => $date ) {
	$aisa_ids[ $name ] = wp_insert_post( array( 'post_type' => 'aisa_evento', 'post_title' => 'AISA teste ' . $name, 'post_name' => 'aisa-teste-' . $name, 'post_status' => 'publish', 'meta_input' => array( '_aisa_evento_inicio' => $date, '_aisa_evento_local' => 'AISA — sala de teste' ) ) );
	aisa_check( 'publish' === get_post_status( $aisa_ids[ $name ] ), 'Evento válido ' . $name );
}
$aisa_ids['invalid'] = wp_insert_post( array( 'post_type' => 'aisa_evento', 'post_title' => 'AISA teste inválido', 'post_status' => 'publish' ) );
aisa_check( 'draft' === get_post_status( $aisa_ids['invalid'] ), 'Evento sem início fica em rascunho' );
$aisa_ids['invalid_end'] = wp_insert_post( array( 'post_type' => 'aisa_evento', 'post_title' => 'AISA teste fim', 'post_status' => 'publish', 'meta_input' => array( '_aisa_evento_inicio' => $future, '_aisa_evento_fim' => $past ) ) );
aisa_check( 'draft' === get_post_status( $aisa_ids['invalid_end'] ), 'Fim anterior ao início bloqueado' );
$aisa_ids['news'] = wp_insert_post( array( 'post_type' => 'post', 'post_title' => 'Teste sem imagem', 'post_status' => 'publish' ) );
aisa_check( 'draft' === get_post_status( $aisa_ids['news'] ), 'Notícia sem imagem fica em rascunho' );
$aisa_ids['testimonial'] = wp_insert_post( array( 'post_type' => 'aisa_depoimento', 'post_title' => 'AISA teste interno', 'post_content' => 'Texto de teste sem dados pessoais.', 'post_status' => 'publish', 'meta_input' => array( '_aisa_dep_relacao' => 'Filha de um utente', '_aisa_dep_data' => '2026-11' ) ) );
aisa_check( 'publish' === get_post_status( $aisa_ids['testimonial'] ), 'Depoimento com relação' );
$upload = wp_upload_bits( 'aisa-verification.pdf', null, "%PDF-1.4\n%%EOF\n" );
$aisa_ids['attachment'] = wp_insert_attachment( array( 'post_title' => 'PDF teste AISA', 'post_mime_type' => 'application/pdf', 'post_status' => 'inherit' ), $upload['file'] );
update_attached_file( $aisa_ids['attachment'], $upload['file'] );
$aisa_ids['document'] = wp_insert_post( array( 'post_type' => 'aisa_documento', 'post_title' => 'AISA teste documento', 'post_status' => 'publish', 'meta_input' => array( '_aisa_doc_ficheiro' => $aisa_ids['attachment'], '_aisa_doc_ano' => 2026 ) ) );
aisa_check( 'publish' === get_post_status( $aisa_ids['document'] ), 'Documento válido' );
$info = aisa_doc_file_info( $aisa_ids['document'] );
aisa_check( 'pdf' === $info['ext'] && '15 B' === $info['size_human'], 'Extensão e tamanho real do PDF' );
aisa_check( 0 === Aisa\Core\Meta::sanitize( '_aisa_doc_ficheiro', $aisa_ids['future'] ), 'Ficheiro deve ser anexo permitido' );
aisa_check( '' === Aisa\Core\Meta::sanitize( '_aisa_evento_inicio', '2026-02-30T12:00' ), 'Data impossível recusada' );
aisa_check( 0 === Aisa\Core\Meta::sanitize( '_aisa_doc_ano', '1999' ), 'Ano fora do intervalo recusado' );
$term = get_term_by( 'slug', 'sad', 'aisa_valencia' );
wp_set_object_terms( $aisa_ids['future'], array( $term->term_id ), 'aisa_valencia' );
aisa_check( 'sad' === aisa_get_valencia( $aisa_ids['future'] )->slug, 'Helper de valência' );
aisa_check( aisa_evento_is_future( $aisa_ids['future'] ) && ! aisa_evento_is_future( $aisa_ids['past'] ), 'Helper de evento futuro' );
$GLOBALS['post'] = get_post( $aisa_ids['future'] );
$html = render_block( array( 'blockName' => 'core/group', 'attrs' => array( 'className' => 'aisa-valencia-scope' ), 'innerBlocks' => array(), 'innerHTML' => '<div class="wp-block-group aisa-valencia-scope"></div>', 'innerContent' => array( '<div class="wp-block-group aisa-valencia-scope"></div>' ) ) );
aisa_check( str_contains( $html, 'data-valencia="sad"' ), 'Atributo no contentor do bloco' );
$query = new WP_Query();
$query->parse_query( array( 'post_type' => 'aisa_evento' ) );
$GLOBALS['wp_the_query'] = $query;
Aisa\Core\Post_Types::agenda( $query );
$result = $query->get_posts();
$found = wp_list_pluck( $result, 'ID' );
aisa_check( in_array( $aisa_ids['future'], $found, true ) && ! in_array( $aisa_ids['past'], $found, true ) && array_search( $aisa_ids['future'], $found, true ) < array_search( $aisa_ids['later'], $found, true ), 'Agenda apenas futura e ordenada' );
function aisa_rest_test( $route, $data ) {
	$request = new WP_REST_Request( 'POST', $route );
	$request->set_body_params( $data );
	return rest_do_request( $request );
}
$response = aisa_rest_test( '/wp/v2/aisa_evento', array( 'title' => 'AISA REST evento', 'status' => 'publish', 'meta' => array( '_aisa_evento_inicio' => $future ) ) );
aisa_check( 201 === $response->get_status() && 'publish' === $response->get_data()['status'], 'REST publica evento com meta do pedido' );
$aisa_ids['rest_event'] = $response->get_data()['id'];
$response = aisa_rest_test( '/wp/v2/posts', array( 'title' => 'AISA REST sem imagem', 'status' => 'publish' ) );
aisa_check( 201 === $response->get_status() && 'draft' === $response->get_data()['status'], 'REST bloqueia notícia sem imagem' );
$aisa_ids['rest_news'] = $response->get_data()['id'];
require_once ABSPATH . 'wp-admin/includes/image.php';
$image = wp_upload_bits( 'aisa-verification.jpg', null, file_get_contents( get_theme_file_path( 'assets/img/default.jpg' ) ) );
$aisa_ids['image'] = wp_insert_attachment( array( 'post_title' => 'AISA teste imagem', 'post_mime_type' => 'image/jpeg', 'post_status' => 'inherit' ), $image['file'] );
update_attached_file( $aisa_ids['image'], $image['file'] );
wp_update_attachment_metadata( $aisa_ids['image'], wp_generate_attachment_metadata( $aisa_ids['image'], $image['file'] ) );
$response = aisa_rest_test( '/wp/v2/posts/' . $aisa_ids['rest_news'], array( 'status' => 'publish', 'featured_media' => $aisa_ids['image'] ) );
aisa_check( 200 === $response->get_status() && 'publish' === $response->get_data()['status'], 'REST publica notícia com imagem no mesmo pedido' );
$response = aisa_rest_test( '/wp/v2/posts/' . $aisa_ids['rest_news'], array( 'featured_media' => 0 ) );
aisa_check( 'draft' === $response->get_data()['status'], 'REST retirar imagem faz regressar a rascunho' );
$response = aisa_rest_test( '/wp/v2/aisa_evento/' . $aisa_ids['rest_event'], array( 'meta' => array( '_aisa_evento_inicio' => '' ) ) );
aisa_check( 'draft' === $response->get_data()['status'], 'REST retirar início faz regressar a rascunho' );
require_once ABSPATH . 'wp-admin/includes/user.php';
$editor_id = wp_insert_user( array( 'user_login' => 'aisa_verification_' . wp_generate_password( 8, false ), 'user_pass' => wp_generate_password(), 'role' => 'editor' ) );
wp_set_current_user( $editor_id );
foreach ( array( 'aisa_valencia', 'aisa_tipo_documento' ) as $taxonomy ) {
	$tax = get_taxonomy( $taxonomy );
	aisa_check( ! current_user_can( $tax->cap->manage_terms ) && ! current_user_can( $tax->cap->edit_terms ) && ! current_user_can( $tax->cap->delete_terms ) && current_user_can( $tax->cap->assign_terms ), 'Editor só atribui ' . $taxonomy );
	$response = aisa_rest_test( '/wp/v2/' . $taxonomy, array( 'name' => 'Termo proibido' ) );
	aisa_check( 403 === $response->get_status(), 'REST recusa criar termo ' . $taxonomy );
}
wp_set_current_user( $admin->ID );
wp_delete_user( $editor_id );
file_put_contents( AISA_CORE_PATH . 'tests/fixtures.json', wp_json_encode( $aisa_ids ) );
WP_CLI::success( 'Integração concluída. IDs em tests/fixtures.json para HTTP e limpeza.' );
