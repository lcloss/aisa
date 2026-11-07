# Subprocesso branding — AISA IPSS

> Router do subprocesso `branding`. Gerado por `core-04-brand` em 2026-11-07.
> O estado foi lido directamente de `specs/` (e de `proto-1/`, onde estão os artefactos já aprovados pelo cliente).
> **Modo mínimo** (decisão do operador, 2026-11-07; ver `core-00-project-map.md` §4): a marca não recomeça do zero. Cada `brand-*` parte dos artefactos aprovados, passa-os para a nomenclatura nova e completa só o que falta. Cada skill decide a sua profundidade.

## 1. Mapa do subprocesso

| # | Fase | Skill | Output | Estado |
|---|---|---|---|---|
| 1 | Briefing de marca (personalidade, tom de voz, confiança, naming) | `brand-01-briefing` | `brand-01-briefing.md` | ☑ Concluído (2026-11-07). Tratamento igual ao de aisaipss.pt ("você" implícito); exemplos de tom sem nomes. **Directiva do operador:** o conteúdo do site actual mantém-se; o foco é o apelo visual e a diagramação (§0). |
| 2 | Requisitos para o visual | `brand-02-requirements` | `brand-02-requirements.md` | ☑ Concluído (2026-11-07, versão curta). Inclui os requisitos de diagramação D1–D9 e os modelos de página (§4). |
| 3 | Identidade visual | `brand-03-visual-identity` | `brand-03-visual-identity.html` | ☑ Concluído (2026-11-07, modo mínimo). Migrada de `proto-1/` e completada com: cores e ícones por valência (as 3 cores do logótipo + acentos neutros; papéis acento/superfície/texto); composição de página com o feedback do cliente (slideshow maior, notícias com imagem dominante, imagem a abrir todas as páginas); tratamento das fotografias do Facebook; princípios 6 e 7. |
| 4 | Design system | `brand-04-design-system` | `brand-04-design-system.md` + `tokens/` | ☑ Concluído (2026-11-07, modo mínimo). Migrado de `proto-1/` para `specs/tokens/` + `brand-04-design-system.html`; acrescentados tokens por valência, `components.css` (componentes do site), `theme.json` gerado, modelos de página (`brand-04-modelos-pagina.html`) e **teste de fundos de secção (Base / V1 tons suaves / V2 faixas fortes) — à espera de decisão da cliente**. Contraste AA a passar (63 pares). |
| 5 | Manual de marca | `brand-05-manual` | `brand-05-manual.html` | ⏸ Adiado para depois do lançamento (modo mínimo). |

**Não contam como fases concluídas:** os ficheiros em `specs/03-brand/` e em `proto-1/` estão fora da nomenclatura `brand-*`. Servem de base às skills acima.

## 2. Próximo passo

Subprocesso de marca concluído para o lançamento (`brand-05-manual` adiado). Pendentes: decisão da cliente sobre os fundos (Base / V1 / V2) e cor de "Saúde e Reabilitação" (`brand-04-design-system.md` §7). Seguir para **`/core-05-product-owner`**.

**Caminho mínimo até à demo:** `brand-01` → `brand-02` (ambos curtos) → `brand-03` (migração + cores por valência) → `brand-04` (migração + tokens por valência + gate WCAG) → `core-05-product-owner`.
