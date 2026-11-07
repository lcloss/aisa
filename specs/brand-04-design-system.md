# Design System — AISA IPSS

> Subprocesso `branding`, passo 4. Gerado por `brand-04-design-system` em 2026-11-07, em **modo mínimo**.
> - **Base:** o design system aprovado pelo cliente (`proto-1/05-brand-design-system.html` + `proto-1/tokens/`), migrado para `specs/`.
> - **Acrescentado:** o que pedem `brand-02-requirements.md` §6 e `brand-03-visual-identity.html`, incluindo o feedback do cliente.
> - **Teste de fundos de secção:** pedido pela cliente, em 2 versões. Ainda não está aprovado.

---

## 1. Ficheiros

| Ficheiro | Papel |
|---|---|
| `specs/tokens/base.css` | **Fonte da verdade.** CSS custom properties em OKLCH, nomes semânticos, só modo claro. Funciona sem framework e sem build. |
| `specs/tokens/base.json` | Os mesmos tokens em JSON. Bate certo com `base.css`: verificado a 2026-11-07, as 63 cores coincidem. |
| `specs/tokens/components.css` | Camada de componentes **personalizada**. Usa só `var(--…)` de `base.css`, sem valores de cor próprios. |
| `specs/tokens/theme.json` | **Gerado** a partir de `base.json` por `scripts/build-theme-json.mjs`, para o WordPress. Não se edita à mão. |
| `specs/tokens/wordpress.md` | Guia de consumo num tema WordPress: enfileiramento, `theme.json`, valências, formulários. |
| `specs/brand-04-design-system.html` | Referência visual: tokens, componentes base (aprovados) e componentes do site (novos). |
| `specs/brand-04-modelos-pagina.html` | Modelos de página (início, serviço, listagem) com o **painel de teste de fundos** (Base / V1 / V2). |
| `specs/pairs.json` + `specs/scripts/check-contrast.mjs` | Verificação de contraste WCAG AA. |
| `specs/scripts/build-theme-json.mjs` | Gerador do `theme.json`. |

**Escolha de framework:** componentes **personalizados**, consumidos por um tema WordPress, com `theme.json` gerado. Já estava fixado no `brand-02` §2: sem Tailwind, sem shadcn/ui, sem build obrigatório.

**Modo:** **só claro.** É uma divergência deliberada da regra por omissão (claro e escuro), aprovada pelo cliente: o público sénior valoriza a previsibilidade. Por isso não há tokens de modo escuro nem pares escuros a validar.

---

## 2. Tokens

### 2.1 Cor (herdada, aprovada)

| Papel | Token | OKLCH | Origem (`brand-03`) |
|---|---|---|---|
| Fundo | `--color-background` | `oklch(0.991 0.003 84.6)` | Branco quente |
| Superfície | `--color-surface` | `oklch(1 0 0)` | Cartões |
| Texto | `--color-foreground` | `oklch(0.285 0.004 84.6)` | Antracite |
| Muted / texto secundário | `--color-muted` / `--color-muted-foreground` | `oklch(0.962 0.004 84.6)` / `oklch(0.518 0.010 84.6)` | Cinzento morno |
| Primária | `--color-primary` (+ hover/active) | `oklch(0.548 0.136 248.2)` | Azul Confiança (#1B75BC do logótipo) |
| Acento | `--color-accent` | `oklch(0.687 0.166 51.4)` | Laranja Próximo (texto antracite) |
| Verde | `--color-brand-green` | `oklch(0.606 0.155 147.5)` | Verde Enraizado, só decorativo |
| Ligação | `--color-link` | `oklch(0.45 0.12 248.2)` | Azul aprofundado |
| Estados | `--color-success/warning/destructive` (+ `-soft`) | ver `base.css` | Semânticas |

### 2.2 Valências (novo)

Cada valência tem três tokens. O `data-valencia` define `--v-accent`, `--v-surface` e `--v-fg` para os componentes.

| Valência | `-accent` (faixa, ícone; decorativo) | superfície (`--color-valencia-X`) | `-foreground` (texto) | Contraste do texto |
|---|---|---|---|---|
| SAD (azul) | `oklch(0.548 0.136 248.2)` | `oklch(0.954 0.014 244.7)` | `oklch(0.462 0.113 247.9)` | 6,16:1 |
| Centro de Dia (verde) | `oklch(0.606 0.155 147.5)` | `oklch(0.959 0.017 153.6)` | `oklch(0.467 0.117 148.1)` | 5,85:1 |
| Infância e Juventude (laranja) | `oklch(0.687 0.166 51.4)` | `oklch(0.963 0.019 60.2)` | `oklch(0.502 0.125 50.4)` | 5,59:1 |
| Genérico (branco) | `oklch(0.768 0.014 82.4)` | `oklch(1 0 0)` | `oklch(0.285 0.004 84.6)` | 14,35:1 |
| Cozinha (cinza) | `oklch(0.619 0.013 79.8)` | `oklch(0.947 0.006 84.6)` | `oklch(0.399 0.008 75.3)` | 7,93:1 |

**Derivação:** os acentos azul, verde e laranja são as cores do logótipo, convertidas sem alterações. A superfície é a mesma tonalidade com L de cerca de 0,95 e croma baixo. O texto é a mesma tonalidade com L entre 0,46 e 0,50, até passar AA. Genérico e Cozinha usam o neutro morno da paleta.

**Regras:**
- O acento nunca leva texto.
- A cor vem sempre com o nome da valência (WCAG 1.4.1).
- **Os botões não mudam por valência:** usam sempre a primária ou o acento laranja.

### 2.3 Fundos de secção — TESTE (não aprovado)

| Versão | Tokens | Uso |
|---|---|---|
| **Base** (aprovada) | `background`, `surface`, `muted` | Alternância de branco quente, branco e cinza muito claro. A cor vive nas fotografias, faixas e etiquetas. |
| **V1 — Tons suaves** | `--color-section-sky` `oklch(0.935 0.028 245)` · `-sage` `oklch(0.940 0.032 150)` · `-sand` `oklch(0.950 0.030 70)` | Tintas claras das três cores do logótipo em secções alternadas. Texto antracite; botões, ligações e texto secundário mantêm-se. |
| **V2 — Faixas fortes** | `--color-section-strong-blue` `oklch(0.43 0.12 248.2)` · `-strong-green` `oklch(0.45 0.11 150)` · `-strong-orange` `oklch(0.72 0.15 55)` (cada um com `-foreground`) | No máximo **1 a 2 faixas fortes por página** (provas, "Participar", "Não sabe qual escolher?"); o resto fica como na base. Texto branco (antracite no laranja). **Só se usa o `.btn-inverse`:** o botão laranja sobre azul ou verde forte falhou o mínimo de 3:1 (2,72 e 2,38) e ficou proibido. |

> **Nota de design:** a preocupação do operador (cor nos fundos pode prejudicar o aspecto) está considerada nas duas versões:
> - **V1** dá cor sem competir com as fotografias;
> - **V2** limita a cor forte a poucas faixas, para não cair no "site de IPSS antigo" rejeitado no `brand-02` §3.
>
> Recomendação: mostrar as três em `brand-04-modelos-pagina.html` e decidir com a cliente. Se ela escolher V1 ou V2, os tokens já existem e passam AA; basta trocar as classes `section--*` nos modelos. Se escolher a base, os tokens `--color-section-*` removem-se.

### 2.4 Escalas não cromáticas

| Escala | Tokens |
|---|---|
| Espaçamento | `--space-1` a `--space-9` (4 a 96 px, base 4) |
| Raios | `--radius-sm` 6 · `md` 10 · `lg` 12 · `xl` 16 · `full`, e `--radius-media` 14 px nas fotografias (novo) |
| Sombras | `--shadow-sm/md/lg`, quentes e suaves |
| Tipografia | Fraunces (títulos, peso até 500) + Atkinson Hyperlegible (corpo 18 px, mínimo 16 px). Escala de `--text-caption` 16 px a `--text-display` 60 px |
| Composição (novo) | `--hero-min-height` 440 px · `--hero-text-ratio` 5fr / `--hero-media-ratio` 7fr (imagem com cerca de 60% da largura) · `--page-header-height` 300 px (200 px em telemóvel) · `--news-media-ratio` 16/10 |

---

## 3. Componentes

### 3.1 Base (aprovados em `proto-1`, sem alterações)

- Botões: primary, secondary, accent, destructive e ghost.
- Ligações.
- Formulário completo: input, textarea, select, checkbox, radio, fieldset, campo obrigatório, ajuda e erro.
- Cards, badges e alertas.

**Estados:** hover, focus visível (`--focus-ring`), disabled e erro.

### 3.2 Site (novos, em `components.css`)

| Componente | Classe | Requisito que cumpre |
|---|---|---|
| Cabeçalho do site | `.site-header`, `.site-nav` | Telefone sempre visível (D5); menu compacto em telemóvel |
| **Hero com slideshow** | `.hero`, `.slideshow` | Feedback do cliente: texto ao lado, slideshow **maior** (cerca de 60% e pelo menos 440 px, até à margem direita). Passagem manual por defeito; o botão de reprodução vira pausa (WCAG 2.2.2); respeita `prefers-reduced-motion`; em telemóvel a imagem vem primeiro |
| **Cabeçalho de página com imagem** | `.page-header` | Feedback do cliente / D1: todas as páginas abrem com fotografia; cartão branco com a faixa da cor da valência |
| Etiqueta / ícone de valência | `.valencia-tag`, `.valencia-icon` | D3: cor e nome |
| Cartão de serviço | `.service-card` | Fotografia 4:3, faixa da valência, ligação "Saber mais" |
| **Cartão de notícia** | `.news-card`, `.news-card--lead` | Feedback do cliente: **imagem dominante** (16:10; a notícia mais recente ocupa 2 colunas com a imagem a 60%) |
| Depoimento | `.testimonial` | D4: citação grande, assinatura por relação e serviço, sem nomes |
| Faixa de provas | `.proof-strip` | 30 anos, média de pessoas apoiadas com o ano, avaliação no Google, documentos |
| Lista de documentos | `.doc-group`, `.doc-list` | D7: agrupada, com formato e tamanho do ficheiro, alvo de toque ≥ 56 px |
| Item de agenda | `.event` | Data em destaque com a cor da valência |
| Bloco de contacto | `.contact-block` | D5: telefone grande e clicável |
| Rodapé | `.site-footer` | Ligações legais, incluindo a Declaração de Acessibilidade |
| Secções | `.section`, `.section--plain/surface/muted` (+ `sky/sage/sand/strong-*` do teste) | Ritmo vertical e fundos |
| Botão invertido | `.btn-inverse` | Único botão permitido sobre `section--strong-*` |

### 3.3 Modelos de página (`brand-04-modelos-pagina.html`)

| Modelo | Conteúdo |
|---|---|
| **Início** | Hero com slideshow; provas; serviços; depoimento; notícias; agenda; participar (associado, IRS, voluntariado); contacto |
| **Página de serviço** (Centro de Dia) | Cabeçalho com imagem; para quem é; o que inclui; fotografia; como aceder com o regulamento; caixa lateral de informação prática; depoimento; contacto |
| **Listagem de serviços** | Cabeçalho com imagem; grupos por fase da vida com ícone e cartões; apelo final "Não sabe qual escolher?" |

O texto é ilustrativo. No site usa-se o texto actual (`brand-01` §0).

**Verificação visual (Chrome, 2026-11-07):**
- Ecrã largo, nas três versões de fundo.
- Telemóvel a 390 px, nos três modelos: sem deslocamento horizontal.
- Correcções feitas durante a verificação:
  - **logótipo:** ficava espremido no cabeçalho;
  - **cabeçalho:** não cabia em 390 px e passou a ter versão compacta abaixo de 560 px;
  - **números de destaque:** passaram a 2 colunas;
  - **topo da página inicial:** o texto ficava desalinhado em ecrãs muito largos e a slideshow passou a ir até à margem direita.

---

## 4. Validação de contraste (WCAG AA)

Os dois testes saíram com código 0 a 2026-11-07:

```bash
node specs/scripts/check-contrast.mjs --tokens specs/tokens/base.css   # 22 pares *-foreground
node specs/scripts/check-contrast.mjs specs/pairs.json                 # 41 pares adicionais
```

| Grupo | Rácios mínimos obtidos |
|---|---|
| Texto, texto secundário e ligação sobre o fundo e a superfície | 13,99 · 5,41 · 7,23 |
| Primária, acento e estados com o respectivo `-foreground` | 4,84 a 7,01 |
| Texto de valência sobre a superfície | 5,59 (Infância) a 14,35 (Genérico) |
| Texto secundário sobre as superfícies de valência | 4,76 a 4,98 |
| V1: texto, texto secundário e ligação sobre as tintas | 11,90 · 4,61 · 6,15 (sky, o pior caso) |
| V1: botão primário sobre as tintas (UI, 3:1) | 4,04 a 4,20 |
| V2: texto sobre as faixas fortes | 8,07 (azul) · 7,07 (verde) · 5,54 (laranja) |
| V2: botão invertido (UI e texto) | 7,07 a 8,07 |

Pares **excluídos por regra:** o botão laranja sobre faixa forte azul ou verde (2,72 e 2,38) é proibido; usa-se `.btn-inverse`. Os acentos de valência são decorativos, por isso não são avaliados como texto.

---

## 5. Uso

1. **CSS puro:** `<link rel="stylesheet" href="tokens/base.css">` e depois `<link rel="stylesheet" href="tokens/components.css">`.
2. **Valência:** `data-valencia="centro-dia"` no contentor (página de serviço, cartão, evento). Os componentes lêem `--v-*`.
3. **Fundos:** `<section class="section section--muted">…`. Os fundos `sky/sage/sand/strong-*` só se usam depois da decisão do cliente.
4. **WordPress:** ver `tokens/wordpress.md`. Copiar `base.css` e `components.css` para o tema (base primeiro) e `theme.json` para a raiz do tema. Regenerar com `node specs/scripts/build-theme-json.mjs`.
5. **Alterar uma cor:** editar `base.css` **e** `base.json`, correr os dois testes de contraste e regenerar o `theme.json`.

---

## 6. Consumo pelas fases seguintes

| Consumidor | Lê |
|---|---|
| Ramo WordPress (`wp-02-architecture`, `wp-05-developer`) | `tokens/theme.json`, `tokens/base.json`, `tokens/base.css`, `tokens/components.css`, `tokens/wordpress.md` |
| Demo estática (`proto-2/`) | Deve convergir para `brand-04-modelos-pagina.html` e para estes tokens, para não haver duas fontes visuais (`brand-02` §6) |
| `brand-05-manual` (depois do lançamento) | Os valores finais (incluindo valências) para a secção de cor do manual, em HEX/RGB/CMYK |
| Referência visual | `brand-03-visual-identity.html` |

---

## 7. Pendentes

- **Decisão da cliente sobre os fundos:** Base, V1 ou V2. Depois, limpar os tokens não usados e fixar as classes nos modelos.
- **Cor de "Saúde e Reabilitação":** o sistema de valências do cliente não a define. Nos modelos está como **Genérico**. A confirmar com o cliente.
- **Imagens por defeito por valência** (para notícias e páginas sem fotografia): escolher no arquivo da AISA.
- **Menu de telemóvel:** o botão "Menu" é só ilustrativo. O comportamento (painel ou gaveta, foco, tecla Esc) define-se no ramo `wp-*`.
