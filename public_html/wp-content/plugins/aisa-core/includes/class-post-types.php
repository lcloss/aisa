<?php
namespace Aisa\Core;
defined( 'ABSPATH' ) || exit;

class Post_Types {
	public static function boot() {
		add_action( 'init', array( self::class, 'register' ) );
		add_action( 'pre_get_posts', array( self::class, 'agenda' ) );
	}
	public static function register() {
		$types = array(
			'aisa_evento' => array( __( 'Eventos', 'aisa' ), __( 'Evento', 'aisa' ), __( 'Novo evento', 'aisa' ), array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields' ), 'dashicons-calendar-alt' ),
			'aisa_depoimento' => array( __( 'Depoimentos', 'aisa' ), __( 'Depoimento', 'aisa' ), __( 'Novo depoimento', 'aisa' ), array( 'title', 'editor', 'custom-fields' ), 'dashicons-format-quote' ),
			'aisa_documento' => array( __( 'Documentos', 'aisa' ), __( 'Documento', 'aisa' ), __( 'Novo documento', 'aisa' ), array( 'title', 'custom-fields' ), 'dashicons-media-document' ),
		);
		foreach ( $types as $type => $settings ) {
			$public = 'aisa_evento' === $type;
			register_post_type( $type, array(
				'labels' => array( 'name' => $settings[0], 'singular_name' => $settings[1], 'add_new_item' => $settings[2], 'edit_item' => sprintf( __( 'Editar %s', 'aisa' ), $settings[1] ), 'search_items' => sprintf( __( 'Pesquisar %s', 'aisa' ), $settings[0] ), 'not_found' => __( 'Não foram encontrados conteúdos.', 'aisa' ) ),
				'public' => $public, 'publicly_queryable' => $public, 'exclude_from_search' => ! $public,
				'show_ui' => true, 'show_in_rest' => true, 'supports' => $settings[3], 'menu_icon' => $settings[4],
				'has_archive' => $public ? 'agenda' : false, 'rewrite' => $public ? array( 'slug' => 'agenda', 'with_front' => false ) : false,
			) );
		}
	}
	public static function agenda( $query ) {
		if ( is_admin() || ! $query->is_main_query() || ! $query->is_post_type_archive( 'aisa_evento' ) ) {
			return;
		}
		$query->set( 'meta_key', '_aisa_evento_inicio' );
		$query->set( 'orderby', 'meta_value' );
		$query->set( 'order', 'ASC' );
		$query->set( 'meta_query', array( 'relation' => 'AND', (array) $query->get( 'meta_query' ), array( 'key' => '_aisa_evento_inicio', 'value' => wp_date( 'Y-m-d', null, new \DateTimeZone( 'Europe/Lisbon' ) ) . 'T00:00', 'compare' => '>=' ) ) );
	}
}
