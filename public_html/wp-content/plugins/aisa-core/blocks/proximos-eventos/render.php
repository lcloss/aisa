<?php
defined( 'ABSPATH' ) || exit;
$items = get_posts( array( 'post_type' => 'aisa_evento', 'post_status' => 'publish', 'posts_per_page' => max( 1, min( 12, (int) ( $attributes['quantidade'] ?? 3 ) ) ), 'no_found_rows' => true, 'tax_query' => \Aisa\Core\Blocks::valencia_query( $attributes['valencia'] ?? '' ), 'meta_key' => '_aisa_evento_inicio', 'meta_value' => wp_date( 'Y-m-d', null, wp_timezone() ) . 'T00:00', 'meta_compare' => '>=', 'orderby' => 'meta_value', 'order' => 'ASC' ) );
$items = array_filter( $items, static function ( $item ) { return aisa_evento_is_future( $item->ID ); } );
?>
<div <?php echo get_block_wrapper_attributes(); ?>>
<?php if ( ! $items ) : ?>
<p><?php esc_html_e( 'Em breve, novas datas.', 'aisa' ); ?> <a href="<?php echo esc_url( home_url( '/noticias/' ) ); ?>"><?php esc_html_e( 'Veja as últimas notícias.', 'aisa' ); ?></a></p>
<?php endif; ?>
<?php foreach ( $items as $item ) :
	$valencia = aisa_get_valencia( $item );
	$date = new DateTimeImmutable( get_post_meta( $item->ID, '_aisa_evento_inicio', true ), wp_timezone() );
?>
<article class="event" data-valencia="<?php echo esc_attr( $valencia ? $valencia->slug : 'generico' ); ?>">
<time class="event__date" datetime="<?php echo esc_attr( $date->format( DATE_ATOM ) ); ?>" aria-label="<?php echo esc_attr( wp_date( 'j F Y, H:i', $date->getTimestamp(), wp_timezone() ) ); ?>"><span class="event__day"><?php echo esc_html( wp_date( 'j', $date->getTimestamp(), wp_timezone() ) ); ?></span><span class="event__month"><?php echo esc_html( wp_date( 'M', $date->getTimestamp(), wp_timezone() ) ); ?></span></time>
<div><h2 class="event__title"><a href="<?php echo esc_url( get_permalink( $item ) ); ?>"><?php echo esc_html( get_the_title( $item ) ); ?></a></h2><p class="event__meta"><?php echo esc_html( get_post_meta( $item->ID, '_aisa_evento_local', true ) ); ?></p><?php if ( $valencia ) : ?><span class="valencia-tag"><?php echo esc_html( $valencia->name ); ?></span><?php endif; ?></div></article>
<?php endforeach; ?>
<?php if ( ! empty( $attributes['mostrarLigacao'] ) ) : ?><a href="<?php echo esc_url( get_post_type_archive_link( 'aisa_evento' ) ); ?>"><?php esc_html_e( 'Ver agenda', 'aisa' ); ?></a><?php endif; ?>
</div>
