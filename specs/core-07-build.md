# Build — router do ramo (AISA IPSS)

> Fase 7 do tronco. Gerado por `core-07-build` em 2026-11-07.
> Inputs: `core-00-project-map.md` (cenário) + estado actual de `specs/`.

## 1. Ramo identificado

- **Ramo B — WordPress** (herdado de `core-00-project-map.md` §1, não redecidido).
- Site institucional editorial, Gutenberg nativo, design personalizado, sem e-commerce.
- **Base do build** (`core-00-project-map.md` §4, "Base da demo e do build"):
  - tokens de `specs/tokens/` (incluindo o `theme.json` gerado);
  - modelos de página de `specs/brand-04-modelos-pagina.html`;
  - a versão de fundos que a cliente escolher (Base / V1 / V2).
- **O `proto-2/` não é base de nada.** Só se aproveitam as fotos de `proto-2/img/facebook/`.
- **Estado do código:** `public_html/` tem um WordPress limpo, só com os temas Twenty* por omissão. Ainda não há tema nem plugin do projecto.

## 2. Mapa do subprocesso do ramo

| # | Fase | Skill | Output | Estado |
|---|---|---|---|---|
| 1 | Envisioning / faseamento | `wp-01-envisioning` | `wp-01-requirements-phase-01.md` | ☑ Concluído (2026-11-07; fase 01 = MVP; fases 02 e 03 no roadmap) |
| 2 | Arquitectura | `wp-02-architecture` | `wp-02-architecture.md` | ☑ Concluído (2026-11-07; block theme próprio `aisa`, plugin `aisa-core`, Rank Math) |
| 3 | Backlog de páginas | `wp-03-business-analyst` | `wp-03-product-backlog.md` | ☑ Concluído (2026-11-07; P01–P30, G01–G06, RF01–RF12, T01–T15; 301 mapeados) |
| 4 | Specs de implementação | `wp-04-project-manager` | `wp-04-FF-II-slug.md` | ☑ Concluído (2026-11-07; 13 specs: 01-01…01-05, 02-01…02-06, 03-01…03-02) |
| 5 | Implementação (uma spec de cada vez) | `wp-05-developer` | código em `public_html/wp-content/` + backlog actualizado | ☐ Não iniciado |
| 6 | Revisão SEO | `wp-06-seo-reviewer` | `wp-06-seo-review.md` | ☐ Não iniciado |
| 7 | Revisão de segurança | `wp-07-security-reviewer` | `wp-07-security-review.md` | ☐ Não iniciado |
| 8 | Revisão de bugs / DoD | `wp-08-bug-reviewer` | `wp-08-known-bugs.md` + `wp-08-test-coverage.md` | ☐ Não iniciado |
| 9 | Deploy | `wp-09-deploy` | `wp-09-deploy-plan.md` + `/deploy/prod.sh` | ☐ Não iniciado |
| — | Conteúdo *(skills soltas, em paralelo depois da fase 3)* | `wp-11-blog-writer` / `wp-12-landing-page-writer` | artigos / landing pages | — |

## 3. Próximo passo

**Lançar `/wp-05-developer`** (manualmente), **uma spec de cada vez, por ordem**: `wp-04-01-01-ambiente-base` → 01-02 → 01-03 → 01-04 → 01-05 (demo M0, 09/11) → 02-01 … 02-06 (beta M1, 12/11; 02-05 antes de 02-06) → 03-01, 03-02 (M2, 14/11). Cada spec tem, na §7, o prompt pronto a usar. Depois do M1: `wp-06`, `wp-07` e `wp-08`. As notas abaixo ficam como contexto.

- **Inputs:**
  - obrigatórios: `core-05-prd.md` (MVP + MoSCoW, revisão 1) e `core-06-legal-requirements.md` (L1–L36);
  - apoio: `business-03-risks.md` (lançamento em duas fases, Meta e Brevo na fase 2), `marketing-04-go-to-market.md` §2 (demo/alpha o mais cedo possível), `brand-04-design-system.md` e `brand-04-modelos-pagina.html`.
- **O que se espera da fase:**
  - uma **fase 1 enxuta**, centrada na demo/alpha e no lançamento de 15/11 (faltam 8 dias);
  - Meta/Instagram e Brevo/newsletter ficam na fase 2.
- **Restrições de composição a passar ao `wp-01`** (`core-00-project-map.md` §4). Em vez do ar "Bootstrap / IA slop":
  - menos grelhas de cartões iguais, mais layouts assimétricos;
  - fotografia real como protagonista;
  - Fraunces com mais personagem;
  - raios menos uniformes;
  - menos sombras e caixas.

## 4. Bloqueios a ter em conta (não impedem o arranque do `wp-01`)

| Ref. | Bloqueio | Bloqueia o quê |
|---|---|---|
| A4 | Acessos ao WordPress actual, ao alojamento, à Meta e ao Brevo | `wp-02` (infra) e migração de conteúdos, `wp-09` |
| A6 | E-mail de destino dos formulários | formulários de contacto e de voluntariado (`wp-05`) |
| A10 | Encarregado de protecção de dados (EPD). **Parcial (2026-11-08):** a política actual indica EPD contactável pela morada postal da AISA. Falta confirmar a designação e a comunicação à CNPD, e definir um e-mail de contacto (recomendado). Ver `core-06` §8, Q1 | Política de Privacidade |
| A12 | Plano do Cookiebot | consentimento de cookies e GA4 com Consent Mode v2 |
| — | Versão de fundos (Base / V1 / V2) e cor de "Saúde e Reabilitação" | `theme.json` final (`wp-02` / `wp-05`) |

Ver `core-05-prd.md` §10.
