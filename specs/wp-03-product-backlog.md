# Product backlog (estrutura de páginas) — Site AISA IPSS

> Ramo B (WordPress), passo 3. Gerado por `wp-03-business-analyst` em 2026-11-07. Estrutura e navegação confirmadas pelo operador no mesmo dia:
> - Saúde e Reabilitação passa para `/saude-e-reabilitacao/`, com 301;
> - menu "Serviços ▾" com grupos;
> - migram todas as notícias.
>
> Inputs:
> - `wp-01-requirements-phase-01.md`;
> - `wp-02-architecture.md`;
> - `marketing-01-personas.md`;
> - `core-05-prd.md` (revisão 1);
> - inventário de https://www.aisaipss.pt (sitemaps do Yoast e menu, lidos a 2026-11-07).
>
> **Documento vivo.** Só a coluna **Estado** é alterada por outras skills:
> - `wp-05-developer` marca ✅;
> - `wp-08-bug-reviewer` reverte para 🔄 se o DoD falhar.
>
> Qualquer outra alteração passa por reinvocar esta skill.

---

## 1. Convenções

| Elemento | Convenção |
|---|---|
| IDs | `P##`: página ou modelo; `G##`: estrutura global (cabeçalho, rodapé, menus); `RF##`: requisito funcional transversal; `T##`: item técnico de base (de `wp-01` §2.1) |
| Estado | ⬜ por fazer · 🔄 em curso · ✅ concluído |
| Personas | P1 Ana, P2 Sofia, P3 Teresa (`marketing-01-personas.md`); **R-SEN** é o requisito transversal "o seu José"; o "encaminhador" é público secundário. Não há outras personas |
| Componentes | Classes de `specs/tokens/components.css` e blocos `aisa/*` de `wp-02-architecture.md` §4.3 |
| Valência | Termo `aisa_valencia`: `sad`, `centro-dia`, `infancia`, `generico`, `cozinha` |
| Texto | É o do site actual, com revisão leve (`brand-01` §0); siglas por extenso na primeira ocorrência |
| Imagem no topo | Todas as páginas (F3): a imagem destacada ou, se não houver, `assets/img/default.jpg` |
| Telefone | `aisa/telefone` → `tel:+351214857700` (F8) |

---

## 2. DoR e DoD

**Herdados de `wp-01-requirements-phase-01.md`, sem redefinição:**
- Definition of Ready: `wp-01` §4, 6 critérios, com a tabela de dependências externas;
- Definition of Done: `wp-01` §3, 10 critérios por item, mais o DoD de fase com os critérios 1 a 13 do PRD §9 e K8 = K9 = K10 = 0.

---

## 3. Fases de construção

| Fase | Janela | Âmbito neste backlog |
|---|---|---|
| **01: MVP** | até 15/11/2026; marcos M0 a 09/11, M1 a 12/11, M2 a 14/11 | §4.1 (P01–P30), §4.2 (T01–T15), §5 (G01–G06), §6 (RF01–RF12) |
| 02: Participação e canais | dez/2026 → jan/2027 | §4.3, só em esboço; detalha-se quando o `wp-01` abrir a fase 02 |
| 03: Pós-MVP | sem data | §4.4, só em esboço |

**Prioridade para a demo M0 (09/11):** T01, G01, G02, P01, P05 (Centro de Dia), P20 e P21 (Notícias), P22 e P23 (Agenda), com conteúdo de exemplo.

---

## 4. Estrutura de páginas

### 4.1 Fase 01: páginas e modelos

| ID | Página / modelo (URL) | Objectivo | Persona / UC | Conteúdo esperado | Componentes | Tipo de conteúdo / taxonomia | Estado |
|---|---|---|---|---|---|---|---|
| **P01** | Início (`/`) | Mostrar em segundos o que a AISA faz, para todas as idades, e levar ao telefone | P1, P2, P3; UC1, UC2, UC8, UC9, UC11, UC12 | Por ordem, conforme `brand-04-modelos-pagina.html`: **(1)** hero, título e frase da proposta de valor ao lado de um slideshow de 3 a 5 fotografias reais, com botão de telefone e "Ver serviços"; **(2)** números de destaque de 2025, com o ano indicado (30 anos e médias de pessoas apoiadas por resposta); **(3)** serviços por fase da vida (Envelhecimento, Infância e Juventude, Saúde e Reabilitação), em layout assimétrico; **(4)** depoimento; **(5)** últimas notícias (a mais recente em destaque, com imagem); **(6)** próximos eventos (3); **(7)** Participar (Sócio, IRS informativo, Voluntariado); **(8)** bloco de contacto | `.hero` + `aisa/slideshow`, `aisa/provas` (`.proof-strip`), `.service-card`, `aisa/depoimentos` (`.testimonial`), `.news-card--lead`, `aisa/proximos-eventos` (`.event`), `.section--*`, `.contact-block` | `post`, `aisa_evento`, `aisa_depoimento` | 🔄 — implementado e verificado em 360/768/1280; axe/Lighthouse pendentes (rede bloqueada); fontes locais pendentes de T01; depoimentos por preencher (RF09) |
| **P02** | Serviços (`/servicos/`) ★ | Explicar a diferença entre as respostas e encaminhar para a certa (UC1, sem ter de telefonar primeiro) | P1, P2, encaminhador; UC1, UC6 | Cabeçalho com imagem; grupos **Envelhecimento** (Centro de Dia, SAD, Novas instalações), **Infância e Juventude** (AAAF, CAF, AEC), **Saúde e Reabilitação**, **Como aceder** (Candidaturas, Protocolos), cada grupo com o ícone de valência, uma frase de explicação e cartões; apelo final "Não sabe qual escolher? Ligue-nos" | `.page-header`, `.valencia-icon`, `.service-card`, `.section--strong-*` só se a A1 = V2, senão `.section--muted`, `.contact-block` | `page` | ✅ |
| **P03** | **Modelo de página de serviço** (template `page-servico`) | Dar a cada serviço a mesma estrutura previsível (R-SEN) e levar ao telefone | P1, P2; UC1, UC3, UC4, UC6, UC7 | Cabeçalho com imagem e cor da valência; **para quem é**; **o que inclui**; fotografia; **horário e condições**; **como aceder**, com a frase da comparticipação da Q2 ("o valor depende do rendimento… ligue-nos", sem valores); **regulamentos**; caixa lateral de informação prática (horário, telefone, morada); depoimento do serviço, se houver; contacto | `.page-header` com `data-valencia`, `.valencia-tag`, `aisa/regulamentos` (`.doc-list`), `aisa/telefone`, `.testimonial`, `.contact-block` | `page` + `aisa_valencia` | 🔄 — implementado e verificado em 360/768/1280; axe/Lighthouse pendentes (rede bloqueada) |
| P04 | Serviço de Apoio Domiciliário (`/servico-apoio-domiciliario/`) | Instância de P03 | P1; UC1, UC3, UC4 | Texto actual do SAD, distribuído pelas secções de P03; regulamento do SAD + Geral | P03 | `page` · `sad` | ✅ |
| **P05** | Centro de Dia (`/centro-de-dia/`) | Instância de P03; **página da demo M0** | P1; UC1–UC4 | Texto actual do Centro de Dia; regulamento do Centro de Dia + Geral; ligação a P06 | P03 | `page` · `centro-dia` | 🔄 — implementado e verificado em 360/768/1280; axe/Lighthouse pendentes (rede bloqueada) |
| P06 | Novas instalações: Lar e Centro de Dia (`/novas-instalacoes/`) ★ | Mostrar que vem aí uma resposta com mais peso, **sem datas nem morada** (R6) | P1; UC5 | Imagem da obra (`assets/capa_facebook.jpg`); texto curto "em construção"; ligações às notícias relacionadas (por ex. `construcao-da-erpi`); telefone para saber mais | `.page-header`, `.contact-block` | `page` · `generico` | ✅ |
| P07 | Actividades de Animação e de Apoio à Família, AAAF (`/atividades-de-animacao-e-de-apoio-a-familia/`) | Instância de P03 | P2; UC6 | Texto actual | P03 | `page` · `infancia` | ✅ |
| P08 | Componente de Apoio à Família, CAF (`/componente-de-apoio-a-familia/`) | Instância de P03 | P2; UC6 | Texto actual (inclui o ATL e as férias, se o texto actual o disser) | P03 | `page` · `infancia` | ✅ |
| P09 | Actividades de Enriquecimento Curricular, AEC (`/atividades-de-enriquecimento-curricular/`) | Instância de P03 | P2; UC6 | Texto actual | P03 | `page` · `infancia` | ✅ |
| P10 | Saúde e Reabilitação (`/saude-e-reabilitacao/`) ★ (301 de `/qualidade-de-vida/`) | Mostrar quem acompanha e que terapias existem | P2 (variante CEE/terapias), P1; UC7 | Texto actual de "Qualidade de Vida", **uma secção por terapia** (âncoras `#fisioterapia`, `#terapia-da-fala`, etc.), com índice no topo. O CEE só é mencionado com o nome oficial (A2); até lá, não é mencionado | P03 + índice de âncoras | `page` · `generico` (D4) | ✅ |
| P11 | Candidaturas (`/candidaturas/`) | Explicar como pedir uma vaga | P1, P2, encaminhador; UC3 | Texto actual; Regulamento Geral; telefone | P03, sem a caixa de horário | `page` · `generico` | ✅ |
| P12 | Protocolos (`/protocolos/`) | Mostrar os protocolos existentes | P1, encaminhador; UC3 | Texto actual | `page.html` | `page` · `generico` | ✅ |
| P13 | Quem Somos (`/quem-somos/`) | Contar 30 anos de proximidade e a missão | P1, P3; UC2, UC10 | Texto actual; números de destaque; fotografia; ligações à Equipa, aos Órgãos Sociais, aos Documentos e a Torne-se Sócio | `.page-header`, `aisa/provas` | `page` | ✅ |
| P14 | Equipa Técnica (`/equipa-tecnica/`) | Mostrar quem cuida (rostos) | P1; UC2, UC10 | Lista por função; **nome e fotografia só de quem deu acordo escrito** (L11, A11); os restantes só com a função | `.page-header`, grelha de pessoas (padrão do tema) | `page` | ✅ |
| P15 | Órgãos Sociais (`/orgaos-sociais/`) | Transparência da governança | P3 (empresa, candidato); UC10 | Nomes e cargos (L12); fotografias só com acordo; ligação aos Documentos | `.page-header` | `page` | 🔄 — estrutura pronta; composição nominativa do site atual por recuperar |
| P16 | Parcerias (`/parcerias/`) | Mostrar a rede local | P3, encaminhador; UC10 | Texto e logótipos actuais (com o nome como texto alternativo) | `.page-header` | `page` | 🔄 — padrão pronto; texto e logótipos do site atual por recuperar |
| P17 | Documentos (`/documentos/`) ★ | Transparência e cumprimento legal (L29) | P3, encaminhador; UC10 | Agrupados por tipo (Estatutos · Relatórios e Contas · Regulamentos · Assembleia Geral), do ano mais recente para o mais antigo, com formato e tamanho; Estatutos 2023 e Relatórios 2017–2025 migrados | `aisa/documentos` (`.doc-group`, `.doc-list`) | `aisa_documento` + `aisa_tipo_documento` | ✅ |
| P18 | Torne-se Sócio (`/torne-se-socio/`) | Converter em associado | P3; UC9 | Texto actual (quotas e como se inscrever, se o texto actual o disser); telefone e e-mail | `.page-header`, `.contact-block` | `page` | ✅ |
| P19 | Apoie a Nossa Causa (`/apoie-a-nossa-causa/`) | Explicar a consignação do IRS e os donativos fora do site, sem pressão | P3; UC11 | NIF da AISA; **1% do IRS liquidado, sem custo para o contribuinte** (L27); IBAN, se o texto actual o tiver, com a nota do recibo de donativo (L28) | `.page-header`, `.section--muted` | `page` | ✅ |
| **P20** | Notícias, listagem (`/noticias/`) | Mostrar a AISA viva | P3, P1; UC12 | A mais recente em destaque (2 colunas, imagem a 60%); as restantes em cartões com imagem dominante, etiqueta de valência e data; paginação acessível | `.news-card--lead`, `.news-card`, `.valencia-tag` | `post` | 🔄 — implementado e verificado em 360/768/1280; axe/Lighthouse pendentes (rede bloqueada) |
| **P21** | Notícia, artigo (`/noticias-aisa/<slug>/`) | Ler a notícia e ir para a acção seguinte | P3, P1; UC12 | Cabeçalho com a imagem destacada; título; data; valência; texto; conteúdos de terceiros incorporados bloqueados até ao consentimento (RF05); no fim, "Próximos eventos" e contacto | `.page-header`, `.valencia-tag`, `aisa/proximos-eventos` | `post` + `aisa_valencia` | 🔄 — implementado e verificado em 360/768/1280; axe/Lighthouse pendentes (rede bloqueada) |
| **P22** | Agenda, listagem (`/agenda/`) ★ | Mostrar o que aí vem | P2, P3; UC8 | Eventos futuros por data; data em destaque com a cor da valência; local; sem eventos passados; estado vazio com uma mensagem calorosa e ligação às notícias | `aisa/proximos-eventos` / `.event` | `aisa_evento` | 🔄 — implementado e verificado em 360/768/1280; axe/Lighthouse pendentes (rede bloqueada) |
| **P23** | Evento (`/agenda/<slug>/`) ★ | Detalhe do evento | P2, P3; UC8 | Título, data e hora (início e fim), local, valência, texto e imagem; JSON-LD Event | `.page-header`, `.event` | `aisa_evento` | 🔄 — implementado e verificado em 360/768/1280; axe/Lighthouse pendentes (rede bloqueada) |
| P24 | Voluntariado (`/voluntariado/`) ★ | Captar voluntários | P3; UC9 | Porque ser voluntário (texto actual ou das notícias de voluntariado); formulário RF01; mensagem de sucesso acolhedora | `.page-header`, formulário CF7 com o estilo do design system | `page` | ✅ |
| P25 | Contactos (`/contactos/`) | Falar com a AISA pelo canal que a pessoa preferir | P1, P2, P3; UC4 | Telefone grande; e-mail; morada; horário; **imagem estática do mapa com ligação** para o Google Maps (L22); formulário RF02; informação do prestador (L17); Livro de Reclamações (A9) | `.contact-block`, `aisa/telefone`, CF7 | `page` | ✅ |
| P26 | Política de Privacidade (`/politica-de-privacidade/`) | Cumprir os arts. 12.º a 14.º do RGPD | Todos | Conteúdo de L14 (incluindo o contacto do EPD, A10), em linguagem simples; data de actualização. **Na demo: espaço reservado** | `page.html` | `page` | 🔄 — espaço reservado na beta local; publicação de lançamento bloqueada até A10 |
| P27 | Política de Cookies (`/politica-de-cookies/`) ★ | Cumprir L15 | Todos | Explicação curta; **declaração gerada** pelo Cookiebot (shortcode); botão "Alterar preferências" | `page.html` | `page` | 🔄 — rascunho preparado; declaração gerada pendente de Cookiebot configurado |
| P28 | Termos de Utilização (`/termos/`) ★ | Cumprir L16 e L18 | Todos | Texto de L16; RAL se a L18 se aplicar. Na demo: espaço reservado | `page.html` | `page` | ✅ |
| P29 | Declaração de Acessibilidade (`/declaracao-de-acessibilidade/`) ★ | Cumprir L31 | Todos, R-SEN | Gerada com o modelo oficial (acessibilidade.gov.pt); PDFs antigos declarados como não acessíveis; mecanismo de contacto; data e método de avaliação | `page.html` | `page` | 🔄 — página provisória; HTML do gerador oficial e avaliação AccessMonitor/manual pendentes |
| P30 | 404 e Pesquisa | Não deixar ninguém perdido | Todos, R-SEN | Mensagem simples; caixa de pesquisa; ligações a Serviços, Notícias e Contactos; telefone | `.contact-block` | — | ✅ |

★ = página nova (não existe no site actual).

### 4.2 Fase 01: itens técnicos de base

Vêm do `wp-01` §2.1 e são concretizados no `wp-02`. O estado também é seguido aqui.

| ID | Item | Serve | Decisão no `wp-02` | Estado |
|---|---|---|---|---|
| T01 | Tema `aisa`: `theme.json` gerado, `base.css`, `components.css`, fontes alojadas no tema, estilos de bloco, templates (§3.4 do `wp-02`) | Todas as P | §1, §5 | 🔄 |
| T02 | Tipo de conteúdo Evento + campos | P01, P21, P22, P23 | §3.1 | ✅ |
| T03 | Tipo de conteúdo Depoimento + campos (sem nome); bloco escondido com menos de 3 | P01, P03 | §3.1, §4.3 | ✅ |
| T04 | Tipo de conteúdo Documento + `aisa_tipo_documento` + campos | P03, P17 | §3.1, §3.2 | ✅ |
| T05 | Taxonomia `aisa_valencia` (5 termos fixos) → `data-valencia` | P03–P11, P20–P23 | §3.2 | ✅ |
| T06 | Imagem no topo / por defeito; imagem destacada obrigatória nas notícias | Todas as P; P21 | §4.1 | 🔄 — implementado e verificado em 360/768/1280; axe/Lighthouse pendentes (rede bloqueada) |
| T07 | Formulário de voluntariado | P24 | §4.1 | 🔄 |
| T08 | Formulário de contacto | P25 | §4.1 | 🔄 |
| T09 | Cookiebot + GTM + GA4 (Consent Mode v2) + bloqueio de conteúdo de terceiros | Todas as P | §4.1, §7 | 🔄 |
| T10 | Migração (notícias, páginas, documentos, depoimentos) | P04–P21 | §4.1 | ⬜ |
| T11 | Redirecionamentos 301 + monitor de 404 | — | §6 | ⬜ |
| T12 | Open Graph, meta e Schema.org | Todas as P | §6 | ⬜ |
| T13 | Acessibilidade transversal | Todas as P | §5, §7 | ⬜ |
| T14 | Hardening e lista de plugins | — | §7 | ⬜ |
| T15 | Formação da Mercês + documento de plugins | — | §4.1 | ⬜ |

### 4.3 Fase 02, esboço (detalhe quando o `wp-01` abrir a fase)

| ID | Página / funcionalidade | Persona / UC | Estado |
|---|---|---|---|
| P40 | Subscrição da newsletter (bloco no rodapé + página de confirmação da dupla confirmação) | P3; UC13 | ⬜ |
| P41 | Destaque do IRS na página inicial (activável) | P3; UC11 | ⬜ |
| P42 | Filtro por valência em P20 e P22 | P2, P3; UC8, UC12 | ⬜ |
| P43 | Imagens por defeito por valência | — | ⬜ |
| P44 | Publicação automática no Facebook (e Instagram, se houver ferramenta estável) | P3; UC12 | ⬜ |

### 4.4 Fase 03, esboço

| ID | Página / funcionalidade | Persona / UC | Estado |
|---|---|---|---|
| P50 | Galeria de fotografias | P3, P1; UC2, UC12 | ⬜ |
| P51 | Páginas por terapia (a partir das secções de P10) | P2; UC7 | ⬜ |
| P52 | Página do CEE (depois da A2) | P2; UC7 | ⬜ |
| P53 | Apoio à comunidade; Cozinha / refeições | P1, P3 | ⬜ |

---

## 5. Navegação e estrutura global

### 5.1 Itens globais

| ID | Elemento | Especificação | Estado |
|---|---|---|---|
| **G01** | Cabeçalho | Logótipo (ligação ao Início); menu principal (G03); **botão de telefone sempre visível** "☎ 214 857 700" (F8, R-SEN); fixo ao fazer scroll só em ecrã largo (opcional); abaixo de 560 px, versão compacta: logótipo, botão "Ligar" e botão "Menu" | 🔄 |
| **G02** | Rodapé | 4 colunas (empilhadas em telemóvel): **(1)** AISA: nome completo, morada, NIPC, telefone, e-mail (L17); **(2)** Serviços; **(3)** A AISA e Documentos; **(4)** Legais: Privacidade, Cookies, Termos, Declaração de Acessibilidade, **"Preferências de cookies"** (reabre o banner), **Livro de Reclamações** (se a A9 o confirmar). Ligações ao Facebook e ao Instagram (só ligações, sem conteúdo incorporado). © e ano | ✅ |
| **G03** | Menu principal | Ver §5.2. No máximo 2 níveis de ligação; os títulos de grupo não são ligações | ✅ |
| **G04** | Menu de telemóvel | Painel de ecrã inteiro; botão "Menu" com `aria-expanded`; os grupos são acordeões (`<button aria-expanded>`); foco preso dentro do painel enquanto está aberto; fecho com Esc e devolução do foco; telefone no topo do painel; alvos ≥ 44 px | 🔄 |
| **G05** | Breadcrumbs | Rank Math, em todas as páginas excepto o Início, abaixo do cabeçalho de página. Estrutura plana (os URLs estão na raiz): `Início › Centro de Dia`; `Início › Notícias › <título>`; `Início › Agenda › <evento>`. Schema BreadcrumbList | ⬜ |
| **G06** | Ligação "Saltar para o conteúdo" | Primeiro elemento focável de todas as páginas (WCAG 2.4.1) | 🔄 |

### 5.2 Menu principal (G03)

```
Serviços ▾                     A AISA ▾            Participar ▾          Notícias   Agenda   Contactos   [☎ 214 857 700]
├─ Envelhecimento (título)     ├─ Quem Somos       ├─ Torne-se Sócio
│  ├─ Centro de Dia            ├─ Equipa Técnica   ├─ Apoie a Nossa Causa
│  ├─ Apoio Domiciliário       ├─ Órgãos Sociais   └─ Voluntariado
│  └─ Novas instalações        ├─ Parcerias
├─ Infância e Juventude        └─ Documentos
│  ├─ AAAF · CAF · AEC (nomes por extenso no menu)
├─ Saúde e Reabilitação
├─ Como aceder (título)
│  ├─ Candidaturas · Protocolos
└─ → Ver todos os serviços (/servicos/)
```

- O painel "Serviços" no ecrã largo é um painel de grupos, com o ícone de valência em cada grupo.
- A ligação "Serviços" abre o painel e não navega. "Ver todos os serviços" é a ligação para P02.
- Os nomes AAAF, CAF e AEC aparecem por extenso no menu (R-SEN), com a sigla entre parênteses.

### 5.3 Mapa de redirecionamentos 301 (input de T11)

| De (site actual) | Para | Motivo |
|---|---|---|
| `/qualidade-de-vida/` | `/saude-e-reabilitacao/` | Novo slug (decisão de 2026-11-07) |
| `/novos-projectos/` | `/novas-instalacoes/` | Página com texto de modelo, substituída |
| `/quem-somos-2/` | `/quem-somos/` | Duplicado |
| `/apoio-a-infancia/`, `/apoio-a-infancia-2/` | `/servicos/` | Fundidas na listagem |
| `/category/noticias-aisa/` (+ `/page/N/`) | `/noticias/` | Listagem nova |
| `/noticias-aisa/hello-world/` | `/noticias/` | Não se migra |
| `/noticias-aisa/consignacao-irs/attachment/relatorio-e-contas-2022/` | URL do PDF de 2022 (ou `/documentos/`) | Página de anexo |
| `/noticias-aisa/<slug>/` (87) | **mantêm-se** | Mesma estrutura de permalinks |
| `/wp-content/uploads/AAAA/MM/*.pdf` | **mantêm-se** | Mesmos caminhos |
| Outras páginas de anexo (`/attachment/`) | Ficheiro | Rank Math: redireccionar anexos para o ficheiro |
| URLs que o monitor de 404 detecte nos primeiros 90 dias | Caso a caso | K9 |

Todas as outras páginas do sitemap actual mantêm o slug (`wp-02` §3.3).

---

## 6. Requisitos funcionais transversais

| ID | Requisito | Serve | Persona / UC | Critérios de aceitação | Estado |
|---|---|---|---|---|---|
| **RF01** | **Formulário de voluntariado** (CF7) | P24 | P3; UC9 | Campos: nome*, telefone ou e-mail (pelo menos um)*, disponibilidade* (opções: dias úteis de manhã, de tarde, fins de semana, pontual), área de interesse* (Envelhecimento, Infância, Eventos, Outra), mensagem (opcional), **"Tem 18 anos ou mais?"*** (Sim/Não, com a ajuda fixa "Se tiver menos de 18 anos, precisamos da autorização dos pais; entramos em contacto."), caixa de consentimento* **não pré-marcada**, com a frase L1 e a ligação a P26. **Não pede** saúde, NIF, data de nascimento nem morada (L2). Erros anunciados (`aria-live`) e associados ao campo. WP Armour + Turnstile. Não guarda os envios na base de dados. E-mail para a caixa da A6. Mensagem de sucesso acolhedora. Evento GA4 `form_submit` (`form_id=voluntariado`), só com consentimento | 🔄 |
| **RF02** | **Formulário de contacto** (CF7) | P25 | P1, P2; UC4 | Campos: nome*, telefone ou e-mail (pelo menos um)*, mensagem*, consentimento* não pré-marcado + L1. Ao lado: "Prefere falar? Ligue 214 857 700" (o formulário nunca é o único caminho). As mesmas regras de anti-spam, não guardar, e-mail, erros e evento (`form_id=contacto`) | 🔄 |
| **RF03** | **Consentimento** (Cookiebot ou Complianz, conforme a A12) | Todas | Todos | Banner acessível (teclado, leitor de ecrã) com "Aceitar" e "Rejeitar" com o mesmo estilo; categorias: necessários, estatísticas, marketing; nenhuma pré-marcada; registo do consentimento; "Preferências de cookies" no rodapé reabre o banner; teste K10 = 0 | 🔄 — ligação de preferências preparada; CMP, A12 e validação K10 pendentes |
| **RF04** | **Medição GA4 via GTM** | Todas | — | Consent Mode v2 com `default denied`; eventos `click_tel` (todas as ligações `tel:`), `form_submit` (RF01, RF02), `file_download` (pdf, doc, docx, xls, xlsx); verificados no DebugView em teste | 🔄 |
| **RF05** | **Conteúdo de terceiros bloqueado** | P21, P25 | Todos | Inventário dos `iframe` e conteúdos incorporados (YouTube, Facebook, Google Maps) nas 87 notícias migradas; cada um bloqueado até ao consentimento, com uma área reservada acessível ("Este vídeo carrega conteúdo do YouTube. [Aceitar e ver]"); nos Contactos, mapa estático (L22) | 🔄 — inventário local sem incorporados; aguarda migração 02-05 e validação do bloqueio |
| **RF06** | **Migração de notícias** | P20, P21 | P3; UC12 | 87 notícias (todas excepto a `hello-world`), com o mesmo slug, data, texto e imagem destacada. Notícias sem imagem → imagem por defeito (critério 3 do PRD). Valência proposta pelo título (palavras-chave: centro de dia/sénior → `centro-dia`; domicílio → `sad`; ATL, férias, crianças, brincar → `infancia`; cozinheira → `cozinha`; restantes → `generico`) e revista pela Mercês depois do lançamento. Metadados do Yoast importados para o Rank Math. Ligações internas antigas verificadas | ⬜ |
| **RF07** | **Migração de páginas** | P04–P19 | — | Texto actual de cada página do sitemap, recolocado no novo modelo (P03 para os serviços); sem texto de modelo (por ex. "Creator of quality designs") | ⬜ |
| **RF08** | **Migração de documentos** | P17, P03 | P3; UC10 | Checklist: Estatutos 2023; Relatórios 2017, 2018, 2019, 2020, 2021, 2022, 2023, 2024 e 2025; Regulamento Geral, do Centro de Dia e do SAD (+ os que existirem, A7); convocatórias da Assembleia Geral que estejam publicadas. Cada um como `aisa_documento` com tipo, ano e valência (regulamentos). Mesmos caminhos em `uploads/`. K9: 0 documentos em falta | ⬜ |
| **RF09** | **Depoimentos iniciais** | P01, P03 | P1, P2; UC2 | Os 3 depoimentos da notícia `testemunhos`, **sem nome**, com relação e serviço (L9; regra contra a reidentificação aplicada); o bloco aparece na página inicial | ⬜ — por preencher, conforme indicação do utilizador em 08/11/2026; aguarda os 3 textos originais de `testemunhos`; secção oculta enquanto houver menos de 3 |
| **RF10** | **Agenda inicial** | P22 | P2, P3; UC8 | Eventos conhecidos até 31/12/2026 (por ex. aniversário da AISA em novembro, Natal, ATL de Natal), **[A CONFIRMAR] com a Mercês**; pelo menos 1 evento futuro (K6) | ⬜ |
| **RF11** | **Permissões e editabilidade** | — | Mercês | Conta "Editor" para a Mercês. Consegue: criar e editar notícias, eventos, depoimentos, documentos, páginas e menus (opcional). Não consegue: plugins, tema, utilizadores, cores e tamanhos livres. A publicação de uma notícia sem imagem destacada é bloqueada. Instruções no ecrã de edição do Depoimento: "Confirme que tem o 'sim' registado de quem deu o depoimento. Não escreva nomes, idades, ruas ou doenças." | ⬜ |
| **RF12** | **Acessibilidade transversal** | Todas | R-SEN; KS3 | Corpo ≥ 18 px; foco visível; navegação por teclado; slideshow manual com pausa; `prefers-reduced-motion`; alvos ≥ 44 px; a cor nunca é o único sinal (etiqueta de valência com o nome); `lang="pt-PT"`; hierarquia de títulos sem saltos; texto alternativo obrigatório nas imagens de conteúdo; teste axe + manual = K8 = 0 | ⬜ |

---

## 7. Rastreabilidade

| PRD | Itens deste backlog |
|---|---|
| F1 Página inicial | P01 |
| F2 Serviços | P02, P03, P04, P05, P07–P12 |
| F3 Imagem no topo | T06 (todas as P) |
| F4 Notícias com imagem | P20, P21, T06, RF06 |
| F5 Agenda | P22, P23, T02, RF10 |
| F6 Depoimentos | T03, RF09, P01, P03 |
| F7 Documentos | P17, T04, RF08 |
| F8 Telefone | G01, G02, P03, P25 |
| F9 Voluntariado | P24, RF01, T07 |
| F10 Contacto | P25, RF02, T08 |
| F11 Institucional, Participar, Privacidade | P13–P16, P18, P19, P26 |
| F12 Novas instalações | P06 |
| F13 Cookies, GTM, GA4, terceiros | RF03, RF04, RF05, T09, P27, P25 (mapa estático) |
| F14 WCAG + Declaração | RF12, T13, P29, G04, G06 |
| F15 301 | §5.3, T11 |
| F16 Migração | RF06, RF07, RF08, T10 |
| F17 Design system | T01 |
| F18 Formação | T15, RF11 |
| F19 Regulamentos por serviço | P03 (`aisa/regulamentos`), RF08 |
| F20 Imagem por defeito (mínimo) | T06; completo → P43 |
| F21 Open Graph | T12 |
| F22 IRS sazonal | P41 (fase 02) |
| F23 Filtro por valência | P42 (fase 02) |
| F24 Galeria | P50 (fase 03) |
| F25 Newsletter, Meta | P40, P44 (fase 02) |
| F26 Terapias, CEE, Cozinha | P51–P53 (fase 03) |
| F27 Donativos, etc. | Nunca: não há itens |
| F28 Livro de Reclamações + informação do prestador | G02, P25 |
| PRD §7 Termos, Cookies (L15, L16) | P27, P28 |
| UC1–UC12 (`marketing-01`) | Todas cobertas na fase 01 (coluna Persona / UC de §4.1 e §6); UC13 → P40 |

---

## 8. Pressupostos e questões em aberto

### Pressupostos

- **BP1:** o texto de cada página vem do site actual (rastreio público ou WXR da A4). Onde o texto actual não preencher uma secção de P03 (por ex. "horário"), a secção é omitida. Não se inventa texto.
- **BP2:** os nomes por extenso no menu (AAAF, CAF, AEC) seguem os títulos das páginas actuais.
- **BP3:** a valência das notícias migradas é uma proposta automática; a revisão pela Mercês não bloqueia o lançamento.
- **BP4:** os conteúdos incorporados nas notícias antigas (vídeos promocionais, publicações do Facebook) são inventariados em RF05; se uma ligação estiver morta, o conteúdo é substituído por uma ligação de texto.

### Em aberto

| # | Questão | Afecta | Bloqueia |
|---|---|---|---|
| A1 | Fundos de secção (Base / V1 / V2) | P01, P02 | ✅ V2 (2026-11-08) |
| A2 | Nome oficial do CEE | P10 | Só a menção ao CEE |
| A6 | E-mail de destino dos formulários | RF01, RF02 | Teste real |
| A7 | Regulamentos por serviço, além dos três conhecidos | P03, RF08 | Não |
| A9 | Livro de Reclamações | G02, P25 | Só a ligação |
| A10 | EPD | P26 | Publicação de P26, e portanto o lançamento com formulários |
| A11 | Acordo da equipa | P14 | Só os nomes e as fotografias |
| A12 | Cookiebot ou Complianz | RF03 | Configuração final |
| BQ1 | **Eventos até ao fim do ano** (datas, locais): lista da Mercês | RF10 | Critério 4 do PRD |
| BQ2 | **NIF, NIPC, IBAN e morada oficial** para P19 e G02 | P19, G02 | Informação do prestador (L17) |
| BQ3 | **Fotografias para o slideshow** e para o cabeçalho de cada página de serviço (do Facebook ou do site actual, com consentimento de imagem) | P01, P03 | Não (há `proto-2/img/facebook/` e a imagem por defeito) |
