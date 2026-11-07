# Envisioning — Fase 01 (MVP) — Site AISA IPSS

> Ramo B (WordPress), passo 1. Gerado por `wp-01-envisioning` em 2026-11-07. Faseamento confirmado pelo operador no mesmo dia.
> Inputs:
> - `core-05-prd.md` (revisão 1);
> - `core-06-legal-requirements.md`;
> - `business-03-risks.md`;
> - `core-07-build.md`.
>
> **Ponto de partida técnico:** WordPress instalado de raiz em `public_html/`, sem modificações (só os temas Twenty* por omissão, sem plugins do projecto).
> **Base visual:** `specs/tokens/` (incluindo `theme.json`) e `specs/brand-04-modelos-pagina.html`. O `proto-2/` não é base de nada; só se aproveitam as fotos de `proto-2/img/facebook/`.
> **Restrições de composição** (`core-00-project-map.md` §4), para fugir ao ar de Bootstrap ou "IA slop":
> - layouts assimétricos em vez de grelhas de cartões iguais;
> - fotografia real como protagonista;
> - Fraunces com personagem (títulos grandes, itálico em destaques);
> - raios menos uniformes;
> - menos sombras e caixas.
>
> Este documento não decide arquitectura, tema nem plugins (isso é `wp-02-architecture`), nem estrutura detalhada de páginas ou navegação (isso é `wp-03-business-analyst`).

---

## 1. Product Roadmap

| Fase | Janela | Objectivo | Entrega |
|---|---|---|---|
| **01 — MVP** (este documento) | 07/11 → **15/11/2026** (lançamento discreto); apresentação pública a 18/11 | Site novo no ar, com o conteúdo actual reestruturado, conforme à lei e à WCAG AA | Todos os **Must** do PRD (F1 a F18 e F28) e os Should baratos (F19, F20 mínimo, F21) |
| **02 — Participação e canais** | dez/2026 → jan/2027 (o IRS tem de estar pronto antes de 1/02) | Newsletter, redes sociais e sazonalidade | F25 (Brevo com dupla confirmação, campanha de confirmação aos associados, publicação automática na Meta, Instagram se houver ferramenta estável); F22 (destaque do IRS); F20 completo (imagem por defeito por valência); F23 (filtro por valência); arranque da recolha contínua de depoimentos (AISA) |
| **03 — Pós-MVP** | sem data; depende de haver texto | Alargar o conteúdo | F24 (galeria de fotografias); F26 (páginas por terapia, CEE, Apoio à comunidade, Cozinha) |
| — **Nunca** | — | — | F27: donativos, pagamentos, marcações, área de cliente, multilingue, anúncios |

O `brand-05-manual` (depois do lançamento) não pertence ao ramo `wp-*`. É uma fase do subprocesso `brand-*`.

---

## 2. Backlog inicial

Agrupamento grosso, sem estrutura nem navegação detalhadas (essas são do `wp-03`).

### 2.1 Fase 01 — MVP

**Páginas e secções**

| ID | Página / secção | PRD | Notas |
|---|---|---|---|
| B01 | Página inicial: slideshow grande com texto ao lado, números de destaque, serviços por fase da vida, depoimento, últimas notícias com imagem, próximos eventos, "Participar", contacto | F1 | Modelo em `brand-04-modelos-pagina.html`; o slideshow não avança sozinho |
| B02 | Listagem de serviços por fase da vida | F2 | — |
| B03 | Páginas de serviço: Centro de Dia, SAD, AAAF, CAF, AEC, Saúde e Reabilitação (uma página, com uma secção por terapia), Candidaturas, Protocolos | F2, F8, F19 | D1 (espelha o site actual); P3 (os cartões ilustrativos do modelo não valem) |
| B04 | Novas instalações: "em construção", **sem datas nem morada** | F12 | `assets/capa_facebook.jpg` |
| B05 | Institucional: Quem Somos, Equipa Técnica, Órgãos Sociais, Parcerias | F11 | Equipa Técnica: só quem deu acordo (L11, A11) |
| B06 | Participar: Torne-se Sócio, Apoie a Nossa Causa (IRS informativo, 1%), Voluntariado | F11, F9 | L27, L28 |
| B07 | Documentos: Estatutos, Relatórios e Contas, Regulamentos, Assembleia Geral; por tipo e ano, com formato e tamanho | F7 | L29; documentos de 2017 a 2025 |
| B08 | Notícias: listagem, artigo, etiqueta de valência, destaque da mais recente | F4 | Imagem destacada obrigatória |
| B09 | Agenda: listagem de eventos futuros e evento individual | F5 | Lança já com as festas conhecidas até ao fim do ano |
| B10 | Contactos, com formulário, imagem estática do mapa e informação do prestador | F10, F13, F28 | L17, L22 |
| B11 | Páginas legais: Privacidade, Cookies, Termos, Declaração de Acessibilidade | F11, F13, F14 | Textos da AISA; espaços reservados na demo. A Privacidade não se publica sem o EPD (A10) |
| B12 | Cabeçalho (telefone clicável) e rodapé (telefone, legais, preferências de cookies, Livro de Reclamações, informação do prestador) | F8, F13, F28 | — |

**Funcionalidades técnicas**

| ID | Funcionalidade | PRD |
|---|---|---|
| T01 | Design system aplicado: tokens, `components.css`, `theme.json`, paleta restrita no editor, fontes alojadas no tema, cores por valência, fundos de secção (Base por defeito até a cliente decidir, A1) | F17; L21 |
| T02 | Tipo de conteúdo **Agenda**: data, local, valência | F5 |
| T03 | Tipo de conteúdo **Depoimentos**, sem campo de nome (relação, serviço, data); o bloco só aparece com pelo menos 3 | F6; L9 |
| T04 | **Documentos**: tipo e ano, formato e tamanho; geridos pela Mercês sem programação | F7, F19 |
| T05 | Taxonomia **valência**: notícias, agenda, serviços, depoimentos | F2, F4, F5 |
| T06 | **Imagem no topo** de todas as páginas e imagem destacada obrigatória nas notícias; uma imagem genérica de recurso | F3, F4, F20 (mínimo) |
| T07 | Formulário de **voluntariado**: pergunta dos 18 anos, informação RGPD, consentimento não pré-marcado, anti-spam acessível, sem conservação indefinida | F9; L1–L6, L13 |
| T08 | Formulário de **contacto**: telefone ou e-mail, informação RGPD, consentimento não pré-marcado | F10; L1–L6 |
| T09 | **Consentimento e medição:** Cookiebot, GTM e GA4 com Consent Mode v2; eventos `tel:`, formulários e descargas; bloqueio de conteúdo de terceiros com área reservada acessível | F13; L19–L24 |
| T10 | **Migração:** artigos (com inventário dos conteúdos incorporados), páginas com o texto actual, documentos de AISA → Documentos, 3 depoimentos sem nome | F16; L22 |
| T11 | **Redirecionamentos 301:** inventário dos URLs actuais e checklist dos documentos | F15 |
| T12 | **Open Graph** (título, descrição, imagem) e SEO técnico básico: URLs limpos, título e descrição, sitemap | F21; PRD §7 |
| T13 | **Acessibilidade:** WCAG 2.1 AA em todos os modelos, `prefers-reduced-motion`, alvos de 44 px, foco visível | F14; L30 |
| T14 | **Segurança e manutenção:** HTTPS, mínimo de plugins, lista de plugins e funções | PRD §7; L13; R7 |
| T15 | **Entrega:** formação da Mercês (incluindo o módulo de acessibilidade editorial, PDFs acessíveis e procedimento para retirar fotografias) e lista de plugins | F18; L10, L33 |

### 2.2 Fase 02 — Participação e canais

| ID | Item | PRD |
|---|---|---|
| B20 | Formulário de newsletter (consentimento próprio, dupla confirmação) e integração com o Brevo; campanha de confirmação aos associados | F25; L25; R10, R11 |
| B21 | Publicação automática no Facebook; Instagram só com ferramenta estável | F25; R5 |
| B22 | Destaque sazonal do IRS na página inicial (activável, a partir de fevereiro; reconfirmar a percentagem) | F22; L27 |
| B23 | Imagem por defeito por valência (escolha no arquivo, A8) | F20 |
| B24 | Filtro por valência nas notícias e na agenda | F23 |
| B25 | Novos depoimentos (recolha contínua pela AISA; publicação pela Mercês) | PRD §3.2 |

### 2.3 Fase 03 — Pós-MVP

| ID | Item | PRD |
|---|---|---|
| B30 | Galeria de fotografias | F24 |
| B31 | Páginas por terapia, CEE (depois de A2), Apoio à comunidade, Cozinha | F26; D1, D2 |

**Verificação de cobertura:**

| Funcionalidades do PRD | Fase |
|---|---|
| F1–F18, F28 (Must), F19, F20 mínimo, F21 | 01 |
| F20 completo, F22, F23, F25 | 02 |
| F24, F26 | 03 |
| F27 | nunca |

Nenhuma funcionalidade do PRD fica sem fase.

---

## 3. Definition of Done (nível de projecto)

Todas as fases herdam esta lista e não a redefinem. Um item do backlog está **feito** quando cumpre todos os pontos aplicáveis:

1. **Critérios de aceitação** do item em `wp-03-product-backlog.md` cumpridos e verificados no ambiente local (`public_html/`).
2. **Design system:** só usa tokens de `specs/tokens/`. Não há cores, tamanhos ou fontes fora do `theme.json`. As fontes são servidas pelo tema.
3. **Acessibilidade:**
   - 0 erros críticos ou graves no axe ou no Lighthouse no modelo afectado;
   - verificação manual feita: navegação por teclado com foco visível, zoom a 200% sem perda de conteúdo e sem deslocamento horizontal, leitor de ecrã (NVDA) nos elementos interactivos.
4. **Responsivo:** sem deslocamento horizontal a 360 px; verificado a 360, 768 e 1280 px.
5. **Privacidade:** com a cache limpa e sem consentimento, o separador de rede não mostra pedidos à Google (fontes, mapas, GA4) nem à Meta (K10 = 0).
6. **Editabilidade:** se o item é conteúdo gerido pela Mercês, um utilizador com o papel de Editor consegue criá-lo e editá-lo sem tocar em código e sem conseguir escolher cores ou tamanhos livres.
7. **Sem erros PHP:** sem avisos nem erros com `WP_DEBUG` ligado no `debug.log` durante o teste do item.
8. **Conteúdo:**
   - não há nomes de utentes;
   - não há datas nem moradas das novas instalações;
   - não há valores de comparticipação;
   - as siglas estão por extenso na primeira ocorrência.
9. **Versionado:** o código do tema ou plugin está commitado no Git. Os plugins de mercado acrescentados estão registados na lista de plugins (T14).
10. **Estado actualizado:** coluna Estado = ✅ em `wp-03-product-backlog.md`. Só o `wp-05-developer` a marca; o `wp-08-bug-reviewer` pode revertê-la.

**DoD de fase** (a fase só fecha quando):

- todos os itens da fase estão ✅;
- o `wp-06-seo-review.md`, o `wp-07-security-review.md` e o `wp-08-known-bugs.md` não têm itens bloqueantes abertos;
- na fase 01, os critérios de aceitação do PRD §9 (1 a 13) passam, com K8 = 0, K9 = 0 e K10 = 0.

---

## 4. Definition of Ready (nível de projecto)

Um item só entra em implementação (`wp-04` / `wp-05`) quando:

1. Está em `wp-03-product-backlog.md` com **critérios de aceitação verificáveis** e a persona ou o requisito legal que serve.
2. A **decisão técnica** que o suporta (tema, CPT, campo, plugin) está em `wp-02-architecture.md`.
3. Tem **spec de implementação** `wp-04-FF-II-slug.md` com o tipo declarado (configuração de plugin, plugin próprio ou blocos/tema).
4. O **conteúdo** de que depende existe (texto actual, imagens, documentos), ou está combinado um espaço reservado, explícito na spec.
5. As **dependências externas** estão resolvidas, ou o item tem um caminho alternativo documentado. Por exemplo, A6 para os formulários e A12 para o Cookiebot.
6. O **modelo visual** de referência está identificado em `brand-04-modelos-pagina.html`, ou a spec diz como adaptar um modelo existente.

### Dependências externas da fase 01

| Ref. | Dependência | Itens afectados | Caminho alternativo se não chegar a tempo |
|---|---|---|---|
| A4 | Acessos ao WordPress actual e ao alojamento | T10, T11 e o deploy | Inventário de URLs e migração a partir do site público (rastreio); deploy adiado |
| A6 | E-mail de destino dos formulários | T07, T08 | Configura-se com um endereço provisório da agência; troca antes de M2 |
| A10 | EPD | B11 (Privacidade) | Publica-se só depois de A10 resolvido; **bloqueia o lançamento** |
| A12 | Plano do Cookiebot | T09 | Alternativa prevista em L23 (plugin de consentimento com Consent Mode v2); decisão no `wp-02` |
| A1 | Versão de fundos | T01 | Base por defeito |
| A9 | Registo no Livro de Reclamações | B12 | Ligação omitida até haver confirmação |
| A11 | Acordo da equipa | B05 | Só funções, sem nomes nem fotografias |

---

## 5. Estimativas iniciais (T-shirt sizing)

| Fase | Tamanho | Racional |
|---|---|---|
| **01 — MVP** | **XL** | Cerca de 25 páginas, 3 tipos de conteúdo, 2 formulários, consentimento e medição, migração e 301, a partir de um WordPress limpo, em 8 dias. Viável só porque o texto é o actual (D1) e o design system já existe (tokens e `theme.json` gerados). Qualquer item não Must sai da fase em vez de atrasar a data. |
| **02 — Participação e canais** | **M** | Integração com o Brevo e campanha de confirmação; a Meta é instável (R5) e é a principal incerteza; o destaque do IRS e o filtro são pequenos. |
| **03 — Pós-MVP** | **S–M** | Depende do texto que a AISA entregar; usa os modelos já feitos. |

---

## 6. Release Plan

| Marco | Data | Fase | Conteúdo | Saída |
|---|---|---|---|---|
| **M0 — Demo/alpha** | **09/11/2026** | 01 | Tema com os tokens; página inicial, uma página de serviço (Centro de Dia), notícias e agenda com conteúdo de exemplo; ambiente local em `public_html/` | Demo à cliente; decisão sobre os fundos (A1) |
| **M1 — Beta** | **12/11/2026** | 01 | Todas as páginas de §2.1; conteúdo migrado; formulários; consentimento e medição; 301 configurados | Revisão pela cliente |
| **M2 — QA** | **14/11/2026** | 01 | `wp-06-seo-reviewer`, `wp-07-security-reviewer`, `wp-08-bug-reviewer`; K8, K9 e K10 a 0; teste real dos formulários | Lista de bloqueantes fechada |
| **M3 — Lançamento discreto** | **15/11/2026** | 01 | `wp-09-deploy`: produção, 301 activos | Site no ar |
| M3b — Apresentação pública | 18/11/2026 | 01 | Comunicação (`marketing-04`) | — |
| M4 — Formação | até 20/11/2026 | 01 | Formação da Mercês (T15); prova do critério 11 do PRD | Fase 01 fechada |
| M5 — Balanço 30 dias | ~15/12/2026 | — | Linha de base dos KPIs; alvos de K1 a K6 | Abre a fase 02 (reinvocar `wp-01-envisioning` → `wp-01-requirements-phase-02.md`) |
| M6 — Fase 02 no ar | antes de 01/02/2027 | 02 | Newsletter, Meta, destaque do IRS | Balanço aos 90 dias |
| — | sem data | 03 | Quando houver texto | `wp-01-requirements-phase-03.md` |

**Caminho crítico da fase 01:**
`wp-02-architecture` → `wp-03-business-analyst` → `wp-04-project-manager` → `wp-05` (T01 primeiro, para a demo do M0) → `wp-06` / `wp-07` / `wp-08` → `wp-09`.

O `wp-03` e o `wp-04` devem ser leves e rápidos para não comerem o prazo do M0.

**Riscos do plano:**
- **KS1/R2:** 8 dias; os acessos (A4) ainda não chegaram.
- **A10:** sem EPD, não há Política de Privacidade, e sem ela não se lança com formulários.
- **A12:** o Cookiebot é condição do GA4.

Escalar ao operador até ao M1 se algum continuar aberto.
