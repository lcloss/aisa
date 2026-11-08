<?php
/** Read-only inventory: wp --path=public_html eval-file specs/scripts/inventory-embeds.php. */
defined( 'ABSPATH' ) || exit;

$ids = get_posts( array( 'post_type' => 'post', 'post_status' => 'any', 'posts_per_page' => -1, 'fields' => 'ids', 'orderby' => 'ID', 'order' => 'ASC' ) );
WP_CLI::log( '| ID da notícia | Estado | Tipo encontrado | Acção |' );
WP_CLI::log( '|---|---|---|---|' );
$matched = 0;
foreach ( $ids as $id ) {
	$content = get_post_field( 'post_content', $id );
	$types   = array();
	foreach ( array( 'iframe' => '/<iframe\b/i', 'core/embed' => '/wp:embed\b/i', 'YouTube' => '/youtube(?:-nocookie)?\.com|youtu\.be/i', 'Facebook' => '/facebook\.com\/plugins/i', 'Instagram' => '/instagram\.com/i', 'Google Maps' => '/maps\.google|maps\.googleapis\.com|google\.[a-z.]+\/maps/i' ) as $type => $pattern ) {
		if ( preg_match( $pattern, $content ) ) {
			$types[] = $type;
		}
	}
	if ( $types ) {
		++$matched;
	}
	WP_CLI::log( '| ' . $id . ' | ' . get_post_status( $id ) . ' | ' . ( $types ? implode( ', ', $types ) : 'Nenhum' ) . ' | ' . ( $types ? 'Validar bloqueio e área reservada com CMP configurada' : 'Sem incorporados detectados pelos padrões da spec' ) . ' |' );
}
WP_CLI::log( 'Total: ' . count( $ids ) . ' notícias examinadas; ' . $matched . ' com correspondências. Não comprova a conclusão da migração 02-05.' );
