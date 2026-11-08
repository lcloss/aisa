<?php
/** Prepare the integration fixtures for the HTTP/schema checks. */
defined( 'ABSPATH' ) || exit;
$aisa_fixture_ids = json_decode( file_get_contents( AISA_CORE_PATH . 'tests/fixtures.json' ), true );
set_post_thumbnail( $aisa_fixture_ids['future'], $aisa_fixture_ids['image'] );
update_post_meta( $aisa_fixture_ids['future'], '_aisa_evento_fim', substr( get_post_meta( $aisa_fixture_ids['future'], '_aisa_evento_inicio', true ), 0, 10 ) . 'T16:00' );
echo get_permalink( $aisa_fixture_ids['future'] );
