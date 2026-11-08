<?php
namespace Aisa\Core;
defined( 'ABSPATH' ) || exit;

class Blocks {
	public static function boot() {
		add_action( 'init', array( self::class, 'register' ) );
		add_filter( 'block_categories_all', static function ( $categories ) {
			$categories[] = array( 'slug' => 'aisa', 'title' => __( 'AISA', 'aisa' ) );
			return $categories;
		} );
	}
	public static function register() {
		if ( ! defined( 'AISA_TELEFONE' ) ) {
			define( 'AISA_TELEFONE', '+351214857700' );
		}
		foreach ( array( 'telefone', 'provas', 'proximos-eventos', 'depoimentos', 'documentos', 'regulamentos', 'slideshow' ) as $name ) {
			register_block_type( AISA_CORE_PATH . 'blocks/' . $name );
		}
	}
	public static function valencia_query( $slug ) {
		return $slug ? array( array( 'taxonomy' => 'aisa_valencia', 'field' => 'slug', 'terms' => sanitize_title( $slug ) ) ) : array();
	}
	/** Shared escaped document markup, grouped by type and ordered by year. */
	public static function documents( $attributes, $regulations = false, $post_id = 0 ) {
		$types = $regulations ? array( 'regulamentos' ) : array_map( 'sanitize_title', (array) ( $attributes['tipos'] ?? array() ) );
		$terms = get_terms( array( 'taxonomy' => 'aisa_tipo_documento', 'hide_empty' => true, 'slug' => $types ) );
		if ( is_wp_error( $terms ) ) { return; }
		$valencia = $regulations ? aisa_get_valencia( $post_id ) : null;
		$slugs = $regulations ? array_unique( array_filter( array( 'generico', $valencia ? $valencia->slug : '' ) ) ) : array_filter( array( sanitize_title( $attributes['valencia'] ?? '' ) ) );
		foreach ( $terms as $term ) {
			$tax = array( array( 'taxonomy' => 'aisa_tipo_documento', 'field' => 'term_id', 'terms' => $term->term_id ) );
			if ( $slugs ) { $tax[] = array( 'taxonomy' => 'aisa_valencia', 'field' => 'slug', 'terms' => $slugs ); }
			$documents = get_posts( array( 'post_type' => 'aisa_documento', 'post_status' => 'publish', 'posts_per_page' => 100, 'no_found_rows' => true, 'tax_query' => $tax, 'meta_key' => '_aisa_doc_ano', 'orderby' => array( 'meta_value_num' => 'DESC', 'title' => 'ASC' ) ) );
			$rows = '';
			foreach ( $documents as $document ) {
				$info = aisa_doc_file_info( $document->ID );
				if ( ! $info ) { continue; }
				$id = wp_unique_id( 'aisa-file-' );
				$rows .= '<li><a download class="aisa-download" href="' . esc_url( $info['url'] ) . '" data-file-ext="' . esc_attr( $info['ext'] ) . '" aria-describedby="' . esc_attr( $id ) . '"><span class="doc-list__name">' . esc_html( get_the_title( $document ) ) . '</span><span>' . esc_html( get_post_meta( $document->ID, '_aisa_doc_ano', true ) ) . '</span><span class="doc-list__meta" id="' . esc_attr( $id ) . '">' . esc_html( strtoupper( $info['ext'] ) . ', ' . $info['size_human'] ) . '</span></a></li>';
			}
			if ( $rows ) {
				echo '<section class="doc-group"><h2 class="doc-group__title">' . esc_html( $term->name ) . '</h2><ul class="doc-list">' . $rows . '</ul></section>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Rows escaped above.
			}
		}
	}
}
