# Briefing — AISA IPSS

> Fase 1 do tronco. Gerado por `core-01-briefing` em 2026-11-02.
> Inputs: `specs/briefing-aisa-ipss-v6.md` (formulário v6, 02/11/2026), `specs/AISA Briefing de Marca.docx` (entrevista de marca com o cliente), `specs/core-00-project-map.md` (cenário herdado) e conversa com o operador em 2026-11-02.
> Quando o formulário v6 e o briefing de marca se contradizem, vale o **v6** ou a decisão do operador registada aqui.

**Cenário (herdado de `core-00-project-map.md`, não reavaliado):** B — WordPress.

---

## 1. Ideia

Um site novo em WordPress para a AISA, que reaproveita o conteúdo do site actual e do Facebook mas com **estrutura e apresentação novas**. O objectivo é que deixe de parecer uma IPSS fria e institucional e passe a mostrar uma instituição próxima e calorosa, que acompanha as famílias da Malveira da Serra e Janes **da infância ao envelhecimento**.

## 2. Problema / objectivo

> **As famílias da Malveira da Serra, Janes e Alcabideche que procuram apoio de confiança para um pai idoso ou para um filho comparam instituições online e têm dificuldade em escolher a AISA. Não conseguem ver com clareza o que ela oferece, nem sentir a proximidade e o rigor que a distinguem, porque o site actual é denso, institucional e parecido com o de qualquer outra IPSS.**

**Prioridade do público**, segundo o briefing de marca, §11.3:

1. Filho ou filha que cuida de um pai idoso (45-60 anos): procura Centro de Dia, SAD ou, no futuro, ERPI.
2. Pais que procuram resposta para os filhos (25-45 anos): ATL, infância e juventude, CEE.
3. Comunidade: particulares que acompanham os eventos, empresas locais e patrocinadores, candidatos a emprego e voluntários.
4. Sénior autónomo (65+): tem literacia digital limitada e precisa de um site legível e simples.

**Objectivos do site:**
- Informar sobre a instituição e transmitir credibilidade, com transparência e documentos legais.
- Ajudar as famílias a encontrar a resposta certa e a contactar a AISA.
- Comunicar eventos e o dia-a-dia da instituição.
- Receber candidaturas a voluntariado.
- Captar subscritores para a newsletter.
- Na época da declaração de IRS (a partir de fevereiro), lembrar a consignação do IRS à AISA.

## 3. Sumário executivo

A AISA, Associação de Apoio Social Nossa Senhora da Assunção, é uma IPSS de Alcabideche, fundada em 1992 e em funcionamento desde 1996. Apoia a comunidade da Malveira da Serra e Janes em todas as fases da vida: infância e juventude (ATL, Centro de Ensino Estruturado), serviços técnico-clínicos abertos à comunidade e envelhecimento (Centro de Dia, SAD e, no futuro, ERPI).

O site actual tem muita informação, mas é institucional e distante. O cliente quer **renovar a imagem e tornar o site mais humano e próximo**, sob a mensagem *"Perto de si, a cuidar dos seus — da infância ao envelhecimento"*. Quer também manter a informação institucional obrigatória por lei, como as contas e os estatutos.

O tom é acessível, empático, seguro e transparente. Quer histórias reais e rostos, trata as pessoas pelo nome ("o seu José", nunca "o utente") e mantém a serenidade nos temas sensíveis.

## 4. Âmbito do projecto

### Entra

**Estrutura e páginas**
- **Páginas institucionais:** Sobre, Equipa, Órgãos Sociais, Contactos e FAQ.
- **Documentos obrigatórios por lei:** relatórios de contas, estatutos, regulamentos e outros. São migrados da secção actual AISA → Documentos.
- **Parcerias:** CM Cascais, Junta de Freguesia de Alcabideche e ISS / Segurança Social, com base na página actual `/parcerias/`.
- **Serviços:** uma listagem e uma página individual por serviço. Os **~15 foram uma estimativa**, não uma meta: entram os serviços descritos no briefing e presentes nos menus do site actual. O site actual agrupa-os em "Serviços Sociais", "Infância e Juventude" e "Saúde e Reabilitação". **O novo site pode agrupá-los de outra forma** (por exemplo, por ciclo de vida), desde que faça sentido para quem procura. A decisão é da arquitectura de informação. Valências já identificadas:
  - **Infância e Juventude:** Apoio à infância e juventude, ATL e CEE — **Centro de Ensino Estruturado**. Esta é a designação da AISA e a que se usa no site.
  - **Serviços Sociais / envelhecimento:** Centro de Dia, SAD e **ERPI, que está em obras e ainda não abriu**. Comunica-se como resposta futura e não como serviço disponível.
  - **Saúde e Reabilitação (técnico-clínicos):** Psicologia, Terapia da Fala, Fisioterapia, Terapia Ocupacional, Enfermagem e Consultas.
  - A lista final fecha-se no PRD (`core-05-product-owner`), a partir dos menus do site actual.
- **Sistema de cores por área:**

  | Cor | Área |
  |---|---|
  | Azul | SAD |
  | Verde | Centro de Dia |
  | Laranja | Infância e Juventude |
  | Branco | Genérico |
  | Cinza | Cozinha |

**Conteúdo editorial**
- **Blog / notícias** com vários artigos por mês.
- **Agenda de eventos**, separada do blog, com data e local. Inclui as festas da Malveira, o São João, as festas organizadas pela AISA e as datas comemorativas. A ideia foi sugerida pela agência e o cliente aprovou-a.
- **Depoimentos** num tipo de conteúdo próprio com **Custom Fields**, para a Mercês ir acrescentando ao longo do tempo. A meta é cerca de 15. No lançamento entram os **3 testemunhos do site actual** (`/noticias-aisa/testemunhos/`).
- **Consignação do IRS:** só informativa (NIF da AISA e explicação), com destaque sazonal a partir de fevereiro. Não há pagamentos.

**Formulários e integrações**
- **Formulário de candidatura a voluntariado.**
- **Newsletter:** formulário de subscrição (Contact Form 7) ligado ao **Brevo**, através de plugin ou ferramenta, desde que exista. A primeira lista é a base de associados.
- **Redes sociais:** os artigos de notícias são publicados automaticamente no **Facebook** por plugin. Publicar também as imagens no **Instagram** é desejável, se houver ferramenta viável.

**Migração**
- **Páginas:** a agência recria-as manualmente na nova estrutura, reaproveitando o texto do site actual.
- **Artigos e notícias:** são migrados com uma ferramenta.
- **A Mercês** revê o conteúdo migrado e cria conteúdo novo.

**Conformidade e formação**
- **Cookies:** gestão do consentimento com o Cookiebot.
- **Medição:** Google Analytics 4 através do Google Tag Manager, com Consent Mode v2 (Cookiebot). Foi acrescentado a 2026-11-02.
- **Acessibilidade:** WCAG 2.1 AA e Declaração de Acessibilidade com mecanismo de contacto (DL 83/2018).
- **Páginas legais:** Política de Privacidade e Termos.
- **Formação** em WordPress e Gutenberg para editores, para 1 pessoa (Mercês, responsável pela comunicação).

**Base técnica**
- WordPress com Gutenberg nativo e design personalizado.
- Um só idioma: português de Portugal.

### Não entra

- **Donativos online**, em qualquer forma (pagamento, MB, formulário de doação). Os donativos de empresas que existem hoje ficam fora do site.
- **Venda online, marcações e agendamentos.**
- **Aplicação ou sistema à medida**, área de cliente ou API.
- **Multilingue.**
- **Publicidade online**, incluindo o Google Ad Grants.
- ~~Analytics~~: **passou a entrar no âmbito** por decisão do operador (2026-11-02). Ver a §4 de `business-02-proposal.md`.
- **Serviços de SEO e produção de conteúdo** pela agência. A migração não conta como produção: a agência migra e estrutura, a Mercês escreve.
- **Recolha de fotografias e vídeos novos.** Usa-se o material do site actual e do Facebook.
- **Recolha dos depoimentos novos:** é responsabilidade da AISA. A agência só entrega a estrutura para os publicar.
- **Landing pages** além do site.
- **Alojamento, manutenção, actualizações de segurança e backups:** ficam com os fornecedores actuais da AISA.
- **Domínio e e-mail:** já estão tratados.
- **Gestão do Google Business Profile e das redes sociais**, para além da publicação automática das notícias.
- **Manual de marca** (`brand-05`): pode ficar para depois do lançamento, conforme o mapa do projecto.

### Decisões do operador (2026-11-02)

- **Eventos:** passam a ser uma agenda própria, e não uma categoria do blog.
- **Serviços:** o número de páginas não está fixo. O agrupamento dos menus pode mudar. A lista final, incluindo se "Cozinha" tem página própria, fecha-se no PRD.

## 5. Material disponível

**Marca**
- **Logótipo:** `proto-2/img/logo-aisa.png`.
- **Identidade visual aprovada pelo cliente:** `proto-1/04-brand-visual-identity.html`. Usa as fontes Fraunces e Atkinson Hyperlegible, o Azul Confiança como cor primária, só modo claro e cumpre WCAG AA.
- **Design system aprovado:** `proto-1/05-brand-design-system.html` e `proto-1/tokens/`.
- **Protótipo da home:** `proto-2/index.html` e `proto-2/tokens/base.css`, com ícones de linha.
- **Briefing de marca:** `specs/AISA Briefing de Marca.docx`. Inclui posicionamento, missão, visão, valores, tom de voz com exemplos, as 4 personas, concorrentes e referências, e palavras-chave.
- **Artefactos do workflow antigo**, como material de apoio: `specs/00-brief.md`, `01-descoberta.md`, `02-marketing.md` e `03-brand/*`.

**Conteúdo**
- **Site actual em WordPress:** https://www.aisaipss.pt/ (páginas, notícias, documentos legais, parcerias e 3 testemunhos).
- **Fotografias** do site e do Facebook, todas com consentimento de imagem recolhido. Algumas já estão em `proto-2/img/facebook/`.

**Canais**
- Facebook: https://www.facebook.com/AISA.IPSS
- Instagram: https://www.instagram.com/aisa_ipss/
- Google Business Profile: https://maps.app.goo.gl/j8kUhyS7JBpyxnU19

**Contactos**
- Comunicação: Mercês Pedroso, comunicacao@aisaipss.pt
- Geral: geral@aisaipss.pt
- Telefone: 214 857 700
- Morada: Rua de Cascais 877, Malveira da Serra

**Lista para a newsletter:** base de associados existente. O Brevo é a plataforma de envio prevista.

**Acessos e infraestrutura**
- Domínio e e-mail já tratados. O alojamento fica com os fornecedores actuais.
- Faltam os **acessos** (WordPress actual, alojamento, Facebook e Instagram para a publicação automática, conta Brevo). Pedem-se ao cliente no início do ramo `wp-*`.

**Referências**
- Indicadas no formulário: scmc.pt, cpestoril.pt, residenciasmontepio.pt e saintjosephsshankill.ie (esta para o estilo de linha dos ícones).
- Indicadas no briefing de marca: Fundação do Gil, navegação por ciclo de vida da Misericórdia de Lisboa e transparência da CERCICA.

## 6. Prazo / orçamento aproximados

- **Lançamento:** **15 de novembro de 2026**, ou seja, 13 dias a partir de 2026-11-02. Foi decidido pelo operador e substitui os "30 dias" do formulário e as datas de 17/08 a 21/09 do briefing de marca.
- **Orçamento:** **2 000 a 5 000 €.**
- **Serviço proposto adicional:** formação em Gutenberg para editores.
