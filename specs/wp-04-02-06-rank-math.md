# wp-04-02-06 — Rank Math: SEO técnico, 301 e breadcrumbs

> Gerado por `wp-04-project-manager` em 2026-11-07. Fase de implementação 02 (beta M1). Depende de: **02-05** (metadados do Yoast e inventário de URLs) e 02-04 (páginas finais).

## 1. Tipo

**Configuração de plugin.**

## 2. Páginas / requisitos cobertos

`wp-03-product-backlog.md`:
- **T11** (301 + monitor de 404);
- **T12** (meta, Open Graph, Schema.org);
- **G05** (breadcrumbs);
- o mapa de 301 de `wp-03` §5.3.

`wp-02` §6. Critério 8 do PRD, K9, F21.

## 3. Âmbito técnico

**Plugin:** **Rank Math SEO** (gratuito). Sem Rank Math PRO.

**Assistente** (modo "Advanced"):
- tipo de site "Organização sem fins lucrativos";
- nome "AISA — Associação de Apoio Social Nossa Senhora da Assunção";
- logótipo (o PNG/SVG do tema);
- imagem social por defeito 1200×630, a partir do `default.jpg`.

**Módulos:**

| Módulo | Estado | Configuração |
|---|---|---|
| SEO Analysis / Content AI / Analytics / Instant Indexing / Image SEO / Local SEO / 404 Monitor | Só **404 Monitor** e **Redirections** ligados; o resto desligado | Menos superfície, sem chamadas externas |
| Schema | Ligado | Organização: `@type` **NGO**, nome, logótipo, url, telefone `+351214857700`, morada (BQ2), `sameAs` (Facebook, Instagram). Notícias → **Article** (NewsArticle opcional). Páginas → WebPage. **`aisa_evento` → nenhum schema do Rank Math** (o JSON-LD Event vem do `aisa-core`, 01-03) |
| Sitemap | Ligado | Incluir: páginas, notícias, `aisa_evento`. Excluir: `aisa_depoimento`, `aisa_documento`, anexos, autores, etiquetas, categorias (a categoria única não acrescenta valor). O `wp-sitemap.xml` do núcleo é desligado pelo próprio Rank Math |
| Breadcrumbs | Ligado | Separador `›`; "Início"; mostrar o título actual. Inserido no `.page-header` dos templates (bloco "Breadcrumbs" do Rank Math ou `rank_math_the_breadcrumbs()` numa template part). **Não aparece** no Início |
| Redirections | Ligado | Ver abaixo. "Redireccionar anexos para o ficheiro" (Títulos e Meta → Media) |
| 404 Monitor | Ligado, modo simples | Revisto no M2 e durante 90 dias (K9) |

**Títulos e meta:**
- separador `·`;
- títulos `%title% %sep% AISA`; início: `AISA %sep% Perto de si, a cuidar dos seus`;
- descrição: a meta description por página (até 155 caracteres) a partir do excerto; nas páginas de serviço, uma frase curta do texto actual (`wp-02` §6);
- `noindex`: arquivos de autor, de data, de etiqueta, de categoria (e pesquisa);
- Open Graph: activo, com a imagem destacada ou a por defeito; Twitter "summary_large_image".

**Importação do Yoast:** Rank Math → Ferramentas de Estado → Importar → Yoast SEO (só os metadados de artigos e páginas; os metadados existem nos `postmeta` vindos do 02-05). Não há tabelas do Yoast instaladas. Se o importador exigir o plugin Yoast activo, usa-se o `wp db query` para mapear `_yoast_wpseo_title` → `rank_math_title` e `_yoast_wpseo_metadesc` → `rank_math_description`, substituindo `%%title%%`, `%%sep%%` e `%%sitename%%` pelas variáveis do Rank Math. Regista-se aqui.

**Redirecionamentos 301** (`wp-03` §5.3 + `scratch/inventario-urls.csv` do 02-05):

| Origem | Destino |
|---|---|
| `/qualidade-de-vida/` | `/saude-e-reabilitacao/` |
| `/novos-projectos/` | `/novas-instalacoes/` |
| `/quem-somos-2/` | `/quem-somos/` |
| `/apoio-a-infancia/` | `/servicos/` |
| `/apoio-a-infancia-2/` | `/servicos/` |
| `/category/noticias-aisa/` (regex `^category/noticias-aisa(/page/\d+)?/?$`) | `/noticias/` |
| `/noticias-aisa/hello-world/` | `/noticias/` |
| `/noticias-aisa/consignacao-irs/attachment/relatorio-e-contas-2022/` | URL do PDF de 2022 (do 02-05) |
| `/feed/` e `/comments/feed/` | mantêm-se (feed do núcleo); os comentários estão desligados |
| Qualquer outra linha do inventário com URL diferente | conforme o CSV |

**Em produção** (`wp-09`), os 301 viajam na base de dados; não há regras de servidor.

## 4. Tarefas ordenadas

1. Instalar o Rank Math; assistente; desligar os módulos não usados.
2. Títulos e meta, Open Graph, `noindex` dos arquivos.
3. Schema: NGO; desligar para o `aisa_evento`.
4. Sitemap (inclusões e exclusões).
5. Breadcrumbs nos templates (`page.html`, `page-servico.html`, `single.html`, `single-aisa_evento.html`, `home.html`, `archive-aisa_evento.html`). Retirar o espaço reservado do 01-02.
6. Importar os metadados do Yoast.
7. Redirecionamentos (tabela + CSV); anexos → ficheiro.
8. Descrições em falta nas páginas principais (P01–P19, P24, P25): a partir do texto actual.
9. Verificar §6.
10. Marcar ✅ em T11, T12 e G05 no `wp-03-product-backlog.md`.

## 5. Critérios de aceitação

- Todas as linhas da tabela de 301 respondem **301** para o destino certo (um salto, sem cadeias).
- **K9 = 0:** os URLs do sitemap antigo (páginas e notícias) respondem 200 ou 301 → 200.
- `/sitemap_index.xml` lista páginas, notícias e eventos; não lista depoimentos, documentos nem anexos.
- Cada página principal tem `<title>`, `meta description` (≤ 155), `og:title`, `og:description`, `og:image` e `canonical`.
- JSON-LD com `NGO` no Início; `Article` nas notícias; **um só** `Event` nos eventos (do `aisa-core`); `BreadcrumbList` nas páginas internas.
- Breadcrumbs visíveis nas páginas internas e ausentes no Início.
- Os metadados do Yoast estão presentes nas notícias migradas (amostra de 5).

## 6. Comandos de verificação

```bash
cd /c/var/www/WordPress/aisa
B=http://aisa.wordpress.test
for u in qualidade-de-vida novos-projectos quem-somos-2 apoio-a-infancia apoio-a-infancia-2 category/noticias-aisa noticias-aisa/hello-world; do curl -s -o /dev/null -w "%{http_code} %{redirect_url}  <- /$u/\n" "$B/$u/"; done
curl -s "$B/sitemap_index.xml" | grep -oE '<loc>[^<]+' | sed 's/<loc>//'
curl -s "$B/centro-de-dia/" | grep -oE '<title>[^<]+|name="description" content="[^"]+|property="og:image" content="[^"]+|rel="canonical" href="[^"]+'
curl -s "$B/" | grep -o '"@type":"NGO"' | head -1
curl -s "$B/agenda/<slug-evento>/" | grep -o '"@type":"Event"' | wc -l   # 1
wp --path=public_html db query "SELECT COUNT(*) FROM aisa_postmeta WHERE meta_key='rank_math_description'"
```

## 7. Prompt para o `wp-05-developer`

```
/wp-05-developer Implementa specs/wp-04-02-06-rank-math.md.
Rank Math gratuito com só Schema, Sitemap, Breadcrumbs, Redirections e 404 Monitor; NGO no schema, sem schema para aisa_evento (já vem do aisa-core); títulos/OG/noindex de arquivos; breadcrumbs nos templates (não no Início); importa os metadados do Yoast; cria todos os 301 da tabela + scratch/inventario-urls.csv. Corre §6 (um salto por 301, K9 = 0) e marca ✅ T11, T12, G05 em wp-03-product-backlog.md.
```
