<?php
defined( 'ABSPATH' ) || exit;
\Aisa\Core\Blocks::documents( $attributes, true, $block->context['postId'] ?? get_the_ID() );
