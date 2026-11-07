# wp-04-01-05 — Modelos da demo M0

> Gerado por `wp-04-project-manager` em 2026-11-07. Fase de implementação 01 (demo M0, **09/11**). Depende de: 01-02, 01-03, 01-04.

## 1. Tipo

**Blocos / tema.**

## 2. Páginas / requisitos cobertos

`wp-03-product-backlog.md`:
- **P01** Início;
- **P03** modelo de página de serviço;
- **P05** Centro de Dia;
- **P20** Notícias, listagem; **P21** Notícia;
- **P22** Agenda; **P23** Evento;
- conclusão de **T06** (imagem no topo e por defeito).

## 3. Âmbito técnico

**Templates novos** em `themes/aisa/templates/`:

| Template | Composição (blocos core + `aisa/*` + padrões) | Referência visual |
|---|---|---|
| `front-page.html` | Padrões, por ordem: `aisa/hero` (texto 5fr + `aisa/slideshow` 7fr, que vai até à margem direita; botões `aisa/telefone` variante botão + "Ver serviços"), `aisa/provas`, `aisa/servicos-fases` (3 grupos assimétricos: fotografia grande + lista de serviços; **não** é uma grelha de cartões iguais), `aisa/depoimentos` (Fraunces itálico grande), `aisa/noticias-recentes` (Query Loop: 1 `.news-card--lead` + 3 `.news-card`), `aisa/proximos-eventos`, `aisa/participar` (Sócio, IRS, Voluntariado; layout assimétrico com fotografia), `aisa/contacto` | `specs/brand-04-modelos-pagina.html` → "Início" |
| `page-servico.html` (Template Name "Página de serviço", atribuível a páginas) | `.page-header` com a imagem destacada (ou a por defeito) + `data-valencia` (via `aisa-valencia-scope`) + `.valencia-tag`; colunas: conteúdo (secções com `h2`: Para quem é · O que inclui · Horário e condições · Como aceder, com a frase Q2) e lateral (`aisa/servico-info`: horário, `aisa/telefone` grande, morada); `aisa/regulamentos`; `aisa/depoimentos` (valência da página); `aisa/contacto` | → "Página de serviço" |
| `home.html` | `.page-header` "Notícias"; Query Loop (`post`, 12 por página): o 1.º item da página 1 como `.news-card--lead`, os restantes `.news-card` (imagem 16:10, `.valencia-tag`, data, título); `core/query-pagination` acessível | `.news-card` em `brand-04-design-system.html` |
| `single.html` | `.page-header` com a imagem destacada e o título; meta (data `<time>`, valência); `core/post-content` (constrained, 720 px); `aisa/proximos-eventos`; `aisa/contacto` | — |
| `archive-aisa_evento.html` | `.page-header` "Agenda"; Query Loop herdado (só eventos futuros por `pre_get_posts`, 01-03) apresentado como `.event`; estado vazio com a mensagem do bloco | `.event` |
| `single-aisa_evento.html` | `.page-header`; caixa com data e hora (início e fim), local e valência; conteúdo; "Voltar à agenda" | — |

**Padrões** em `themes/aisa/patterns/*.php`, categoria "AISA":
- `hero`, `servicos-fases`, `noticias-recentes`, `participar`, `contacto`, `servico-info`, `servico-seccoes`;
- este último insere os `h2` de P03 vazios, com textos de ajuda para a editora.

**Campos do Query Loop:**
- a imagem do cartão é `core/post-featured-image`, com fallback para `default.jpg` (filtro `post_thumbnail_html` no tema);
- a etiqueta de valência é um bloco `core/post-terms` (`aisa_valencia`) com o estilo `.valencia-tag`.

**Conteúdo de exemplo para a demo** (marcado no título ou no excerto como "[DEMO]" e apagado ou substituído em 02-05):

| Item | Conteúdo |
|---|---|
| Página "Início" | Definida como página inicial estática (`show_on_front=page`, `page_on_front`) |
| Página "Notícias" | `page_for_posts` |
| Centro de Dia (`/centro-de-dia/`) | Template `page-servico`, valência `centro-dia`, **texto actual** do site (https://www.aisaipss.pt/centro-de-dia/), distribuído pelas secções; imagem de `proto-2/img/facebook/centro-dia-sala.png` |
| Slideshow | 4 a 5 fotografias de `proto-2/img/facebook/`, com texto alternativo descritivo |
| Notícias | 4 a 6 notícias reais do site actual (texto e imagem), com a valência |
| Eventos | 3 eventos futuros plausíveis, marcados "[DEMO]" até BQ1 (por ex. "Aniversário da AISA", "Festa de Natal") |
| Depoimentos | Os 3 da notícia `testemunhos`, sem nomes (RF09 antecipado) |
| Provas | Números de destaque do `brand-04-modelos-pagina.html` / `business-02` Q1 (dados de 2025) |

**Composição** (`core-00-project-map.md` §4 / `wp-02` §1):
- **proibido** alinhar 3 ou mais cartões iguais como secção principal na página inicial;
- fotografia dominante;
- Fraunces em títulos grandes e em itálico nos destaques e depoimentos;
- fundos de secção Base (A1) a alternar `plain`, `surface` e `muted`.

**Acessibilidade:**
- um `h1` por página e hierarquia sem saltos;
- cartões com uma só ligação (o título), sem ligações duplicadas para o mesmo destino;
- `<time datetime>`;
- a imagem do cartão é decorativa (`alt=""`) quando o título já descreve a notícia.

## 4. Tarefas ordenadas

1. Padrões `servico-info` e `servico-seccoes` + `page-servico.html`. Criar o Centro de Dia com o texto actual.
2. `home.html` + `single.html` + fallback da imagem nos cartões.
3. `archive-aisa_evento.html` + `single-aisa_evento.html`.
4. Padrões da página inicial + `front-page.html`.
5. Conteúdo de exemplo (§3) e definições de leitura (página inicial e de artigos).
6. Actualizar o menu principal com as páginas que já existem.
7. Verificar §6 a 360, 768 e 1280 px, comparando com `brand-04-modelos-pagina.html`.
8. Marcar ✅ em P01, P03, P05, P20, P21, P22, P23 e T06 no `wp-03-product-backlog.md`.

## 5. Critérios de aceitação

- A página inicial mostra as 8 secções de P01, pela ordem indicada; o slideshow ocupa cerca de 60% e não avança sozinho.
- O Centro de Dia mostra a cor da valência verde (faixa, etiqueta com o nome), o telefone grande, os regulamentos (se houver documentos de teste) e o texto actual. Não tem valores de comparticipação.
- `/noticias/`: a 1.ª notícia está em destaque e todos os cartões têm imagem (própria ou por defeito). Não há cartões só de texto (critério 3 do PRD).
- `/agenda/` só tem eventos futuros; o evento individual mostra a data, a hora e o local.
- 360 px sem deslocamento horizontal; ordem de leitura lógica (no hero, a imagem vem primeiro em telemóvel, conforme o `components.css`).
- axe: 0 erros críticos ou graves em `/`, `/centro-de-dia/`, `/noticias/`, um artigo, `/agenda/` e um evento.
- Lighthouse em telemóvel (local, indicativo): Acessibilidade ≥ 95; LCP registado para referência.
- `debug.log` sem avisos.

## 6. Comandos de verificação

```bash
cd /c/var/www/WordPress/aisa
wp --path=public_html option get show_on_front && wp --path=public_html option get page_on_front
for u in / /centro-de-dia/ /noticias/ /agenda/; do echo "$u $(curl -s -o /dev/null -w '%{http_code}' http://aisa.wordpress.test$u)"; done
for u in / /centro-de-dia/ /noticias/ /agenda/; do npx --yes @axe-core/cli "http://aisa.wordpress.test$u" --exit || echo "FALHA $u"; done
curl -s http://aisa.wordpress.test/noticias/ | grep -c 'news-card'
npx --yes lighthouse http://aisa.wordpress.test/ --only-categories=accessibility,performance --form-factor=mobile --quiet --chrome-flags="--headless" --output=json --output-path=./lh-home.json
tail -5 public_html/wp-content/debug.log 2>/dev/null
```

## 7. Prompt para o `wp-05-developer`

```
/wp-05-developer Implementa specs/wp-04-01-05-modelos-demo.md — é a demo M0 de 09/11.
Templates front-page, page-servico, home, single, archive-aisa_evento, single-aisa_evento e padrões AISA, seguindo specs/brand-04-modelos-pagina.html mas com as restrições de composição (sem grelhas de cartões iguais, fotografia dominante, Fraunces com personagem). Cria o Centro de Dia com o texto actual do site, o conteúdo de exemplo de §3 (marcado [DEMO]) e define a página inicial. Corre §6 em 360/768/1280 px e marca ✅ P01, P03, P05, P20, P21, P22, P23, T06 em wp-03-product-backlog.md.
```
