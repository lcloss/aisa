<?php
namespace Aisa\Core;
defined( 'ABSPATH' ) || exit;

class Meta {
	/** Values supplied by REST before core writes the post and its metadata. */
	public static $rest_values = null;
	public static function fields() {
		return array(
			'aisa_evento' => array( '_aisa_evento_inicio' => array( __( 'Início', 'aisa' ), 'datetime-local', true ), '_aisa_evento_fim' => array( __( 'Fim', 'aisa' ), 'datetime-local', false ), '_aisa_evento_local' => array( __( 'Local', 'aisa' ), 'text', false, 120 ) ),
			'aisa_depoimento' => array( '_aisa_dep_relacao' => array( __( 'Relação', 'aisa' ), 'text', true, 80 ), '_aisa_dep_data' => array( __( 'Mês e ano', 'aisa' ), 'month', false ) ),
			'aisa_documento' => array( '_aisa_doc_ficheiro' => array( __( 'Ficheiro', 'aisa' ), 'number', true ), '_aisa_doc_ano' => array( __( 'Ano', 'aisa' ), 'number', true ) ),
		);
	}
	public static function boot() {
		add_action( 'init', array( self::class, 'register' ) );
		add_action( 'add_meta_boxes', array( self::class, 'boxes' ) );
		add_action( 'save_post', array( self::class, 'save' ), 10, 2 );
		add_filter( 'wp_insert_post_data', array( self::class, 'validate' ), 20, 2 );
		add_action( 'admin_notices', array( self::class, 'notices' ) );
		add_action( 'admin_enqueue_scripts', array( self::class, 'assets' ) );
		foreach ( array( 'post', 'aisa_evento', 'aisa_depoimento', 'aisa_documento' ) as $type ) {
			add_filter( 'rest_pre_insert_' . $type, array( self::class, 'rest_prepare' ), 20, 2 );
			add_action( 'rest_after_insert_' . $type, static function () { self::$rest_values = null; } );
		}
		// Also clear the context after REST errors (including batch subrequests).
		add_filter( 'rest_request_after_callbacks', static function ( $response ) { self::$rest_values = null; return $response; } );
		foreach ( array( 'aisa_evento', 'aisa_depoimento', 'aisa_documento' ) as $type ) {
			add_filter( 'manage_' . $type . '_posts_columns', array( self::class, 'columns' ) );
			add_action( 'manage_' . $type . '_posts_custom_column', array( self::class, 'column' ), 10, 2 );
		}
	}
	public static function register() {
		foreach ( self::fields() as $type => $fields ) {
			foreach ( $fields as $key => $field ) {
				register_post_meta( $type, $key, array(
					'type' => 'number' === $field[1] ? 'integer' : 'string', 'single' => true, 'show_in_rest' => true,
					'auth_callback' => static function ( $allowed, $meta_key, $id ) { return current_user_can( 'edit_post', $id ); },
					'sanitize_callback' => static function ( $value ) use ( $key ) { return self::sanitize( $key, $value ); },
				) );
			}
		}
	}
	public static function sanitize( $key, $value ) {
		if ( ! is_scalar( $value ) ) {
			return '';
		}
		$value = sanitize_text_field( (string) $value );
		if ( in_array( $key, array( '_aisa_evento_inicio', '_aisa_evento_fim', '_aisa_dep_data' ), true ) ) {
			$format = '_aisa_dep_data' === $key ? 'Y-m' : 'Y-m-d\TH:i';
			$date = \DateTimeImmutable::createFromFormat( '!' . $format, $value, new \DateTimeZone( 'Europe/Lisbon' ) );
			return $date && $date->format( $format ) === $value ? $value : '';
		}
		if ( '_aisa_doc_ficheiro' === $key ) {
			$id = absint( $value );
			$ext = strtolower( pathinfo( (string) get_attached_file( $id ), PATHINFO_EXTENSION ) );
			$allowed = array( 'pdf' => 'application/pdf', 'doc' => 'application/msword', 'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'xls' => 'application/vnd.ms-excel', 'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' );
			return 'attachment' === get_post_type( $id ) && isset( $allowed[ $ext ] ) && get_post_mime_type( $id ) === $allowed[ $ext ] ? $id : 0;
		}
		if ( '_aisa_doc_ano' === $key ) {
			$year = (int) $value;
			return (string) $year === $value && $year >= 2000 && $year <= (int) wp_date( 'Y' ) + 1 ? $year : 0;
		}
		return mb_substr( $value, 0, '_aisa_dep_relacao' === $key ? 80 : 120 );
	}
	public static function boxes( $type ) {
		if ( isset( self::fields()[ $type ] ) ) {
			add_meta_box( 'aisa_fields', __( 'Dados do conteúdo', 'aisa' ), array( self::class, 'box' ), $type, 'normal', 'default' );
		}
		if ( 'aisa_depoimento' === $type ) {
			add_meta_box( 'aisa_consent', __( 'Antes de escrever', 'aisa' ), static function () {
				echo '<p>' . esc_html__( "Confirme que tem o 'sim' registado de quem deu o depoimento. Não escreva nomes, idades, ruas ou doenças.", 'aisa' ) . '</p>';
			}, $type, 'normal', 'high' );
		}
	}
	public static function box( $post ) {
		wp_nonce_field( 'aisa_meta', 'aisa_meta_nonce' );
		foreach ( self::fields()[ $post->post_type ] as $key => $field ) {
			$value = get_post_meta( $post->ID, $key, true );
			echo '<p><label for="' . esc_attr( $key ) . '">' . esc_html( $field[0] ) . ( $field[2] ? ' *' : '' ) . '</label><br>';
			// Publication is validated on the server; drafts may keep empty required fields.
			echo '<input id="' . esc_attr( $key ) . '" name="aisa_meta[' . esc_attr( $key ) . ']" type="' . esc_attr( $field[1] ) . '" value="' . esc_attr( $value ) . '"' . ( isset( $field[3] ) ? ' maxlength="' . esc_attr( $field[3] ) . '"' : '' ) . ( '_aisa_doc_ano' === $key ? ' min="2000" max="' . esc_attr( (int) wp_date( 'Y' ) + 1 ) . '"' : '' ) . '>';
			if ( '_aisa_dep_relacao' === $key ) {
				echo '<br><span class="description">' . esc_html__( 'Ex.: Filha de um utente', 'aisa' ) . '</span>';
			}
			if ( '_aisa_doc_ficheiro' === $key ) {
				echo ' <button type="button" class="button" id="aisa-select-file">' . esc_html__( 'Escolher ficheiro', 'aisa' ) . '</button><br><span id="aisa-file-name">' . esc_html( basename( (string) get_attached_file( (int) $value ) ) ) . '</span>';
			}
			echo '</p>';
		}
	}
	public static function submitted() {
		if ( isset( $_POST['aisa_meta_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['aisa_meta_nonce'] ) ), 'aisa_meta' ) ) {
			return isset( $_POST['aisa_meta'] ) && is_array( $_POST['aisa_meta'] ) ? wp_unslash( $_POST['aisa_meta'] ) : array();
		}
		return array();
	}
	public static function values( $postarr ) {
		$id = isset( $postarr['ID'] ) ? (int) $postarr['ID'] : 0;
		$values = array();
		foreach ( self::fields() as $fields ) {
			foreach ( $fields as $key => $field ) {
				$values[ $key ] = get_post_meta( $id, $key, true );
			}
		}
		return array_replace( $values, (array) ( $postarr['meta_input'] ?? array() ), self::submitted(), self::$rest_values['meta'] ?? array() );
	}
	public static function rest_prepare( $post, $request ) {
		if ( is_wp_error( $post ) ) {
			return $post;
		}
		self::$rest_values = array( 'meta' => (array) $request->get_param( 'meta' ) );
		if ( $request->has_param( 'featured_media' ) ) {
			self::$rest_values['thumbnail'] = (int) $request->get_param( 'featured_media' );
		}
		return $post;
	}
	public static function validate( $data, $postarr ) {
		if ( ! in_array( $data['post_status'], array( 'publish', 'future', 'private' ), true ) || ! isset( self::fields()[ $data['post_type'] ] ) ) {
			return $data;
		}
		$values = self::values( $postarr );
		foreach ( self::fields()[ $data['post_type'] ] as $key => $field ) {
			$value = self::sanitize( $key, $values[ $key ] );
			if ( ( $field[2] && ! $value ) || ( ! $field[2] && '' !== (string) $values[ $key ] && ! $value ) || ( '_aisa_evento_fim' === $key && $value && $value < self::sanitize( '_aisa_evento_inicio', $values['_aisa_evento_inicio'] ) ) ) {
				$data['post_status'] = 'draft';
				self::notice( __( 'O conteúdo ficou em rascunho. Verifica os campos obrigatórios, o formato das datas, a ordem de início e fim e o ficheiro.', 'aisa' ) );
				break;
			}
		}
		return $data;
	}
	public static function save( $id, $post ) {
		if ( wp_is_post_revision( $id ) || wp_is_post_autosave( $id ) || ! current_user_can( 'edit_post', $id ) ) {
			return;
		}
		foreach ( self::submitted() as $key => $value ) {
			if ( isset( self::fields()[ $post->post_type ][ $key ] ) ) {
				update_post_meta( $id, $key, self::sanitize( $key, $value ) );
			}
		}
		if ( isset( $_POST['aisa_terms_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['aisa_terms_nonce'] ) ), 'aisa_terms' ) && isset( $_POST['aisa_terms'] ) && is_array( $_POST['aisa_terms'] ) ) {
			foreach ( array( 'aisa_valencia', 'aisa_tipo_documento' ) as $taxonomy ) {
				if ( isset( $_POST['aisa_terms'][ $taxonomy ] ) && is_object_in_taxonomy( $post->post_type, $taxonomy ) && current_user_can( get_taxonomy( $taxonomy )->cap->assign_terms ) ) {
					$term = absint( $_POST['aisa_terms'][ $taxonomy ] );
					if ( ! $term || term_exists( $term, $taxonomy ) ) {
						wp_set_object_terms( $id, $term ? array( $term ) : array(), $taxonomy );
					}
				}
			}
		}
	}
	public static function notice( $message ) {
		set_transient( 'aisa_notice_' . get_current_user_id(), $message, MINUTE_IN_SECONDS * 5 );
	}
	public static function notices() {
		$key = 'aisa_notice_' . get_current_user_id();
		$message = get_transient( $key );
		if ( $message ) {
			echo '<div class="notice notice-error"><p>' . esc_html( $message ) . '</p></div>';
			delete_transient( $key );
		}
	}
	public static function assets() {
		$screen = get_current_screen();
		if ( $screen && 'post' === $screen->base && 'aisa_documento' === $screen->post_type ) {
			wp_enqueue_media();
			wp_enqueue_script( 'aisa-document', AISA_CORE_URL . 'assets/js/admin-document.js', array( 'media-views', 'wp-i18n' ), AISA_CORE_VERSION, true );
		}
	}
	public static function columns( $columns ) {
		$type = get_current_screen()->post_type;
		$extra = array(
			'aisa_evento' => array( 'aisa_start' => __( 'Data', 'aisa' ), 'aisa_location' => __( 'Local', 'aisa' ), 'aisa_service' => __( 'Valência', 'aisa' ) ),
			'aisa_documento' => array( 'aisa_type' => __( 'Tipo', 'aisa' ), 'aisa_year' => __( 'Ano', 'aisa' ), 'aisa_file' => __( 'Ficheiro', 'aisa' ) ),
			'aisa_depoimento' => array( 'aisa_relation' => __( 'Relação', 'aisa' ), 'aisa_service' => __( 'Serviço', 'aisa' ) ),
		);
		return array_merge( $columns, $extra[ $type ] );
	}
	public static function column( $column, $id ) {
		$keys = array( 'aisa_start' => '_aisa_evento_inicio', 'aisa_location' => '_aisa_evento_local', 'aisa_year' => '_aisa_doc_ano', 'aisa_relation' => '_aisa_dep_relacao' );
		if ( isset( $keys[ $column ] ) ) {
			echo esc_html( get_post_meta( $id, $keys[ $column ], true ) );
		} elseif ( in_array( $column, array( 'aisa_service', 'aisa_type' ), true ) ) {
			$terms = wp_get_object_terms( $id, 'aisa_service' === $column ? 'aisa_valencia' : 'aisa_tipo_documento', array( 'fields' => 'names' ) );
			echo esc_html( is_wp_error( $terms ) ? '' : implode( ', ', $terms ) );
		} elseif ( 'aisa_file' === $column ) {
			$info = \aisa_doc_file_info( $id );
			if ( $info ) {
				echo '<a href="' . esc_url( $info['url'] ) . '">' . esc_html( strtoupper( $info['ext'] ) . ' · ' . $info['size_human'] ) . '</a>';
			}
		}
	}
}
