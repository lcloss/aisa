<?php
/**
 * Local, repeatable M0 seed: wp --path=public_html eval-file .../tools/seed-demo.php
 * Own IDs and sources are recorded for replacement by migration slice 02-05.
 */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) { exit; }
if ( 'aisa' !== get_stylesheet() || ! class_exists( '\Aisa\Core\Blocks' ) ) {
	WP_CLI::error( 'Ativa o tema aisa e o plugin aisa-core primeiro.' );
}
require_once ABSPATH . 'wp-admin/includes/image.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';

$manifest = get_option( 'aisa_demo_manifest', array() );
$GLOBALS['aisa_demo_manifest'] =& $manifest;
if ( ! $manifest ) {
	$manifest = array( 'created' => wp_date( DATE_ATOM ), 'posts' => array(), 'reading_before' => array() );
	foreach ( array( 'show_on_front', 'page_on_front', 'page_for_posts', 'posts_per_page' ) as $key ) {
		$manifest['reading_before'][ $key ] = get_option( $key );
	}
}
$media = get_option( 'aisa_demo_media', array() );
$photos = array(
	'passeio-mar' => 'Utentes do Centro de Dia num passeio junto ao mar',
	'centro-dia-sala' => 'Sala do Centro de Dia da AISA',
	'atelier-pizza' => 'Utentes e equipa num atelier de pizzas da AISA',
	'procissao-assuncao' => 'Encontro da comunidade na festa da Assunção',
	'cerimonia-bandeiras' => 'Cerimónia com bandeiras na AISA',
);
foreach ( $photos as $key => $alt ) {
	if ( ! empty( $media[ $key ] ) && wp_attachment_is_image( $media[ $key ] ) ) {
		update_post_meta( $media[ $key ], '_wp_attachment_image_alt', $alt );
		continue;
	}
	$source = dirname( ABSPATH ) . '/proto-2/img/facebook/' . $key . '.png';
	$editor = wp_get_image_editor( $source );
	if ( is_wp_error( $editor ) ) { WP_CLI::error( $editor->get_error_message() ); }
	$editor->resize( 1600, 1200, false );
	$editor->set_quality( 85 );
	$upload = wp_upload_dir();
	$target = wp_unique_filename( $upload['path'], 'aisa-demo-' . $key . '.jpg' );
	$saved = $editor->save( $upload['path'] . '/' . $target, 'image/jpeg' );
	if ( is_wp_error( $saved ) ) { WP_CLI::error( $saved->get_error_message() ); }
	$id = wp_insert_attachment( array( 'post_title' => '[DEMO] ' . $alt, 'post_status' => 'inherit', 'post_mime_type' => 'image/jpeg' ), $saved['path'], 0, true );
	if ( is_wp_error( $id ) ) { WP_CLI::error( $id->get_error_message() ); }
	wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $saved['path'] ) );
	update_post_meta( $id, '_wp_attachment_image_alt', $alt );
	update_post_meta( $id, '_aisa_demo_source', 'proto-2/img/facebook/' . $key . '.png' );
	$media[ $key ] = $id;
	update_option( 'aisa_demo_media', $media, false );
}
update_option( 'aisa_demo_slides', array_map( static function ( $key ) use ( $media, $photos ) {
	return array( 'id' => $media[ $key ], 'alt' => $photos[ $key ] );
}, array( 'passeio-mar', 'procissao-assuncao', 'atelier-pizza', 'cerimonia-bandeiras' ) ), false );

/** Never overwrite a pre-existing page that this seed does not own. */
function aisa_demo_post( $key, $data, $valencia = '', $source = '' ) {
	$manifest =& $GLOBALS['aisa_demo_manifest'];
	$id = (int) ( $manifest['posts'][ $key ] ?? 0 );
	if ( ! $id ) {
		$existing = get_page_by_path( $data['post_name'], OBJECT, $data['post_type'] );
		if ( $existing ) { WP_CLI::error( 'O slug já existe e não pertence à demo: ' . $data['post_name'] ); }
	}
	$data['ID'] = $id;
	$data['post_status'] = 'publish';
	$data['post_excerpt'] = '[DEMO] Conteúdo de demonstração M0; substituir ou remover em 02-05.';
	$data['meta_input']['_aisa_demo'] = 'M0-2026-11-09';
	if ( $source ) { $data['meta_input']['_aisa_demo_source'] = $source; }
	$id = wp_insert_post( wp_slash( $data ), true );
	if ( is_wp_error( $id ) ) { WP_CLI::error( $id->get_error_message() ); }
	if ( 'publish' !== get_post_status( $id ) ) { WP_CLI::error( 'Validação impediu publicar ' . $key ); }
	if ( $valencia ) { wp_set_object_terms( $id, $valencia, 'aisa_valencia' ); }
	$manifest['posts'][ $key ] = $id;
	update_option( 'aisa_demo_manifest', $manifest, false );
	return $id;
}
function aisa_demo_paragraph( $text ) {
	return '<!-- wp:paragraph --><p>' . esc_html( $text ) . '</p><!-- /wp:paragraph -->';
}
function aisa_demo_heading( $text, $level = 2 ) {
	return '<!-- wp:heading {"level":' . $level . '} --><h' . $level . ' class="wp-block-heading">' . esc_html( $text ) . '</h' . $level . '><!-- /wp:heading -->';
}

$front = aisa_demo_post( 'inicio', array( 'post_type' => 'page', 'post_name' => 'inicio', 'post_title' => 'Início', 'post_content' => '' ) );
$news_page = aisa_demo_post( 'noticias', array( 'post_type' => 'page', 'post_name' => 'noticias', 'post_title' => 'Notícias', 'post_content' => '', 'meta_input' => array( '_thumbnail_id' => $media['procissao-assuncao'] ) ) );

// Verbatim public source, reorganised into the approved service sections.
$content = aisa_demo_heading( 'Para quem é' ) . aisa_demo_paragraph( 'O nosso Centro de Dia oferece atividades divertidas e estimulantes, ateliers criativos, e um espaço acolhedor que promove a interação social.' );
$content .= aisa_demo_heading( 'O que inclui' );
$services = array(
	'Alimentação' => array( 'O nosso Centro de Dia disponibiliza três refeições diárias (pequeno-almoço, almoço e lanche), sendo as ementas elaboradas cuidadosamente por uma nutricionista.', 'As nossas refeições assentam numa alimentação saudável e equilibrada e têm em conta as preferências e as necessidades dos nossos utentes. Durante a refeição, também apoiamos aqueles que são mais dependentes.' ),
	'Higiene Pessoal' => array( 'O nosso Centro de Dia possibilita-lhe o apoio necessário para a realização da sua higiene pessoal, numa periodicidade definida de acordo com a sua necessidade.' ),
	'Tratamento de Roupa' => array( 'No caso de beneficiar do serviço de Higiene Pessoal, também poderá usufruir do nosso apoio ao nível do tratamento da sua roupa pessoal, a qual será devidamente higienizada por nós.' ),
	'Cuidados de Saúde' => array( 'Cuidados de Saúde/Serviço de Enfermagem:', 'No Centro de Dia a nossa enfermeira assegura cuidados como avaliação inicial, avaliação e monitorização de Sinais Vitais. Sempre que necessário os nossos utentes e familiares dirigem-se a enfermeira para aconselhamento.', 'O serviço de enfermagem também disponibiliza cuidados como o tratamento de feridas e administração de injetáveis por um valor acrescido.', 'Orgulhamo-nos de ter uma parceria com a Farmácia da Luz, com quem asseguramos a administração segura da medicação dos nossos utentes. A farmácia executa a preparação da medicação oral que é administrada em Centro de Dia, mediante carteiras de medicação que são entregues na AISA todas as semanas. Desta forma garantimos a preparação eficaz, nas condições ideais (acondicionamento do medicamento, temperatura correta, etc..) da medicação, minimizando erros e garantindo a segurança dos nossos utentes.' ),
	'Cuidados de Imagem' => array( 'No nosso Centro de Dia poderá contar com o serviço de cabeleireiro, manicure e pedicure mediante marcação.' ),
	'Transporte' => array( 'Conte connosco para transportá-lo de casa para o nosso Centro de Dia e deste de regresso a casa! A nossa frota automóvel possui três viaturas, sendo uma delas adaptada a pessoas com mobilidade reduzida.' ),
	'Atividades Diárias' => array( 'Venha participar nas atividades que o nosso Centro de Dia desenvolve ao longo do dia:', '– Atividades de estimulação física e cognitiva, realizadas individualmente ou em grupo, com o objetivo de preservar as capacidades físicas e cognitivas dos nossos utentes;', '– Oficinas, jogos interativos e passeios ao ar livre que pretendem promover o bem-estar e o envelhecimento ativo dos utentes.' ),
	'Apoio Multidisciplinar' => array( 'No nosso Centro de Dia poderá contar com o apoio de técnicos especializados nas áreas Social, Terapia da Fala, Fisioterapia, Psicologia, Psicomotricidade, Enfermagem e Medicina, em prol da sua qualidade de vida.' ),
);
foreach ( $services as $title => $paragraphs ) {
	$content .= aisa_demo_heading( $title, 3 );
	foreach ( $paragraphs as $paragraph ) { $content .= aisa_demo_paragraph( $paragraph ); }
}
$content .= aisa_demo_heading( 'Horário e condições' ) . aisa_demo_paragraph( 'Dias úteis das 8h30 às 18h00' );
$content .= aisa_demo_paragraph( 'O valor depende do rendimento do agregado familiar, segundo as regras da Segurança Social. Ligue-nos e fazemos as contas consigo.' );
$content .= aisa_demo_heading( 'Como aceder' ) . aisa_demo_paragraph( 'Tornar-se sócio é um requisito para quem pretende ser utente de qualquer um dos nossos serviços, sendo também um critério de priorização na avaliação das candidaturas.' );
$content .= '<!-- wp:aisa/telefone {"variante":"grande"} /-->';
aisa_demo_post( 'centro-dia', array( 'post_type' => 'page', 'post_name' => 'centro-de-dia', 'post_title' => 'Centro de Dia', 'post_content' => $content, 'meta_input' => array( '_wp_page_template' => 'page-servico', '_thumbnail_id' => $media['centro-dia-sala'] ) ), 'centro-dia', 'https://www.aisaipss.pt/centro-de-dia/' );

// Real source text is distinguished from the temporary illustrative demo photos.
$news = array(
	array( 'novo-servico-terapia-ocupacional', 'Novo Serviço – Terapia Ocupacional', '2025-03-12 10:00:00', 'generico', 'passeio-mar', array( 'A AISA dispõe de um novo serviço de Terapia Ocupacional.', 'O Terapeuta Ocupacional atua no tratamento e reabilitação de pessoas de todas as idades. O seu objetivo é facilitar e capacitar a realização de atividades do dia-a-dia que deixaram de poder ser realizadas por alguma condição clínica (motora, cognitiva, emocional ou social) decorrente de alguma doença, acidente, lesão ou da própria idade.', 'Quer saber mais informações? Contacte-nos! Telefone: 214 857 700' ) ),
	array( 'cozinheira-recrutamento', 'Cozinheira – Recrutamento', '2025-02-06 10:00:00', 'cozinha', 'centro-dia-sala', array( 'Estamos a recrutar Cozinheira.', 'Conhece alguém que está à procura de trabalho na área, ou acha que é a candidata perfeita para esta vaga?' ) ),
	array( 'inscricoes-abertas-para-o-atl-de-natal-2024', 'Inscrições abertas para o ATL de Natal 2024', '2024-12-11 10:00:00', 'infancia', 'atelier-pizza', array( 'Estão abertas as inscrições para o ATL de Natal 2024.', 'Atividades natalícias doces e divertidas!!! Inscrições até dia 13 dezembro.', 'Para mais informações contacte-nos: 214 857 700' ) ),
	array( 'a-aisa-conta-agora-com-um-centro-de-ensino-estruturado', 'A AISA conta agora com um Centro de Ensino Estruturado', '2025-04-03 10:00:00', 'infancia', 'cerimonia-bandeiras', array( 'O Centro de Ensino Estruturado (CEE) é um projeto que visa apoiar crianças e jovens com necessidades educativas especiais, entre os 6 e 18 anos, após o período escolar.' ) ),
);
$category = get_category_by_slug( 'noticias-aisa' );
$category_id = $category ? $category->term_id : wp_insert_term( 'Notícias AISA', 'category', array( 'slug' => 'noticias-aisa' ) )['term_id'];
foreach ( $news as [ $slug, $title, $date, $valencia, $photo, $paragraphs ] ) {
	$body = aisa_demo_paragraph( '[DEMO] Texto recuperado do site atual; fotografia ilustrativa da demo. A migração 02-05 repõe o artigo e a imagem completos. Esta notícia conserva a sua data original.' );
	foreach ( $paragraphs as $paragraph ) { $body .= aisa_demo_paragraph( $paragraph ); }
	$source = 'https://www.aisaipss.pt/noticias-aisa/' . $slug . '/';
	$body .= '<!-- wp:paragraph --><p><a href="' . esc_url( $source ) . '">Consultar a notícia original</a></p><!-- /wp:paragraph -->';
	aisa_demo_post( $slug, array( 'post_type' => 'post', 'post_name' => $slug, 'post_title' => '[DEMO] ' . $title, 'post_date' => $date, 'post_date_gmt' => get_gmt_from_date( $date ), 'post_content' => $body, 'post_category' => array( $category_id ), 'meta_input' => array( '_thumbnail_id' => $media[ $photo ] ) ), $valencia, $source );
}

foreach ( array(
	array( 'aniversario-aisa', 'Aniversário da AISA', '2026-11-20T15:00', '2026-11-20T17:00', 'generico', 'cerimonia-bandeiras' ),
	array( 'festa-natal', 'Festa de Natal', '2026-12-18T14:30', '2026-12-18T17:30', 'centro-dia', 'centro-dia-sala' ),
	array( 'atelier-natal', 'Atelier de Natal em família', '2026-12-21T10:00', '2026-12-21T12:00', 'infancia', 'atelier-pizza' ),
) as [ $slug, $title, $start, $end, $valencia, $photo ] ) {
	aisa_demo_post( 'evento-' . $slug, array( 'post_type' => 'aisa_evento', 'post_name' => $slug, 'post_title' => '[DEMO] ' . $title, 'post_content' => aisa_demo_paragraph( '[DEMO] Proposta de encontro para a demonstração M0. Data, horário, local e programa fictícios, a confirmar com a Mercês (BQ1). Não constitui um convite nem uma inscrição.' ), 'meta_input' => array( '_aisa_evento_inicio' => $start, '_aisa_evento_fim' => $end, '_aisa_evento_local' => '[DEMO] Instalações da AISA · local a confirmar', '_thumbnail_id' => $media[ $photo ] ) ), $valencia );
}
update_option( 'show_on_front', 'page' );
update_option( 'page_on_front', $front );
update_option( 'page_for_posts', $news_page );
update_option( 'posts_per_page', 12 );
// Retire only the untouched WordPress welcome post; keep its ID for rollback.
$welcome = get_post( 1 );
if ( $welcome && 'Olá, mundo!' === $welcome->post_title && str_contains( $welcome->post_content, 'WordPress' ) && 'publish' === $welcome->post_status ) {
	$manifest['welcome_before'] = array( 'ID' => 1, 'post_status' => 'publish' );
	update_option( 'aisa_demo_manifest', $manifest, false );
	wp_update_post( array( 'ID' => 1, 'post_status' => 'draft' ) );
}
wp_get_theme()->delete_pattern_cache();
flush_rewrite_rules( false );
WP_CLI::success( 'Demo criada/atualizada. Manifesto: aisa_demo_manifest. Depoimentos originais e imagens de notícias aguardam acesso à fonte; não foram inventados.' );
