<?php
/** Native editorial blocks shared by the remaining-page patterns and provisioning. */
defined( 'ABSPATH' ) || exit;

function aisa_page_text( $text ) {
	return '<!-- wp:paragraph --><p>' . wp_kses_post( $text ) . '</p><!-- /wp:paragraph -->';
}

function aisa_page_heading( $text, $level = 2, $anchor = '' ) {
	$attrs = array( 'level' => $level );
	if ( $anchor ) { $attrs['anchor'] = $anchor; }
	return '<!-- wp:heading ' . wp_json_encode( $attrs ) . ' --><h' . $level . ( $anchor ? ' id="' . esc_attr( $anchor ) . '"' : '' ) . ' class="wp-block-heading">' . esc_html( $text ) . '</h' . $level . '><!-- /wp:heading -->';
}

function aisa_page_group( $content, $class = '', $tag = 'div' ) {
	return '<!-- wp:group ' . wp_json_encode( array( 'className' => $class, 'tagName' => $tag, 'layout' => array( 'type' => 'default' ) ) ) . ' --><' . $tag . ' class="wp-block-group ' . esc_attr( $class ) . '">' . $content . '</' . $tag . '><!-- /wp:group -->';
}

function aisa_page_link( $slug, $label ) {
	return '<a href="' . esc_url( home_url( '/' . $slug . '/' ) ) . '">' . esc_html( $label ) . '</a>';
}

function aisa_page_list( $items ) {
	$html = '<!-- wp:list --><ul class="wp-block-list">';
	foreach ( $items as $item ) { $html .= '<!-- wp:list-item --><li>' . wp_kses_post( $item ) . '</li><!-- /wp:list-item -->'; }
	return $html . '</ul><!-- /wp:list -->';
}

function aisa_page_image( $url, $alt, $class = '' ) {
	return '<!-- wp:image ' . wp_json_encode( array( 'sizeSlug' => 'large', 'linkDestination' => 'none', 'className' => $class ) ) . ' --><figure class="wp-block-image size-large ' . esc_attr( $class ) . '"><img src="' . esc_url( $url ) . '" alt="' . esc_attr( $alt ) . '"/></figure><!-- /wp:image -->';
}

function aisa_therapy_sections() {
	return array( 'terapia-da-fala' => __( 'Terapia da Fala', 'aisa' ), 'fisioterapia' => __( 'Fisioterapia', 'aisa' ), 'psicologia' => __( 'Psicologia', 'aisa' ), 'psicomotricidade' => __( 'Psicomotricidade', 'aisa' ), 'terapia-ocupacional' => __( 'Terapia Ocupacional', 'aisa' ), 'medicina' => __( 'Medicina', 'aisa' ), 'enfermagem' => __( 'Enfermagem', 'aisa' ), 'produtos-de-apoio' => __( 'Produtos de Apoio', 'aisa' ) );
}

/** Names/photos require editorial approval; this pattern deliberately defaults to roles only. */
function aisa_people_blocks( $people ) {
	$html = '';
	foreach ( $people as $person ) {
		$item = '';
		if ( ! empty( $person['image'] ) && ! empty( $person['photo_approved'] ) ) { $item .= aisa_page_image( $person['image'], $person['name'] ?? $person['role'] ); }
		$item .= aisa_page_heading( $person['name'] ?? $person['role'], 3 );
		if ( ! empty( $person['name'] ) ) { $item .= aisa_page_text( esc_html( $person['role'] ) ); }
		$html .= aisa_page_group( $item, 'aisa-person' );
	}
	return aisa_page_group( $html, 'aisa-people' );
}

/** Uses real imported partner logos only, never substitutes invented brand marks. */
function aisa_partner_blocks( $partners ) {
	$html = '';
	foreach ( $partners as $partner ) {
		$html .= aisa_page_group( aisa_page_image( $partner['image'], $partner['name'] ), 'aisa-partner' );
	}
	return aisa_page_group( $html, 'aisa-partners' );
}

function aisa_contact_form_block( $kind ) {
	$form = get_page_by_path( 'aisa-' . $kind, OBJECT, 'wpcf7_contact_form' );
	if ( ! $form ) { return aisa_page_text( __( 'Prefere falar? Ligue 214 857 700.', 'aisa' ) ); }
	$title = 'contacto' === $kind ? __( 'Contacto', 'aisa' ) : __( 'Voluntariado', 'aisa' );
	return '<!-- wp:shortcode -->[contact-form-7 id="' . (int) $form->ID . '" title="' . esc_attr( $title ) . '" html_title="' . esc_attr( $title ) . '" html_class="form-' . esc_attr( $kind ) . '"]<!-- /wp:shortcode -->';
}

/** The shared cached pattern cannot use conditional tags while it is registered. */
add_filter( 'render_block_core/paragraph', static function ( $html, $block ) {
	if ( 'aisa-service-hours' !== ( $block['attrs']['className'] ?? '' ) ) { return $html; }
	if ( is_page( 'candidaturas' ) ) { return ''; }
	$hours = array( 'centro-de-dia' => __( 'Dias úteis das 8h30 às 18h00', 'aisa' ), 'servico-apoio-domiciliario' => __( 'Dias úteis das 09h00 às 18h00', 'aisa' ) );
	$slug = get_post_field( 'post_name', get_queried_object_id() );
	return isset( $hours[ $slug ] ) ? '<p class="aisa-service-hours">' . esc_html( $hours[ $slug ] ) . '</p>' : '';
}, 10, 2 );

add_filter( 'render_block_core/post-content', static function ( $html ) {
	if ( ! is_page( 'servicos' ) ) { return $html; }
	$tags = new WP_HTML_Tag_Processor( $html );
	if ( $tags->next_tag() ) { $tags->add_class( 'aisa-services-index' ); }
	return $tags->get_updated_html();
} );
