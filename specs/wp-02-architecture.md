# Arquitectura — Fase 01 (MVP) — Site AISA IPSS

> Ramo B (WordPress), passo 2. Gerado por `wp-02-architecture` em 2026-11-07. Decisões confirmadas pelo operador no mesmo dia:
> - block theme próprio;
> - campos nativos no `aisa-core`;
> - Rank Math para SEO e 301.
>
> Inputs:
> - `wp-01-requirements-phase-01.md`;
> - `core-05-prd.md` (revisão 1);
> - `core-06-legal-requirements.md`;
> - `business-03-risks.md`;
> - `brand-04-design-system.md` + `specs/tokens/`;
> - checklist de segurança do workflow.
>
> **Estado de partida:**
> - `public_html/` tem o WordPress **7.0** instalado de raiz, sem modificações, com os temas Twenty* e os plugins Akismet e Hello Dolly;
> - site actual em https://www.aisaipss.pt: nginx, Yoast SEO, permalinks `/%category%/%postname%/` (categoria `noticias-aisa`), páginas na raiz, documentos em `/wp-content/uploads/AAAA/MM/`.
>
> Este documento não escreve o backlog nem a estrutura detalhada de páginas (`wp-03-business-analyst`), nem implementa nada (`wp-05-developer`).

---

## 1. Tema

**Decisão:** **block theme próprio `aisa`**, sem tema pai, com Full Site Editing.

| Aspecto | Decisão |
|---|---|
| Tipo | Block theme (FSE), WordPress 6.x/7.x: `theme.json` v3, `templates/*.html`, `parts/*.html` e `patterns/*.php` |
| `theme.json` | Gerado a partir de `specs/tokens/base.json` por `specs/scripts/build-theme-json.mjs`, depois fundido com as definições de templates e blocos do tema. `color.custom`, `customGradient`, `defaultPalette` e `defaultGradients` = `false`; `typography.customFontSize` = `false` (paleta e tamanhos restritos, PRD §7 "Editabilidade") |
| CSS | `assets/css/base.css` e `assets/css/components.css`, copiados de `specs/tokens/` sem alterações, enfileirados no front-end e no editor (`add_editor_style`), conforme `specs/tokens/wordpress.md` |
| Fontes | Fraunces e Atkinson Hyperlegible em **woff2 dentro do tema** (`assets/fonts/`), declaradas em `theme.json` → `settings.typography.fontFamilies[].fontFace`. **Nenhum pedido a `fonts.googleapis.com`** (L21) |
| Estilos de bloco | `register_block_style` para as variantes do design system (botões primary, secondary, accent e inverse; secções plain, surface e muted), em vez de classes livres |
| Variações de estilo | Nenhuma (`styles/` vazio). O editor não troca o visual |

**Racional:** o caminho por omissão do workflow é um child theme. O desvio justifica-se assim:
1. O design system (`brand-04`) é **todo próprio**: tokens, componentes e modelos de página. Um tema pai como o Twenty Twenty-Five traria estilos globais, variações de estilo, padrões e fontes que teríamos de anular um a um.
2. **R7, manutenção por terceiros:** uma actualização do tema pai pode mudar o aspecto ou os templates sem ninguém dar conta. Um tema sem pai só muda quando a agência o altera.
3. As variações de estilo do tema pai deixariam a editora sair da paleta, contra o PRD §7 e o KS3.
4. Continua a ser um block theme nativo, não um page builder. A Mercês edita no Gutenberg e o tema não depende de nenhum plugin de construção de páginas.

O tema **não** guarda dados: tipos de conteúdo, taxonomias, campos e blocos dinâmicos ficam no plugin `aisa-core` (§4). Assim, trocar de tema não faz perder conteúdo.

**Restrições de composição** (`core-00-project-map.md` §4), que valem para os padrões e templates:
- secções assimétricas (texto 5fr / imagem 7fr) em vez de grelhas de cartões iguais;
- a fotografia real como elemento dominante;
- títulos em Fraunces grandes, com itálico nos destaques e nos depoimentos;
- `--radius-media` nas fotografias e raios mais contidos nos botões e campos;
- separação por espaço e por fundos de secção, sem sombras nem caixas.

---

## 2. E-commerce

**Inactivo.** A fase 01 não tem carrinho, checkout, catálogo, pagamentos nem donativos online (PRD §3.2, F27; L26). O WooCommerce não é instalado. Se um dia houver donativos online, isto reabre-se por `core-08-change-request`, e com isso a L34 (Diretiva Europeia de Acessibilidade).

---

## 3. Tipos de conteúdo e estrutura de páginas (blueprint de provisionamento)

### 3.1 Tipos de conteúdo (registados no `aisa-core`)

| Tipo | Slug / URL | Suporta | Campos (meta nativa, `register_post_meta` com `show_in_rest`) | Arquivo |
|---|---|---|---|---|
| **Notícia** | `post` nativo; categoria `noticias-aisa`; URL `/noticias-aisa/<slug>/` (mantém-se) | título, editor, excerto, **imagem destacada obrigatória** | — (a valência vai na taxonomia) | `/noticias/` (página de artigos) |
| **Evento** | `aisa_evento`, `/agenda/<slug>/` | título, editor, excerto, imagem destacada | `_aisa_evento_inicio` (data e hora, ISO 8601, obrigatória), `_aisa_evento_fim` (opcional), `_aisa_evento_local` (texto) | `/agenda/`: só eventos futuros, por data ascendente; os passados ficam acessíveis pelo URL próprio, sem listagem |
| **Depoimento** | `aisa_depoimento`, **sem página pública** (`publicly_queryable` = false) | editor (texto), **sem título público nem nome** | `_aisa_dep_relacao` (por ex. "filha de um utente"), `_aisa_dep_data` (mês e ano) | Não tem; só aparece no bloco (§4.3) |
| **Documento** | `aisa_documento`, sem página individual: o URL vai directo ao ficheiro | título | `_aisa_doc_ficheiro` (ID do anexo; o formato e o tamanho calculam-se a partir do anexo), `_aisa_doc_ano` (inteiro) | `/documentos/` (página com o bloco de documentos) |

Os campos usam **meta boxes nativos**, renderizados em PHP, sem ACF e sem passo de build. Validação no gravar: data obrigatória no Evento, ficheiro e ano obrigatórios no Documento.

### 3.2 Taxonomias

| Taxonomia | Termos | Aplica-se a | Uso |
|---|---|---|---|
| `aisa_valencia` | `sad`, `centro-dia`, `infancia`, `generico`, `cozinha` (fixos; a editora não cria termos novos) | `post`, `page`, `aisa_evento`, `aisa_depoimento`, `aisa_documento` | Imprime `data-valencia="<slug>"` no `<body>` ou no cartão; mostra a etiqueta com o nome (WCAG 1.4.1); filtra os regulamentos por serviço |
| `aisa_tipo_documento` | Estatutos, Relatórios e Contas, Regulamentos, Assembleia Geral (L29, G5) | `aisa_documento` | Agrupamento em `/documentos/` |

Saúde e Reabilitação usa `generico` (PRD D4). O `cozinha` só se usa em notícias e na agenda (D2).

### 3.3 Páginas (hierarquia e slugs)

Os slugs actuais **mantêm-se** sempre que a página continua a existir, para reduzir os 301 (R9). Os marcados com ★ são novos.

| Grupo | Página | Slug | Valência |
|---|---|---|---|
| — | Início | `/` (página estática) | — |
| Envelhecimento | Centro de Dia | `/centro-de-dia/` | centro-dia |
| | Serviço de Apoio Domiciliário | `/servico-apoio-domiciliario/` | sad |
| | ★ Novas instalações: Lar e Centro de Dia | `/novas-instalacoes/` | generico |
| Infância e Juventude | AAAF | `/atividades-de-animacao-e-de-apoio-a-familia/` | infancia |
| | CAF | `/componente-de-apoio-a-familia/` | infancia |
| | AEC | `/atividades-de-enriquecimento-curricular/` | infancia |
| Saúde e Reabilitação | Saúde e Reabilitação | a definir no `wp-03` a partir do inventário (o actual `/qualidade-de-vida/` é candidato) | generico |
| Como aceder | Candidaturas · Protocolos | `/candidaturas/` · `/protocolos/` | generico |
| ★ Listagem | Serviços (por fase da vida) | `/servicos/` | — |
| A AISA | Quem Somos · Equipa Técnica · Órgãos Sociais · Parcerias | slugs actuais | generico |
| | ★ Documentos | `/documentos/` | generico |
| Participar | Torne-se Sócio · Apoie a Nossa Causa | slugs actuais | generico |
| | ★ Voluntariado | `/voluntariado/` | generico |
| Topo | Notícias · ★ Agenda · Contactos | `/noticias/` · `/agenda/` · `/contactos/` | — |
| Rodapé | Política de Privacidade · ★ Política de Cookies · ★ Termos · ★ Declaração de Acessibilidade | `/politica-de-privacidade/` · `/politica-de-cookies/` · `/termos/` · `/declaracao-de-acessibilidade/` | — |

A página `/apoio-a-infancia/` e outras do menu actual que desapareçam recebem 301 para a página equivalente. O inventário completo e o mapa de 301 são do `wp-03`.

### 3.4 Templates do tema

| Template | Uso |
|---|---|
| `front-page.html` | Início (padrões: hero com slideshow, provas, serviços, depoimentos, notícias, agenda, participar, contacto) |
| `page.html` | Páginas genéricas: **cabeçalho com imagem** (imagem destacada, ou a imagem por defeito se não houver, F3) |
| `page-servico.html` (template de página escolhido pela editora) | Página de serviço: cabeçalho com imagem e cor da valência, caixa lateral de informação prática, regulamentos, telefone em destaque |
| `home.html` / `archive.html` / `single.html` | Notícias (cartão com imagem dominante; a mais recente em destaque) |
| `archive-aisa_evento.html` / `single-aisa_evento.html` | Agenda |
| `404.html`, `search.html` | Mínimos, com telefone e ligações principais |
| Partes | `header.html` (logótipo, navegação com 2 níveis no máximo, telefone clicável), `footer.html` (informação do prestador L17, legais, "Preferências de cookies", Livro de Reclamações se A9) |

O menu de telemóvel é um painel acessível: botão com `aria-expanded`, foco preso dentro do painel enquanto está aberto, fecho com Esc e devolução do foco ao botão (pendente do `brand-04` §7).

---

## 4. Plugins: mercado vs. desenvolvimento próprio

### 4.1 Decisões por funcionalidade

| Funcionalidade (fase 01) | Decisão | Plugin / componente | Racional |
|---|---|---|---|
| Tipos de conteúdo, taxonomias, campos, blocos dinâmicos (T02–T06) | **Próprio** | `aisa-core` | São poucos campos (no máximo 3 por tipo), por isso não se justifica o ACF (R7, menos plugins). Os dados sobrevivem a uma troca de tema |
| Slideshow acessível (F1) | **Próprio** | bloco `aisa/slideshow` no `aisa-core` | Os sliders de mercado avançam sozinhos, carregam muito e raramente cumprem a WCAG 2.2.2. Este é manual por defeito, com pausa, `prefers-reduced-motion`, primeira imagem prioritária e as outras diferidas |
| Imagem destacada obrigatória nas notícias (F4) | **Próprio** | `aisa-core` (bloqueio da publicação no editor e validação no servidor) | Não há plugin pequeno e mantido para isto |
| GTM com Consent Mode v2 (F13) | **Próprio** | `aisa-core` (snippet com `gtag('consent','default',{…denied})` antes do GTM; ID do contentor numa opção) | São poucas linhas; um plugin de GTM seria superfície de ataque sem ganho |
| Formulários de voluntariado e de contacto (F9, F10) | **Mercado** | **Contact Form 7** | Por omissão **não guarda os envios na base de dados**, e assim cumpre a L5 (só e-mail). Tem integração nativa com o Turnstile, é suportado pelo WP Armour, muito mantido e com marcação acessível (`aria-invalid`, `aria-describedby`). Os campos e textos RGPD (L1–L4) configuram-se nele. A pergunta dos 18 anos é um campo de rádio com texto de ajuda fixo, sem lógica condicional |
| Envio fiável de e-mail (critério 6 do PRD) | **Mercado** | **WP Mail SMTP** (gratuito) | O `mail()` do alojamento cai muitas vezes no spam. Envia por SMTP autenticado da caixa institucional (A6) |
| Anti-spam | **Mercado** | **WP Armour** + **Cloudflare Turnstile** (pela integração do CF7) | Checklist §1. Os dois são invisíveis, sem CAPTCHA visual inacessível (L13, KS3) |
| Consentimento de cookies (F13; L19–L24) | **Mercado** | **Cookiebot** (plugin oficial da Usercentrics) com o modelo GTM / Consent Mode v2 | Decisão do PRD. Bloqueio automático e marcação `data-cookieconsent` nos `iframe` de terceiros (L22); registo do consentimento (L24); declaração de cookies gerada (L15). **Alternativa se a A12 recusar o plano pago:** Complianz (gratuito), com Consent Mode v2, bloqueio de iframes com área reservada e declaração gerada |
| SEO técnico + 301 (F15, F21, PRD §7) | **Mercado** | **Rank Math** (gratuito) | Um só plugin para título e meta, Open Graph, Schema.org, sitemap, **redirecionamentos 301** e monitor de 404 (K9). Importa os metadados do Yoast que vêm na migração. Desactivar: Analytics, Rank Math SEO score no editor e qualquer módulo não listado em §6 |
| Login (checklist §2) | **Mercado** | **Limit Login Attempts Reloaded** + **Two Factor** (plugin oficial, comunidade WordPress) | Limitação de tentativas e 2FA obrigatório para administradores. Ficam de fora o Wordfence e outros "tudo-em-um": demasiado pesados para o risco do site |
| Migração (F16, T10) | **Mercado, temporário** | **WordPress Importer** | Importa o WXR do site actual (A4). **Removido** depois da migração |
| Ligação ao Livro de Reclamações, mapa estático, informação do prestador | **Próprio** | padrões do tema | É conteúdo estático; não precisa de plugin |
| Imagens modernas (PRD §7) | **Nenhum plugin** | Núcleo do WordPress (`srcset`, carregamento diferido, `fetchpriority`, suporte AVIF/WebP no upload) | O tema define os tamanhos de imagem. As imagens migradas são optimizadas antes do upload. Um plugin de conversão só entra se o Lighthouse o exigir (reavaliar no M2) |
| Cache / CDN | **A decidir no `wp-09`** | depende do alojamento (A4) | Não se escolhe sem saber a infra |

**A remover:** Akismet, Hello Dolly e os temas Twenty* não usados (fica só um Twenty* de recurso, ou nenhum).

**Total em produção: 7 plugins de mercado** (CF7, WP Mail SMTP, WP Armour, Cookiebot, Rank Math, Limit Login Attempts Reloaded, Two Factor) **+ 1 próprio** (`aisa-core`). Esta lista é a base do documento de plugins para o fornecedor (F18, R7).

### 4.2 Fase 02 (só para registo; decide-se no `wp-02` da fase 02)

Brevo (plugin oficial ou formulário próprio com dupla confirmação), publicação automática na Meta, filtro por valência.

### 4.3 Blocos do `aisa-core`

Blocos dinâmicos (`block.json` com `render` em PHP). O editor usa `ServerSideRender` em JavaScript simples (`wp.element.createElement`), **sem passo de build** (npm). Isto reduz o tempo e a dependência de ferramentas para quem mantém o site.

| Bloco | Faz | Requisito |
|---|---|---|
| `aisa/slideshow` | Galeria de imagens com texto alternativo; controlos anterior, seguinte e reproduzir/pausa | F1; WCAG 2.2.2 |
| `aisa/proximos-eventos` | N próximos eventos (por defeito 3), filtráveis por valência | F5; UC8 |
| `aisa/depoimentos` | 1 a N depoimentos aleatórios ou fixos; **não mostra nada com menos de 3 publicados** | F6; R1 |
| `aisa/documentos` | Documentos agrupados por tipo e ano, com formato e tamanho, ligações com `download` e evento GA4 | F7; L29; K4 |
| `aisa/regulamentos` | Regulamentos da valência da página actual + Regulamento Geral | F19; Q2 |
| `aisa/telefone` | Telefone clicável `tel:+351214857700` com a classe de evento | F8; K1 |
| `aisa/provas` | Números de destaque (texto editável; o ano é sempre indicado) | F1 |

As restantes secções da página inicial e das páginas de serviço são **padrões** do tema (blocos nativos + estilos de bloco), editáveis pela Mercês.

### 4.4 Versionamento

O Git versiona **só** `wp-content/themes/aisa/`, `wp-content/plugins/aisa-core/` e `wp-content/mu-plugins/`. O núcleo do WordPress, os plugins de mercado, `uploads/` e `wp-config.php` ficam fora do Git (`.gitignore`). As versões dos plugins de mercado ficam registadas na lista de plugins.

---

## 5. Design System: mapeamento aos tokens

| Elemento do design system (`brand-04`) | Concretização no WordPress |
|---|---|
| Cores, tipografia, espaçamento (`base.css`, `base.json`) | `theme.json` gerado (paleta, `fontSizes`, `spacingSizes`), mais `base.css` enfileirado. O tema não redefine valores |
| Componentes do site (`components.css`) | Carregado no front-end e no editor. Os padrões e templates usam as classes: `.site-header`, `.hero`, `.page-header`, `.service-card`, `.news-card(--lead)`, `.testimonial`, `.proof-strip`, `.doc-list`, `.event`, `.contact-block`, `.site-footer`, `.section--*` |
| Valências | Termo `aisa_valencia` → `data-valencia` no `<body>` (páginas e eventos) ou no cartão (listagens) → `--v-accent`, `--v-surface`, `--v-fg` |
| Botões | Estilos do bloco `core/button`: primary (omissão), secondary, accent e inverse. O inverse só aparece dentro de `section--strong-*` |
| Fundos de secção | Estilos do bloco `core/group`: `section--plain`, `--surface` e `--muted` (**Base**, por omissão). **A1 = V2 (2026-11-08):** acrescentam-se `section-strong-blue` e `section-strong-green` — provas e "Participar" no início, depoimentos na página de serviço, "Não sabe qual escolher?" em Serviços (1–2 faixas por página, texto branco, botão invertido) |
| Formulários (CF7) | O CSS do tema aplica os estilos do `components.css` (input, textarea, select, checkbox, radio, ajuda e erro) às classes do CF7. A folha de estilos própria do CF7 é desactivada (`wpcf7_load_css` = false) |
| Cookiebot | Banner com cores e tipografia dos tokens. "Aceitar" e "Rejeitar" com o mesmo estilo (L20). Testado com axe (KS3) |
| Ícones de valência | SVG de linha do `brand-03`, dentro do tema (`assets/icons/`) |
| Imagens por defeito | 1 genérica no MVP (`assets/img/default.jpg`, F20 mínimo); uma por valência na fase 02 |

**Contraste:** qualquer cor nova passa primeiro por `specs/scripts/check-contrast.mjs` e só depois se regenera o `theme.json` (`brand-04` §5).

---

## 6. SEO técnico (camada 1: primeira criação)

| Item | Especificação |
|---|---|
| Permalinks | `/%category%/%postname%/`, a mesma estrutura do site actual: as notícias mantêm `/noticias-aisa/<slug>/`. Páginas na raiz, com os slugs actuais (§3.3). Base de categoria por omissão |
| Redirecionamentos | Rank Math → Redirections: um 301 por URL inventariado que muda (páginas removidas ou fundidas, `/category/noticias-aisa/` → `/noticias/`, anexos). **Os ficheiros de `uploads/` mantêm os caminhos `AAAA/MM/`** (a importação preserva-os), por isso os PDFs não precisam de 301. Monitor de 404 ligado durante 90 dias (K9) |
| Sitemap | Rank Math (substitui o `wp-sitemap.xml` do núcleo). Inclui páginas, notícias e eventos. Exclui depoimentos, documentos (são ficheiros), anexos, autores e etiquetas |
| Título e meta | Título `%título% · AISA`; meta description por página (até 155 caracteres), preenchida no `wp-03`/`wp-05` com o texto actual. Metadados do Yoast importados para as notícias |
| Open Graph / Twitter | Rank Math: título, descrição e imagem (a destacada ou a por defeito, 1200×630) (F21) |
| Schema.org | **NGO** (Organization), com nome, logótipo, morada, telefone e `sameAs` (Facebook, Instagram), pelo Rank Math. **Article** nas notícias (Rank Math). **Event** nos eventos, com JSON-LD gerado pelo `aisa-core` (data, local, organizador). **BreadcrumbList** |
| Canonical | Rank Math, automático |
| `robots` | Staging/local: `noindex` (Definições → Leitura). Produção: indexável. Arquivos de autor, de data e de etiqueta: `noindex` |
| Língua | `<html lang="pt-PT">`, WordPress em português de Portugal (`pt_PT`) |
| Performance | Ver §4.1 (núcleo do WordPress). Alvo: LCP < 2,5 s e CLS < 0,1 em telemóvel **[A CONFIRMAR]** com o alojamento |

O `wp-06-seo-reviewer` verifica esta tabela página a página.

---

## 7. Requisitos de segurança a implementar

### 7.1 Checklist

| # | Item | Estado | Racional / concretização |
|---|---|---|---|
| 1.1 | **WP Armour** | ✔ Aplicável | Anti-spam nos dois formulários do CF7 (L13) |
| 1.2 | **Cloudflare Turnstile** | ✔ Aplicável | Modo invisível ou gerido, pela integração nativa do CF7, nos formulários de contacto e voluntariado. As chaves ficam no `wp-config.php` / na base de dados, fora do Git. Nota: o Turnstile faz um pedido à Cloudflare quando o formulário carrega; é essencial e fica declarado na Política de Cookies e de Privacidade (L14). |
| 2.1 | `DISALLOW_FILE_EDIT` | ✔ Aplicável | `define('DISALLOW_FILE_EDIT', true);` em `wp-config.php` (local e produção) |
| 2.2 | XML-RPC desactivado | ✔ Aplicável | Nenhuma integração da fase 01 o usa (a Meta e o Brevo, na fase 02, usam REST/APIs próprias). `add_filter('xmlrpc_enabled','__return_false')` num mu-plugin, mais o bloqueio de `/xmlrpc.php` no servidor se o alojamento o permitir. Remover o `<link rel="EditURI">` |
| 2.3 | Enumeração de utilizadores bloqueada | ✔ Aplicável | Mu-plugin: `?author=N` → 404; endpoint REST `/wp/v2/users` só para utilizadores autenticados; arquivos de autor desactivados (Rank Math) |
| 2.4 | Limitação de tentativas de login + 2FA | ✔ Aplicável | Limit Login Attempts Reloaded (por ex. 4 tentativas, bloqueio de 20 minutos); Two Factor obrigatório para administradores (TOTP) e recomendado para o Editor |
| 2.5 | Política de actualizações | ✔ Aplicável | Automáticas para as versões menores do núcleo e para as actualizações de segurança dos plugins. Versões maiores do núcleo e dos plugins: revisão manual pelo fornecedor de manutenção (R7), documentada na lista de plugins |
| 3.1 | HTTPS obrigatório | ✔ Aplicável | Redirecção 301 de HTTP para HTTPS no servidor; `siteurl` e `home` em `https://`. Depende do alojamento (A4; PRD P2) |
| 3.2 | HSTS | ✔ Aplicável | `Strict-Transport-Security: max-age=31536000` (sem `preload` no lançamento), no servidor ou num mu-plugin |
| 3.3 | Cabeçalhos adicionais | ✔ Aplicável | `X-Content-Type-Options: nosniff`, `X-Frame-Options: SAMEORIGIN`, `Referrer-Policy: strict-origin-when-cross-origin`, `Permissions-Policy` mínima. **Sem CSP restritiva no MVP**, porque o GTM e o Cookiebot exigem uma lista de domínios que se afina depois (reavaliar na fase 02) |
| 4.1 | Segredos fora do código | ✔ Aplicável | Chaves do Turnstile, credenciais SMTP (WP Mail SMTP com constantes `WPMS_*` em `wp-config.php`) e ID do GTM: em `wp-config.php` ou na base de dados, nunca no Git |
| 4.2 | Salts únicos | ✔ Aplicável | Gerados de novo para a produção (API de salts do wordpress.org), diferentes dos do ambiente local |
| 5.1 | Backup diário (base de dados + `uploads`) | ✔ Aplicável, **a cargo do alojamento** | A manutenção e os backups estão fora do contrato (PRD §3.2). Confirmar com o fornecedor na A4 (frequência diária, retenção ≥ 14 dias, fora do servidor). Se não existir: UpdraftPlus para armazenamento externo (decisão no `wp-09`) |
| 5.2 | Teste de restauro documentado | ✔ Aplicável | Um restauro de teste antes do lançamento (M2), registado no `wp-09-deploy-plan.md` |
| 6.1 | Retenção RGPD | ✔ Aplicável | O CF7 **não guarda** os envios (L5); os dados existem só na caixa de e-mail institucional (A6, L6). O prazo de 12 ou 24 meses aplica-se à caixa de e-mail e é responsabilidade da AISA (procedimento na formação F18). Não há comentários: **comentários desactivados** no site todo. O registo de utilizadores está desactivado |
| 6.2 | Consentimento | ✔ Aplicável | Cookiebot + Consent Mode v2: por omissão `denied` em `analytics_storage`, `ad_storage`, `ad_user_data` e `ad_personalization`; o GA4 só dispara com consentimento (K10). Caixa de consentimento nos formulários, não pré-marcada (L3) |
| 6.3 | Direitos dos titulares | ✔ Aplicável | Ferramentas nativas (Exportar e Apagar dados pessoais) para os poucos dados que existem no WordPress (utilizadores). Os pedidos sobre os formulários tratam-se na caixa de e-mail. O procedimento fica descrito na Política de Privacidade (L14) |
| 7.1 | Validação de uploads | ✔ Aplicável (só na biblioteca, por utilizadores autenticados) | Os formulários públicos **não** aceitam ficheiros. Tipos permitidos à biblioteca: imagens (jpg, png, webp, avif), pdf, svg **não** (o logótipo e os ícones vêm no tema). Tamanho máximo de upload: 10 MB |
| 7.2 | Menor privilégio | ✔ Aplicável | Mercês = **Editor** (publica notícias, eventos, depoimentos e documentos; não instala plugins nem mexe no tema). Agência e fornecedor = Administrador com 2FA. Não há contas partilhadas |

### 7.2 Mitigação técnica dos riscos (`business-03-risks.md`)

| Risco | Medida arquitectural | Ponto da checklist / secção |
|---|---|---|
| **KS1 / R2:** prazo e acessos | Tema próprio sem build (CSS e PHP simples); blocos sem npm; tokens já gerados. Migração e 301 com alternativa pelo rastreio do site público, se a A4 atrasar | §1, §4.3 |
| **KS2 / R4:** consentimento RGPD | O tipo Depoimento **não tem campo de nome** nem página pública; nota com o lembrete do "sim" registado no ecrã de edição | §3.1 |
| **KS3 / R3:** WCAG | Paleta e tamanhos restritos no `theme.json`; slideshow próprio sem avanço automático; CF7 com o estilo do design system; Turnstile invisível; teste axe dos modelos e do banner | §1, §4.1, §5 |
| R1: site parado | O bloco de depoimentos esconde-se com menos de 3; a agenda lista só eventos futuros (nunca mostra eventos passados como próximos) | §4.3 |
| R5: Meta | Fora da fase 01 | §4.2 |
| R6: datas das novas instalações | Página estática sem campos de data | §3.3 |
| R7: manutenção por terceiros | 7 plugins de mercado + 1 próprio; nenhum page builder; sem tema pai; lista de plugins entregue | §4.1, 7.1 #2.5 |
| R8: GA antes do consentimento | Consent Mode v2 com `default denied` antes do GTM; fontes alojadas no tema; mapa estático; iframes bloqueados | 7.1 #6.2; §1; §4.1 |
| R9: URLs partidos | Mesma estrutura de permalinks; slugs mantidos; `uploads/` com os mesmos caminhos; 301 e monitor de 404 no Rank Math | §6 |
| R10, R11: Brevo | Fase 02 | §4.2 |

---

## 8. User Flows

| Fluxo | Percurso técnico | Pontos de medição (GA4, com consentimento) |
|---|---|---|
| **P1 Ana: encontrar um serviço e telefonar** | Pesquisa → `/centro-de-dia/` (template de serviço, `data-valencia="centro-dia"`) → lê, abre o regulamento (bloco `aisa/regulamentos`) → clica no telefone (`aisa/telefone`) ou vai a `/contactos/` (CF7) | `click_tel`, `file_download`, `form_submit` (evento DOM `wpcf7mailsent`) |
| **P2 Sofia: comparar respostas para crianças** | Início → bloco de serviços → `/servicos/` (grupo Infância) → AAAF/CAF/AEC → `/agenda/` → telefone | `click_tel` |
| **P3 Teresa: participar** | Facebook → `/noticias-aisa/<slug>/` → `/agenda/` → `/voluntariado/` (CF7: pergunta dos 18 anos, consentimento) → mensagem de sucesso | `form_submit` (voluntariado) |
| **Encaminhador / empresa: transparência** | `/orgaos-sociais/` → `/documentos/` (bloco `aisa/documentos`) → PDF | `file_download` |
| **Visitante: consentimento** | Primeira visita → banner do Cookiebot (Aceitar / Rejeitar) → sem consentimento: nenhum pedido à Google nem à Meta; mapa estático; iframes com área reservada → "Preferências de cookies" no rodapé reabre o banner | K10 (Tag Assistant) |
| **Mercês: publicar** | wp-admin (Editor, 2FA recomendado) → Notícia: título, texto, imagem destacada (sem ela **não publica**), valência → Evento: data e local obrigatórios → Depoimento: texto e relação → Documento: ficheiro, tipo e ano | — |

---

## 9. Pressupostos e questões em aberto

### Pressupostos

- **AP1:** o alojamento actual (nginx) suporta PHP ≥ 8.1, MySQL/MariaDB actual e HTTPS. Confirma-se com a A4; sem isso, o `wp-09` propõe uma alternativa.
- **AP2:** a A4 inclui um administrador no WordPress actual, para exportar o WXR (artigos e páginas, com os metadados do Yoast) e descarregar `uploads/`. Sem a A4: rastreio do site público, os PDFs listados e as imagens dos artigos.
- **AP3:** o desenvolvimento é **local**, em `public_html/` (WordPress 7.0). A demo do M0 corre localmente ou num staging a definir no `wp-09`.
- **AP4:** o ID do contentor GTM e a propriedade GA4 são criados pela agência em nome da AISA, ou pela AISA (a decidir no `wp-09`).
- **AP5:** a integração do CF7 com o Turnstile está disponível na versão actual do CF7; se não estiver, usa-se o plugin "Simple Cloudflare Turnstile" (mais 1 plugin, a registar).

### Em aberto

| # | Questão | Bloqueia |
|---|---|---|
| A1 | Fundos de secção (Base / V1 / V2) | ✅ Decidido 2026-11-08: **V2 — faixas fortes** |
| A4 | Acessos (WordPress actual, alojamento) | Migração com metadados, 301 completos, `wp-09` |
| A6 | Caixa de e-mail de destino e credenciais SMTP | Teste real dos formulários (critério 6) |
| A9 | Registo no Livro de Reclamações | Só a ligação no rodapé |
| A10 | EPD | Publicação da Política de Privacidade, e portanto o lançamento com formulários |
| A12 | Plano pago do Cookiebot | Escolha final do consentimento (Cookiebot ou Complianz) |
| AQ1 | Quem cria e é dono das contas Cloudflare (Turnstile), GTM e GA4 | Configuração no `wp-05` |
| AQ2 | O fornecedor de alojamento garante os backups diários? | Item 5.1 |
