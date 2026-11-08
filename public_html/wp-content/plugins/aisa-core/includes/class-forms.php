<?php
/** Minimal cross-field validation for the two CF7 forms. */
namespace Aisa\Core;

defined( 'ABSPATH' ) || exit;

class Forms {

	public static function boot() {
		// Run before WP Armour so ordinary field errors remain associated with fields.
		add_filter( 'wpcf7_validate', array( self::class, 'validate' ), 9, 2 );
		add_filter( 'wpcf7_mail_components', array( self::class, 'mail_components' ), 10, 3 );
		add_action( 'wp_enqueue_scripts', array( self::class, 'enqueue' ) );
		add_filter( 'wpcf7_form_elements', array( self::class, 'accessible_fields' ), 20 );
		// Lite always records send errors, even with email debugging disabled.
		add_filter( 'query', array( self::class, 'prevent_mail_logs' ) );
		add_filter( 'pre_update_option_wp_mail_smtp_debug', '__return_empty_array' );
		add_filter( 'pre_update_option_wp_mail_smtp_email_sending_debug', '__return_empty_array' );
	}

	/** Block only writes to the SMTP diagnostic log; never inspect/store values. */
	public static function prevent_mail_logs( $sql ) {
		global $wpdb;
		$table = preg_quote( $wpdb->prefix . 'wpmailsmtp_debug_events', '/' );
		return preg_match( '/^\s*(?:INSERT\s+(?:IGNORE\s+)?INTO|REPLACE\s+INTO|UPDATE)\s+`?' . $table . '`?\s/i', $sql ) ? '' : $sql;
	}

	/** Add persistent help associations without replacing CF7's error handling. */
	public static function accessible_fields( $html ) {
		$kind = self::kind( \WPCF7_ContactForm::get_current() );
		if ( ! $kind ) {
			return $html;
		}
		$processor = new \WP_HTML_Tag_Processor( $html );
		while ( $processor->next_tag( 'INPUT' ) ) {
			$name = $processor->get_attribute( 'name' );
			if ( in_array( $name, array( 'telefone', 'email' ), true ) ) {
				$processor->set_attribute( 'aria-describedby', $kind . '-contacto-ajuda' );
			}
			if ( in_array( $name, array( 'area', 'maior18', 'consentimento' ), true ) ) {
				$processor->set_attribute( 'aria-required', 'true' );
			}
			if ( 'maior18' === $name ) {
				$processor->set_attribute( 'aria-describedby', 'voluntariado-idade-ajuda' );
			}
		}
		return $processor->get_updated_html();
	}

	/** Scope custom rules to forms provisioned by this project. */
	public static function kind( $form ) {
		$kind = $form ? $form->additional_setting( 'aisa_form' ) : array();
		return in_array( $kind[0] ?? '', array( 'voluntariado', 'contacto' ), true ) ? $kind[0] : '';
	}

	public static function validate( $result, $tags ) {
		$form = \WPCF7_ContactForm::get_current();
		$kind = self::kind( $form );
		$submission = \WPCF7_Submission::get_instance();
		if ( ! $kind || ! $submission ) {
			return $result;
		}
		$data = $submission->get_posted_data();
		$telephone = is_string( $data['telefone'] ?? null ) ? trim( $data['telefone'] ) : '';
		$email = is_string( $data['email'] ?? null ) ? trim( $data['email'] ) : '';
		foreach ( $tags as $tag ) {
			if ( in_array( $tag->name, array( 'telefone', 'email' ), true ) && '' === $telephone && '' === $email ) {
				$result->invalidate( $tag, __( 'Indique pelo menos um contacto', 'aisa' ) );
			}
			if ( 'voluntariado' === $kind && in_array( $tag->name, array( 'area', 'maior18' ), true ) ) {
				$value = $data[ $tag->name ] ?? '';
				// CF7 normalises selectable values (including radios) to arrays.
				if ( is_array( $value ) && 1 === count( $value ) ) {
					$value = reset( $value );
				}
				if ( ! is_string( $value ) || ! in_array( $value, $tag->values, true ) ) {
					$result->invalidate( $tag, __( 'Selecione uma opção.', 'aisa' ) );
				}
			}
		}
		return $result;
	}

	/** An optional email must never create an empty or untrusted Reply-To header. */
	public static function mail_components( $components, $form, $mail ) {
		if ( ! self::kind( $form ) || ! \WPCF7_Submission::get_instance() ) {
			return $components;
		}
		$email = \WPCF7_Submission::get_instance()->get_posted_data( 'email' );
		$components['additional_headers'] = is_string( $email ) && is_email( $email ) ? 'Reply-To: ' . sanitize_email( $email ) : '';
		return $components;
	}

	public static function enqueue() {
		if ( function_exists( 'wpcf7' ) ) {
			wp_enqueue_script( 'aisa-forms', AISA_CORE_URL . 'assets/js/forms.js', array( 'contact-form-7' ), AISA_CORE_VERSION, true );
		}
	}
}
