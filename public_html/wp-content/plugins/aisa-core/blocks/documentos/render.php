<?php
defined( 'ABSPATH' ) || exit;
\Aisa\Core\Blocks::documents( $attributes, false, $block->context['postId'] ?? get_the_ID() );
