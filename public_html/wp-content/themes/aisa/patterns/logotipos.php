<?php
/**
 * Title: Logótipos dos parceiros
 * Slug: aisa/logotipos
 * Categories: aisa
 */
defined( 'ABSPATH' ) || exit;
// Import source logos as attachments with _aisa_partner_name. Alt always equals the partner name.
$logos = get_posts( array( 'post_type' => 'attachment', 'post_status' => 'inherit', 'posts_per_page' => 100, 'meta_key' => '_aisa_partner_name' ) );
$partners = array();
foreach ( $logos as $logo ) {
	$url = wp_get_attachment_image_url( $logo->ID, 'medium' );
	if ( $url ) { $partners[] = array( 'name' => get_post_meta( $logo->ID, '_aisa_partner_name', true ), 'image' => $url ); }
}
echo aisa_partner_blocks( $partners ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
