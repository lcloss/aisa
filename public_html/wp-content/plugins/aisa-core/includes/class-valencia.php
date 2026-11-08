<?php
namespace Aisa\Core;
defined( 'ABSPATH' ) || exit;

class Valencia {
	public static function boot() {
		add_filter( 'body_class', static function ( $classes ) {
			$term = is_singular() ? \aisa_get_valencia() : null;
			if ( $term ) {
				$classes[] = 'valencia-' . sanitize_html_class( $term->slug );
			}
			return $classes;
		} );
		add_filter( 'aisa_body_attributes', static function ( $attributes ) {
			$term = \aisa_get_valencia();
			if ( $term ) {
				$attributes['data-valencia'] = $term->slug;
			}
			return $attributes;
		} );
		add_filter( 'render_block', static function ( $html, $block, $instance ) {
			$classes = preg_split( '/\s+/', $block['attrs']['className'] ?? '' );
			if ( ! in_array( 'aisa-valencia-scope', $classes, true ) ) {
				return $html;
			}
			$term = \aisa_get_valencia( $instance->context['postId'] ?? null );
			if ( $term ) {
				$processor = new \WP_HTML_Tag_Processor( $html );
				if ( $processor->next_tag() ) {
					$processor->set_attribute( 'data-valencia', $term->slug );
					return $processor->get_updated_html();
				}
			}
			return $html;
		}, 10, 3 );
	}
}
