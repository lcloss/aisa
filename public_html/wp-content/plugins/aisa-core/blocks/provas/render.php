<?php
defined( 'ABSPATH' ) || exit;
$year = trim( $attributes['ano'] ?? '' );
// Undated evidence must never be presented as current data.
if ( ! $year || empty( $attributes['items'] ) ) { return; }
?>
<div <?php echo get_block_wrapper_attributes(); ?>>
<dl class="proof-strip">
<?php foreach ( $attributes['items'] as $item ) : ?>
<div class="proof"><dt class="proof__num"><?php echo esc_html( $item['numero'] ?? '' ); ?></dt><dd class="proof__label"><?php echo esc_html( $item['legenda'] ?? '' ); ?></dd></div>
<?php endforeach; ?>
</dl><p class="proof__source"><?php echo esc_html( $year ); ?></p></div>
