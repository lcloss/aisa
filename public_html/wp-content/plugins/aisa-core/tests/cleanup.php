<?php
/** Remove only the test records created by this suite, including interrupted runs. */
defined( 'ABSPATH' ) || exit;
require_once ABSPATH . 'wp-admin/includes/user.php';
$aisa_test_titles = array( 'AISA teste future', 'AISA teste later', 'AISA teste past', 'AISA teste inválido', 'AISA teste fim', 'Teste sem imagem', 'AISA teste interno', 'AISA teste documento', 'PDF teste AISA', 'AISA REST evento', 'AISA REST sem imagem', 'AISA teste imagem' );
foreach ( get_posts( array( 'post_type' => array( 'aisa_evento', 'aisa_documento', 'aisa_depoimento', 'post', 'attachment' ), 'post_status' => array( 'publish', 'draft', 'private', 'future', 'pending', 'inherit' ), 'numberposts' => -1 ) ) as $aisa_test_post ) {
	if ( in_array( $aisa_test_post->post_title, $aisa_test_titles, true ) ) {
		if ( 'attachment' === $aisa_test_post->post_type ) {
			wp_delete_attachment( $aisa_test_post->ID, true );
		} else {
			wp_delete_post( $aisa_test_post->ID, true );
		}
	}
}
foreach ( get_users( array( 'search' => 'aisa_verification_*', 'search_columns' => array( 'user_login' ) ) ) as $aisa_test_user ) {
	wp_delete_user( $aisa_test_user->ID );
}
foreach ( array( 0, get_current_user_id() ) as $aisa_test_user_id ) {
	delete_transient( 'aisa_notice_' . $aisa_test_user_id );
}
if ( is_file( AISA_CORE_PATH . 'tests/fixtures.json' ) ) {
	unlink( AISA_CORE_PATH . 'tests/fixtures.json' );
}
WP_CLI::success( 'Fixtures removidas.' );
