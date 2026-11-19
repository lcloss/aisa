<?php
/**
 * Breadcrumbs (G05). Fallback próprio até ao Rank Math (wp-04-02-06); quando este
 * estiver activo, usa o breadcrumb dele (que também gera o Schema BreadcrumbList).
 */
defined( 'ABSPATH' ) || exit;

if ( is_front_page() ) {
	return;
}

$wrapper = get_block_wrapper_attributes( array( 'class' => 'breadcrumb' ) );

if ( function_exists( 'rank_math_get_breadcrumbs' ) ) {
	$trail = rank_math_get_breadcrumbs();
	if ( $trail ) {
		echo '<div ' . $wrapper . '>' . wp_kses_post( $trail ) . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Block wrapper attributes are escaped by core.
	}
	return;
}

$crumbs   = array( array( __( 'Início', 'aisa' ), home_url( '/' ) ) );
$page_url = static function ( $slug ) {
	$page = get_page_by_path( $slug );
	return $page ? get_permalink( $page ) : '';
};

if ( is_singular() ) {
	$post = get_queried_object();
	if ( 'post' === $post->post_type ) {
		$posts_page = (int) get_option( 'page_for_posts' );
		$crumbs[]   = array( __( 'Notícias', 'aisa' ), $posts_page ? get_permalink( $posts_page ) : $page_url( 'noticias' ) );
	} elseif ( 'aisa_evento' === $post->post_type ) {
		$crumbs[] = array( __( 'Agenda', 'aisa' ), get_post_type_archive_link( 'aisa_evento' ) );
	} elseif ( 'page' === $post->post_type ) {
		if ( 'page-servico' === get_page_template_slug( $post ) && ! $post->post_parent ) {
			$servicos = get_page_by_path( 'servicos' );
			if ( $servicos ) {
				$crumbs[] = array( get_the_title( $servicos ), get_permalink( $servicos ) );
			}
		}
		foreach ( array_reverse( get_post_ancestors( $post ) ) as $ancestor ) {
			$crumbs[] = array( get_the_title( $ancestor ), get_permalink( $ancestor ) );
		}
	}
	$crumbs[] = array( get_the_title( $post ), '' );
} elseif ( is_home() ) {
	$crumbs[] = array( __( 'Notícias', 'aisa' ), '' );
} elseif ( is_post_type_archive( 'aisa_evento' ) ) {
	$crumbs[] = array( __( 'Agenda', 'aisa' ), '' );
} elseif ( is_search() ) {
	$crumbs[] = array( __( 'Pesquisa', 'aisa' ), '' );
} elseif ( is_404() ) {
	$crumbs[] = array( __( 'Página não encontrada', 'aisa' ), '' );
} elseif ( is_archive() ) {
	$crumbs[] = array( wp_strip_all_tags( get_the_archive_title() ), '' );
}

if ( count( $crumbs ) < 2 ) {
	return;
}
$last = count( $crumbs ) - 1;
?>
<nav <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> aria-label="<?php esc_attr_e( 'Localização', 'aisa' ); ?>">
	<ol class="breadcrumb__list">
		<?php foreach ( $crumbs as $i => $crumb ) : ?>
			<li class="breadcrumb__item">
				<?php if ( $i === $last || ! $crumb[1] ) : ?>
					<span aria-current="page"><?php echo esc_html( $crumb[0] ); ?></span>
				<?php else : ?>
					<a href="<?php echo esc_url( $crumb[1] ); ?>"><?php echo esc_html( $crumb[0] ); ?></a>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
	</ol>
</nav>
