# Demo M0 — fatia 01-05

Verificação local em 08/11/2026, WordPress 7.0 / PHP 8.5, tema AISA 0.2.0.

## Implementação

- Seis templates pedidos, template de serviço atribuível e categoria de padrões AISA.
- Início com fotografia em 7/12 da largura em ecrã largo, até à margem direita;
  imagem antes do texto em telemóvel; três faixas de serviços assimétricas;
  fundos Base alternados e destaques em itálico. Sem alteração dos tokens canónicos.
- Listagem de notícias com uma única consulta herdada, 12 por página, primeiro
  artigo em destaque apenas na primeira página. Imagem nativa, fallback,
  alternativa decorativa e uma ligação por cartão. Etiqueta sem ligação duplicada.
- Serviço com conteúdo, lateral, âmbito verde, regulamentos e depoimentos por valência.
- Agenda herdada do filtro de eventos futuros do aisa-core; início, fim, local,
  valência e ligação de regresso no evento. Dados estruturados reutilizam o plugin.
- Menu principal apresenta os destinos existentes; não apresenta ligações `#`.
- Corrigida a cache de padrões através da versão do tema e da invalidação no seed.
- Marcação estática validada com o parser e os save functions reais do Gutenberg:
  107 tipos nativos registados, 24 padrões/templates examinados, zero blocos inválidos.
  `tabindex` do main é acrescentado no render, preservando a validade editorial.

## Dados locais

`tools/seed-demo.php` é idempotente, exclusivo de WP-CLI. Regista os IDs próprios
na opção `aisa_demo_manifest`, recusa sobrescrever slugs de terceiros, guarda a
origem nos metadados e conserva os uploads originais. Fotografias importadas como
JPEG com variantes responsivas; não há pedidos externos para as imagens.

- Início 65: `show_on_front=page`, `page_on_front=65`.
- Notícias 66: `page_for_posts=66`, `posts_per_page=12`.
- Centro de Dia 67: `page-servico`, `centro-dia`, fotografia da sala.
- Notícias 68–71, com título `[DEMO]` e datas originais.
- Eventos 72–74: 20/11, 18/12 e 21/12/2026; `[DEMO]` e informação por confirmar.
- Quatro slides manuais, fotografias 60, 63, 62 e 64; alternativas descritivas.
- Números médios de 2025: 337 pessoas, 46 no Centro de Dia, 30 apoiadas em casa,
  126 crianças/jovens; ano visível.
- O artigo inicial do WordPress foi colocado em rascunho; ID conservado no manifesto.

O texto do Centro de Dia foi recuperado do índice público de
https://www.aisaipss.pt/centro-de-dia/ e distribuído pelas quatro secções.
Mantém os oito apoios descritos na origem, o horário publicado e o requisito de
associação. Acrescenta a frase Q2 aprovada, sem valores ou tabelas de comparticipação.

## Verificações que passaram

- `tools/verify-demo.php`: definições de leitura, seis templates, atribuição do serviço,
  quatro slides com alternativas, três eventos futuros, fallback sem fotografia,
  secções e frase Q2.
- PHP lint dos patterns, funções, helper e seed; JavaScript e theme.json válidos.
- Navegador headless isolado, sem acesso ao perfil pessoal do Chrome:
  `/`, `/centro-de-dia/`, `/noticias/`, artigo Terapia Ocupacional, `/agenda/`
  e evento Festa de Natal, em **360, 768 e 1280 px**.
- Nas 18 combinações: HTTP 200, um h1, hierarquia sem saltos, sem deslocamento
  horizontal, sem imagens quebradas após carregamento e sem erros JavaScript.
- Slideshow sem avanço automático após 6,5 segundos; botão Seguinte funcional,
  execução com reduced-motion; menu com ciclo de Tab, Escape e foco devolvido.
- Uma ligação, imagem decorativa e `time[datetime]` por cartão; verde e horário na
  lateral do Centro de Dia; três eventos ordenados; início/fim/local no detalhe.
- Em 1280 px, limite direito do slideshow em 1279,98 px; menu horizontal.

Resultados e capturas: `tools/demo-browser-results.json`, `tools/*-360.png`,
`tools/*-768.png`, `tools/*-1280.png`, `tools/demo-interactions.json` e
`tools/demo-native-validation.json`. O LCP nativo está no JSON, sem simulação de
rede/CPU; não equivale a uma medição Lighthouse.

## Pendências explícitas

1. **Depoimentos por preencher**, por indicação do utilizador em 08/11/2026.
   Não foram inventados textos. O padrão é mantido, mas a secção fica oculta
   com menos de três depoimentos publicados. RF09 assinalado no backlog.
2. **Notícias com recuperação parcial e fotografias ilustrativas.** O acesso direto
   à origem falhou; foram usados os textos indexados de Terapia Ocupacional,
   Cozinheira, ATL de Natal e CEE. Cada artigo informa que é demo, liga à origem
   e declara a fotografia provisória. A migração 02-05 deve repor o texto integral
   e a imagem original. As fotografias disponíveis não documentam a infância;
   as alternativas descrevem a imagem real, sem atribuir idades incorretas.
3. **Fontes de T01 ainda em falta:** a pasta só contém README, como na fatia anterior.
   Fraunces/Atkinson estão configuradas, mas o navegador confirma erro nos ficheiros
   locais e usa fallback. Os destaques em itálico estão implementados; a identidade
   tipográfica só ficará efetiva depois de instalar os seis WOFF2 e licenças.
   O importador foi tentado: falta `fontTools`; o acesso externo também está restrito.
4. **axe e Lighthouse não correram:** os comandos da §6 foram tentados, mas npm
   devolveu `EACCES` ao contactar registry.npmjs.org. Não se declara zero erros axe,
   pontuação de acessibilidade ≥95 nem LCP Lighthouse.
5. O Chrome pessoal recusou acesso por Computer Use. A inspeção por headless não
   usa esse perfil; NVDA e revisão humana final permanecem por fazer.

O debug.log histórico foi preservado. Contém avisos anteriores, depreciações do
WP-CLI/PHP 8.5 e uma falha do primeiro teste de fallback, corrigida no teste final.
Não é apresentado como um ficheiro historicamente vazio. A comparação após os
pedidos HTTP finais é registada separadamente.
`tools/demo-http-final.json` confirma os seis HTTP 200 e **0 bytes acrescentados**
ao debug.log nessa ronda final.

Os oito itens pedidos ficam **🔄**, com implementação e verificações locais
registadas na coluna Estado. O gate da skill exige os comandos de verificação
antes de marcar ✅; as auditorias bloqueadas impedem declarar a fatia concluída.

## Repetir

```powershell
wp --no-color --path=public_html eval-file public_html/wp-content/themes/aisa/tools/seed-demo.php
wp --no-color --path=public_html eval-file public_html/wp-content/themes/aisa/tools/verify-demo.php
node public_html/wp-content/themes/aisa/tools/verify-demo.cjs
node public_html/wp-content/themes/aisa/tools/verify-interactions.cjs
```

Os testes de navegador usam o Playwright e o headless shell já instalados neste
ambiente; os caminhos constam dos scripts. Repetir axe e Lighthouse da §6 quando
o acesso npm estiver disponível, depois de instalar as fontes locais.
