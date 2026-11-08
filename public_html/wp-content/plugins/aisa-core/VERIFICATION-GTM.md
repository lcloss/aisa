# Verificação — fatia 02-01

Executada em 2026-11-08 no WordPress local `http://aisa.wordpress.test/`.

- `tests/gtm.php`: 26 verificações aprovadas, incluindo as prioridades 1/5,
  sanitização, ausência de saída sem ID, consentimento antes do GTM por HTTP,
  noscript, exclusão de administrador/editor e preview, subscritor elegível,
  filtro, precedência da constante e carregamento do ouvinte no rodapé.
- A opção original é restaurada em `finally`; contas temporárias removidas.
- `tests/gtm-forms.cjs`: evento de sucesso CF7 simulado no wrapper e no formulário,
  classes e `data-form-id`, formulários desconhecidos ignorados e ausência de
  valores dos campos no evento. Só os identificadores `form-voluntariado` e
  `form-contacto` da 02-03 são enviados.
- Lint PHP dos ficheiros alterados e do teste; `node --check` do ouvinte e
  `git diff --check` sem erros.
- Comandos do §6 executados em PowerShell com `curl.exe` e `Select-String`:
  `GTM-XXXXXXX` guardado, consentimento antes de `googletagmanager.com/gtm.js`,
  opção eliminada e contagem de `googletagmanager` igual a **0**. O ambiente
  terminou sem ID de teste, como estava inicialmente.
- Pedido HTTP final isolado acrescentou **0 bytes** ao `debug.log`.
- RF04 e T09 ficam em 🔄; a configuração GTM/GA4/CMP e DebugView pertence à 02-02.

## Repetir

```powershell
wp --path=public_html eval-file public_html/wp-content/plugins/aisa-core/tests/gtm.php
node public_html/wp-content/plugins/aisa-core/tests/gtm-forms.cjs
php -l public_html/wp-content/plugins/aisa-core/includes/class-gtm.php
```

O teste de integração destina-se ao ambiente local, sem `AISA_GTM_ID` já definida
e com CF7 inativo. Simula a disponibilidade do CF7 apenas no processo de teste.

## Limites

- PHPCS não está instalado no PATH nem no diretório global de ferramentas Composer;
  não se declara aprovação WPCS.
- CF7 ainda não instalado: envio real de e-mail e inspeção no navegador ficam
  dependentes da 02-03; o ouvinte foi verificado com eventos DOM simulados.
- O `debug.log` preserva histórico anterior e depreciações do WP-CLI em
  `cli/Colors.php` com PHP 8.5. A primeira execução do teste também registou uma
  exceção por assumir um editor existente; o teste foi corrigido para criar e
  remover contas temporárias, e passou integralmente.
