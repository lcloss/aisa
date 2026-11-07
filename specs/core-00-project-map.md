# Project map — AISA IPSS

> Fase 0 do tronco. Gerado por `core-00-orchestrator` (Modo 1 — Intake) em 2026-11-02.
> Input: `specs/briefing-aisa-ipss-v6.md` (briefing v6, árvore 1.1, Closs Digital).

## 1. Classificação

- **Cenário:** B — WordPress (site institucional editorial, Gutenberg nativo, design personalizado)
- **E-commerce:** não (sem venda online nem donativos online)
- **Nota de híbrido:** nenhuma. Flags a tratar dentro do ramo B:
  - Newsletter para associados (até 5 000 envios/mês)
  - Candidatura a voluntariado (formulário)
  - Gestão de consentimento de cookies (Cookiebot)
  - Área de documentos obrigatórios por lei (contas, estatutos, regulamentos), migrada de AISA → Documentos
  - Acessibilidade WCAG 2.1 AA + Declaração de Acessibilidade (DL 83/2018)
  - Ligação/sincronização com Facebook e Instagram
  - Migração de conteúdos do site WordPress actual (https://www.aisaipss.pt/), com **estrutura e apresentação novas**. Não é só trocar o tema: muda a arquitectura de informação e reaproveita-se o texto.
  - Sistema de cores por valência (ver secção 4)
  - Cerca de 15 páginas de serviço, 15 depoimentos, blog com vários artigos por mês
- **Cliente:** AISA — Associação de Apoio Social Nossa Senhora da Assunção (IPSS, Alcabideche: Malveira da Serra e Janes). Contacto: Mercês Pedroso, comunicacao@aisaipss.pt, 214 857 700
- **Prazo / orçamento aproximados:** lançamento a **15 de novembro de 2026** (13 dias a partir de 2026-11-02) · 2 000 a 5 000 €

## 2. Project map (tronco)

| # | Fase | Skill | Output | Estado |
|---|---|---|---|---|
| 1 | Briefing | `core-01-briefing` | `core-01-briefing.md` | ☑ Concluído (2026-11-02) |
| 2 | Negócio | `core-02-business` (router do subprocesso `business-*`) | `core-02-business.md` + `business-*.md` | ☑ Concluído (2026-11-02) |
| 3 | Marketing | `core-03-marketing` (router do subprocesso `marketing-*`) | `core-03-marketing.md` + `marketing-*.md` | ☑ Concluído (2026-11-07; `marketing-03` saltado) |
| 4 | Marca | `core-04-brand` (router do subprocesso `brand-*`) | `core-04-brand.md` + `brand-*.md` | ☑ Concluído para o lançamento (2026-11-07, modo mínimo; `brand-05` adiado; fundos à espera de decisão da cliente) |
| 5 | Requisitos funcionais | `core-05-product-owner` | `core-05-prd.md` | ☑ Concluído (2026-11-07) |
| 6 | Revisão legal | `core-06-legal` | `core-06-legal-requirements.md` | ☑ Concluído (2026-11-07; lacunas G1–G8 incorporadas no PRD, revisão 1) |
| 7 | Build (ramo B — WordPress) | `core-07-build` (router do ramo `wp-*`) | `core-07-build.md` + `wp-*` | ◐ Em curso (2026-11-07; mapa do ramo criado, próximo: `wp-01-envisioning`) |
| — | Pedido de alteração *(skill solta, pós-MVP)* | `core-08-change-request` | `core-08-change-request-NN-slug.md` | — |
| — | Revisão de projeto *(skill solta, reinvocável)* | `core-09-project-reviewer` | `core-09-divergencias-projeto.md` | — |
| 10 | Documentação | `core-10-documentator` | `docs/documentation.html` | ☐ Não iniciado |

## 3. Specification map (detalhe por subprocesso)

| ID | Specification | Contexto | Estado |
|---|---|---|---|
| business-01 | `business-01-discovery.md` | pesquisa de concorrência | ☑ |
| business-02 | `business-02-proposal.md` | SWOT | ☑ |
| business-03 | `business-03-risks.md` | riscos (vivo) | ☑ |
| marketing-01 | `marketing-01-personas.md` | personas + use cases | ☑ (2026-11-03) |
| marketing-02 | `marketing-02-value-proposition.md` | posicionamento + proposta de valor | ☑ (2026-11-07) |
| marketing-03 | `marketing-03-pricing.md` | pricing/revenue | ⊘ Saltado (2026-11-07): sem venda online; os preços são definidos internamente |
| marketing-04 | `marketing-04-go-to-market.md` | canais + plano de lançamento | ☑ (2026-11-07) |
| brand-01 | `brand-01-briefing.md` | personalidade, tom de voz, confiança, naming | ☑ (2026-11-07) |
| brand-02 | `brand-02-requirements.md` | brief de requisitos para o visual | ☑ (2026-11-07, versão curta + diagramação) |
| brand-03 | `brand-03-visual-identity.html` | logo, paleta, tipografia, aplicações | ☑ (2026-11-07, migrada de `proto-1/` + valências + feedback do cliente) |
| brand-04 | `brand-04-design-system.md` + `tokens/` | tokens OKLCH, gate WCAG | ☑ (2026-11-07, migrado + valências + componentes do site + modelos de página + teste de fundos V1/V2) |
| brand-05 | `brand-05-manual.html` | manual de marca client-facing | ☐ |
| wp-01…09 | `wp-*` (envisioning → architecture → business-analyst → project-manager → developer → seo → security → bug-reviewer → deploy) | ramo B — ver `core-07-build` | ☐ |

## 4. Desvios e justificações

- **Artefactos do workflow antigo (`core-00-intake`) em `specs/`:** `00-brief.md`, `00-questionario-cliente.md`, `01-descoberta.md`, `02-marketing.md`, `03-brand/01-brand-discussion.md`, `03-brand/02-brand-strategy.md`, `03-brand/03-brand-requirements.md`, `pairs.json`, `scripts/check-contrast.mjs`. Seguem a nomenclatura antiga e foram feitos com pressupostos da agência, sem entrevista ao cliente. **Não contam como fases concluídas** neste mapa. Servem de material de apoio para `core-01-briefing`, `business-*`, `marketing-*` e `brand-*`.
- **Contradições entre o briefing v6 e o workflow antigo, resolvidas pelo operador em 2026-11-02.** As fases seguintes seguem estas decisões:
  - **Conteúdo:** reaproveita-se o conteúdo do site actual e do Facebook, mas a **estrutura e a apresentação são novas**. É uma reestruturação completa, não apenas uma troca de tema. O `00-brief.md` ("nada a reaproveitar") fica sem efeito.
  - **Identidade visual:** no início só existia o logótipo. A identidade visual e o design system foram criados pela agência e **aprovados pelo cliente**: `proto-1/04-brand-visual-identity.html` e `proto-1/05-brand-design-system.html` + `proto-1/tokens/` (Fraunces + Atkinson Hyperlegible, Azul Confiança como primária, só modo claro, WCAG AA). Por isso a fase 4 (Marca) não recomeça do zero. `core-04-brand` deve partir destes artefactos aprovados, passá-los para a nomenclatura `brand-*` e completar o que falta.
  - **Sistema de cores por valência**, indicado pelo cliente. Tem de entrar em `brand-03` e `brand-04` como tokens, com o gate de contraste WCAG AA:

    | Cor | Área |
    |---|---|
    | Azul | SAD (Serviço de Apoio Domiciliário) |
    | Verde | Centro de Dia |
    | Laranja | Infância e Juventude |
    | Branco | Genérico |
    | Cinza | Cozinha |

    A identidade aprovada já usa azul (primária), verde (acento decorativo, nunca com texto) e laranja (com texto escuro). Falta decidir se as cores por valência reutilizam estes tons ou se são tons próprios, e garantir AA quando levam texto.
  - **Prazo:** lançamento a **15 de novembro de 2026**, ou seja, 13 dias.
  - **Referência saintjosephsshankill.ie:** confirmada, como referência para o estilo de linha dos ícones.
- **Tom "institucional", ambiguidade resolvida:** o cliente não quer um site institucional no sentido frio e corporativo. Quer um site **pessoal, próximo, humano e caloroso**. Para a forma de tratamento, o cliente deu esta indicação: não é "a senhora utente", nem "o Zé", mas sim **"o seu José"**, com respeito, proximidade e o nome da pessoa. Isto é um requisito de tom de voz para `brand-01` e para todo o conteúdo (`wp-11-blog-writer`, páginas de serviço).
- **Prazo muito apertado (13 dias):** o tronco completo tem de ser encolhido. Sugestões, cada skill decide e regista aqui:
  - `business-*`: leve. Os artefactos antigos `01-descoberta.md` e `00-questionario-cliente.md` podem servir de base.
  - `marketing-03-pricing`: não se aplica, porque não há monetização.
  - `marketing-04-go-to-market`: mínimo (newsletter aos associados, Facebook e Instagram).
  - `brand-*`: partir dos artefactos aprovados em `proto-1/`.
  - `brand-05-manual`: pode passar para depois do lançamento.
  - O caminho crítico é `core-05-prd` → `core-06-legal` → ramo `wp-*`.
- **Modos mínimos para acelerar a demo (decisão do operador, 2026-11-07):**
  - `marketing-03-pricing` **saltado**: a AISA não vende nada online e os serviços já têm preço definido internamente. Definir preços não é função deste projecto.
  - `core-04-brand` em **modo mínimo**: passar para `specs/` a identidade visual e o design system aprovados em `proto-1/` e acrescentar as cores por valência com o gate WCAG AA. `brand-05-manual` passa para depois do lançamento.
  - `core-05-product-owner`: PRD curto, logo a seguir à marca, para fixar o âmbito do MVP.
  - `core-06-legal` corre **em paralelo** com o arranque do ramo `wp-*`. A demo leva espaços reservados para os textos legais (cookies, privacidade, declaração de acessibilidade).
  - ~~`proto-2/` serve de base para a **demo estática** ao cliente, enquanto o tema WordPress se constrói.~~ **Corrigido (2026-11-07):** ver o ponto seguinte.
- **Base da demo e do build (correcção do operador, 2026-11-07):**
  - `proto-1/` (versão mais humana, cantos arredondados) e `proto-2/` (versão mais institucional, linhas rectas) foram as duas propostas apresentadas ao cliente. **O cliente escolheu o `proto-1/`.**
  - A `brand-03` e a `brand-04` foram migradas do `proto-1/`. Do `proto-2/` só se aproveitaram as fotos do Facebook (`proto-2/img/facebook/`). **O `proto-2/` não é base de nada.**
  - A base da demo e do ramo `wp-*` é: tokens de `specs/tokens/` + modelos de página de `specs/brand-04-modelos-pagina.html` (com o selector flutuante de fundos Base / V1 / V2) + a versão de fundos que a cliente escolher.
  - **Receio do operador:** os componentes do `proto-1/` têm um ar de Bootstrap ou "IA slop". Não se reabre a `brand-03`, que está aprovada. Fica como restrição de composição para o `wp-01-envisioning`, ou para uma pequena revisão dos modelos antes do build:
    - menos grelhas de cartões iguais, mais layouts assimétricos;
    - fotografia real como protagonista;
    - Fraunces com mais personagem (títulos grandes, itálico em destaques e depoimentos);
    - raios menos uniformes (arredondado em fotos, mais contido em botões e inputs);
    - menos sombras e caixas, separação por espaço e fundos de secção.

## 5. Ponto de retoma (actualizado 2026-11-07)

- **Feito:**
  - fase 1, `core-01-briefing.md`;
  - fase 2, `core-02-business.md` e `business-01` a `business-03`;
  - fase 3 concluída (2026-11-07): `marketing-01` personas, `marketing-02` posicionamento e `marketing-04` go-to-market; `marketing-03` saltado;
  - respostas Q1, Q1a, Q2 e Q4 registadas em `business-02-proposal.md` §3 (2026-11-03).
- **`brand-01` concluído (2026-11-07):** tratamento igual ao do site actual ("você" implícito); exemplos sem nomes. **Directiva do operador:** o conteúdo mantém-se igual ao site actual; o foco é o apelo visual e a diagramação, mais humana, próxima e moderna (`brand-01-briefing.md` §0).
- **`brand-02` concluído (2026-11-07):** versão curta, com os requisitos de diagramação D1–D9 e os modelos de página.
- **`brand-03` concluído (2026-11-07):** cores e ícones por valência e composição de página com o feedback do cliente (slideshow maior, notícias com imagem, imagem em todas as páginas).
- **`brand-04` concluído (2026-11-07):** tokens em `specs/tokens/` (incluindo `components.css` e `theme.json` gerado), modelos de página e teste de fundos (Base / V1 / V2) em `brand-04-modelos-pagina.html`. **Pendente com a cliente:** escolher a versão de fundos; cor de "Saúde e Reabilitação".
- **`core-05` concluído (2026-11-07):** PRD com MVP, KPIs e MoSCoW. Decisões: serviços espelham o site actual (D1); Cozinha sem página (D2); formulário de contacto (D3); Saúde e Reabilitação = Genérico (D4). Pendentes: ver `core-05-prd.md` §10 (A1–A8; A4 acessos e A6 e-mail de destino bloqueiam).
- **`core-06` concluído (2026-11-07):** requisitos legais L1–L36. PRD actualizado (revisão 1: F28 e critério 13). **Bloqueiam:** A10 (EPD, para a Política de Privacidade) e A12 (plano do Cookiebot), além de A4 (acessos) e A6 (e-mail dos formulários). Ver `core-05-prd.md` §10.
- **Próximo passo:** `/wp-01-envisioning` (ver `core-07-build.md`). Objectivo: chegar à **versão demo/alpha** o mais cedo possível, partindo de `specs/tokens/` e `specs/brand-04-modelos-pagina.html`, não do `proto-2/` (ver §4, "Base da demo e do build").
- **Lacuna resolvida (2026-11-07):** `core-03-marketing.md` foi sincronizado e já mostra `marketing-01` como concluído. Não há lacunas abertas no tronco.
- **Prazo (2026-11-07):** faltam 8 dias para o lançamento. O operador pediu uma **versão demo/alpha o mais cedo possível** (`marketing-04-go-to-market.md` §2). Seguem-se os modos mínimos registados em §4.
- **Decisões tomadas nas fases 1 e 2 que afectam as seguintes** (o registo completo está nos ficheiros indicados):
  - **Lançamento:** 15/11 fixo, em duas fases. A Meta e o Brevo ficam para a fase 2. Ver `business-03-risks.md`.
  - **Âmbito:** o GA4 por GTM, com Consent Mode v2, entra no âmbito. A agenda de eventos é própria e separada do blog. As páginas de serviço não são um número fixo e os menus podem ser reagrupados. Ver `core-01-briefing.md`.
- **Pendentes do operador junto do cliente:**
  - pedir os acessos a 03/11 (WordPress actual, alojamento, Meta, Brevo);
  - **Q3**, novas instalações (Lar e Centro de Dia): data de abertura e se já se aceitam pré-inscrições para a ERPI;
  - **Q5**, mudança do Centro de Dia para as novas instalações (ampliação num local próximo): data da mudança, morada e contactos;
  - **nome oficial do CEE**: "Centro Educativo Especializado" (relatório de 2025) ou "Centro de Ensino Estruturado" (`core-01-briefing.md`). Ver `business-02-proposal.md`, Q1a.
- **A actualizar quando se reinvocar `business-03-risks`:** o R4 desce com a anonimização dos depoimentos; fica o risco residual de reidentificação pelo conteúdo (`business-02-proposal.md`, Q4).
