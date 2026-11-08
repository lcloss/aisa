<?php
namespace Aisa\Core;
defined( 'ABSPATH' ) || exit;

class Featured_Required {
	public static function boot() {
		add_filter( 'wp_insert_post_data', array( self::class, 'validate' ), 20, 2 );
		add_action( 'enqueue_block_editor_assets', static function () {
			if ( 'post' === get_post_type() ) {
				wp_enqueue_script( 'aisa-featured', AISA_CORE_URL . 'assets/js/editor-featured-required.js', array( 'wp-data', 'wp-dom-ready', 'wp-i18n', 'wp-notices', 'wp-edit-post' ), AISA_CORE_VERSION, true );
			}
		} );
	}
	public static function validate( $data, $postarr ) {
		if ( 'post' !== $data['post_type'] || ! in_array( $data['post_status'], array( 'publish', 'future', 'private' ), true ) || ( defined( 'AISA_IMPORTING' ) && AISA_IMPORTING ) ) {
			return $data;
		}
		$id = (int) ( $postarr['ID'] ?? 0 );
		$thumbnail = Meta::$rest_values['thumbnail'] ?? $postarr['_thumbnail_id'] ?? $postarr['meta_input']['_thumbnail_id'] ?? get_post_thumbnail_id( $id );
		if ( isset( $_POST['_thumbnail_id'], $_POST['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ), 'update-post_' . $id ) && current_user_can( 'edit_post', $id ) ) {
			$thumbnail = (int) $_POST['_thumbnail_id'];
		}
		if ( ! $thumbnail || ! wp_attachment_is_image( $thumbnail ) ) {
			$data['post_status'] = 'draft';
			Meta::notice( __( 'A notícia precisa de uma imagem destacada (com texto alternativo)', 'aisa' ) );
		}
		return $data;
	}
}
