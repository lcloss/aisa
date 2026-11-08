<?php
defined( 'ABSPATH' ) || exit;
$data = json_decode( file_get_contents( AISA_CORE_PATH . 'tests/blocks-fixtures.json' ), true );
wp_set_current_user( $data['user'] );
foreach ( WP_Block_Type_Registry::get_instance()->get_all_registered() as $name => $type ) {
 if ( str_starts_with( $name, 'aisa/' ) ) { WP_CLI::log( $name ); }
}
foreach ( parse_blocks( get_post_field( 'post_content', $data['page'] ) ) as $block ) {
 if ( empty( $block['blockName'] ) ) { continue; }
 $request = new WP_REST_Request( 'GET', '/wp/v2/block-renderer/' . $block['blockName'] );
 $request->set_param( 'context', 'edit' );
 $request->set_param( 'attributes', $block['attrs'] );
 $request->set_param( 'post_id', $data['page'] );
 $response = rest_do_request( $request );
 if ( 200 !== $response->get_status() || ! array_key_exists( 'rendered', $response->get_data() ) ) { WP_CLI::log( wp_json_encode( $response->get_data() ) ); WP_CLI::error( 'Preview REST ' . $block['blockName'] ); }
 WP_CLI::log( 'PASS ServerSideRender REST ' . $block['blockName'] );
}
