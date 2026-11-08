<?php
require dirname(__DIR__,4) . '/wp-load.php';
if ( 'local' !== wp_get_environment_type() ) { exit; }
wp_enqueue_script( 'wp-block-library' );
wp_enqueue_script( 'wp-format-library' );
$checks = array();
foreach ( WP_Block_Patterns_Registry::get_instance()->get_all_registered() as $pattern ) { if ( str_starts_with( $pattern['name'], 'aisa/' ) ) { $checks[$pattern['name']] = $pattern['content']; } }
foreach ( glob( get_template_directory() . '/templates/*.html' ) as $path ) { $checks[basename($path)] = file_get_contents($path); }
?><!doctype html><html lang="pt-PT"><head><?php wp_head(); ?></head><body><?php wp_footer(); ?><script>
window.templateChecks=<?php echo wp_json_encode($checks); ?>;
wp.blockLibrary.registerCoreBlocks(); window.validation=[];
for(const [name,content] of Object.entries(window.templateChecks)){
 const blocks=wp.blocks.parse(content);
 const check=items=>items.flatMap(b=>[...(b.isValid===false?[{name,block:b.name,original:b.originalContent,errors:b.validationIssues}]:[]),...check(b.innerBlocks)]);
 window.validation.push(...check(blocks));
}
</script></body></html>

