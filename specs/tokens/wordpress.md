# AISA — Consumo dos tokens num tema WordPress

Como usar os tokens do AISA (`base.css`) num tema WordPress próprio, **sem
bibliotecas externas** — sem Tailwind, sem shadcn/ui, sem frameworks de UI.
Apenas CSS nativo (custom properties) e os componentes do design system
(`brand-04-design-system.html`).

> Regra de ouro: `base.css` é a **fonte da verdade única**. O tema não redefine
> nenhum valor de cor/tipo/raio/sombra — consome as variáveis `--color-*`,
> `--text-*`, `--space-*`, etc. por referência.

## 1. Enfileirar os tokens no tema

Copia `tokens/base.css` para o tema (ex.: `assets/css/base.css`) e enfileira-o
**antes** da folha de estilos do tema:

```php
// functions.php
add_action( 'wp_enqueue_scripts', function () {
    wp_enqueue_style(
        'aisa-tokens',
        get_theme_file_uri( 'assets/css/base.css' ),
        array(),
        wp_get_theme()->get( 'Version' )
    );
    wp_enqueue_style(
        'aisa-theme',
        get_stylesheet_uri(),
        array( 'aisa-tokens' ), // depende dos tokens
        wp_get_theme()->get( 'Version' )
    );
} );
```

Para o editor de blocos ver as mesmas cores/tipos que o front-end:

```php
add_action( 'after_setup_theme', function () {
    add_theme_support( 'editor-styles' );
    add_editor_style( array( 'assets/css/base.css', 'assets/css/components.css' ) );
} );
```

## 2. `theme.json` (gerado — paleta, tipografia e espaçamento do editor)

`tokens/theme.json` é **gerado** a partir de `tokens/base.json` por
`scripts/build-theme-json.mjs`. Não se edita à mão: `base.css`/`base.json`
continuam a ser a fonte da verdade.

- O WordPress exige hex/rgb nas entradas de paleta, por isso o script converte
  cada token OKLCH para hex (mesma conversão do `check-contrast.mjs`). É o único
  artefacto com hex, por exigência da plataforma.
- Paleta do editor: neutros, primária, laranja, verde, ligação e as cores por
  valência (acento, superfície e texto). Os fundos de secção do teste (V1/V2)
  **não** entram enquanto não forem aprovados.
- `styles` aponta para as variáveis do base (`var(--color-*)`, `var(--font-*)`).
- Regenerar a cada alteração de `base.json`:

```bash
node scripts/build-theme-json.mjs
```

Copiar `tokens/theme.json` para a raiz do tema (fundir com as definições de
blocos/templates do ramo `wp-*`).

> **Nota:** o verde (`--color-brand-green`) e os acentos de valência são
> decorativos — nunca carregam texto. Estão na paleta para faixas e
> separadores; o tema deve restringi-los a fundos/bordas nos blocos.

## 2b. Valências no tema

Aplica-se a valência com `data-valencia="sad|centro-dia|infancia|generico|cozinha"`
num ancestral (corpo da página de serviço, cartão, item de agenda).
`components.css` define `--v-accent`, `--v-surface` e `--v-fg` a partir dele.
No WordPress: campo (ACF ou taxonomia) "Valência" no serviço/artigo, impresso
como atributo no template. A imagem destacada é **obrigatória** nos artigos
(cartão de notícia) e nas páginas (cabeçalho com imagem); sem ela, o template
usa a imagem por defeito da valência.

## 3. Componentes (CSS próprio do tema)

As classes de componentes vivem em `tokens/components.css` (componentes do site: cabeçalho, hero com slideshow, cabeçalho de página, cartões de serviço e notícia, depoimento, provas, documentos, agenda, contacto, rodapé, secções). Os componentes base vêm do bloco `<style>` de
`brand-04-design-system.html`: `.btn` (+ variantes), `.card`, `.badge`,
`.alert`, e os componentes de formulário (`.field`, `.input`, `.textarea`,
`.select`, `.check-row`, fieldsets, estados de erro).

```css
/* exemplo — botão primário do tema, só com tokens */
.btn-primary {
  background: var(--color-primary);
  color: var(--color-primary-foreground);
  border-radius: var(--radius-md);
  font-family: var(--font-body);
}
.btn-primary:hover { background: var(--color-primary-hover); }
.btn-primary:focus-visible { outline: none; box-shadow: var(--focus-ring); }
```

## 4. Formulários no WordPress

Os formulários de admissão/contacto usam a marcação e as classes demonstradas
na secção **Formulários** de `brand-04-design-system.html`. Se o ramo
`wp-*` escolher um plugin de formulários, o CSS do tema deve
sobrepor-se aos estilos do plugin para que os campos usem os tokens
(`--color-input`, `--focus-ring`, estados de erro com `--color-destructive`)
— o aspecto final tem de ser indistinguível do design system.

## 5. Validação

Antes de entregar qualquer alteração de cor, corre o gate de contraste:

```bash
node scripts/check-contrast.mjs --tokens tokens/base.css
node scripts/check-contrast.mjs pairs.json
node scripts/build-theme-json.mjs   # regenera theme.json
```

## Tema `aisa`: gerado + extras

O `public_html/wp-content/themes/aisa/theme.json` é uma fusão manual do ficheiro gerado `specs/tokens/theme.json` com os extras de `wp-04-01-02` §3: layout 720/1200 px, `fontFace` local, restrições de blocos e template parts. A origem está documentada no `$comment` do tema. Ao regenerar, preservar os extras e não alterar a paleta nem as escalas geradas.
