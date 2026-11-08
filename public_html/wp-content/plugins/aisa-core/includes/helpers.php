<?php
/** Public template API. */
defined( 'ABSPATH' ) || exit;

function aisa_get_valencia( $post = null ): ?WP_Term {
	$post = get_post( $post );
	if ( ! $post ) {
		return null;
	}
	$terms = wp_get_object_terms( $post->ID, 'aisa_valencia' );
	return ! is_wp_error( $terms ) && $terms ? reset( $terms ) : null;
}

function aisa_evento_is_future( $post_id ) {
	$start = get_post_meta( $post_id, '_aisa_evento_inicio', true );
	return 'aisa_evento' === get_post_type( $post_id ) && $start && $start >= wp_date( 'Y-m-d', null, new DateTimeZone( 'Europe/Lisbon' ) ) . 'T00:00';
}

function aisa_doc_file_info( $post_id ) {
	$id = (int) get_post_meta( $post_id, '_aisa_doc_ficheiro', true );
	$file = get_attached_file( $id );
	$url = wp_get_attachment_url( $id );
	if ( ! $file || ! is_file( $file ) || ! is_readable( $file ) || ! $url ) {
		return array();
	}
	$bytes = filesize( $file );
	if ( false === $bytes ) {
		return array();
	}
	$units = array( 'B', 'KB', 'MB', 'GB', 'TB' );
	$power = $bytes > 0 ? min( 4, (int) floor( log( $bytes, 1024 ) ) ) : 0;
	return array( 'url' => $url, 'ext' => strtolower( pathinfo( $file, PATHINFO_EXTENSION ) ), 'size_human' => number_format( $bytes / pow( 1024, $power ), $power ? 1 : 0, ',', ' ' ) . ' ' . $units[ $power ] );
}
