# wp-04-02-01 — `aisa-core`: GTM com Consent Mode v2

> Gerado por `wp-04-project-manager` em 2026-11-07. Fase de implementação 02 (beta M1, 12/11). Depende de: 01-03 (o plugin já existe).

## 1. Tipo

**Plugin próprio.**

## 2. Páginas / requisitos cobertos

`wp-03-product-backlog.md`: **RF04** (parte do código: snippet + `dataLayer`) e **T09** (parte do código).

- `wp-02` §4.1: GTM sem plugin.
- `wp-02` §7.1 #6.2.
- `core-06` L19.
- KPI K10.

## 3. Âmbito técnico

**Ficheiro:** `aisa-core/includes/class-gtm.php`.

**Opção** `aisa_gtm_id`, editável só por administradores (`manage_options`):
- campo no ecrã Definições → Geral (Settings API, `register_setting` com sanitização `^GTM-[A-Z0-9]+$`);
- alternativa: a constante `AISA_GTM_ID` em `wp-config.php`, que tem precedência.
- **Sem ID → não imprime nada** (local e demo).

**Em `wp_head`, prioridade 1** (antes de qualquer outro script, e em particular antes do Cookiebot e do GTM):

```html
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('consent','default',{
  ad_storage:'denied', ad_user_data:'denied', ad_personalization:'denied',
  analytics_storage:'denied', functionality_storage:'denied',
  personalization_storage:'denied', security_storage:'granted',
  wait_for_update:500
});
gtag('set','ads_data_redaction',true);
gtag('set','url_passthrough',false);
</script>
```

**Depois**, o snippet oficial do GTM (`wp_head`, prioridade 5) e o `<noscript>` em `wp_body_open`.

- **Nonce/CSP:** sem CSP no MVP (`wp-02` §7.1 #3.3), por isso não é necessário.
- **Não carregar** no wp-admin, nas pré-visualizações (`is_preview()`) nem para utilizadores com sessão iniciada que tenham `edit_posts`, para não contaminar as métricas. Fica configurável pelo filtro `aisa_gtm_enabled`.

**`dataLayer` dos formulários:** o CF7 dispara o evento DOM `wpcf7mailsent`. O plugin acrescenta (via `wp_footer`, só se o CF7 estiver activo) um ouvinte que faz `dataLayer.push({event:'form_submit', form_id:<data-form-id do form>})`. O `form_id` vem do atributo `html_class` configurado em 02-03 (`form-voluntariado` / `form-contacto`).

**Os eventos `click_tel` e `file_download`** são configurados **no GTM** (02-02), com acionadores de clique em `.aisa-tel` e `.aisa-download`. Aqui não há código para eles.

## 4. Tarefas ordenadas

1. `class-gtm.php`: Settings API + constante + filtro.
2. Impressão do `consent default` (prioridade 1) e do GTM (prioridade 5) + `noscript`.
3. Ouvinte `wpcf7mailsent` → `dataLayer`.
4. Testar com um ID de teste (contentor da agência) em local.
5. Marcar ✅ só depois de 02-02 (o RF04 e o T09 dependem da configuração). Aqui fica 🔄.

## 5. Critérios de aceitação

- Sem `aisa_gtm_id`, o HTML não tem nenhum `googletagmanager.com`.
- Com ID, o `gtag('consent','default', …denied)` aparece **antes** do script do GTM no `<head>`.
- Um administrador com sessão iniciada não carrega o GTM.
- O envio de teste de um formulário CF7 faz `dataLayer.push` com `form_submit` e o `form_id` correcto (consola: `dataLayer`).
- PHPCS sem erros; `debug.log` sem avisos.

## 6. Comandos de verificação

```bash
cd /c/var/www/WordPress/aisa
wp --path=public_html option update aisa_gtm_id GTM-XXXXXXX
curl -s http://aisa.wordpress.test/ | grep -n "consent','default'\|googletagmanager.com/gtm.js" | head   # consent tem de vir antes
wp --path=public_html option delete aisa_gtm_id && curl -s http://aisa.wordpress.test/ | grep -c googletagmanager   # 0
php -l public_html/wp-content/plugins/aisa-core/includes/class-gtm.php
```

## 7. Prompt para o `wp-05-developer`

```
/wp-05-developer Implementa specs/wp-04-02-01-aisa-core-gtm-consent.md.
No aisa-core: opção aisa_gtm_id (Settings API, ou constante AISA_GTM_ID), consent default denied em wp_head prioridade 1, GTM em prioridade 5 + noscript, não carregar para editores com sessão/preview, ouvinte wpcf7mailsent → dataLayer form_submit. Corre §6. Deixa RF04/T09 em 🔄 em wp-03-product-backlog.md (fecham em 02-02).
```
