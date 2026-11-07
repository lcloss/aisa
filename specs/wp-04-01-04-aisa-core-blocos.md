# wp-04-01-04 — `aisa-core`: blocos dinâmicos

> Gerado por `wp-04-project-manager` em 2026-11-07. Fase de implementação 01 (demo M0). Depende de: 01-03 (tipos de conteúdo e helpers) e 01-02 (classes do `components.css` carregadas pelo tema).

## 1. Tipo

**Plugin próprio.**

## 2. Páginas / requisitos cobertos

`wp-03-product-backlog.md`:
- os blocos de **P01** (slideshow, provas, depoimentos, próximos eventos);
- **P03** (regulamentos, telefone, depoimentos);
- **P17** (documentos);
- **P21** e **P22** (próximos eventos);
- conclusão de **T03** (bloco escondido com menos de 3);
- parte de **RF04** (classes e atributos para os eventos `click_tel` e `file_download`);
- **RF12** (slideshow acessível).

`wp-02` §4.3.

## 3. Âmbito técnico

**Pasta:** `aisa-core/blocks/<nome>/`, com `block.json` (apiVersion 3, `"render": "file:./render.php"`, `"editorScript": "file:./editor.js"`), `render.php` e `editor.js`.

- **Sem build:** o `editor.js` usa `wp.blocks.registerBlockType`, `wp.element.createElement`, `wp.serverSideRender` e `wp.blockEditor.InspectorControls` / `wp.components`.
- Registo com `register_block_type( __DIR__ . '/blocks/<nome>' )` em `init`.
- Categoria própria: "AISA".
- Os estilos vêm do `components.css` (tema). Os blocos **não trazem CSS de cor**; no máximo, CSS estrutural mínimo em `style.css` do bloco.

| Bloco | Atributos | Render (HTML / classes) | Regras |
|---|---|---|---|
| `aisa/slideshow` | `slides`: array de `{id, alt}` (Media Library, multi-selecção); `label` (string, "Fotografias da AISA") | `<section class="slideshow" aria-roledescription="carrossel" aria-label="…">`; cada slide é um `<figure>` com `wp_get_attachment_image` (1.º com `fetchpriority="high"` e sem lazy; os outros `loading="lazy"`); controlos `<button>` Anterior, Seguinte e **Reproduzir/Pausar** (o texto e o `aria-label` mudam); indicador "1 de 5" com `aria-live="polite"` | **Não avança sozinho por omissão** (WCAG 2.2.2); o botão "Reproduzir" inicia o avanço a cada 6 s; pausa com o foco ou o rato em cima; com `prefers-reduced-motion`, sem transição; com JS desligado, mostra todas as imagens empilhadas. `view.js` (viewScript) sem dependências. Texto alternativo obrigatório (o editor avisa) |
| `aisa/provas` | `items`: array de `{numero, legenda}`, por ex. "30" / "anos ao lado das famílias"; `ano` (string, por ex. "Dados de 2025") | `.proof-strip` com `<dl>` | O ano é sempre visível (PRD §3.1) |
| `aisa/depoimentos` | `quantidade` (1–3, omissão 1), `valencia` (opcional), `ordem` ("aleatoria" ou "recente") | `.testimonial`: `<figure><blockquote>` texto `</blockquote><figcaption>` relação · nome da valência `</figcaption></figure>` | **Se houver menos de 3 depoimentos publicados (no total), não imprime nada** (nem o título da secção, que vem dentro do bloco como atributo `titulo`). Sem nomes (L9) |
| `aisa/proximos-eventos` | `quantidade` (omissão 3), `valencia` (opcional), `mostrarLigacao` (bool, "Ver agenda") | Lista `.event`: `<time datetime>` com dia e mês em destaque (cor da valência via `data-valencia`), título com ligação, local | Só eventos futuros (`aisa_evento_is_future`). Estado vazio: "Em breve, novas datas. Veja as últimas notícias." com ligação para `/noticias/` |
| `aisa/documentos` | `tipos` (multi; omissão: todos), `valencia` (opcional) | `.doc-group` por tipo (`<h2>`), `.doc-list` por ano descendente: `<a href download class="aisa-download" data-file-ext>` título — "PDF, 1,2 MB" | Alvo ≥ 56 px (classe do `components.css`). Ligações com `aria-describedby` para o formato e o tamanho |
| `aisa/regulamentos` | — (usa a valência da página actual) | Como `aisa/documentos`, filtrado: tipo "Regulamentos" + (valência da página **ou** o documento "Regulamento Geral", identificado pelo termo `generico`) | Nada a mostrar → não imprime nada |
| `aisa/telefone` | `variante` ("botao" / "grande" / "texto") | `<a href="tel:+351214857700" class="aisa-tel …">214 857 700</a>`, com `aria-label` "Ligar para a AISA, 214 857 700" | A classe `aisa-tel` serve o evento GA4 `click_tel` (02-02). O número vem de uma constante `AISA_TELEFONE` (filtrável) |

- **Datas em pt-PT:** `wp_date()` com o fuso do site.
- **Escapamento:** `esc_html`, `esc_url`, `esc_attr` em todos os outputs.
- **Cache:** sem cache própria. As consultas são simples: `no_found_rows`, `posts_per_page` limitado, `fields` se aplicável.

## 4. Tarefas ordenadas

1. Categoria de blocos "AISA" (`block_categories_all`).
2. `aisa/telefone` (o mais simples, para validar o padrão `block.json` + `render.php` + `editor.js` sem build).
3. `aisa/provas`.
4. `aisa/proximos-eventos`.
5. `aisa/depoimentos` (com o limiar de 3).
6. `aisa/documentos` + `aisa/regulamentos`.
7. `aisa/slideshow` + `view.js` (acessibilidade completa).
8. Trocar o botão de telefone estático do `parts/header.html` (01-02) pelo `aisa/telefone`.
9. Página de teste (rascunho) com todos os blocos; verificar §6 com 2 e depois com 3 depoimentos.
10. Marcar ✅ em T03 no `wp-03-product-backlog.md`. Os blocos não têm item próprio; as páginas que os usam são marcadas em 01-05 e 02-04.

## 5. Critérios de aceitação

- Os 7 blocos aparecem no inseridor, na categoria "AISA", e são pré-visualizados no editor (ServerSideRender).
- Com 2 depoimentos publicados, o bloco não imprime nada; com 3, imprime.
- O slideshow não avança sozinho; Reproduzir/Pausar funciona por teclado; o anúncio "n de N" é feito; com `prefers-reduced-motion`, sem animação; sem JS, as imagens ficam visíveis.
- `aisa/documentos` mostra o formato e o tamanho reais; o regulamento de uma página de serviço mostra só o da valência + o Geral.
- `aisa/proximos-eventos` nunca mostra um evento passado.
- Todas as ligações `tel:` levam a classe `aisa-tel`; os downloads levam `aisa-download`.
- axe: 0 erros críticos ou graves na página de teste. Navegação por teclado completa.
- `debug.log` sem avisos.

## 6. Comandos de verificação

```bash
cd /c/var/www/WordPress/aisa
wp --path=public_html eval 'foreach (WP_Block_Type_Registry::get_instance()->get_all_registered() as $n=>$b) if (str_starts_with($n,"aisa/")) echo $n,"\n";'   # 7 linhas
for f in public_html/wp-content/plugins/aisa-core/blocks/*/render.php; do php -l "$f"; done
curl -s http://aisa.wordpress.test/?p=<ID-pagina-teste>&preview=true | grep -c 'aisa-tel'
npx --yes @axe-core/cli "http://aisa.wordpress.test/?page_id=<ID>" --exit
tail -5 public_html/wp-content/debug.log 2>/dev/null
```

Verificação manual do slideshow: Tab até aos controlos, Enter e Espaço; NVDA anuncia o indicador; DevTools → Rendering → `prefers-reduced-motion: reduce`.

## 7. Prompt para o `wp-05-developer`

```
/wp-05-developer Implementa specs/wp-04-01-04-aisa-core-blocos.md.
7 blocos dinâmicos no aisa-core (block.json + render.php + editor.js em JS simples, sem build): slideshow acessível (não avança sozinho, pausa, reduced-motion, sem-JS), provas, depoimentos (nada com <3), proximos-eventos (só futuros), documentos, regulamentos (valência + Geral), telefone (classe aisa-tel). Sem CSS de cor — usa as classes do components.css. Troca o telefone estático do header pelo bloco. Corre §6 e marca ✅ T03 em wp-03-product-backlog.md.
```
