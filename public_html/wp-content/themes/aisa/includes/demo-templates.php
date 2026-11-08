<?php
/** Native template presentation. Content and event fields remain in aisa-core. */
defined( 'ABSPATH' ) || exit;

add_action( 'init', static function () {
	register_block_pattern_category( 'aisa', array( 'label' => __( 'AISA', 'aisa' ) ) );
	wp_register_script( 'aisa-event-details-editor', get_theme_file_uri( 'assets/js/event-details-editor.js' ), array( 'wp-blocks', 'wp-element', 'wp-i18n', 'wp-server-side-render' ), wp_get_theme()->get( 'Version' ), true );
	register_block_type( 'aisa/event-details', array(
		'api_version' => 3,
		'uses_context' => array( 'postId', 'postType' ),
		'editor_script' => 'aisa-event-details-editor',
		'supports' => array( 'html' => false, 'inserter' => false ),
		'render_callback' => 'aisa_event_details',
	) );
} );

/** Resolve demo media to native attachment markup; no remote image requests. */
function aisa_pattern_image( $key, $alt ) {
	$id = (int) ( get_option( 'aisa_demo_media', array() )[ $key ] ?? 0 );
	$url = $id ? wp_get_attachment_image_url( $id, 'large' ) : get_theme_file_uri( 'assets/img/default.jpg' );
	return '<!-- wp:image ' . wp_json_encode( array( 'id' => $id, 'sizeSlug' => 'large', 'linkDestination' => 'none', 'className' => 'is-style-media' ) ) . ' --><figure class="wp-block-image size-large is-style-media"><img src="' . esc_url( $url ) . '" alt="' . esc_attr( $alt ) . '" class="wp-image-' . $id . '"/></figure><!-- /wp:image -->';
}

function aisa_event_details( $attributes, $content, $block ) {
	$id = $block->context['postId'] ?? get_the_ID();
	if ( 'aisa_evento' !== get_post_type( $id ) ) { return ''; }
	$html = '<div class="aisa-event-details">';
	foreach ( array( '_aisa_evento_inicio' => __( 'Início', 'aisa' ), '_aisa_evento_fim' => __( 'Fim', 'aisa' ) ) as $key => $label ) {
		$value = get_post_meta( $id, $key, true );
		if ( ! $value ) { continue; }
		$date = DateTimeImmutable::createFromFormat( '!Y-m-d\TH:i', $value, wp_timezone() );
		if ( ! $date ) { continue; }
		$html .= '<p><strong>' . esc_html( $label ) . ':</strong> <time datetime="' . esc_attr( $date->format( DATE_ATOM ) ) . '">' . esc_html( wp_date( 'j F Y, H:i', $date->getTimestamp(), wp_timezone() ) ) . '</time></p>';
	}
	$place = get_post_meta( $id, '_aisa_evento_local', true );
	if ( $place ) { $html .= '<p><strong>' . esc_html__( 'Local', 'aisa' ) . ':</strong> ' . esc_html( $place ) . '</p>'; }
	return $html . '</div>';
}

/** Card images are decorative: their only link is the adjacent title. */
add_filter( 'post_thumbnail_html', static function ( $html, $id, $thumbnail, $size, $attributes ) {
	if ( $html ) { return $html; }
	return '<img src="' . esc_url( get_theme_file_uri( 'assets/img/default.jpg' ) ) . '" alt="" width="1600" height="900" loading="lazy">';
}, 10, 5 );

add_filter( 'render_block_core/post-featured-image', static function ( $html, $parsed, $block ) {
	if ( ! str_contains( $parsed['attrs']['className'] ?? '', 'news-card__media' ) ) { return $html; }
	$id = $block->context['postId'] ?? get_the_ID();
	$image = get_the_post_thumbnail( $id, 'large', array( 'alt' => '', 'loading' => 'lazy' ) );
	return '<figure class="wp-block-post-featured-image news-card__media">' . $image . '</figure>';
}, 20, 3 );

/** Preserve Query Loop context for article headers as well as singular pages. */
add_filter( 'render_block_core/post-terms', static function ( $html, $parsed, $block ) {
	if ( 'aisa_valencia' !== ( $parsed['attrs']['term'] ?? '' ) || ! str_contains( $parsed['attrs']['className'] ?? '', 'valencia-tag' ) ) { return $html; }
	$terms = get_the_terms( $block->context['postId'] ?? get_the_ID(), 'aisa_valencia' );
	if ( ! $terms || is_wp_error( $terms ) ) { return ''; }
	return '<div class="wp-block-post-terms valencia-tag">' . esc_html( implode( ', ', wp_list_pluck( $terms, 'name' ) ) ) . '</div>';
}, 10, 3 );

/** Only page one has a lead; one inherited query means correct pagination. */
add_filter( 'render_block_core/post-template', static function ( $html, $parsed ) {
	if ( ! str_contains( $parsed['attrs']['className'] ?? '', 'aisa-news-list' ) || max( 1, (int) get_query_var( 'paged' ) ) > 1 ) { return $html; }
	$tags = new WP_HTML_Tag_Processor( $html );
	if ( $tags->next_tag( 'ARTICLE' ) ) { $tags->add_class( 'news-card--lead' ); }
	return $tags->get_updated_html();
}, 10, 2 );

/** Service testimonials follow the page taxonomy instead of a fixed service. */
add_filter( 'render_block_data', static function ( $parsed ) {
	if ( 'aisa/depoimentos' === $parsed['blockName'] && 'servico-depoimentos' === ( $parsed['attrs']['className'] ?? '' ) && is_singular( 'page' ) && function_exists( 'aisa_get_valencia' ) ) {
		$term = aisa_get_valencia( get_queried_object_id() );
		if ( $term ) { $parsed['attrs']['valencia'] = $term->slug; }
	}
	return $parsed;
} );

add_filter( 'render_block_core/group', static function ( $html, $parsed ) {
	if ( str_contains( $parsed['attrs']['className'] ?? '', 'aisa-testimonials' ) && (int) ( wp_count_posts( 'aisa_depoimento' )->publish ?? 0 ) < 3 ) { return ''; }
	if ( 'conteudo' === ( $parsed['attrs']['anchor'] ?? '' ) ) {
		$tags = new WP_HTML_Tag_Processor( $html );
		if ( $tags->next_tag( 'MAIN' ) ) { $tags->set_attribute( 'tabindex', '-1' ); }
		return $tags->get_updated_html();
	}
	return $html;
}, 10, 2 );

/** Native post types expose their archive without a redundant Agenda page. */
add_filter( 'render_block_core/navigation-link', static function ( $html, $parsed ) {
	if ( __( 'Agenda', 'aisa' ) !== ( $parsed['attrs']['label'] ?? '' ) ) { return $html; }
	$tags = new WP_HTML_Tag_Processor( $html );
	if ( $tags->next_tag( 'A' ) ) { $tags->set_attribute( 'href', get_post_type_archive_link( 'aisa_evento' ) ); }
	return $tags->get_updated_html();
}, 10, 2 );
