# PRD — Site AISA IPSS

> Fase 5 do tronco. Gerado por `core-05-product-owner` em 2026-11-07.
> Inputs:
> - `core-01-briefing.md`;
> - `business-01-discovery.md`, `business-02-proposal.md` e `business-03-risks.md`;
> - `marketing-01-personas.md`, `marketing-02-value-proposition.md` e `marketing-04-go-to-market.md`;
> - `brand-02-requirements.md` e `brand-04-design-system.md`;
> - menu actual de https://www.aisaipss.pt (lido a 2026-11-07).
>
> `marketing-03-pricing.md` não existe: foi saltado porque não há venda online.
> Os gates 1 (âmbito), 2 (KPIs) e 3 (MoSCoW) foram apresentados ao operador a 2026-11-07, que confirmou as quatro decisões em aberto (§10).
> **Revisão 1 (2026-11-07):** incorpora as lacunas G1 a G8 delegadas por `core-06-legal-requirements.md` §7, confirmadas pelo operador. O âmbito, os KPIs e o MoSCoW não foram reabertos; acrescenta-se só F28 e completam-se F7, F9, F10, F11, F13, §7, §9 e §10. Este documento é a fonte da verdade sobre o que se constrói. Não define arquitectura (`wp-02-architecture`) nem user stories (`wp-03-business-analyst`).

---

## 1. Contexto e objectivos

**Problema** (`core-01-briefing.md` §2): as famílias da Malveira da Serra, Janes e Alcabideche comparam instituições online e não conseguem ver com clareza o que a AISA oferece, nem sentir a proximidade e o rigor que a distinguem. O site actual é denso, institucional e parecido com o de qualquer outra IPSS.

**Oportunidade** (`business-01-discovery.md` §5 e `business-02-proposal.md`): há quatro lacunas que a concorrência deixa abertas.
1. Nenhum concorrente organiza o site pela necessidade da família.
2. Nenhuma IPSS da zona mostra prova humana (depoimentos).
3. A transparência que existe ou é fria (SCMC) ou vem com pressão para o donativo (CPE).
4. Não há presença digital local credível; a ABESA tem o site parado desde 2013.

A AISA tem as forças para responder a isto:
- 30 anos na terra;
- respostas para todas as idades;
- documentos já publicados (2017 a 2025);
- matéria-prima humana real;
- conversão alta ao telefone (F6).

**Posicionamento e proposta de valor** (herdados de `marketing-02-value-proposition.md`): IPSS de proximidade, para todas as idades. *"Cuidado de confiança para todas as idades, aqui ao lado: percebe em minutos o que serve à sua família, vê quem cuida e fala connosco por telefone, sem pressão."*

**Objectivos do produto:**
1. Levar as famílias (P1, P2) a perceber a resposta certa e a **telefonar**.
2. Mostrar confiança: pessoas reais, depoimentos, documentos e regulamentos.
3. Mostrar a AISA viva: notícias, agenda e festas.
4. Captar participação (P3): voluntariado, associados e IRS (sazonal); a newsletter fica para a fase 2.
5. Cumprir as obrigações legais: documentos, WCAG 2.1 AA, Declaração de Acessibilidade e RGPD (cookies e consentimento).

**Directiva de conteúdo** (`brand-01-briefing.md` §0): o texto é o do site actual, com revisão leve. A melhoria vem da estrutura, do visual e da diagramação.

**Prazo:** lançamento discreto a **15/11/2026** (data fixa) e apresentação pública a 18/11. Antes disso, uma **versão demo/alpha o mais cedo possível**.

---

## 2. Métricas de sucesso (KPIs)

Medição no GA4, através do GTM com Consent Mode v2: só se mede o que for consentido. Os números são indicativos da tendência, não absolutos.

| # | KPI | Definição | Alvo |
|---|---|---|---|
| K1 | **Contactos telefónicos** (principal) | Eventos de clique em `tel:` por mês, no site todo | **[A CONFIRMAR]**: fixar com a linha de base dos primeiros 30 dias |
| K2 | Taxa de contacto nas páginas de serviço | Sessões com visita a uma página de serviço que terminam num clique em `tel:` ou no envio do formulário de contacto, a dividir pelas sessões com visita a uma página de serviço | **[A CONFIRMAR]**: linha de base aos 30 dias |
| K3 | Formulário de contacto | Envios com sucesso por mês | **[A CONFIRMAR]** |
| K4 | Confiança / transparência | Descargas de documentos e regulamentos por mês | **[A CONFIRMAR]** |
| K5 | Voluntariado | Envios do formulário de voluntariado por trimestre | **[A CONFIRMAR]** |
| K6 | Site vivo (R1) | Notícias publicadas por mês; eventos futuros na agenda | **≥ 2 notícias/mês** e **pelo menos 1 evento futuro** na agenda (proposta da agência, **[A CONFIRMAR]** com a Mercês) |
| K7 | Hipótese O7 (pesquisas locais) | Peso da pesquisa orgânica nas entradas das páginas de serviço; pesquisas locais na Search Console, se for ligada | Sem alvo. **Valida ou rejeita a O7** no balanço aos 30 e 90 dias |
| K8 | Acessibilidade (KS3) | Erros críticos ou graves num teste automático (axe/Lighthouse) e numa verificação manual (teclado, leitor de ecrã, zoom a 200%) nos modelos principais | **0** antes do lançamento |
| K9 | Migração (R9) | URLs antigos inventariados que dão erro 404 no site novo; documentos de AISA → Documentos em falta | **0** e **0** |
| K10 | Consentimento (R8) | Disparos do GA4 antes do consentimento, num teste com o Tag Assistant | **0** |

O balanço faz-se aos 30 e aos 90 dias (`marketing-04-go-to-market.md` §2). Os alvos [A CONFIRMAR] fixam-se no balanço dos 30 dias.

---

## 3. Âmbito do MVP

### 3.1 Entra (fase 1, lançamento a 15/11)

| Área | Conteúdo |
|---|---|
| **Página inicial** | Slideshow grande com texto ao lado; números de destaque (dados de 2025, médias); serviços por fase da vida; depoimento; últimas notícias com imagem; próximos eventos; "Participar" (associado, IRS, voluntariado); bloco de contacto. Referência: `brand-04-modelos-pagina.html`. |
| **Serviços** (espelham o site actual, §4.1) | Listagem por fase da vida, mais uma página por serviço. |
| **Institucional** | Quem Somos, Equipa Técnica, Órgãos Sociais, Parcerias, Torne-se Sócio, Apoie a Nossa Causa (com consignação do IRS, só informativa) e Contactos. |
| **Documentos** | Estatutos e relatórios de 2017 a 2025, agrupados por tipo e ano, com o formato e o tamanho do ficheiro indicados. |
| **Notícias** | Artigos migrados; imagem destacada obrigatória; etiqueta de valência. |
| **Agenda** | Tipo de conteúdo próprio, separado das notícias, com data, local e valência. Lança já com as festas conhecidas. |
| **Depoimentos** | Tipo de conteúdo próprio (texto, relação, serviço e data; **sem nome**). Os 3 do site actual são migrados. |
| **Novas instalações** | Página "Lar e Centro de Dia: em construção" com a imagem da obra, **sem datas nem morada** (Q3, Q5). |
| **Formulários** | Voluntariado; contacto geral (nome, contacto, mensagem e consentimento RGPD). |
| **Conformidade** | Cookiebot; GA4 por GTM com Consent Mode v2; conteúdo de terceiros bloqueado até ao consentimento; fontes alojadas no site; Política de Privacidade (com o contacto do EPD); Política de Cookies; Termos; Declaração de Acessibilidade com mecanismo de contacto; Livro de Reclamações Eletrónico (se aplicável); redirecionamentos 301. Detalhe em `core-06-legal-requirements.md`. |
| **Entrega** | Formação em Gutenberg para 1 editora (a Mercês), com um módulo de acessibilidade editorial; lista dos plugins e das suas funções (R7). |

### 3.2 Fora de âmbito

| Item | Quando | Motivo |
|---|---|---|
| Newsletter (formulário e Brevo) e campanha de confirmação aos associados | **Fase 2** (dez/2026 a jan/2027) | R2, R10, R11 |
| Publicação automática no Facebook; Instagram | **Fase 2**; o Instagram só com ferramenta estável | R5 |
| Novos depoimentos (recolha) | **Fase 2**, contínuo; a recolha é responsabilidade da AISA | `core-01` §4 |
| Páginas individuais por terapia (Fisioterapia, Terapia da Fala, etc.), página do CEE e página "Apoio à comunidade" | Pós-MVP, se houver texto | Não há texto no site actual; decisão do operador (§10, D1) |
| Página própria da Cozinha / refeições | Pós-MVP | Decisão do operador (§10, D2). O cinza fica como cor interna (notícias e agenda). |
| Manual de marca (`brand-05`) | Depois do lançamento | `core-04-brand.md` |
| Donativos ou pagamentos online, venda, marcações, área de cliente, API | **Nunca neste projecto** | `core-01` §4 |
| Multilingue | Nunca neste projecto | `core-01` §4 |
| Anúncios (Google Ad Grants, Meta Ads) | Decisão humana após o balanço | `marketing-04` §4 |
| Serviços de SEO e produção de conteúdo; recolha de fotografias e vídeos | Fora do contrato | `core-01` §4 |
| Alojamento, manutenção, actualizações e backups | Com os fornecedores da AISA | `core-01` §4 |
| Gestão do Google Business Profile e das redes sociais | Do cliente | `marketing-04` §1 |
| PDFs antigos tornados acessíveis | Não; assume-se na Declaração de Acessibilidade | KS3 |

---

## 4. Funcionalidades priorizadas (MoSCoW)

### 4.1 Mapa de páginas (Must)

Os serviços espelham o menu actual (decisão D1). O agrupamento no menu é por fase da vida.

| Grupo (menu) | Página | Valência (cor) | Regulamentos ligados |
|---|---|---|---|
| **Envelhecimento** | Centro de Dia | Centro de Dia (verde) | Regulamento Geral + Regulamento do Centro de Dia |
| | Serviço de Apoio Domiciliário (SAD) | SAD (azul) | Regulamento Geral + Regulamento do SAD |
| | Novas instalações: Lar e Centro de Dia (em construção) | Genérico | — |
| **Infância e Juventude** | Actividades de Animação e de Apoio à Família (AAAF) | Infância (laranja) | conforme o site actual |
| | Componente de Apoio à Família (CAF) | Infância (laranja) | conforme o site actual |
| | Actividades de Enriquecimento Curricular (AEC) | Infância (laranja) | conforme o site actual |
| **Saúde e Reabilitação** | Saúde e Reabilitação: uma página, com uma secção por terapia, conforme o texto actual | Genérico (D4) | conforme o site actual |
| **Como aceder** | Candidaturas; Protocolos | Genérico | Regulamento Geral |
| **A AISA** | Quem Somos, Equipa Técnica, Órgãos Sociais, Parcerias, Documentos, Torne-se Sócio | Genérico | — |
| **Participar** | Apoie a Nossa Causa (IRS informativo), Voluntariado (formulário), Torne-se Sócio | Genérico | — |
| Topo | Notícias, Agenda, Contactos (com formulário) | — | — |
| Rodapé | Privacidade, Termos, Cookies, Declaração de Acessibilidade | — | — |

As siglas aparecem sempre por extenso (R-SEN). A ligação de "Candidaturas" e "Protocolos" a "Como aceder" é uma proposta; a arquitectura de informação final é do `wp-02`.

### 4.2 MoSCoW

| # | Funcionalidade | Prioridade | Liga a |
|---|---|---|---|
| F1 | Página inicial com os blocos de §3.1 | **Must** | Proposta de valor; UC1, UC2, UC8 |
| F2 | Listagem e páginas de serviço (modelo do `brand-04`): imagem no topo, cor da valência, "para quem é", "o que inclui", horário e condições, regulamentos, telefone em destaque | **Must** | UC1, UC3, UC4, UC6, UC7; Q2 |
| F3 | Imagem no topo de **todas** as páginas; sem imagem própria, usa a imagem por defeito da secção | **Must** | Feedback do cliente; D1 do `brand-02` |
| F4 | Notícias com imagem destacada **obrigatória** e etiqueta de valência; destaque da mais recente | **Must** | Feedback do cliente; UC12 |
| F5 | Agenda (tipo de conteúdo próprio): data, local, valência; listagem de eventos futuros e bloco na página inicial | **Must** | UC8; R1 |
| F6 | Depoimentos (tipo de conteúdo sem campo de nome; relação, serviço, data); o bloco só aparece com pelo menos 3 | **Must** | UC2; KS2/Q4; R1 |
| F7 | Documentos agrupados por tipo e ano, com formato e tamanho. Tipos: Estatutos, Relatórios e Contas, Regulamentos, **Assembleia Geral (convocatórias e documentos)**. A Mercês acrescenta as contas anuais até 31 de maio sem programação | **Must** | UC10; R9; G5 / L29 (Estatuto das IPSS, art. 14.º-A) |
| F8 | Telefone 214 857 700 clicável no cabeçalho, nas páginas de serviço e no rodapé | **Must** | UC4; R-SEN; K1 |
| F9 | Formulário de voluntariado: nome, contacto, disponibilidade, área de interesse e mensagem opcional. **Sem** dados de saúde, NIF, data de nascimento ou morada. Pergunta "tem 18 anos ou mais?" e, se não, indica que é precisa a autorização dos pais. Mostra a informação RGPD junto ao envio; caixa de consentimento não pré-marcada; mensagem de sucesso acolhedora | **Must** | UC9; K5; G4 / L1–L6 |
| F10 | Formulário de contacto geral: nome, telefone **ou** e-mail, e mensagem. Informação RGPD junto ao envio; caixa não pré-marcada | **Must** | D3; R-SEN ("nenhum formulário é o único caminho"); K3; G4 / L1–L6 |
| F11 | Páginas institucionais e Participar (§4.1). **Equipa Técnica:** nome e fotografia só de quem deu acordo escrito; os restantes aparecem só com a função ou não aparecem. **Política de Privacidade** com o conteúdo mínimo de L14, incluindo o contacto do EPD | **Must** | UC10, UC11; G6 / L11; G7 / L7, L14 |
| F12 | Página "Novas instalações: em construção", sem datas | **Must** | UC5; R6; Q3/Q5 |
| F13 | Cookiebot + GTM + GA4 com Consent Mode v2 e os eventos: clique em `tel:`, envio de formulários, descarga de documentos. Banner com "Aceitar" e "Rejeitar" com o mesmo destaque; ligação no rodapé para alterar as preferências; Política de Cookies com a declaração gerada. **Conteúdo de terceiros (Google Maps, vídeos e publicações do Facebook, Instagram ou YouTube) bloqueado até ao consentimento**, com uma área reservada acessível; os Contactos usam uma imagem estática do mapa com ligação. A migração inventaria os incorporados nos artigos antigos | **Must** | R8; K1 a K5, K10; G3 / L19–L24 |
| F14 | WCAG 2.1 AA e Declaração de Acessibilidade com mecanismo de contacto | **Must** | KS3; K8 |
| F15 | Redirecionamentos 301 de todos os URLs inventariados e checklist dos documentos | **Must** | R9; K9 |
| F16 | Migração dos artigos (ferramenta) e recriação das páginas com o texto actual | **Must** | `core-01` §4 |
| F17 | Design system do `brand-04` aplicado: tokens, `components.css`, `theme.json`, fundos de secção conforme a decisão da cliente | **Must** | `brand-04-design-system.md` |
| F18 | Formação da Mercês, com módulo de acessibilidade editorial (incluindo PDFs novos acessíveis) e procedimento para retirar uma fotografia a pedido; lista de plugins | **Must** | KS3; R1; R7; L10, L33 |
| F28 | Ligação ao **Livro de Reclamações Eletrónico** no rodapé e nos Contactos; informação do prestador (nome, morada, NIPC, e-mail, telefone) sempre acessível | **Must**, condicionado: a ligação ao livro só entra se a AISA confirmar o registo (A9) | G1; L17 |
| F19 | Regulamentos associados a cada serviço, geridos pela Mercês sem programação | **Should** | Q2 |
| F20 | Imagem por defeito por valência para notícias e páginas sem imagem | **Should** (o fallback de F3/F4 pode ser uma só imagem genérica no MVP) | `brand-03` |
| F21 | Pré-visualização nas partilhas (Open Graph: título, descrição, imagem) | **Should** | Canal WhatsApp (`marketing-04` §1) |
| F22 | Destaque sazonal do IRS (bloco activável na página inicial, a partir de fevereiro) | **Should** | UC11 |
| F23 | Filtro por valência nas notícias e na agenda | **Could** | UC8, UC12 |
| F24 | Galeria de fotografias | **Could** | Pilar "Humano" |
| F25 | Newsletter / Brevo; publicação automática na Meta; Instagram | **Won't (fase 2)** | R5, R10, R11 |
| F26 | Páginas por terapia, CEE, Apoio à comunidade, Cozinha | **Won't (pós-MVP)** | D1, D2 |
| F27 | Donativos, pagamentos, marcações, área de cliente, multilingue, anúncios | **Won't** | `core-01` §4 |

O conjunto **Must** (F1 a F18, mais F28) corresponde a §3.1. O conjunto **Won't** corresponde a §3.2.

---

## 5. Fluxos de utilizador principais

### 5.1 P1 Ana — "Encontrar quem cuide do meu pai" (UC1 a UC5)

1. Pesquisa no Google ("centro de dia Cascais") e entra na página do Centro de Dia ou na página inicial.
2. Na página inicial, escolhe "Envelhecimento" e vê os cartões do Centro de Dia e do SAD, com a explicação da diferença.
3. Na página de serviço:
   - lê para quem é e o que inclui;
   - vê fotografias reais e um depoimento;
   - abre o regulamento;
   - lê "o valor depende do rendimento… ligue-nos".
4. Vê que vêm aí as novas instalações ("em construção", sem datas).
5. **Telefona** pelo botão (K1) ou envia o formulário de contacto (K3). Partilha a página por WhatsApp (F21).

### 5.2 P2 Sofia — "Onde fica o meu filho" (UC6 a UC8)

1. Entra por "Infância e Juventude" (página inicial ou menu).
2. Compara AAAF, CAF e AEC: horários, idades e actividades.
3. Na variante de terapias, abre "Saúde e Reabilitação" e encontra a secção da terapia.
4. Consulta a agenda (festas, férias) e **telefona**.

### 5.3 P3 Teresa — "Quero fazer parte" (UC8 a UC12)

1. Chega pelo Facebook a uma notícia ou à agenda.
2. Vê os próximos eventos e as notícias com fotografias.
3. Em "Participar": candidata-se a voluntária (K5), lê como ser sócia ou, a partir de fevereiro, como consignar o IRS.
4. Empresa ou candidato: abre Órgãos Sociais e Documentos (K4).

### 5.4 Mercês — editora

1. Publica uma notícia: o título, o texto, a **imagem destacada (obrigatória)** com texto alternativo e a valência são obrigatórios.
2. Cria um evento: data, local e valência.
3. Acrescenta um depoimento: texto, relação e serviço. Não há campo de nome; nas instruções do editor fica o lembrete do "sim" registado de quem o deu.
4. Carrega um relatório novo em Documentos (tipo e ano).
5. Tudo isto sem programação e sem conseguir quebrar o layout ou o contraste (paleta restrita no editor, `theme.json`).

---

## 6. Personas

Herdadas de `marketing-01-personas.md`, sem redefinição:
- **P1 Ana**, filha cuidadora (prioridade 1);
- **P2 Sofia**, mãe que trabalha (prioridade 2);
- **P3 Teresa**, vizinha e associada (prioridade 3);
- requisito transversal **R-SEN**, "o seu José, 72 anos";
- público secundário: o encaminhador (técnicos da Junta, da CM Cascais e do ISS).

---

## 7. Requisitos não funcionais

| Área | Requisito |
|---|---|
| **Acessibilidade** | WCAG 2.1 AA em todos os modelos. Corpo de texto ≥ 18 px (mínimo absoluto 16 px). Foco visível. Navegação por teclado. Slideshow sem avanço automático por defeito, com pausa (2.2.2). A cor nunca é o único sinal (1.4.1). Respeita `prefers-reduced-motion`. Alvos de toque ≥ 44 px. Banner de cookies e formulários de terceiros incluídos no teste (KS3). |
| **Legibilidade sénior (R-SEN)** | Poucos níveis de menu (no máximo 2). Siglas sempre explicadas. Telefone visível em todas as páginas. |
| **Responsivo** | Funciona de 360 px a ecrãs largos sem deslocamento horizontal. Pensado primeiro para telemóvel (D9 do `brand-02`). |
| **Performance** | Imagens redimensionadas e em formato moderno (WebP/AVIF), com `srcset`. Carregamento diferido abaixo da dobra. Slideshow: primeira imagem prioritária, as outras diferidas. Alvo proposto: LCP < 2,5 s e CLS < 0,1 em telemóvel, no Lighthouse **[A CONFIRMAR]** (depende do alojamento actual). |
| **Privacidade / RGPD** | Sem rastreio antes do consentimento (K10). **Fontes (Fraunces, Atkinson Hyperlegible) alojadas no tema, sem pedidos a `fonts.googleapis.com` em produção** (G2 / L21). Nenhum pedido a domínios de terceiros antes do consentimento, além dos essenciais. Envios dos formulários sem conservação indefinida no WordPress: ou vão só por e-mail para uma caixa institucional, ou são apagados automaticamente ao fim do prazo de L5. Acordos de tratamento de dados (DPA) com os subcontratantes (L8). Formulários com caixa de consentimento e ligação à Política de Privacidade. Sem nomes reais de utentes (Q4). Sem fotografias de rostos de crianças sem consentimento. Dados dos formulários enviados por e-mail e guardados pelo mínimo necessário (concretização no `core-06-legal` e no `wp-02`). |
| **Segurança e manutenção** (R7) | Mínimo de plugins. Nenhum plugin abandonado. Formulários com protecção anti-spam sem CAPTCHA visual inacessível. Lista de plugins e funções entregue ao fornecedor. |
| **Editabilidade** | A Mercês gere notícias, agenda, depoimentos, documentos e regulamentos sem programação. Paleta do editor restrita aos tokens. Sem cor ou tamanho livres. |
| **Língua** | Só português europeu. |
| **SEO técnico básico** | URLs limpos. Título e descrição por página. Sitemap. 301. Open Graph (F21). Sem trabalho de SEO de conteúdo. |
| **Compatibilidade** | As duas últimas versões de Chrome, Safari (incluindo iOS), Firefox e Edge. |

---

## 8. Riscos e mitigações reflectidas no produto

| Risco (`business-03-risks.md`) | Resposta no produto |
|---|---|
| **KS1 · R2 — Prazo, âmbito e acessos** | Âmbito em duas fases (§3.2). Serviços espelham o site actual (D1), sem texto novo. Fora de âmbito explícito. Versão demo/alpha cedo (`brand-04-modelos-pagina.html` serve de base). Tudo o que não seja Must passa para a fase 2 em vez de atrasar a data. |
| **KS2 · R4 — Consentimento RGPD** | F6: depoimentos sem campo de nome e sem fotografia da pessoa. Regra editorial contra a reidentificação. "O seu José" só como estilo. Nas fotografias de crianças, preferir mãos, costas e actividade. Lembrete do "sim" registado nas instruções do editor. |
| **KS3 · R3 — WCAG 2.1 AA** | F14 e K8: teste automático e manual antes do lançamento, incluindo Cookiebot e formulários. Contraste AA já validado nos tokens, incluindo valências e fundos (`brand-04` §4). Declaração de Acessibilidade a assumir os PDFs antigos. Módulo de acessibilidade na formação (F18). Paleta restrita no editor. |
| R1 — Site parado | Agenda lançada já preenchida. Bloco de depoimentos escondido com menos de 3 (F6). K6 como alarme. Publicação automática no Facebook na fase 2. |
| R5 — Meta | Fase 2, extra e não crítico (F25). |
| R6 — Datas das novas instalações | F12 sem datas nem morada. |
| R7 — Manutenção | Mínimo de plugins; lista de plugins entregue (F18; §7). |
| R8 — GA antes do consentimento | F13 com Consent Mode v2; K10 testado. |
| R9 — URLs partidos | F15: inventário, 301 e checklist dos documentos; K9 = 0. |
| R10, R11 — Brevo | Fase 2 (F25), com dupla confirmação e campanha de confirmação. |

---

## 9. Critérios de aceitação (produto)

O MVP está **pronto** quando:

1. Todas as páginas de §4.1 existem, com o texto actual revisto, imagem no topo e a cor da valência correcta.
2. Cada página de serviço tem telefone clicável, regulamentos ligados e a frase da comparticipação (Q2), sem valores.
3. As notícias migradas aparecem todas com imagem (própria ou por defeito). Não há cartões só de texto.
4. A agenda tem pelo menos os eventos conhecidos até ao fim do ano.
5. Os 3 depoimentos migrados estão publicados sem nomes, e o bloco aparece na página inicial.
6. Os formulários de voluntariado e de contacto chegam ao e-mail definido pela AISA e mostram mensagem de sucesso. Testado com envio real.
7. K8 = 0 erros críticos de acessibilidade; a Declaração de Acessibilidade está publicada.
8. K9 = 0 erros 404 nos URLs inventariados; todos os documentos de 2017 a 2025 e os estatutos estão acessíveis.
9. K10 = 0 disparos do GA4 antes do consentimento; os eventos `tel:`, formulários e descargas aparecem no GA4 em teste.
10. O site funciona a 360 px sem deslocamento horizontal e passa a revisão do cliente.
11. A Mercês publica sozinha uma notícia, um evento e um depoimento depois da formação.
12. Não há nomes de utentes, datas ou moradas das novas instalações, nem valores de comparticipação (`marketing-02` §4).
13. **Legal** (`core-06-legal-requirements.md`):
    - num teste com a cache limpa e sem consentimento, o separador de rede **não mostra pedidos** à Google (fontes, mapas, GA4) nem à Meta;
    - a Política de Privacidade tem o conteúdo de L14, incluindo o contacto do EPD;
    - a Política de Cookies mostra a declaração gerada;
    - a página Equipa Técnica só mostra quem deu acordo;
    - os documentos incluem a categoria Assembleia Geral;
    - a informação do prestador está acessível;
    - a ligação ao Livro de Reclamações existe, se a A9 o confirmar.

---

## 10. Pressupostos e questões em aberto

### Decisões tomadas nesta fase (operador, 2026-11-07)

| # | Decisão |
|---|---|
| D1 | Os serviços **espelham o site actual**: Centro de Dia, SAD, AAAF, CAF, AEC, Saúde e Reabilitação (uma página), Candidaturas e Protocolos. Não há páginas novas por terapia no MVP. |
| D2 | A **Cozinha não tem página própria**. O cinza fica como cor interna (notícias e agenda da cozinha). |
| D3 | Há **formulário de contacto geral** na página Contactos. |
| D4 | **Saúde e Reabilitação usa a valência Genérico** (branco/neutro). A confirmar depois com a cliente, sem bloquear. |

### Em aberto (com o cliente, via operador)

| # | Questão | Bloqueia? |
|---|---|---|
| A1 | **Fundos de secção:** Base, V1 ou V2 (`brand-04-modelos-pagina.html`) | Não. Por defeito fica a base. |
| A2 | **Nome oficial do CEE:** "Centro Educativo Especializado" ou "Centro de Ensino Estruturado" (Q1a) | Só bloqueia o texto que o mencione |
| A3 | Q3 e Q5: data das novas instalações, pré-inscrições na ERPI, data e morada da mudança do Centro de Dia | Não (comunica-se "em construção") |
| A4 | **Acessos** ao WordPress actual, ao alojamento, à Meta e ao Brevo (pedidos a 03/11) | **Sim**, para a migração e os 301 |
| A5 | Alvos dos KPIs K1 a K6 | Não (fixam-se aos 30 dias) |
| A6 | E-mail de destino dos formulários (geral@ ou comunicacao@) | Sim, para o teste do critério 6 |
| A7 | Que regulamentos existem por serviço (além do Geral, do Centro de Dia e do SAD) | Não (liga-se o que existir) |
| A8 | Imagens por defeito por valência (escolha no arquivo) | Não (F20 é Should) |
| A9 | A AISA está registada no **Livro de Reclamações Eletrónico**? (G1) | Só bloqueia a ligação de F28 |
| A10 | Há **EPD** designado? Qual o contacto? (G7 / L7) | **Sim**, bloqueia a publicação da Política de Privacidade |
| A11 | **Acordo escrito da equipa** para nome e fotografia na Equipa Técnica (G6 / L11) | Bloqueia só os nomes e fotografias de quem não deu acordo |
| A12 | **Plano pago do Cookiebot** e orçamento, ou alternativa (G8 / L23) | **Sim**, para F13 |
| A13 | Prazos de conservação dos formulários, idade mínima no voluntariado, NIPC e morada oficial (L4, L5, L17) | Não; há valores por defeito propostos em `core-06` |
| A14 | Aplicabilidade do DL 83/2018 e da informação sobre RAL (L18, L32), a validar com um advogado | Não (WCAG AA aplica-se na mesma) |

### Pressupostos

- **P1:** a família decide depois de telefonar, por isso o site é desenhado para levar ao telefone (`business-02` P1).
- **P2:** o alojamento actual suporta WordPress actual e HTTPS. Confirma-se com os acessos (A4).
- **P3:** os modelos de `brand-04-modelos-pagina.html` usam serviços ilustrativos (por exemplo "ATL e férias", terapias em cartões separados) que **não** correspondem a D1. O `wp-*` segue §4.1 deste PRD, não os cartões do modelo.
