# Guia do editor: site AISA

> Instruções para quem edita o site no painel do WordPress: classes CSS, convenções e cuidados.
> Este documento é vivo. Vai sendo completado ao longo do build e serve de base à formação da Mercês (backlog T15) e ao `core-10-documentator`.

## 1. Menu principal (cabeçalho)

**Onde se edita:** Aparência → Editor → Navegação → "Menu" (ou, no editor do site, o modelo *Cabeçalho* → bloco Navegação).
**Onde se põe a classe:** seleciona o item → painel lateral → **Avançado** → **Classe(s) CSS adicionais**. Se houver várias classes, separa-as com um espaço.

### 1.1 Classes do mega-menu "Serviços"

| Classe | Onde se põe | Efeito |
|---|---|---|
| `aisa-services` | No item de topo "Serviços" (submenu) | Faz do submenu um mega-menu com a largura do cabeçalho, até 4 colunas. **Só um item deve ter esta classe.** |
| `aisa-nav-group` | Num submenu dentro de "Serviços" (ex.: Envelhecimento) | Mostra-o como **coluna**: o título fica em cima, a negrito e com ícone, e os subitens ficam por baixo. **O título não é ligação**: para ligar à página do grupo, acrescenta-a como primeiro subitem. |
| `aisa-nav-solo` | Numa ligação simples dentro de "Serviços" (ex.: Saúde e Reabilitação) | Mostra-a como coluna só com título, que neste caso é ligação. Se lhe acrescentares subitens, o tema converte-a automaticamente em `aisa-nav-group` (filtro `aisa_nav_solo_to_group` em `functions.php`). |
| `aisa-nav-all` | Na última ligação de "Serviços" ("Ver todos os serviços") | Mostra-a em toda a largura, no fundo do mega-menu, com uma seta. |

### 1.2 Ícone da coluna (põe-se junto com `aisa-nav-group` ou `aisa-nav-solo`)

| Classe | Ícone |
|---|---|
| `aisa-icon-centro-dia` | Envelhecimento / Centro de Dia |
| `aisa-icon-infancia` | Infância e Juventude |
| `aisa-icon-generico` | Genérico (Saúde e Reabilitação, Como aceder) |

Exemplo, coluna "Saúde e Reabilitação" com subitens: `aisa-nav-group aisa-icon-generico`.

> Os ficheiros `sad.svg` e `cozinha.svg` existem em `assets/icons/`, mas ainda não há classes `aisa-icon-sad` / `aisa-icon-cozinha`. Se forem precisas, têm de ser acrescentadas ao `theme.css`.

### 1.3 Regras e cuidados

- O mega-menu tem **4 colunas** em ecrã largo. Uma 5.ª coluna passa para uma segunda linha.
- Os outros submenus de topo (A AISA, Participar) **não levam classe**: abrem como lista simples.
- No telemóvel, todos os grupos funcionam como acordeões, com ou sem classe.
- Máximo de 2 níveis abaixo de "Serviços" (grupo → página). Não cries submenus dentro de subitens.
- Depois de guardar, confirma o resultado no site em ecrã largo e em ecrã de telemóvel.
- **No editor**, o Cabeçalho aparece "fechado", como no site. Os submenus abrem quando seleccionas o item (ou passas o rato por cima). O botão "Fechar menu", o telefone do menu de telemóvel e a caixa de pesquisa estão escondidos no editor, porque no site só aparecem nesses estados (CSS em `assets/css/editor.css`). Para os alterar, usa a vista de lista (ícone ☰ no topo do editor).

### 1.4 Botão de destaque e "Participar"

- O botão laranja do cabeçalho diz **"Apoie a nossa causa"** e liga a `/apoie-a-nossa-causa/`. Está no modelo *Cabeçalho* (bloco HTML com a classe `site-header__cta`), não no menu.
- O menu **Participar** mantém-se: Torne-se Sócio, Apoie a Nossa Causa e Voluntariado.
- A página "Apoie a Nossa Causa" tem três secções com âncoras fixas, que podem ser usadas em ligações e itens de menu:
  `/apoie-a-nossa-causa/#donativos`, `#consignacao-irs` e `#voluntariado`. **Não mudes estas âncoras** (bloco Título → Avançado → Âncora HTML).

## 2. Breadcrumbs

- O bloco **AISA › Breadcrumbs** (`aisa/breadcrumbs`) mostra o caminho até à página: `Início › Serviços › Centro de Dia`, `Início › Notícias › <título>`, `Início › Agenda › <evento>`.
- Já está incluído nos modelos (página, página de serviço, notícia, evento, listagens). Não é preciso pô-lo no conteúdo das páginas.
- As páginas com o modelo *Página de serviço* aparecem automaticamente debaixo de "Serviços". As outras páginas seguem a página-mãe (Atributos da página → Superior).
- Quando o Rank Math for instalado (spec `wp-04-02-06`), o bloco passa a usar o breadcrumb do Rank Math, que acrescenta o Schema BreadcrumbList.

## 3. Modelos de página

| Modelo | Quando usar | Notas |
|---|---|---|
| **Página de serviço** (`page-servico`) | Páginas de cada serviço (Centro de Dia, SAD, AAAF…) | Barra do cartão com a cor da valência (taxonomia *Valência*). Escolhe sempre a valência certa e uma imagem destacada. Aparece em "Início › Serviços › …". |
| **Página** (`page`) | Restantes páginas institucionais | Barra do cartão no azul da marca. |

**Padrão único de cabeçalho em todo o site:** imagem no topo (a imagem destacada ou, se não houver, a imagem por defeito) e, por cima, um cartão com o breadcrumb, a etiqueta de valência (só em serviços, notícias e eventos) e o título. Isto vale para páginas, serviços, notícias, eventos e as listagens de Notícias e Agenda (nestas, a barra é sempre azul). A página inicial tem um *hero* próprio. Não recries o cabeçalho dentro do conteúdo da página: ele vem do modelo.
