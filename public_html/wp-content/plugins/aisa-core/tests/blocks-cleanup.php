<?php
defined( 'ABSPATH' ) || exit;
$file = AISA_CORE_PATH . 'tests/blocks-fixtures.json';
if ( ! is_file( $file ) ) { return; }
$data = json_decode( file_get_contents( $file ), true );
WP_Session_Tokens::get_instance( $data['user'] )->destroy( $data['token'] );
foreach ( array_reverse( $data['ids'] ) as $id ) {
 if ( 'attachment' === get_post_type( $id ) ) { wp_delete_attachment( $id, true ); } else { wp_delete_post( $id, true ); }
}
unlink( $file );
WP_CLI::success( 'Conteúdos e sessão temporários removidos.' );
