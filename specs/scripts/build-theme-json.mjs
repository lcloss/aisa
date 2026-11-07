#!/usr/bin/env node
/**
 * build-theme-json.mjs — gera tokens/theme.json (WordPress) a partir de tokens/base.json.
 *
 * base.css / base.json são a fonte da verdade. theme.json NÃO se edita à mão:
 * o WordPress exige hex/rgb nas entradas de paleta, por isso este script converte
 * cada token OKLCH → sRGB hex (mesma conversão do check-contrast.mjs) e copia
 * tipografia e espaçamento. Regenerar a cada alteração de base.json:
 *
 *   node specs/scripts/build-theme-json.mjs
 */
import { readFileSync, writeFileSync } from "node:fs";
import { dirname, join } from "node:path";
import { fileURLToPath } from "node:url";

const root = join(dirname(fileURLToPath(import.meta.url)), "..");
const base = JSON.parse(readFileSync(join(root, "tokens/base.json"), "utf8"));

function oklchToHex(str) {
  const m = str.match(/oklch\(\s*([\d.]+)\s+([\d.]+)\s+([\d.]+)/i);
  if (!m) throw new Error(`não é OKLCH: ${str}`);
  const [L, C, H] = m.slice(1).map(Number);
  const h = (H * Math.PI) / 180, a = C * Math.cos(h), b = C * Math.sin(h);
  const l = (L + 0.3963377774 * a + 0.2158037573 * b) ** 3;
  const mm = (L - 0.1055613458 * a - 0.0638541728 * b) ** 3;
  const s = (L - 0.0894841775 * a - 1.291485548 * b) ** 3;
  const lin = [
    4.0767416621 * l - 3.3077115913 * mm + 0.2309699292 * s,
    -1.2684380046 * l + 2.6097574011 * mm - 0.3413193965 * s,
    -0.0041960863 * l - 0.7034186147 * mm + 1.707614701 * s,
  ];
  return "#" + lin.map(c => {
    const v = Math.abs(c) <= 0.0031308 ? 12.92 * c : 1.055 * Math.abs(c) ** (1 / 2.4) - 0.055;
    return Math.round(Math.min(1, Math.max(0, v)) * 255).toString(16).padStart(2, "0");
  }).join("").toUpperCase();
}

// Só os papéis que fazem sentido escolher no editor (o resto vive no CSS do tema).
const PALETTE = [
  ["background", "Fundo"], ["surface", "Branco"], ["foreground", "Texto"], ["muted", "Cinza claro"],
  ["muted-foreground", "Texto secundário"], ["primary", "Azul Confiança"], ["accent", "Laranja Próximo"],
  ["brand-green", "Verde Enraizado"], ["link", "Ligação"],
  ["valencia-sad-accent", "SAD — acento"], ["valencia-sad", "SAD — superfície"], ["valencia-sad-foreground", "SAD — texto"],
  ["valencia-centro-dia-accent", "Centro de Dia — acento"], ["valencia-centro-dia", "Centro de Dia — superfície"], ["valencia-centro-dia-foreground", "Centro de Dia — texto"],
  ["valencia-infancia-accent", "Infância — acento"], ["valencia-infancia", "Infância — superfície"], ["valencia-infancia-foreground", "Infância — texto"],
  ["valencia-generico-accent", "Genérico — acento"], ["valencia-cozinha-accent", "Cozinha — acento"], ["valencia-cozinha", "Cozinha — superfície"], ["valencia-cozinha-foreground", "Cozinha — texto"],
];

const theme = {
  $schema: "https://schemas.wp.org/trunk/theme.json",
  version: 3,
  $comment: "GERADO por specs/scripts/build-theme-json.mjs a partir de tokens/base.json — não editar à mão. Fonte da verdade: tokens/base.css + base.json (OKLCH). Hex por exigência do WordPress.",
  settings: {
    appearanceTools: true,
    color: {
      custom: false, customGradient: false, defaultPalette: false, defaultGradients: false,
      palette: PALETTE.map(([slug, name]) => {
        if (!base.color[slug]) throw new Error(`token em falta em base.json: color.${slug}`);
        return { slug, name, color: oklchToHex(base.color[slug]) };
      }),
    },
    typography: {
      customFontSize: false, defaultFontSizes: false,
      fontFamilies: [
        { slug: "display", name: "Fraunces (títulos)", fontFamily: base.font.display },
        { slug: "body", name: "Atkinson Hyperlegible (corpo)", fontFamily: base.font.body },
      ],
      fontSizes: Object.entries(base.text).map(([slug, size]) => ({ slug, name: slug, size })),
    },
    spacing: {
      units: ["px", "rem", "%"], defaultSpacingSizes: false,
      spacingSizes: Object.entries(base.space).map(([k, size]) => ({ slug: k, name: `Espaço ${k}`, size })),
    },
    layout: { contentSize: "760px", wideSize: "1240px" },
  },
  styles: {
    color: { background: "var(--color-background)", text: "var(--color-foreground)" },
    typography: { fontFamily: "var(--font-body)", fontSize: "var(--text-body)", lineHeight: "1.6" },
    elements: {
      heading: { typography: { fontFamily: "var(--font-display)", fontWeight: "500" } },
      link: { color: { text: "var(--color-link)" } },
    },
  },
};

writeFileSync(join(root, "tokens/theme.json"), JSON.stringify(theme, null, 2) + "\n");
console.log(`tokens/theme.json gerado: ${theme.settings.color.palette.length} cores, ${theme.settings.typography.fontSizes.length} tamanhos, ${theme.settings.spacing.spacingSizes.length} espaços.`);
for (const p of theme.settings.color.palette) console.log(`  ${p.slug.padEnd(32)} ${base.color[p.slug].padEnd(28)} → ${p.color}`);
