# wp-04-02-05 — Migração de conteúdos

> Gerado por `wp-04-project-manager` em 2026-11-07. Fase de implementação 02 (beta M1). Depende de: 01-03 (tipos de conteúdo e taxonomias). Pode correr antes do 02-04. **Depende de A4** (WXR); há alternativa sem ela.

## 1. Tipo

**Configuração de plugin** (WordPress Importer, temporário) + operações WP-CLI.

## 2. Páginas / requisitos cobertos

`wp-03-product-backlog.md`: **RF06** (notícias), **RF07** (páginas), **RF08** (documentos), **RF09** (depoimentos), **RF10** (agenda inicial), **T10**.

- `wp-02` §4.1 (WordPress Importer) e §6 (permalinks e `uploads`).
- R9, K9.

## 3. Âmbito técnico

### 3.1 Fonte

| Caminho | Quando | O que importa |
|---|---|---|
| **A: WXR** (preferido) | Com a A4: um administrador no WordPress actual → Ferramentas → Exportar → "Todo o conteúdo" | Notícias, páginas, anexos, categorias e metadados do Yoast (`_yoast_wpseo_title`, `_yoast_wpseo_metadesc`) |
| **B: rastreio público** (sem a A4) | Se a A4 não chegar até 10/11 | Lista de URLs dos sitemaps (`post-sitemap.xml`, `page-sitemap.xml`); para cada notícia: título, data (`article:published_time`), conteúdo (`.entry-content`), imagem destacada (`og:image`); criação com `wp post create` e `wp media import`. Um script PHP ou Node **temporário** em `scratch/`, fora do Git e fora do `public_html` |

### 3.2 Ficheiros

- **Caminho A:** o importer descarrega os anexos ("Download and import file attachments"). Se o alojamento antigo bloquear, copia-se `wp-content/uploads/` por SFTP (A4) para `public_html/wp-content/uploads/`, **com os mesmos caminhos `AAAA/MM`**, e corre-se `wp media regenerate --only-missing`.
- **Caminho B:** `wp media import <url> --post_id=<id> --featured_image`. **Os PDFs são descarregados para o mesmo caminho relativo** (`uploads/AAAA/MM/ficheiro.pdf`) e registados com `wp media import` + `_wp_attached_file` corrigido, para os URLs antigos dos PDFs continuarem a funcionar sem 301.

### 3.3 Regras

| RF | Regra |
|---|---|
| RF06 | **87 notícias** (todas excepto a `hello-world`). O mesmo `post_name`, `post_date`, conteúdo e imagem destacada. Categoria `noticias-aisa`. Notícias sem imagem → **fica sem `_thumbnail_id`** e o tema mostra a imagem por defeito (o bloqueio de publicação do 01-03 só se aplica no editor e na passagem de rascunho a publicado; na importação, **desligar temporariamente** o bloqueio com `define('AISA_IMPORTING', true)` em `wp-config.php` e retirá-lo no fim). **Valência proposta** por palavras do título e do conteúdo (`wp-03` RF06): `centro de dia`, `sénior`, `idosos`, `avós` → `centro-dia`; `domicílio`, `domiciliário` → `sad`; `ATL`, `férias`, `crianças`, `brincar`, `escola`, `ano letivo`, `páscoa` (com ATL) → `infancia`; `cozinha`, `cozinheira`, `refeições` → `cozinha`; o resto → `generico`. Gera-se o CSV `scratch/valencias.csv` (ID, título, valência proposta) para a Mercês rever depois |
| RF06 | **Shortcodes e page builder:** o site actual usa o WPBakery (`vc_*`). Limpar `[vc_row]`, `[vc_column]`, `[vc_column_text]` e semelhantes (manter só o texto e as imagens); converter para blocos (`core/paragraph`, `core/image`) com "Converter em blocos" ou `wp post update` com conteúdo limpo. **Sem shortcodes `vc_` visíveis** no front-end |
| RF06 | **Ligações internas** absolutas `https://www.aisaipss.pt/…` → relativas ou para o domínio novo (`wp search-replace 'https://www.aisaipss.pt' 'http://aisa.wordpress.test' --precise --dry-run` primeiro; em produção, o `wp-09` repõe o domínio) |
| RF07 | **Páginas:** importam-se as 22 do sitemap. Depois: renomear `qualidade-de-vida` → slug `saude-e-reabilitacao`; **apagar** (para o lixo) `novos-projectos`, `quem-somos-2`, `apoio-a-infancia` e `apoio-a-infancia-2` (têm 301 no 02-06); aplicar o template e a valência (o 02-04 completa). Limpar o WPBakery como acima |
| RF08 | **Documentos:** um `aisa_documento` por ficheiro, ligado ao anexo importado. Checklist (marcar no fim desta spec): Estatutos 2023 · Relatórios e Contas 2017, 2018, 2019, 2020, 2021, 2022 (o URL do PDF tem de ser obtido a partir da página de anexo), 2023, 2024, 2025 · Regulamento Geral · Regulamento do Centro de Dia · Regulamento do SAD · outros que existam (A7) · convocatórias da Assembleia Geral publicadas (as notícias `convocacao-assembleia-geral-…` e `convocatoria-para-assembleia-geral-…`; se tiverem PDF anexo). Tipo, ano e valência (só os regulamentos) |
| RF09 | **Depoimentos:** os 3 da notícia `testemunhos` → 3 `aisa_depoimento`, **sem nome** (o nome que existir no texto é removido); relação e serviço deduzidos do texto; revistos contra a regra L9 (sem idade exacta, rua, doença ou datas). A notícia `testemunhos` mantém-se publicada (tem URL), mas **sem os nomes**: editar o conteúdo e registar aqui |
| RF10 | **Agenda inicial:** os eventos da BQ1 (lista da Mercês). Sem a BQ1 até ao M1: manter os eventos "[DEMO]" do 01-05 só em local, e o RF10 fica 🔄 |
| — | **Conteúdo de exemplo "[DEMO]"** do 01-05: apagar as notícias "[DEMO]"; o Centro de Dia passa a ser a página importada (ou a mesma, se já tiver o texto actual) |
| — | **Metadados do Yoast:** ficam nos `postmeta` para o Rank Math importar no 02-06 |
| — | **Utilizadores:** os autores importados são mapeados para o administrador da agência (não se importam utilizadores antigos) |

### 3.4 Plugin temporário

O **WordPress Importer** é instalado, usado e **desinstalado** no fim. Fica registado como removido na lista de plugins.

## 4. Tarefas ordenadas

1. `define('AISA_IMPORTING', true)` em `wp-config.php` (o `aisa-core` suporta a constante desde o 01-03).
2. Caminho A (WXR) ou B (rastreio): importar notícias e páginas.
3. Ficheiros e PDFs com os mesmos caminhos.
4. Limpeza do WPBakery; conversão em blocos.
5. Valências propostas + `scratch/valencias.csv`.
6. Páginas: renomear, apagar duplicados, templates.
7. Documentos (checklist RF08).
8. Depoimentos (RF09) e limpeza dos nomes na notícia `testemunhos`.
9. Agenda (RF10).
10. Apagar o conteúdo "[DEMO]".
11. Retirar `AISA_IMPORTING`; desinstalar o WordPress Importer.
12. Verificar §6 e gerar a **lista de URLs antigos → novos** (`scratch/inventario-urls.csv`), que é input do 02-06.
13. Marcar ✅ em RF06, RF07, RF08, RF09, T10 (e RF10 se houver BQ1) no `wp-03-product-backlog.md`.

## 5. Critérios de aceitação

- **87** notícias publicadas com os slugs antigos; `/noticias-aisa/<slug>/` responde 200 para todos os URLs do `post-sitemap.xml` antigo (excepto o `hello-world`).
- 0 shortcodes `[vc_` visíveis no front-end.
- Todas as notícias mostram imagem no cartão (própria ou por defeito).
- Os PDFs antigos respondem 200 no **mesmo caminho** `/wp-content/uploads/AAAA/MM/…`.
- Checklist RF08 completa: 0 documentos em falta (K9).
- 3 depoimentos sem nomes; a notícia `testemunhos` sem nomes.
- `AISA_IMPORTING` retirado; WordPress Importer desinstalado.
- `scratch/valencias.csv` e `scratch/inventario-urls.csv` gerados.

## 6. Comandos de verificação

```bash
cd /c/var/www/WordPress/aisa
wp --path=public_html post list --post_type=post --post_status=publish --format=count   # 87
curl -s https://www.aisaipss.pt/post-sitemap.xml | grep -oE '<loc>[^<]+' | sed 's/<loc>//; s#https://www.aisaipss.pt#http://aisa.wordpress.test#' | grep -v hello-world | while read u; do c=$(curl -s -o /dev/null -w '%{http_code}' "$u"); [ "$c" != 200 ] && echo "$c $u"; done   # vazio
wp --path=public_html db query "SELECT COUNT(*) FROM aisa_posts WHERE post_status='publish' AND post_content LIKE '%[vc_%'"   # 0
for p in 2021/01/Relatorio-Atividades-AISA-2017.pdf 2020/02/RelatorioGestaoeContas2018.pdf 2020/09/AISA-Relatorio-e-Contas-2019.pdf 2021/07/Relatorio-e-Contas-2020.pdf 2022/04/Relatorio-e-Contas-2021.pdf 2023/10/20230900_ESTATUTOS-1.pdf 2024/04/Relatorio-e-Gestao-de-Contas-2023.pdf 2025/04/20250324_Relatorio-e-Contas-2024-1.pdf 2026/05/AISA-Relatorio-e-Contas-2025.pdf; do echo "$(curl -s -o /dev/null -w '%{http_code}' http://aisa.wordpress.test/wp-content/uploads/$p) $p"; done
wp --path=public_html post list --post_type=aisa_documento --fields=ID,post_title --format=table
wp --path=public_html post list --post_type=aisa_depoimento --format=count   # 3
wp --path=public_html config has AISA_IMPORTING && echo "RETIRAR" || echo OK
wp --path=public_html plugin is-installed wordpress-importer && echo "DESINSTALAR" || echo OK
```

## 7. Prompt para o `wp-05-developer`

```
/wp-05-developer Implementa specs/wp-04-02-05-migracao.md.
Usa o WXR da A4 se existir (pergunta-me); senão, rastreio público dos sitemaps com um script temporário em scratch/ (fora do Git e do public_html). 87 notícias com slugs/datas/imagens, páginas do sitemap, PDFs nos mesmos caminhos de uploads, limpeza do WPBakery, valências propostas (CSV), documentos pela checklist RF08, 3 depoimentos sem nomes, agenda da BQ1. No fim retira AISA_IMPORTING e desinstala o importer. Corre §6, gera scratch/inventario-urls.csv e marca ✅ em wp-03-product-backlog.md conforme §4.13.
```
