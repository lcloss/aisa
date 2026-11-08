<?php
namespace Aisa\Core;
defined( 'ABSPATH' ) || exit;

class Schema_Event {
	public static function boot() {
		add_action( 'wp_head', array( self::class, 'render' ) );
		add_filter( 'rank_math/json_ld', static function ( $data ) {
			if ( is_singular( 'aisa_evento' ) ) {
				foreach ( $data as $key => $entity ) {
					if ( is_array( $entity ) && in_array( 'Event', (array) ( $entity['@type'] ?? array() ), true ) ) {
						unset( $data[ $key ] );
					}
				}
			}
			return $data;
		}, 99 );
	}
	public static function render() {
		if ( ! is_singular( 'aisa_evento' ) || post_password_required() ) {
			return;
		}
		$id = get_queried_object_id();
		$start = get_post_meta( $id, '_aisa_evento_inicio', true );
		if ( ! $start ) {
			return;
		}
		$zone = new \DateTimeZone( 'Europe/Lisbon' );
		$local = get_post_meta( $id, '_aisa_evento_local', true );
		$location = array( '@type' => 'Place', 'name' => $local ? $local : 'AISA' );
		if ( ! $local ) {
			// Official address is pending BQ2; supplied by the site configuration later.
			$address = apply_filters( 'aisa_organization_address', '' );
			if ( $address ) {
				$location['address'] = $address;
			}
		}
		$data = array(
			'@context' => 'https://schema.org', '@type' => 'Event', 'name' => get_the_title( $id ),
			'startDate' => ( new \DateTimeImmutable( $start, $zone ) )->format( DATE_ATOM ),
			'location' => $location, 'organizer' => array( '@type' => 'Organization', 'name' => 'AISA', 'url' => home_url( '/' ) ),
			'eventStatus' => 'https://schema.org/EventScheduled', 'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
		);
		$end = get_post_meta( $id, '_aisa_evento_fim', true );
		if ( $end ) {
			$data['endDate'] = ( new \DateTimeImmutable( $end, $zone ) )->format( DATE_ATOM );
		}
		$image = get_the_post_thumbnail_url( $id, 'full' );
		if ( $image ) {
			$data['image'] = $image;
		}
		echo '<script type="application/ld+json">' . wp_json_encode( $data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
	}
}
