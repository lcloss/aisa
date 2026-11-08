# Verificação da fatia 01-02 — 2026-11-07

Tema próprio AISA 0.1.0 ativo em WordPress 7.0 / pt_PT.
O core em falta foi reposto a partir da cópia local de WordPress 7.0 em
C:/var/www/WordPress/wordpress; a configuração e os uploads existentes foram preservados.

Passou:
- `wp --path=public_html theme list --status=active`: aisa, active, 0.1.0.
- PHP lint em functions.php e nos três patterns; `node --check assets/js/nav.js`.
- JSON válido; paleta, tamanhos tipográficos e espaçamentos iguais aos gerados.
- SHA-256 de base.css e components.css igual ao original (cópias literais).
- HTTP de página sem imagem e 404: pt-PT, um h1, main#conteudo,
  skip link primeiro, 0 referências a fonts.googleapis.com / fonts.gstatic.com.
- Página sem imagem destacada: assets/img/default.jpg (1600 × 900).
- Os quatro templates são reconhecidos pelo WordPress; menu com todos os grupos
  e nomes por extenso. O controlador interativo nativo é retirado deste menu
  para não disputar aria-expanded com nav.js.
- Novo pedido HTTP depois da correção do JSON: sem novos avisos no debug.log.
  O log conserva depreciações anteriores do WP-CLI em PHP 8.5 (cli/Colors.php)
  e os avisos já corrigidos; não foi apagado.

Por concluir, sem aprovação de conclusão:
- Fontes WOFF2 e licenças OFL: o acesso a raw.githubusercontent.com está bloqueado.
  As seis declarações fontFace locais estão preparadas. A pasta assets/fonts ainda
  não contém os binários. O browser usa as fontes de fallback entretanto.
  Com rede, instalar `fonttools[woff]` e correr `python tools/fetch-fonts.py`.
  O importador converte os TTF oficiais completos para WOFF2 (Latin + Latin Extended).
- `npx --yes @axe-core/cli http://aisa.wordpress.test/?page_id=2 --exit`:
  EACCES ao contactar registry.npmjs.org; não correu a auditoria. Repetir também no 404.
- Verificação visual e manual: 360/768/1280 px, zoom 200%, Tab, foco preso,
  acordeões, Escape e devolução do foco. A ferramenta recusou acesso ao Chrome.
- screenshot.png deve ser capturado depois da instalação das fontes e da revisão visual.

O overlay nativo não foi adotado: nav.js acrescenta um painel modal ao
core/navigation com telefone no topo, acordeões por grupo, inert nos restantes
ramos do documento, ciclo de Tab, Escape e bloqueio de scroll.

T01, G01, G03, G04 e G06 ficam em curso enquanto o gate da §6 não estiver completo.
G02 fica em curso até à fatia 02-04; NIPC e morada mantêm [A CONFIRMAR — BQ2].
