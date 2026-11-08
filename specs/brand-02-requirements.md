# Requisitos de Marca — AISA IPSS

> Subprocesso `branding`, passo 2. Gerado por `brand-02-requirements` em 2026-11-07, em **versão curta**: o foco está no visual e na organização das páginas (diagramação).
> Inputs:
> - `core-01-briefing.md`;
> - `marketing-01-personas.md`;
> - `marketing-02-value-proposition.md`;
> - `brand-01-briefing.md`.
>
> Base reaproveitada: `03-brand/03-brand-requirements.md` (workflow antigo, de 2026-07-04). Desse ficheiro, as restrições continuam válidas. O tom de voz e os exemplos com nomes ficam substituídos pelo `brand-01`.
> Directiva do operador (`brand-01-briefing.md` §0): **o conteúdo mantém-se igual ao do site actual.** O trabalho é o apelo visual e a diagramação, para um site mais humano, mais próximo e mais moderno.

---

## 1. Síntese da marca

**Posicionamento** (`marketing-02-value-proposition.md` §1.1): a AISA é a **IPSS de proximidade, para todas as idades**. Explica com clareza o que oferece a cada fase da vida, mostra quem cuida, presta contas e trata a família com o calor de quem a conhece.

**Proposta de valor** (§2): "Cuidado de confiança para todas as idades, aqui ao lado: percebe em minutos o que serve à sua família, vê quem cuida e fala connosco por telefone, sem pressão."

**Pilares:** Claro · Humano · Transparente sem pressão · Enraizado e vivo.

**Personas** (`marketing-01-personas.md`):

| Persona | Quem é | O que procura | Use cases |
|---|---|---|---|
| **P1 Ana** | Filha cuidadora, 45 a 60 anos | Resposta para o pai | UC1–UC5 |
| **P2 Sofia** | Mãe que trabalha | ATL, férias, CEE e terapias | UC6–UC8 |
| **P3 Teresa** | Vizinha e associada | Agenda, voluntariado, IRS e transparência | UC8–UC13 |

O público sénior lê o site com estas pessoas, por isso aplica-se sempre o requisito R-SEN.

**Personalidade** (o Cuidador):
- **Próxima:** pessoas reais em primeiro plano.
- **Calorosa:** acolhe mais do que informa.
- **Íntegra:** contas e regulamentos à vista.
- **Enraizada:** a terra e as festas fazem parte da identidade.
- **Serena:** nunca alarmista.

**Tom de voz:** o leitor é tratado na 3.ª pessoa, com "você" implícito ("necessita", "a sua casa", "torne-se"). A AISA fala por "nós". O registo é claro, caloroso e sereno.

| ✅ Sim | ❌ Não |
|---|---|
| "Aqui, ninguém é só mais um processo: é alguém que todas as manhãs vem tomar o pequeno-almoço connosco." | "A AISA disponibiliza respostas sociais integradas nas valências de infância e terceira idade." |
| "Estamos disponíveis para o acompanhar em cada passo. Ligue-nos e fazemos as contas consigo." | "Solicita-se o preenchimento do formulário em anexo para efeitos de análise da candidatura." |
| "Consignar parte do seu IRS à AISA não lhe custa nada e ajuda-nos a continuar." | "URGENTE: sem o seu donativo não conseguimos continuar." |

**Nome / tagline:**
- **Nome:** AISA — Associação de Apoio Social Nossa Senhora da Assunção.
- **Tagline:** "Perto de si, a cuidar dos seus — da infância ao envelhecimento." Forma curta: "Perto de si, a cuidar dos seus."

---

## 2. Restrições e não-negociáveis

- **Nome fixo. O logótipo mantém-se tal e qual** (`proto-2/img/logo-aisa.png`). Nenhuma variação altera a forma do símbolo.
- **A identidade aprovada pelo cliente é a base e não se refaz:**
  - `proto-1/04-brand-visual-identity.html`;
  - `proto-1/05-brand-design-system.html`;
  - `proto-1/tokens/`.
- **Paleta:** parte das três cores do logótipo (azul, verde e laranja) e **inclui o sistema de cores por valência:**

  | Cor | Valência |
  |---|---|
  | Azul | SAD |
  | Verde | Centro de Dia |
  | Laranja | Infância e Juventude |
  | Branco | Genérico |
  | Cinza | Cozinha |

- **Acessibilidade:** WCAG 2.1 AA é obrigatório. O público é sénior:
  - corpo de texto com pelo menos 16 px;
  - alvos de clique generosos;
  - contraste verificado por um script de verificação de contraste.
- **Conteúdo:** usa-se o texto do site actual. A diagramação tem de funcionar com esse texto e não pode depender de texto novo. Não se publicam nomes reais de utentes, nem datas ou moradas das novas instalações, nem valores de comparticipação (`marketing-02-value-proposition.md` §4).
- **Plataforma:** WordPress com Gutenberg nativo e tema próprio:
  - CSS nativo com custom properties;
  - **sem Tailwind, shadcn/ui nem frameworks de UI**;
  - sem passos de build obrigatórios.
- **Língua:** português europeu, num só idioma.
- **Imagens:** fotografias reais da AISA (`proto-2/img/facebook/`). Fotografias de utentes só com consentimento; as de crianças, preferencialmente sem rosto identificável.

> **Nota de requisitos:** a directiva "o conteúdo mantém-se" é nova. Transfere para o visual a tarefa de humanizar o site. O texto do site actual é formal e institucional, por isso a proximidade tem de vir da fotografia, dos depoimentos, da cor por valência e do ritmo da página. A fase visual não pode contar com reescrita para o conseguir.

---

## 3. Direcção geral acordada

| Aspecto | Direcção |
|---|---|
| **Centro de gravidade estético** | Quente e humano, com a disciplina de um registo clean e premium. Moderno, mas nunca frio nem corporativo. |
| **Nível de abstracção** | **Literal.** Fotografias reais de pessoas, lugares e festas da terra. Os ícones de traço são apoio e nunca protagonistas. |
| **Admira** | Residências Montepio (fundos claros, muito espaço em branco, testemunhos em destaque); Fundação do Gil (calor); Santa Casa de Lisboa (navegação por ciclo de vida); saintjosephsshankill.ie (ícones de linha). |
| **Rejeita** | O "site de IPSS antigo" (menus densos, clip-art, cores saturadas sem disciplina, aspecto dos anos 2000); o visual carregado e insistente no donativo (CPE); a lista densa de áreas da SCMC. |
| **Estilo da casa** | Moderno, atractivo e minimalista, sempre humano. Já validado pelo cliente em `proto-1/`. |

> **Nota de requisitos:** são direcções a respeitar. As escolhas concretas de tons por valência e de composição das páginas decidem-se em `brand-03` e `brand-04`.

---

## 4. Requisitos de diagramação (organização das páginas)

Requisitos transversais às páginas do site. Dizem **o que cada página tem de conseguir**, e não como fica desenhada.

### 4.1 Regras transversais

- **D1 — Pessoas primeiro.** Cada página de topo (início, cada serviço, Sobre) abre com uma **fotografia real** da AISA, nunca só com texto ou um fundo liso.
- **D2 — Ritmo de leitura.** O texto do site actual parte-se em blocos curtos: título, parágrafo de 3 a 5 linhas, depois imagem, lista, citação ou cartão. Não há paredes de texto. Largura de leitura confortável (cerca de 60 a 75 caracteres).
- **D3 — Cor por valência como sinal de orientação.** Cada página de serviço e cada cartão mostram a cor da sua valência de forma consistente (faixa, etiqueta, ícone ou fundo suave). A cor ajuda a reconhecer a área, mas **nunca é o único sinal**: o nome da valência aparece sempre (WCAG 1.4.1).
- **D4 — Prova humana visível.** Depoimentos com tratamento gráfico de destaque (citação grande, relação e serviço) aparecem no início e nas páginas de serviço com depoimento associado.
- **D5 — Contacto sempre à mão.** O telefone 214 857 700 é clicável e visível no cabeçalho, junto à mensagem principal de cada página de serviço e no rodapé (UC4). *Revisto a 2026-11-08: no cabeçalho, o telefone passa para a barra de topo (por cima do cabeçalho); o botão de destaque do cabeçalho é "Torne-se sócio".*
- **D6 — Organização por necessidade.** A navegação e a página inicial permitem entrar por fase da vida ou por necessidade, e não só pela estrutura da instituição. Respeita-se a decisão final de arquitectura de informação do PRD.
- **D7 — Transparência acessível.** Os documentos aparecem como lista legível, agrupada por tipo e ano, com o formato e o tamanho do ficheiro indicados. Nunca como uma lista solta de ligações.
- **D8 — Calma visual.** Muito espaço em branco, poucas cores por ecrã, sem carrosséis automáticos nem animações chamativas. Respeita-se `prefers-reduced-motion`.
- **D9 — Mobile primeiro.** A P1 partilha páginas por WhatsApp. As páginas funcionam a 360 px sem deslocamento horizontal, e o telefone fica acessível com o polegar.

### 4.2 Modelos de página a prever

O `brand-04` tem de demonstrar estes modelos. A lista final de páginas fecha-se no PRD.

| Modelo | O que tem de conter (requisito, não layout) |
|---|---|
| **Início** | Abertura com fotografia e tagline; acesso por necessidade e valência; provas (30 anos, avaliação no Google, pessoas apoiadas com o ano); depoimento; agenda e notícias recentes; associado, IRS e voluntariado; contacto. *(Já ensaiado em `proto-2/index.html`.)* |
| **Listagem de serviços** | Cartões por valência com a cor, o nome, uma linha de descrição e a ligação. Agrupamento por ciclo de vida ou área, conforme o PRD. |
| **Página de serviço** | Fotografia real; a cor da valência; para quem é; o que inclui; horário e condições de acesso; regulamento ligado; depoimento, se houver; telefone de destaque. Funciona com o texto actual, curto ou longo. |
| **Sobre / Equipa / Órgãos Sociais** | Rostos ou grupos com consentimento; história e território; governança legível. |
| **Documentos** | Ver D7. |
| **Notícias / blog** | Listagem com imagem, data e excerto; artigo com largura de leitura confortável. |
| **Agenda** | Data e local em destaque; separada do blog. |
| **Contactos / Voluntariado** | Formulário acessível; mapa ou morada; horário; telefone. |
| **Páginas legais** | Privacidade, termos, cookies e Declaração de Acessibilidade, num modelo de texto simples. |

---

## 5. Requisitos da identidade visual (`brand-03-visual-identity`)

Modo mínimo: **migra** `proto-1/04-brand-visual-identity.html` para `specs/brand-03-visual-identity.html` e **completa-o**. Não se refaz.

- **Logótipo:** o original, uma versão monocromática e uma versão reduzida (ícone ou favicon). Se já existirem na identidade aprovada, mantêm-se.
- **Paleta (papéis):** mantém-se a aprovada (primária Azul Confiança, acento laranja, verde decorativo, neutros e semânticas). **Acrescenta-se o papel "cor de valência"** para SAD, Centro de Dia, Infância e Juventude, Genérico e Cozinha. Cada uma tem:
  - uma variante de **superfície** (fundo suave);
  - uma variante de **acento** (faixa ou ícone);
  - uma variante de **texto**, quando a cor leva texto.

  Os tons são decisão da fase 3. Se reutilizam o azul, o verde e o laranja do logótipo, ou se têm tons próprios, regista-se a escolha.
- **Tipografia:** mantém-se a aprovada (papéis display e corpo).
- **Iconografia:** ícones de linha coerentes (referência saintjosephsshankill.ie), com um ícone por valência. Ponto de partida: `proto-2/img/linear_icon_*.png`.
- **Imagética:** direcção fotográfica com luz natural, pessoas em actividade e a terra. Inclui o tratamento de imagens do Facebook com qualidade baixa (enquadramento, cantos, sobreposição) e uma alternativa gráfica para quando não houver fotografia com consentimento.
- **Aplicações a demonstrar:**
  - página web (cabeçalho e cartão de serviço com a cor da valência);
  - publicação em redes sociais.

  A papelaria e a sinalética passam para `brand-05`, depois do lançamento.
- **Critérios de aceitação:**
  - existe `specs/brand-03-visual-identity.html`, auto-contido;
  - as 5 cores de valência estão definidas por papel e mostradas em aplicação;
  - cada cor de valência com texto passa AA;
  - os ícones por valência estão definidos;
  - a direcção fotográfica está escrita;
  - nada do que foi aprovado em `proto-1/` foi contrariado.

---

## 6. Requisitos do design system (`brand-04-design-system`)

Modo mínimo: **migra** `proto-1/05-brand-design-system.html` e `proto-1/tokens/` para `specs/` e **completa-os**.

- **Componentes obrigatórios:** os já aprovados (texto, títulos, botões, links, cards, badges e o formulário completo), mais estes:
  - **cartão de serviço com cor de valência**;
  - **bloco de depoimento**;
  - **faixa de provas** (números com o ano);
  - **lista de documentos**;
  - **item de agenda**;
  - **cartão de notícia**;
  - **bloco de contacto ou telefone**;
  - **cabeçalho e navegação** (incluindo mobile);
  - **rodapé**.
- **Modelos de página:** demonstração de pelo menos o **início**, a **página de serviço** (numa valência com texto real do site actual) e a **listagem de serviços**, cumprindo D1 a D9.
- **Estados:** hover, focus visível, disabled e erro (formulários).
- **Tokens:** OKLCH semântico por papel, **sem hex** nos tokens. Juntam-se os tokens de valência (`--color-valencia-sad-*` ou equivalente semântico) e mantém-se `base.css`, `base.json` e `wordpress.md`. Os tokens são consumíveis por `theme.json` e servem para as variações de estilo de bloco no Gutenberg.
- **Modo:** **só claro.** É uma divergência deliberada da regra por omissão (claro e escuro), aprovada pelo cliente.
- **Acessibilidade:** gate de contraste AA a passar com os pares de valência acrescentados a `pairs.json` e `check-contrast.mjs`.
- **Critérios de aceitação:**
  - existem `specs/brand-04-design-system.md`, o HTML de referência e `specs/tokens/`;
  - todos os componentes e modelos acima estão renderizados com os tokens reais;
  - o gate de contraste passa;
  - não há hex nos tokens, nem modo escuro, nem Tailwind ou shadcn.

> **Nota de requisitos:** `proto-2/` é a base da demo estática ao cliente. Os modelos de página do `brand-04` devem convergir com ela, ou substituí-la, para não haver duas fontes visuais.

---

## 7. Requisitos do manual (`brand-05-manual`) — adiado para depois do lançamento

- **Secções:**
  - essência;
  - logótipo (usos correctos e incorrectos, respiro, tamanho mínimo, variações);
  - cores reproduzíveis (HEX, RGB e CMYK), **incluindo as cores por valência**;
  - tipografia;
  - tom de voz com os pares ✅/❌ da §1;
  - aplicações.
- **Público:** a equipa da AISA, sem formação técnica. Sem jargão de código.
- **Formato:** HTML auto-contido, imprimível para PDF.
- **Aplicações:**
  - digitais: website e redes sociais;
  - impressas: cartão, ofício e sinalética do edifício, incluindo a sinalização por valência com a respectiva cor.
- **Critérios de aceitação:** todas as secções e aplicações acima demonstradas, compreensíveis sem formação técnica e prontas a imprimir.
