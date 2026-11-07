# wp-04-01-02 — Tema base `aisa`

> Gerado por `wp-04-project-manager` em 2026-11-07. Fase de implementação 01 (demo M0). Depende de: 01-01.

## 1. Tipo

**Blocos / tema.**

## 2. Páginas / requisitos cobertos

`wp-03-product-backlog.md`:
- **T01** (tema, design system, fontes);
- **G01** (cabeçalho), **G02** (rodapé, estrutura), **G03** (menu principal), **G04** (menu de telemóvel), **G06** ("Saltar para o conteúdo");
- **T06**, parte do tema: imagem por defeito no cabeçalho de página;
- base de **RF12 / T13** (acessibilidade transversal);
- estilos de formulário usados por RF01 e RF02.

## 3. Âmbito técnico

**Pasta:** `public_html/wp-content/themes/aisa/`

```
aisa/
  style.css               # cabeçalho do tema (Theme Name: AISA; Requires at least: 6.6; Requires PHP: 8.1; Text Domain: aisa; Version: 0.1.0)
  theme.json              # gerado de specs/tokens/theme.json + definições de layout e blocos (abaixo)
  functions.php           # enqueue, editor styles, estilos de bloco, menus de navegação, imagem por defeito
  assets/css/base.css     # cópia literal de specs/tokens/base.css
  assets/css/components.css  # cópia literal de specs/tokens/components.css
  assets/css/theme.css    # só ligação entre os blocos core e as classes do components.css (sem valores de cor/tamanho)
  assets/css/forms-cf7.css   # mapeia .wpcf7 → estilos de formulário do components.css
  assets/fonts/           # Fraunces (variável, 400–500 + itálico) e Atkinson Hyperlegible (400, 700, itálicos), woff2, com subset latin + latin-ext
  assets/js/nav.js        # menu de telemóvel e painel "Serviços" (sem dependências)
  assets/img/default.jpg  # imagem por defeito (F20 mínimo), 1600×900, a partir de proto-2/img/facebook/
  assets/icons/           # ícones de linha das valências (de brand-03)
  templates/index.html, page.html, 404.html, search.html
  parts/header.html, footer.html
  screenshot.png
```

**`theme.json`:**
- Partir de `specs/tokens/theme.json`, que é gerado, e acrescentar:
  - `settings.layout`: `contentSize` 720 px, `wideSize` 1200 px;
  - `settings.typography.fontFamilies` com `fontFace` a apontar para `file:./assets/fonts/…` (**sem Google Fonts**, L21);
  - `customFontSize` = false;
  - `settings.spacing.units` = `["px","rem","%"]`;
  - `settings.blocks` com as restrições por bloco onde fizer falta.
- **Não alterar** a paleta nem os tamanhos gerados.
- Registar em `specs/tokens/wordpress.md` que o `theme.json` do tema = gerado + extras. A fusão faz-se à mão e documenta-se no topo do ficheiro em `$comment`.

**`functions.php`:**
- `wp_enqueue_style`: base → components → theme → forms-cf7 (este só se o CF7 estiver activo).
- `add_editor_style` com os mesmos três.
- `register_block_style`:
  - `core/button`: `primary` (omissão), `secondary`, `accent`, `inverse`;
  - `core/group`: `section-plain`, `section-surface`, `section-muted`;
  - `core/image`: `media` (`--radius-media`).
- `register_nav_menus` ou menus de bloco: `principal` e `rodape`. O menu principal é um `core/navigation` em `parts/header.html`.
- Filtro do cabeçalho de página: se não houver imagem destacada, usa `assets/img/default.jpg` (função `aisa_header_image_url()`; o plugin `aisa-core` pode sobrepor por valência na fase 02).
- `remove_theme_support('core-block-patterns')`: sem padrões core genéricos no inseridor.

**Cabeçalho (G01, G03):**
- Logótipo (o SVG de `specs/brand-03`; texto alternativo "AISA — início").
- `core/navigation` com a estrutura de `wp-03` §5.2: o painel "Serviços" tem títulos de grupo que não são ligações (`core/navigation-submenu` com etiquetas) e "Ver todos os serviços".
- Botão de telefone `tel:+351214857700`, com o texto "214 857 700" visível.
- Abaixo de 560 px, versão compacta: logótipo, botão "Ligar" e botão "Menu".
- O botão de telefone é HTML estático, porque o bloco `aisa/telefone` só existe a partir de 01-04. Depois troca-se.

**Menu de telemóvel (G04), `nav.js`:**
- painel de ecrã inteiro;
- o botão "Menu" tem `aria-expanded` e `aria-controls`;
- grupos em acordeão (`<button aria-expanded>`);
- foco preso dentro do painel; Esc fecha e devolve o foco ao botão;
- `body` sem scroll enquanto o painel está aberto;
- telefone no topo do painel;
- alvos ≥ 44 px.

Se o overlay nativo do `core/navigation` cumprir tudo isto, usa-se o nativo e regista-se no fim da spec. Senão, `nav.js`.

**Rodapé (G02, estrutura):**
- 4 colunas (empilhadas em telemóvel), com o conteúdo de `wp-03` §5.1 G02;
- o NIPC e a morada ficam com o texto **"[A CONFIRMAR — BQ2]"** até a AISA responder;
- "Preferências de cookies" fica como ligação com `href="#"` e a classe `aisa-cookie-prefs` (ligada em 02-02);
- Livro de Reclamações comentado no padrão até à A9.

**"Saltar para o conteúdo" (G06):** primeiro elemento do `<body>`, visível com foco, aponta para `#conteudo` (o `<main>` dos templates).

**`page.html`:**
- cabeçalho de página (`.page-header`: imagem destacada ou por defeito, título, breadcrumbs, estes com espaço reservado até 02-06);
- conteúdo (`core/post-content`, `layout constrained`).

**Acessibilidade:**
- `lang="pt-PT"` (vem do `pt_PT`);
- foco visível pelo `--focus-ring`;
- `prefers-reduced-motion` no CSS;
- hierarquia de títulos: um `h1` por página.

**Restrições de composição** (`wp-02` §1): sem sombras em cartões; raios contidos em botões e campos; `--radius-media` só nas fotografias.

## 4. Tarefas ordenadas

1. Criar a estrutura de pastas e o `style.css`.
2. Copiar `base.css` e `components.css` de `specs/tokens/` (sem editar).
3. Descarregar as fontes (woff2 oficiais: Google Fonts download / repositórios oficiais, licença OFL) para `assets/fonts/`, mais o `OFL.txt`.
4. Construir o `theme.json` (gerado + extras), com `fontFace` local.
5. `functions.php`: enqueue, editor styles, estilos de bloco, imagem por defeito, remoção dos padrões core.
6. `parts/header.html` + `core/navigation` + botão de telefone; `nav.js`, se necessário.
7. `parts/footer.html`.
8. `templates/index.html`, `page.html`, `404.html` (com o conteúdo de P30), `search.html`.
9. `forms-cf7.css`: mapeia `.wpcf7-form-control`, `.wpcf7-not-valid-tip`, `.wpcf7-response-output`, `.wpcf7-acceptance` e `.wpcf7-radio` para os estilos de formulário do `components.css`.
10. `assets/img/default.jpg` (escolher em `proto-2/img/facebook/`, redimensionar e comprimir).
11. Activar o tema (`wp theme activate aisa`), criar o menu principal com as páginas que existirem (os itens em falta ficam como ligações personalizadas `#` até 02-04) e verificar §6.
12. Marcar ✅ em T01, G01, G03, G04 e G06 no `wp-03-product-backlog.md`. O **G02** fica 🔄 até 02-04 (conteúdo completo).

## 5. Critérios de aceitação

- O tema `aisa` está activo. O editor só mostra a paleta e os tamanhos do `theme.json` (sem cor nem tamanho personalizados).
- Separador de rede com a cache limpa: **0 pedidos** a `fonts.googleapis.com` ou `fonts.gstatic.com`; as fontes vêm de `/wp-content/themes/aisa/assets/fonts/`.
- O cabeçalho mostra o telefone clicável em todas as larguras (360, 768, 1280 px); sem deslocamento horizontal a 360 px.
- Menu de telemóvel: abre e fecha por teclado; Esc fecha e o foco volta ao botão; o foco não sai do painel enquanto está aberto.
- O "Saltar para o conteúdo" é o primeiro Tab e salta para o `<main>`.
- Uma página sem imagem destacada mostra `default.jpg` no cabeçalho.
- axe: 0 erros críticos ou graves em `page.html` e no 404.
- `debug.log` sem avisos.

## 6. Comandos de verificação

```bash
cd /c/var/www/WordPress/aisa
wp --path=public_html theme list --status=active
diff specs/tokens/base.css public_html/wp-content/themes/aisa/assets/css/base.css && echo base OK
diff specs/tokens/components.css public_html/wp-content/themes/aisa/assets/css/components.css && echo components OK
php -r 'json_decode(file_get_contents("public_html/wp-content/themes/aisa/theme.json")); echo json_last_error()===0?"theme.json OK\n":"ERRO\n";'
curl -s http://aisa.wordpress.test/ | grep -c "fonts.googleapis\|fonts.gstatic"   # tem de dar 0
curl -s http://aisa.wordpress.test/ | grep -o 'lang="[^"]*"' | head -1
npx --yes @axe-core/cli http://aisa.wordpress.test/?page_id=2 --exit
tail -5 public_html/wp-content/debug.log 2>/dev/null
```

Verificação manual: teclado (Tab, Esc), zoom a 200%, 360 px no Chrome DevTools.

## 7. Prompt para o `wp-05-developer`

```
/wp-05-developer Implementa specs/wp-04-01-02-tema-base.md.
Block theme próprio "aisa" (sem tema pai), em public_html/wp-content/themes/aisa. Copia base.css e components.css de specs/tokens sem alterar; theme.json = specs/tokens/theme.json + extras de §3 com fontes locais (nada de Google Fonts). Cabeçalho com telefone, menu "Serviços" com grupos (wp-03 §5.2), menu de telemóvel acessível, rodapé com espaços reservados [A CONFIRMAR — BQ2]. Respeita as restrições de composição (sem sombras/caixas, raios contidos). Corre §6, e marca ✅ T01, G01, G03, G04, G06 em wp-03-product-backlog.md (G02 fica 🔄).
```
