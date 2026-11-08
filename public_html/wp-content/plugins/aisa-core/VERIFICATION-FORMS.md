# Formulários — implementação e verificação local (08/11/2026)

Implementa `specs/wp-04-02-03-formularios.md`. RF01/RF02/T07/T08 ficam em 🔄: **não houve envio para uma caixa institucional nem validação de SPF/DKIM**.

## Configuração aplicada

- CF7 6.1.7: Voluntariado (ID 86) e Contacto (ID 88), com integração **nativa** do Turnstile; sem plugin Turnstile adicional.
- WP Mail SMTP 4.10.3 e WP Armour (`honeypot`) 2.4.02 ativos. O Armour integra automaticamente com o CF7.
- P24 (ID 87, `/voluntariado/`) e P25 (ID 89, `/contactos/`) criadas como **rascunhos**, com os shortcodes e as classes para o evento `form_submit`. P25 inclui o telefone alternativo. A fatia 02-04 completa as páginas.
- `wp-config.php`, ignorado pelo Git: `WPCF7_LOAD_CSS=false`, `WPCF7_AUTOP=false`, `WPMS_ON=true`, mailer `smtp`, host `127.0.0.1`, porta `1025`, sem autenticação/TLS **apenas no Mailpit local**, From `site@aisa.wordpress.test`, nome `Site AISA`, destino `formularios@aisa.wordpress.test`. Utilizador/password vazios; constantes de registo e conteúdo de e-mails a `false`; fila e resumos desligados.
- Turnstile: par oficial **de teste** armazenado na opção privada do CF7, fora do Git. Estas chaves não protegem um site público. Ver [documentação Cloudflare de testes](https://developers.cloudflare.com/turnstile/troubleshooting/testing/).
- CF7 sem armazenamento (`do_not_store: true`), confirmação ao candidato desativada, Flamingo ausente. O Mailpit captura as mensagens de teste fora do WordPress.

`specs/scripts/provision-forms.php` permite repetir o provisionamento com WP-CLI, depois de instalar/ativar os três plugins e definir `AISA_FORMS_RECIPIENT` e `WPMS_*` no `wp-config.php`. Preserva o conteúdo editorial das páginas e altera apenas os shortcodes próprios. Não inclui nem inventa credenciais de produção; não substitui chaves Turnstile existentes.

## Desvios mínimos de configuração de plugin

`includes/class-forms.php`: validação conjunta telefone/e-mail e rádios obrigatórios, limitada aos formulários com a definição adicional `aisa_form`; Reply-To apenas quando existe e-mail válido; associações ARIA. Usa o CF7 para as restantes validações, consentimento, Turnstile e envio. `assets/js/forms.js` coloca o foco no primeiro erro depois da atualização do DOM do CF7. O CSS do tema inclui as regras estruturais que deixam de existir ao desligar o CSS do CF7.

O WP Mail SMTP Lite permite desligar o diagnóstico detalhado, mas grava erros técnicos independentemente dessa opção. Para cumprir a ausência de registos, um filtro `query` bloqueia exclusivamente INSERT/REPLACE/UPDATE na tabela `{$wpdb->prefix}wpmailsmtp_debug_events`; dois filtros impedem guardar as opções de diagnóstico legado e de falhas de envio. Não altera o envio nem outras queries. Esta pequena adaptação de privacidade deve ser revista ao atualizar o plugin. As constantes `WPMS_LOGS_*` também estão desligadas para versões que suportem registo de e-mails.

## Resultados

- **28 verificações HTTP/SMTP passaram**: formulário vazio ou só com nome; contacto obrigatório, erro associado a ambos os campos; email inválido; consentimento desmarcado; disponibilidade, área, idade e mensagem obrigatórias; token Turnstile ausente; Armour ausente e honeypot preenchido; ambos os formulários enviados com só telefone e com só e-mail. As respostas negativas não enviam mensagens. Mailpit confirmou os assuntos, From, destino local e Reply-To condicional. A resposta de Voluntariado coincide com §3.
- **28 verificações de configuração/privacidade passaram**: configuração dos dois formulários, consentimento, ausência de confirmação, integração nativa, live region, bloqueio dos diagnósticos e ausência de Flamingo. Não existe tabela de diagnósticos SMTP neste ambiente.
- Chromium headless 147 + axe-core: **0 violações WCAG A/AA** nas duas pré-visualizações, antes e depois de submeter sem contacto. Confirmados `aria-invalid`, `aria-describedby`, consentimento inicialmente desmarcado e foco no telefone. Foram testadas cópias temporárias do HTML das pré-visualizações autenticadas, com os scripts/CSS reais; removida apenas a barra administrativa do WordPress. As páginas públicas continuam em rascunho, pelo que os URLs públicos da spec ainda devolvem 404. Não equivale a uma avaliação manual com leitor de ecrã.
- Mantido o ouvinte de medição existente; as classes são `form-voluntariado` e `form-contacto`. O teste existente `tests/gtm-forms.cjs` passou (evento de sucesso, classes/atributos, formas desconhecidas ignoradas, ausência de valores dos campos). Validação real em GTM/GA4 depende da configuração da fatia 02-01.

Comandos repetíveis (apenas no ambiente local com chaves Turnstile de teste e Mailpit):

```powershell
php public_html/wp-content/plugins/aisa-core/tests/forms.php
$armour = wp --path=public_html --skip-plugins option get wpa_field_name
node public_html/wp-content/plugins/aisa-core/tests/forms.cjs $armour 86 88
```

## Antes de publicar

Configurar A6: caixa institucional e constantes SMTP autenticadas em `wp-config.php`, fora do Git; definir `AISA_FORMS_RECIPIENT` e repetir o provisionamento para atualizar destinatário/remetente dos formulários. Configurar as chaves Turnstile de produção no CF7 para o domínio final (AQ1), com widget Managed ou Invisible. Completar/publicar a Política de Privacidade (A10) e declarar Cloudflare nas políticas. Completar P24/P25, repetir axe e testes manuais nos URLs finais. Enviar os dois formulários para a caixa A6, confirmar receção e cabeçalhos/spam. Só então marcar RF01/RF02/T07/T08 como ✅.
