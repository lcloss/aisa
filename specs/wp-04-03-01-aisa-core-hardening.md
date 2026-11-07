# wp-04-03-01 — Hardening (mu-plugin)

> Gerado por `wp-04-project-manager` em 2026-11-07. Fase de implementação 03 (QA e pré-lançamento M2, 14/11). Depende de: 01-01.

## 1. Tipo

**Plugin próprio** (mu-plugin, carregado sempre e não desactivável no wp-admin).

## 2. Páginas / requisitos cobertos

`wp-03-product-backlog.md`: **T14** (parte técnica).

`wp-02` §7.1:
- #2.2 XML-RPC;
- #2.3 enumeração de utilizadores;
- #3.2 HSTS;
- #3.3 cabeçalhos;
- #7.1 validação de uploads.

## 3. Âmbito técnico

**Ficheiro:** `public_html/wp-content/mu-plugins/aisa-hardening.php` (um só ficheiro, namespace `Aisa\Hardening`).

| Medida | Implementação |
|---|---|
| XML-RPC | `add_filter('xmlrpc_enabled','__return_false')`; `remove_action('wp_head','rsd_link')`; `remove_action('wp_head','wlwmanifest_link')`; pedido a `/xmlrpc.php` → 403 (`add_filter('xmlrpc_methods', fn() => [])` + bloqueio no servidor, se o `wp-09` o permitir) |
| Enumeração de utilizadores | `?author=N` no front-end (sem sessão) → 404 (`template_redirect`); endpoints REST `/wp/v2/users` e `/wp/v2/users/<id>` → `rest_endpoints` filtrado para sem sessão (401); `oembed` sem `author_name` / `author_url` (`oembed_response_data`); sitemap sem autores (Rank Math já, 02-06) |
| Cabeçalhos | `send_headers`: `X-Content-Type-Options: nosniff`; `X-Frame-Options: SAMEORIGIN`; `Referrer-Policy: strict-origin-when-cross-origin`; `Permissions-Policy: camera=(), microphone=(), geolocation=(), interest-cohort=()`. **HSTS** `Strict-Transport-Security: max-age=31536000` **só** se `is_ssl()` e `wp_get_environment_type() === 'production'` |
| Versão | `remove_action('wp_head','wp_generator')`; `the_generator` → `''` |
| Uploads | `upload_mimes`: só `jpg|jpeg|png|webp|avif|pdf|doc|docx|xls|xlsx` (**sem svg**); `wp_handle_upload_prefilter`: máximo de **10 MB** (mensagem em pt-PT); `big_image_size_threshold` 2560 (núcleo) |
| Comentários | Reforço: `comments_open` e `pings_open` → false; remover o menu "Comentários" e o suporte `comments` de `post` e `page` (os comentários estão desligados desde o 01-01) |
| Ferramentas de privacidade | Mantidas (núcleo), sem alterações (`wp-02` §7.1 #6.3) |

Sem opções nem interface: tudo é código, e as excepções fazem-se por filtros `aisa_hardening_*`.

## 4. Tarefas ordenadas

1. Criar `mu-plugins/aisa-hardening.php` com as medidas de §3.
2. Testar cada medida sem sessão e com sessão (o Editor tem de conseguir carregar PDF e imagens; a REST do editor de blocos tem de continuar a funcionar com sessão).
3. Verificar §6.
4. T14 fica 🔄 até ao 03-02 (lista de plugins).

## 5. Critérios de aceitação

- `curl -X POST /xmlrpc.php` → 403 ou resposta de erro sem métodos; nenhum `EditURI` no `<head>`.
- `/?author=1` → 404 sem sessão; `/wp-json/wp/v2/users` → 401 sem sessão. O editor de blocos funciona com sessão (autores, por ex. no painel do artigo).
- Os cabeçalhos de §3 estão presentes; o HSTS não aparece em local (HTTP).
- Um SVG é recusado no upload; um PDF de 11 MB é recusado com mensagem pt-PT; um PDF de 2 MB é aceite.
- Não há `meta name="generator"`.
- `debug.log` sem avisos.

## 6. Comandos de verificação

```bash
B=http://aisa.wordpress.test
curl -s -o /dev/null -w "%{http_code}\n" -X POST "$B/xmlrpc.php" -d '<?xml version="1.0"?><methodCall><methodName>system.listMethods</methodName></methodCall>'
curl -s "$B/" | grep -ci 'EditURI\|name="generator"'   # 0
curl -s -o /dev/null -w "%{http_code}\n" "$B/?author=1"   # 404
curl -s -o /dev/null -w "%{http_code}\n" "$B/wp-json/wp/v2/users"   # 401
curl -sI "$B/" | grep -iE "x-content-type-options|x-frame-options|referrer-policy|permissions-policy|strict-transport"
php -l /c/var/www/WordPress/aisa/public_html/wp-content/mu-plugins/aisa-hardening.php
```

## 7. Prompt para o `wp-05-developer`

```
/wp-05-developer Implementa specs/wp-04-03-01-aisa-core-hardening.md.
mu-plugin aisa-hardening.php: XML-RPC fora, enumeração de utilizadores bloqueada sem sessão (sem partir o editor), cabeçalhos de segurança (HSTS só em produção HTTPS), sem generator, uploads só jpg/png/webp/avif/pdf/doc(x)/xls(x) até 10 MB, comentários reforçados. Corre §6. T14 fica 🔄 até ao 03-02.
```
