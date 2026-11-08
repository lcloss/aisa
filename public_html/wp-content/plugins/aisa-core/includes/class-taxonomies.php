<?php
namespace Aisa\Core;
defined( 'ABSPATH' ) || exit;

class Taxonomies {
	public static function boot() {
		// Core lets assign_terms create non-hierarchical terms, including through REST.
		add_filter( 'pre_insert_term', static function ( $term, $taxonomy ) {
			if ( in_array( $taxonomy, array( 'aisa_valencia', 'aisa_tipo_documento' ), true ) && get_current_user_id() && ! current_user_can( 'manage_options' ) ) {
				return new \WP_Error( 'aisa_fixed_terms', __( 'Não tens permissão para criar estes termos.', 'aisa' ), array( 'status' => 403 ) );
			}
			return $term;
		}, 10, 2 );
		add_action( 'enqueue_block_editor_assets', static function () {
			wp_enqueue_script( 'aisa-taxonomies', AISA_CORE_URL . 'assets/js/editor-taxonomies.js', array( 'wp-data', 'wp-dom-ready', 'wp-editor' ), AISA_CORE_VERSION, true );
		} );
		add_action( 'init', array( self::class, 'register' ) );
		add_action( 'add_meta_boxes', array( self::class, 'boxes' ) );
		add_filter( 'rest_pre_insert_post', array( self::class, 'single_rest' ), 10, 2 );
		foreach ( array( 'page', 'aisa_evento', 'aisa_depoimento', 'aisa_documento' ) as $type ) {
			add_filter( 'rest_pre_insert_' . $type, array( self::class, 'single_rest' ), 10, 2 );
		}
		add_action( 'set_object_terms', array( self::class, 'single_term' ), 10, 6 );
	}
	public static function register() {
		foreach ( array( 'aisa_valencia' => array( __( 'Valência', 'aisa' ), array( 'post', 'page', 'aisa_evento', 'aisa_depoimento', 'aisa_documento' ) ), 'aisa_tipo_documento' => array( __( 'Tipo de documento', 'aisa' ), array( 'aisa_documento' ) ) ) as $name => $settings ) {
			register_taxonomy( $name, $settings[1], array(
				'label' => $settings[0], 'public' => false, 'show_ui' => true, 'show_in_rest' => true,
				'hierarchical' => false, 'rewrite' => false, 'meta_box_cb' => false,
				'capabilities' => array( 'manage_terms' => 'manage_options', 'edit_terms' => 'manage_options', 'delete_terms' => 'manage_options', 'assign_terms' => 'edit_posts' ),
			) );
		}
	}
	public static function seed() {
		$terms = array(
			'aisa_valencia' => array( 'sad' => 'Serviço de Apoio Domiciliário', 'centro-dia' => 'Centro de Dia', 'infancia' => 'Infância e Juventude', 'generico' => 'Geral', 'cozinha' => 'Cozinha' ),
			'aisa_tipo_documento' => array( 'estatutos' => 'Estatutos', 'relatorios-contas' => 'Relatórios e Contas', 'regulamentos' => 'Regulamentos', 'assembleia-geral' => 'Assembleia Geral' ),
		);
		foreach ( $terms as $taxonomy => $items ) {
			foreach ( $items as $slug => $name ) {
				if ( ! term_exists( $slug, $taxonomy ) ) {
					wp_insert_term( $name, $taxonomy, array( 'slug' => $slug ) );
				}
			}
		}
	}
	public static function boxes( $type ) {
		foreach ( get_object_taxonomies( $type, 'objects' ) as $taxonomy ) {
			if ( in_array( $taxonomy->name, array( 'aisa_valencia', 'aisa_tipo_documento' ), true ) ) {
				add_meta_box( $taxonomy->name, $taxonomy->label, array( self::class, 'box' ), $type, 'side', 'default', array( 'taxonomy' => $taxonomy->name ) );
			}
		}
	}
	public static function box( $post, $box ) {
		$name = $box['args']['taxonomy'];
		wp_nonce_field( 'aisa_terms', 'aisa_terms_nonce' );
		$current = wp_get_object_terms( $post->ID, $name, array( 'fields' => 'ids' ) );
		echo '<select aria-label="' . esc_attr( get_taxonomy( $name )->label ) . '" name="aisa_terms[' . esc_attr( $name ) . ']">';
		echo '<option value="0">' . esc_html__( 'Selecionar…', 'aisa' ) . '</option>';
		foreach ( get_terms( array( 'taxonomy' => $name, 'hide_empty' => false ) ) as $term ) {
			echo '<option value="' . esc_attr( $term->term_id ) . '" ' . selected( in_array( $term->term_id, $current, true ), true, false ) . '>' . esc_html( $term->name ) . '</option>';
		}
		echo '</select>';
	}
	public static function single_rest( $post, $request ) {
		if ( count( (array) $request->get_param( 'aisa_valencia' ) ) > 1 ) {
			return new \WP_Error( 'aisa_single_valencia', __( 'Seleciona apenas uma valência.', 'aisa' ), array( 'status' => 400 ) );
		}
		return $post;
	}
	public static function single_term( $id, $terms, $tt_ids, $taxonomy, $append, $old ) {
		if ( 'aisa_valencia' === $taxonomy && count( wp_get_object_terms( $id, $taxonomy, array( 'fields' => 'ids' ) ) ) > 1 ) {
			$term = get_term_by( 'term_taxonomy_id', reset( $tt_ids ), $taxonomy );
			wp_set_object_terms( $id, array( (int) $term->term_id ), $taxonomy );
		}
	}
}
