<?php
/**
 * Consent defaults and Google Tag Manager integration.
 *
 * @package Aisa\Core
 */

namespace Aisa\Core;

defined( 'ABSPATH' ) || exit;

/** Loads tracking only for eligible public requests with a valid container. */
class GTM {

	/** Register settings and ordered front-end hooks. */
	public static function boot() {
		add_action( 'admin_init', array( self::class, 'register_settings' ) );
		add_action( 'wp_head', array( self::class, 'render_consent' ), 1 );
		add_action( 'wp_head', array( self::class, 'render_container' ), 5 );
		add_action( 'wp_body_open', array( self::class, 'render_noscript' ) );
		add_action( 'wp_enqueue_scripts', array( self::class, 'enqueue_forms' ) );
	}

	/** Register an administrator-only field in Settings > General. */
	public static function register_settings() {
		register_setting(
			'general',
			'aisa_gtm_id',
			array(
				'type'              => 'string',
				'sanitize_callback' => array( self::class, 'sanitize_id' ),
				'default'           => '',
				'show_in_rest'      => false,
			)
		);
		add_settings_field( 'aisa_gtm_id', __( 'Contentor Google Tag Manager', 'aisa' ), array( self::class, 'render_field' ), 'general', 'default', array( 'label_for' => 'aisa_gtm_id' ) );
	}

	/**
	 * Accept only complete GTM container IDs, including for constant values.
	 *
	 * @param mixed $value Candidate ID.
	 * @return string Valid ID or an empty string.
	 */
	public static function sanitize_id( $value ) {
		return is_string( $value ) && preg_match( '/^GTM-[A-Z0-9]+$/D', $value ) ? $value : '';
	}

	/** Render the field; options.php supplies capability and nonce checks. */
	public static function render_field() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		?>
		<input type="text" class="regular-text" id="aisa_gtm_id" name="aisa_gtm_id" value="<?php echo esc_attr( get_option( 'aisa_gtm_id', '' ) ); ?>" pattern="GTM-[A-Z0-9]+" placeholder="GTM-XXXXXXX" aria-describedby="aisa-gtm-description" <?php disabled( defined( 'AISA_GTM_ID' ) ); ?> />
		<p class="description" id="aisa-gtm-description"><?php esc_html_e( 'Deixa vazio para desativar a medição. A constante AISA_GTM_ID, se definida em wp-config.php, tem precedência.', 'aisa' ); ?></p>
		<?php
	}

	/** @return string The validated constant takes precedence over the option. */
	public static function container_id() {
		return self::sanitize_id( defined( 'AISA_GTM_ID' ) ? AISA_GTM_ID : get_option( 'aisa_gtm_id', '' ) );
	}

	/** @return bool Whether this request may load the configured container. */
	public static function enabled() {
		$enabled = ! is_admin() && ! is_preview() && ! ( is_user_logged_in() && current_user_can( 'edit_posts' ) );
		return '' !== self::container_id() && (bool) apply_filters( 'aisa_gtm_enabled', $enabled );
	}

	/** Establish Consent Mode v2 defaults before any GTM script. */
	public static function render_consent() {
		if ( ! self::enabled() ) {
			return;
		}
		wp_print_inline_script_tag(
			"window.dataLayer = window.dataLayer || [];\nfunction gtag(){dataLayer.push(arguments);}\ngtag('consent','default', {\n ad_storage:'denied', ad_user_data:'denied', ad_personalization:'denied',\n analytics_storage:'denied', functionality_storage:'denied',\n personalization_storage:'denied', security_storage:'granted', wait_for_update:500\n});\ngtag('set','ads_data_redaction',true);\ngtag('set','url_passthrough',false);",
			array( 'id' => 'aisa-consent-default' )
		);
	}

	/** Print the official asynchronous GTM loader. */
	public static function render_container() {
		if ( ! self::enabled() ) {
			return;
		}
		wp_print_inline_script_tag(
			"(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':\nnew Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],\nj=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=\n'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);\n})(window,document,'script','dataLayer'," . wp_json_encode( self::container_id() ) . ');',
			array( 'id' => 'aisa-gtm' )
		);
	}

	/** Print the official noscript fallback immediately after the body opens. */
	public static function render_noscript() {
		if ( ! self::enabled() ) {
			return;
		}
		?>
		<noscript><iframe src="<?php echo esc_url( 'https://www.googletagmanager.com/ns.html?id=' . self::container_id() ); ?>" height="0" width="0" style="display:none;visibility:hidden" title="Google Tag Manager"></iframe></noscript>
		<?php
	}

	/** Load the success listener in the footer only when CF7 is available. */
	public static function enqueue_forms() {
		if ( self::enabled() && defined( 'WPCF7_VERSION' ) ) {
			wp_enqueue_script( 'aisa-gtm-forms', AISA_CORE_URL . 'assets/js/gtm-forms.js', array(), AISA_CORE_VERSION, true );
		}
	}
}
