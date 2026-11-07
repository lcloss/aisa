# Requisitos legais — Site AISA IPSS

> Fase 6 do tronco. Gerado por `core-06-legal` em 2026-11-07. Achados e lacunas confirmados pelo operador a 2026-11-07.
> Inputs:
> - `core-01-briefing.md`;
> - `core-05-prd.md`;
> - `marketing-04-go-to-market.md`;
> - `brand-01-briefing.md`;
> - pesquisa web, com as fontes citadas em cada secção.
>
> **Jurisdição:** Portugal / UE. **Entidade:** IPSS (Alcabideche), responsável pelo tratamento. **Âmbito:** site institucional sem vendas, pagamentos ou donativos online.
> **Isto não é aconselhamento jurídico vinculativo.** Os pontos marcados **[VALIDAR]** devem ser confirmados com a AISA ou com um advogado antes do lançamento.
> Leitores obrigatórios: `wp-02-architecture` (consentimento, retenção, terceiros) e `core-05-product-owner` (lacunas, §7).

---

## 1. Protecção de dados (RGPD)

**Base:** Regulamento (UE) 2016/679 (RGPD) e Lei 58/2019 (execução nacional).

| # | Requisito | Fonte | Onde se aplica |
|---|---|---|---|
| L1 | **Informação no momento da recolha:** cada formulário mostra, junto ao botão de envio, uma frase curta com quem trata os dados (AISA), para quê, por quanto tempo e a ligação à Política de Privacidade. | RGPD art. 13.º | F9 (voluntariado), F10 (contacto) |
| L2 | **Minimização:** o contacto pede nome, telefone ou e-mail (um dos dois obrigatório) e mensagem. O voluntariado pede nome, contacto, disponibilidade, área de interesse e uma mensagem opcional. **Não se pedem dados de saúde, NIF, data de nascimento completa nem morada.** | RGPD art. 5.º, n.º 1, c) | F9, F10 |
| L3 | **Fundamento:** o contacto assenta em diligências pré-contratuais ou no interesse legítimo de responder (art. 6.º, n.º 1, b/f); o voluntariado, no consentimento ou em diligências para a colaboração. A caixa de consentimento **não vem pré-marcada** e não serve para mais nada (por exemplo, não inscreve na newsletter). | RGPD arts. 6.º e 7.º | F9, F10 |
| L4 | **Menores no voluntariado:** o formulário pergunta se a pessoa tem 18 anos ou mais. Se não tiver, mostra a indicação de que é precisa a autorização dos pais e que a AISA entra em contacto. **[VALIDAR]** a idade mínima de voluntariado que a AISA aceita. | RGPD art. 8.º (por analogia); Lei 71/98 (voluntariado) | F9 |
| L5 | **Conservação:** os envios dos formulários **não ficam guardados indefinidamente no WordPress**. Ou vão só por e-mail, ou são apagados automaticamente ao fim de um prazo. Proposta: **contacto 12 meses** e **voluntariado 24 meses ou até ao fim da colaboração**, **[VALIDAR]** com a AISA. | RGPD art. 5.º, n.º 1, e) | `wp-02` (plugin de formulários) |
| L6 | **E-mail de destino** dos formulários: uma caixa institucional da AISA (geral@ ou comunicacao@), nunca um e-mail pessoal. | RGPD art. 32.º | F9, F10 (PRD A6) |
| L7 | **Encarregado de Proteção de Dados (EPD/DPO):** uma IPSS cuja actividade principal inclui o tratamento em grande escala de dados de saúde (Centro de Dia, SAD, terapias) **provavelmente tem de designar um EPD**. O contacto do EPD figura na Política de Privacidade. **[VALIDAR]** com a AISA se já existe. | RGPD art. 37.º, n.º 1, c), e art. 38.º | Política de Privacidade |
| L8 | **Subcontratantes** com acordo de tratamento de dados (DPA): alojamento, Cookiebot (Usercentrics, UE), Google (GA4/GTM) e, na fase 2, o Brevo (UE). As transferências para os EUA (Google) estão cobertas pelo EU-US Data Privacy Framework (Decisão de Execução (UE) 2023/1795). | RGPD arts. 28.º e 45.º | `wp-02`; Política de Privacidade |
| L9 | **Depoimentos:** anónimos, sem nome nem fotografia da pessoa (decisão Q4). Regra editorial contra a reidentificação: sem idade exacta, rua, doença concreta ou datas, porque um depoimento que revele uma doença identificável é um dado de saúde (art. 9.º). | RGPD arts. 4.º, 9.º; `business-02` Q4 | F6 |
| L10 | **Fotografias de pessoas:** só com consentimento de imagem registado. No caso de crianças, consentimento dos pais; dá-se preferência a imagens sem rosto identificável. Tem de existir um **procedimento para retirar uma fotografia a pedido**, em 5 dias úteis (proposta). | Código Civil art. 79.º; RGPD arts. 6.º, 7.º e 17.º | Conteúdo; formação (F18) |
| L11 | **Equipa Técnica:** a publicação do nome, da função e da fotografia de cada funcionário exige o **acordo escrito de cada um**. Quem não der acordo aparece só com a função, ou não aparece. | RGPD art. 6.º; Código Civil art. 79.º | Página Equipa Técnica |
| L12 | **Órgãos Sociais:** a publicação dos nomes e cargos é legítima pela transparência da instituição. Fotografias, só com acordo. | Estatuto das IPSS; RGPD art. 6.º, n.º 1, c/f) | Página Órgãos Sociais |
| L13 | **Segurança:** HTTPS em todo o site; formulários com anti-spam acessível (sem CAPTCHA visual); mínimo de plugins, todos actualizados. | RGPD art. 32.º | `wp-02`; R7 |

---

## 2. Termos de utilização e Política de Privacidade

| # | Requisito | Fonte |
|---|---|---|
| L14 | **Política de Privacidade**, em linguagem simples (R-SEN), com: a identificação da AISA (nome, NIPC, morada); o contacto do EPD (L7); as finalidades e o fundamento de cada tratamento do site (contacto, voluntariado, estatísticas, newsletter na fase 2); os prazos (L5); os subcontratantes e as transferências (L8); os direitos (acesso, rectificação, apagamento, oposição, portabilidade, retirada do consentimento) e como exercê-los; o direito de queixa à **CNPD**. Data da última actualização. | RGPD arts. 12.º a 14.º |
| L15 | **Política de Cookies:** página própria, ou secção da Política de Privacidade, com a **declaração de cookies gerada pelo Cookiebot** (lista actualizada automaticamente) e uma ligação para reabrir as preferências. | Lei 41/2004 art. 5.º |
| L16 | **Termos de Utilização**, curtos: propriedade do conteúdo e das imagens; uso das ligações externas; limitação de responsabilidade pela informação (os valores de comparticipação são definidos pelo regulamento); lei portuguesa. | Boa prática; DL 7/2004 (comércio electrónico, informação do prestador) |
| L17 | **Informação do prestador**, sempre acessível (rodapé ou Contactos): nome completo, morada, NIPC, e-mail e telefone. | DL 7/2004, art. 10.º |
| L18 | **Resolução alternativa de litígios (RAL):** se se aplicar às respostas sociais pagas pelas famílias, os Termos indicam a entidade RAL competente e a ligação. **[VALIDAR]** com um advogado. | Lei 144/2015, art. 18.º |

Os textos legais são da AISA. A agência entrega a estrutura e um rascunho para validação; a demo leva espaços reservados (`core-00-project-map.md` §4).

---

## 3. Cookies e consentimento

**Base:** Lei 41/2004, art. 5.º (alterada pela Lei 46/2012), RGPD art. 7.º e Diretrizes 05/2020 do CEPD sobre consentimento.

| # | Requisito |
|---|---|
| L19 | **Nenhum cookie ou script não essencial antes do consentimento.** O GA4 e o GTM funcionam com **Consent Mode v2** (PRD F13; K10 = 0). |
| L20 | O banner tem os botões **"Aceitar" e "Rejeitar" com o mesmo destaque**, as categorias (necessários, estatísticas, marketing) e o fecho com rejeição por defeito. Sem caixas pré-marcadas. O consentimento pode ser alterado a qualquer momento (ligação no rodapé). |
| L21 | **Fontes alojadas no próprio site.** Fraunces e Atkinson Hyperlegible servidas a partir do tema, e não de `fonts.googleapis.com`: carregar a partir da Google envia o IP do visitante antes do consentimento. As páginas de especificação (`brand-0x`) podem continuar a usar a Google Fonts; o **site em produção** não. |
| L22 | **Conteúdo de terceiros bloqueado até ao consentimento:** mapas do Google nos Contactos, vídeos e publicações do Facebook, do Instagram ou do YouTube em artigos migrados. Mostra-se uma área reservada acessível ("Ver o mapa — carrega conteúdo da Google") ou, nos Contactos, uma imagem estática do mapa com uma ligação. A migração tem de **inventariar os incorporados** nos artigos antigos. |
| L23 | **Plano do Cookiebot:** o gratuito cobre poucas páginas, por isso o site da AISA deve exigir um plano pago. É um custo recorrente a avisar à AISA. **[VALIDAR]** o plano e o orçamento. Em alternativa, um plugin de consentimento com o Consent Mode v2 e uma lista de cookies mantida pela agência. |
| L24 | **Registo do consentimento** guardado pela ferramenta (prova, art. 7.º, n.º 1, do RGPD). |
| L25 | **Newsletter (fase 2):** consentimento próprio e separado; **dupla confirmação**; ligação para anular a subscrição em cada envio; registo da data e da origem do consentimento. Campanha de confirmação à base de associados antes do primeiro envio (R11). | Lei 41/2004, art. 13.º-A; RGPD art. 7.º |

---

## 4. Requisitos fiscais e de facturação

| # | Requisito | Fonte |
|---|---|---|
| L26 | **Não há facturação no site**: não há vendas, pagamentos nem donativos online (PRD §3.2). Não se aplicam requisitos de e-commerce nem de facturação electrónica ao site. | `core-05-prd.md` |
| L27 | **Consignação do IRS:** a página "Apoie a Nossa Causa" indica o **NIF da AISA** e explica que o contribuinte pode consignar **1% do IRS liquidado** sem custo para si: o valor sai da parte do Estado e não do reembolso. A percentagem passou de 0,5% para 1% a partir das declarações de 2025. Reconfirmar todos os anos antes do destaque de fevereiro (F22). | Lei 16/2001, art. 32.º, na redacção actual; OE 2025 |
| L28 | **Donativos fora do site:** se a página mostrar o IBAN, explica que a AISA passa um recibo de donativo (benefício fiscal do mecenato) a pedido, com o NIF do doador. Sem pressão para o donativo (`marketing-02` §4). | Estatuto dos Benefícios Fiscais, art. 62.º |
| L29 | **Publicação das contas:** os relatórios e contas são publicados no site **até 31 de maio** do ano seguinte. O site tem de permitir à Mercês acrescentá-los sem programação (F7). | Estatuto das IPSS, DL 172-A/2014, art. 14.º-A |

---

## 5. Acessibilidade legal

| # | Requisito | Fonte |
|---|---|---|
| L30 | **WCAG 2.1 AA** em todo o site (PRD F14, K8). Mantém-se como requisito, qualquer que seja a resposta a L32. | PRD; DL 83/2018 / EN 301 549 |
| L31 | **Declaração de Acessibilidade e Usabilidade** gerada com o **modelo oficial** (acessibilidade.gov.pt), ligada no rodapé de todas as páginas, com: nível de conformidade, conteúdos não acessíveis (os **PDFs antigos dos relatórios**), um **mecanismo de contacto** para pedir conteúdo acessível, data e método de avaliação (AccessMonitor e verificação manual). | DL 83/2018, art. 7.º |
| L32 | **Aplicabilidade do DL 83/2018 à AISA, [VALIDAR].** O texto consolidado estende o regime a organizações não governamentais **com financiamento público** que prestam **serviços essenciais**. Uma IPSS com acordos de cooperação com a Segurança Social pode estar abrangida. A agência trata-a como abrangida, por prudência. | Lei 36/2011, na redacção do DL 83/2018 |
| L33 | **PDFs novos acessíveis:** a partir do lançamento, os relatórios e regulamentos novos são publicados em PDF acessível (com etiquetas, título e língua). Faz parte da formação (F18). | DL 83/2018; WCAG 2.1 |
| L34 | A **Diretiva Europeia de Acessibilidade** (DL 82/2022) **não se aplica**: o site não vende produtos nem serviços online a consumidores. Reavaliar se um dia houver donativos ou marcações online. | Diretiva (UE) 2019/882; DL 82/2022 |

---

## 6. Risco de marca e naming

| # | Achado |
|---|---|
| L35 | **Risco baixo.** "AISA — Associação de Apoio Social Nossa Senhora da Assunção" é um nome em uso há cerca de 30 anos, e não há naming novo (`brand-01-briefing.md` §6). A tagline "Perto de si, a cuidar dos seus" é descritiva e não pretende registo. **Opcional:** pesquisa de "AISA" na classe 44/45 no INPI/EUIPO, se a AISA quiser registar a marca. Não há delegação ao `brand-01`. |
| L36 | **Imagens de terceiros:** as fotografias são da AISA (Facebook e site). Não se usam imagens de banco sem licença. Os ícones são próprios. |

---

## 7. Lacunas face ao PRD e ao branding

| # | Lacuna | Acção proposta | Delegado a |
|---|---|---|---|
| G1 | **Livro de Reclamações Eletrónico** não previsto | Ligação visível (rodapé e Contactos) ao livro electrónico, se a AISA estiver registada. **[VALIDAR]** com a AISA: uma fonte de consumo (não oficial) indica que abrange as IPSS com acordos com a Segurança Social; não foi confirmado no texto oficial (DL 156/2005, alterado pelo DL 74/2017). | `core-05-product-owner` |
| G2 | Fontes carregadas da Google (envio do IP antes do consentimento) | Requisito não funcional: fontes alojadas no tema (L21) | `core-05-product-owner` |
| G3 | Incorporados de terceiros (mapas, vídeos) não tratados | Bloqueio até ao consentimento e inventário na migração (L22) | `core-05-product-owner` |
| G4 | Regras RGPD dos formulários incompletas | L1 a L6 nos critérios de F9 e F10 | `core-05-product-owner` |
| G5 | Assembleias Gerais não previstas em Documentos | Categoria "Assembleia Geral" (convocatórias e documentos) em F7 | `core-05-product-owner` |
| G6 | Acordo da equipa para a página Equipa Técnica | Pré-condição de conteúdo (L11) | `core-05-product-owner` |
| G7 | Contacto do EPD e conteúdo mínimo da Política de Privacidade | L7 e L14 nos critérios de aceitação | `core-05-product-owner` |
| G8 | Plano pago do Cookiebot | Custo e decisão em aberto (L23) | `core-05-product-owner` (questão em aberto) |
| — | Branding / naming | Sem lacunas | — (`brand-01` não precisa de alteração) |

## 8. Questões a validar com a AISA ou com um advogado

1. Há EPD designado? Qual o contacto? (L7)
2. A AISA está registada no Livro de Reclamações Eletrónico? (G1)
3. O DL 83/2018 aplica-se à AISA? (L32) Não bloqueia: aplica-se WCAG AA na mesma.
4. Aplica-se a obrigação de informação sobre RAL (Lei 144/2015)? (L18)
5. Prazos de conservação dos formulários e idade mínima no voluntariado. (L4, L5)
6. Plano e orçamento do Cookiebot. (L23)
7. NIPC e morada oficial para a informação do prestador. (L17)

## Fontes consultadas

- [Lei 36/2011, na redacção do DL 83/2018 (texto do diploma)](https://www.cm-cantanhede.pt/mcsite/Media/upload/2020/2020724105559_Dl832018.pdf) · [AccessMonitor e declaração — exemplo da DGT](https://smos.dgterritorio.gov.pt/sites/default/files/accessibility/relatorio-acessibilidade.pdf)
- [Publicação das contas das IPSS no sítio institucional (art. 14.º-A do DL 172-A/2014) — estudo do IPB](https://recipp.ipp.pt/bitstreams/340b7f7c-a2e9-435c-8794-bcecfe0ca7da/download) · [OCC](https://occ.pt/fotos/editor2/ve15maio_iii.pdf)
- [Livro de reclamações electrónico: quem é obrigado](https://crncontabilidade.pt/blog/livro-de-reclamacoes-eletronico-quem-e-obrigado-a-ter-e-como-ativar/) · [Doutor Finanças](https://www.doutorfinancas.pt/?p=139584)
- [Consignação de IRS em 2025 aumenta para 1%](https://economiafinancas.com/2024/consignacao-de-irs-em-2025-aumenta-para-1/) · [Observador, 14/11/2024](https://observador.pt/2024/11/14/publicada-em-diario-da-republica-lei-que-duplica-parcela-do-irs-que-contribuintes-podem-consignar/)
- RGPD (Reg. UE 2016/679); Lei 58/2019; Lei 41/2004 (art. 5.º e 13.º-A); DL 7/2004; Lei 144/2015; Diretrizes 05/2020 do CEPD; Decisão de Execução (UE) 2023/1795 (EU-US DPF); DL 82/2022.
