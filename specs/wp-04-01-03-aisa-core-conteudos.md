# wp-04-01-03 — `aisa-core`: tipos de conteúdo, taxonomias e campos

> Gerado por `wp-04-project-manager` em 2026-11-07. Fase de implementação 01 (demo M0). Depende de: 01-01. Pode correr em paralelo com 01-02.

## 1. Tipo

**Plugin próprio.**

## 2. Páginas / requisitos cobertos

`wp-03-product-backlog.md`:
- **T02** (Evento), **T03** (Depoimento), **T04** (Documento), **T05** (`aisa_valencia`);
- **T06**, parte do plugin: imagem destacada obrigatória nas notícias;
- **RF11**, parte do plugin: instruções no Depoimento e validação;
- o JSON-LD Event de **P23** (`wp-02` §6).

## 3. Âmbito técnico

**Pasta:** `public_html/wp-content/plugins/aisa-core/`

```
aisa-core/
  aisa-core.php            # cabeçalho (Plugin Name: AISA Core; Requires PHP: 8.1; Text Domain: aisa); bootstrap; constantes AISA_CORE_VERSION/PATH/URL
  includes/class-post-types.php
  includes/class-taxonomies.php
  includes/class-meta.php        # register_post_meta + meta boxes nativos + gravação com nonce/capability/sanitização
  includes/class-valencia.php    # data-valencia (body_class / filtro), helper aisa_get_valencia($post)
  includes/class-featured-required.php  # bloqueio de publicação sem imagem destacada em 'post'
  includes/class-schema-event.php       # JSON-LD Event em single aisa_evento
  assets/js/editor-featured-required.js # wp.data lockPostSaving (JS simples, sem build)
  uninstall.php            # não apaga conteúdos (só opções próprias, se houver)
```

PHP com namespace `Aisa\Core`, funções públicas com o prefixo `aisa_`, WPCS.

### Tipos de conteúdo (`wp-02` §3.1)

| Tipo | Argumentos principais |
|---|---|
| `aisa_evento` | `public` = true, `has_archive` = `agenda`, `rewrite` = `['slug' => 'agenda', 'with_front' => false]`, `show_in_rest` = true, `supports` = title, editor, excerpt, thumbnail; `menu_icon` = dashicons-calendar-alt; etiquetas em pt-PT ("Eventos", "Novo evento") |
| `aisa_depoimento` | `public` = false, `show_ui` = true, `publicly_queryable` = false, `exclude_from_search` = true, `show_in_rest` = true, `supports` = editor (e title, só como referência interna; **não é mostrado**); etiquetas "Depoimentos" |
| `aisa_documento` | `public` = false, `show_ui` = true, `publicly_queryable` = false, `show_in_rest` = true, `supports` = title; etiquetas "Documentos" |

### Taxonomias (`wp-02` §3.2)

| Taxonomia | Argumentos |
|---|---|
| `aisa_valencia` | Hierárquica = false; `show_in_rest` = true; objectos `post`, `page`, `aisa_evento`, `aisa_depoimento`, `aisa_documento`; `rewrite` = false (sem arquivo público na fase 01). Na activação cria os 5 termos (`sad` "Serviço de Apoio Domiciliário", `centro-dia` "Centro de Dia", `infancia` "Infância e Juventude", `generico` "Geral", `cozinha` "Cozinha"). **Editor sem permissão para criar ou apagar termos** (`capabilities`: `manage_terms`, `edit_terms` e `delete_terms` = `manage_options`; `assign_terms` = `edit_posts`). Na interface, selecção de um só termo |
| `aisa_tipo_documento` | Objecto `aisa_documento`; termos criados na activação: Estatutos, Relatórios e Contas, Regulamentos, Assembleia Geral; as mesmas restrições de capacidades |

### Campos (meta nativa, `register_post_meta` com `show_in_rest` + meta box)

| Tipo | Chave | Tipo de dado | Validação | Interface |
|---|---|---|---|---|
| Evento | `_aisa_evento_inicio` | string ISO 8601 (`Y-m-d\TH:i`) | **obrigatório**; sem data não publica (volta a rascunho com aviso) | `<input type="datetime-local">` |
| | `_aisa_evento_fim` | string ISO, opcional | ≥ início | idem |
| | `_aisa_evento_local` | string (máx. 120) | `sanitize_text_field` | texto |
| Depoimento | `_aisa_dep_relacao` | string (máx. 80), obrigatória | — | texto, com a ajuda "Ex.: Filha de um utente" |
| | `_aisa_dep_data` | string `Y-m` | — | `<input type="month">` |
| Documento | `_aisa_doc_ficheiro` | int (ID do anexo) | **obrigatório**; só pdf, doc, docx, xls, xlsx | botão "Escolher ficheiro" (`wp.media`, JS simples) |
| | `_aisa_doc_ano` | int (2000–ano actual + 1) | **obrigatório** | número |

- Gravação: nonce, `current_user_can('edit_post')`, sanitização.
- Meta com `_` + `auth_callback` = `edit_post`.
- Helpers: `aisa_doc_file_info($post_id)` → `['url','ext','size_human']` (o tamanho vem de `filesize(get_attached_file())`, formatado em pt-PT, por ex. "1,2 MB"); `aisa_evento_is_future($post_id)`.
- **Ordem das listas:** `pre_get_posts` no arquivo `aisa_evento` (front-end): só eventos com início ≥ hoje (00:00, `Europe/Lisbon`), ordenados por `_aisa_evento_inicio` ASC.
- **Colunas no wp-admin:** Eventos (Data, Local, Valência), Documentos (Tipo, Ano, Ficheiro), Depoimentos (Relação, Serviço).

### Valência no front-end

- `body_class` → acrescenta `valencia-<slug>`.
- Filtro `aisa_body_attributes`, ou injecção por `wp_body_open` + JS mínimo, para pôr `data-valencia="<slug>"` no `<body>`. **Preferência:** o tema lê `aisa_get_valencia()` e imprime o atributo num `core/group` do template. Fica combinado com 01-05: o plugin expõe `aisa_get_valencia( $post = null ): ?WP_Term` e o filtro `render_block` acrescenta `data-valencia` a blocos com a classe `aisa-valencia-scope`.

### Imagem destacada obrigatória (`post`)

- Editor: `editor-featured-required.js` faz `lockPostSaving('aisa-featured')` enquanto não houver imagem destacada e mostra o aviso "A notícia precisa de uma imagem destacada (com texto alternativo)".
- Servidor: em `wp_insert_post_data`, se `post_status` = publish sem `_thumbnail_id`, força `draft` e regista um aviso no ecrã (`admin_notices` por transient).
- **Excepção de migração:** se a constante `AISA_IMPORTING` estiver definida e for true, a validação do servidor não se aplica. É usada só durante o 02-05 e retirada no fim.

### Depoimento: instruções (RF11)

Meta box de ajuda, só de leitura, no topo do ecrã de edição: *"Confirme que tem o 'sim' registado de quem deu o depoimento. Não escreva nomes, idades, ruas ou doenças."*

### Schema Event (P23)

Em `is_singular('aisa_evento')`, imprime JSON-LD no `wp_head`:
- `@type` Event;
- `name`, `startDate`, `endDate`;
- `location` (Place, com `name` = local e `address` = morada da AISA, se o local estiver vazio);
- `organizer` (Organization AISA, com url);
- `image`;
- `eventStatus` = EventScheduled;
- `eventAttendanceMode` = Offline.

O Rank Math (02-06) não duplica: desliga o seu schema para este tipo de conteúdo.

## 4. Tarefas ordenadas

1. Criar o bootstrap do plugin, o autoload simples (`require_once`) e o hook de activação, que regista os tipos de conteúdo e as taxonomias, cria os termos e faz `flush_rewrite_rules`.
2. `class-post-types.php` com os três tipos.
3. `class-taxonomies.php` com as capacidades restritas e os termos iniciais.
4. `class-meta.php`: `register_post_meta`, meta boxes, gravação, validação (com retorno a rascunho e aviso), colunas do admin.
5. `pre_get_posts` da agenda (só eventos futuros, por data ascendente).
6. `class-valencia.php`: helper, `body_class`, filtro `render_block` para `aisa-valencia-scope`.
7. `class-featured-required.php` + JS.
8. Meta box de instruções do Depoimento.
9. `class-schema-event.php`.
10. Activar (`wp plugin activate aisa-core`), criar 1 evento futuro, 1 passado, 1 depoimento e 1 documento de teste, e verificar §6.
11. Marcar ✅ em T02, T03 (o bloco que esconde os depoimentos com menos de 3 fica em 01-04; marcar T03 ✅ só depois de 01-04), T04 e T05. A parte do plugin de T06 fica registada; T06 ✅ só depois de 01-02 e 01-05.

## 5. Critérios de aceitação

- Os 3 tipos de conteúdo e as 2 taxonomias aparecem no wp-admin, em pt-PT; os 5 termos de valência e os 4 tipos de documento existem.
- Um utilizador Editor **não consegue** criar termos de valência nem de tipo de documento.
- Um evento sem data de início não fica publicado (volta a rascunho, com aviso).
- `/agenda/` lista só eventos futuros, por data ascendente; um evento passado continua acessível pelo seu URL.
- Depoimentos e documentos **não** têm URL público (404 ao tentar `?post_type=aisa_depoimento&p=ID`).
- `aisa_doc_file_info()` devolve a extensão e o tamanho correctos de um PDF de teste.
- Uma notícia sem imagem destacada não se publica, nem no editor nem por REST/WP-CLI.
- O JSON-LD Event é válido (Rich Results Test ou validator.schema.org) num evento de teste.
- A página com valência tem `data-valencia` no contentor esperado.
- `debug.log` sem avisos. PHPCS (WordPress) sem erros, se estiver disponível.

## 6. Comandos de verificação

```bash
cd /c/var/www/WordPress/aisa
wp --path=public_html plugin activate aisa-core
wp --path=public_html post-type list --fields=name,public | grep aisa_
wp --path=public_html term list aisa_valencia --fields=slug,name
wp --path=public_html term list aisa_tipo_documento --fields=slug,name
wp --path=public_html post create --post_type=post --post_title="Teste sem imagem" --post_status=publish --porcelain | xargs -I{} wp --path=public_html post get {} --field=post_status   # tem de dar draft
curl -s http://aisa.wordpress.test/agenda/ | grep -c 'class="event'
curl -s -o /dev/null -w "%{http_code}\n" "http://aisa.wordpress.test/?post_type=aisa_depoimento&p=$(wp --path=public_html post list --post_type=aisa_depoimento --field=ID | head -1)"   # 404
curl -s http://aisa.wordpress.test/agenda/<slug-teste>/ | grep -c '"@type":"Event"'
php -l public_html/wp-content/plugins/aisa-core/aisa-core.php
tail -5 public_html/wp-content/debug.log 2>/dev/null
```

## 7. Prompt para o `wp-05-developer`

```
/wp-05-developer Implementa specs/wp-04-01-03-aisa-core-conteudos.md.
Plugin próprio aisa-core (namespace Aisa\Core, prefixo aisa_, sem ACF, sem build): CPTs aisa_evento/aisa_depoimento/aisa_documento, taxonomias aisa_valencia e aisa_tipo_documento com termos fixos e capacidades restritas, meta nativa com meta boxes e validação, agenda só futura, imagem destacada obrigatória em 'post' (editor + servidor), instruções no Depoimento, JSON-LD Event, helper aisa_get_valencia(). Corre §6 e marca ✅ T02, T04, T05 em wp-03-product-backlog.md (T03 e T06 só depois das specs indicadas em §4.11).
```
