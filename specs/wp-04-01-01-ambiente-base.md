# wp-04-01-01 — Ambiente base

> Gerado por `wp-04-project-manager` em 2026-11-07. Fase de implementação 01 (base e demo M0, 09/11).
> Decisões do operador (2026-11-07):
> - URL local `http://aisa.wordpress.test`;
> - o núcleo do WordPress sai do Git (`git rm --cached`).

## 1. Tipo

**Configuração** (núcleo do WordPress e ambiente). Não há código de tema nem de plugin.

## 2. Páginas / requisitos cobertos

- `wp-01` DoD #7 (sem erros PHP com `WP_DEBUG`) e #9 (versionado).
- `wp-02` §4.1, remoção do Akismet e do Hello Dolly.
- `wp-02` §4.4, versionamento.
- `wp-02` §6, permalinks e `robots` em local.
- `wp-02` §7.1: #2.1 `DISALLOW_FILE_EDIT`, #4.2 salts únicos, #6.1 sem comentários nem registo.
- Base para todos os itens P, T, G e RF do `wp-03-product-backlog.md`.

## 3. Âmbito técnico

| Área | Configuração |
|---|---|
| Servidor local | Laragon (Apache). Novo vhost `aisa.wordpress.test` → `C:/var/www/WordPress/aisa/public_html`; entrada no `hosts`. O vhost `aisa-01.wordpress.test` **não se altera** |
| Base de dados | MySQL do Laragon: base `aisa`, `utf8mb4_unicode_ci`; prefixo de tabelas `aisa_` |
| `wp-config.php` | Gerado com `wp config create`; salts novos; `WP_DEBUG` = true, `WP_DEBUG_LOG` = true, `WP_DEBUG_DISPLAY` = false; `DISALLOW_FILE_EDIT` = true; `WP_ENVIRONMENT_TYPE` = `local`. **Fora do Git** |
| Instalação | `wp core install`, título "AISA — Associação de Apoio Social Nossa Senhora da Assunção", administrador da agência (palavra-passe forte, e-mail da agência) |
| Língua e hora | `pt_PT`; fuso `Europe/Lisbon`; data `j \d\e F \d\e Y`; semana a começar à segunda-feira |
| Permalinks | `/%category%/%postname%/` (`wp-02` §6). Categoria por omissão renomeada: "Notícias AISA", slug `noticias-aisa` |
| Leitura | "Desencorajar os motores de pesquisa" = **ligado** (local); passa para desligado só na produção (`wp-09`) |
| Discussão | Comentários e pingbacks **desligados** por omissão; registo de utilizadores desligado (`users_can_register` = 0) |
| Plugins | Remover o Akismet e o Hello Dolly |
| Temas | Manter **só** o `twentytwentyfive` como recurso até o `aisa` estar activo (01-02); remover `twentytwentytwo`, `twentytwentythree` e `twentytwentyfour` |
| Media | Tamanhos: thumbnail 400×300 (cortado), medium 800, large 1600; organizar em pastas `AAAA/MM` = **ligado** (mantém os caminhos do site actual, R9) |
| Git | `.gitignore` na raiz do repositório (ver tarefa 9). `git rm -r --cached` do núcleo e do resto que não se versiona; os ficheiros **ficam no disco** |

## 4. Tarefas ordenadas

1. Criar o vhost `aisa.wordpress.test` no Laragon (Menu → Apache → sites-enabled, ou um ficheiro `aisa.wordpress.test.conf` ao estilo dos existentes, incluindo o bloco 443 se os outros o tiverem) e a entrada no `hosts`. Recarregar o Apache.
2. Criar a base de dados: `wp db create` depois do passo 3, ou com o cliente MySQL do Laragon.
3. `wp config create --dbname=aisa --dbuser=root --dbpass= --dbprefix=aisa_ --locale=pt_PT` e acrescentar as constantes de §3 (`wp config set`).
4. `wp core install --url=http://aisa.wordpress.test --title="…" --admin_user=… --admin_email=… --skip-email`.
5. `wp language core install pt_PT --activate`; `wp option update timezone_string Europe/Lisbon`; formato de data e `start_of_week` = 1.
6. Permalinks e categoria: `wp rewrite structure '/%category%/%postname%/' --hard`; renomear a categoria 1 (`wp term update category 1 --name="Notícias AISA" --slug=noticias-aisa`).
7. Leitura e discussão: `blog_public` = 0; `default_comment_status` e `default_ping_status` = closed; `users_can_register` = 0.
8. Remover os plugins e os temas (§3). Definir os tamanhos de media.
9. `.gitignore` na raiz do repositório:
   ```gitignore
   /.claude
   /.idea/
   # WordPress: só se versiona o código do projecto
   /public_html/*
   !/public_html/wp-content/
   /public_html/wp-content/*
   !/public_html/wp-content/themes/
   /public_html/wp-content/themes/*
   !/public_html/wp-content/themes/aisa/
   !/public_html/wp-content/plugins/
   /public_html/wp-content/plugins/*
   !/public_html/wp-content/plugins/aisa-core/
   !/public_html/wp-content/mu-plugins/
   ```
10. `git rm -r --cached public_html` e depois `git add .gitignore public_html` (só entra o que o `.gitignore` permite). **Confirmar com o operador antes de fazer o commit.** Mensagem: "Retira o núcleo do WordPress do Git (wp-02 §4.4)".
11. Verificar §6.

## 5. Critérios de aceitação

- `http://aisa.wordpress.test` abre o WordPress em português de Portugal, sem erros.
- `wp option get permalink_structure` = `/%category%/%postname%/`.
- Comentários, pingbacks e registo desligados; `blog_public` = 0.
- Akismet e Hello Dolly removidos; só resta o `twentytwentyfive`.
- `DISALLOW_FILE_EDIT` activo: não aparece "Editor de ficheiros" em Aparência nem em Plugins.
- `wp-config.php` e o núcleo não estão no índice do Git (`git ls-files public_html` lista 0 ficheiros nesta fase).
- `wp-content/debug.log` vazio depois de abrir o front-end e o wp-admin.

## 6. Comandos de verificação

```bash
cd /c/var/www/WordPress/aisa
curl -sI http://aisa.wordpress.test/ | head -1
wp --path=public_html core version && wp --path=public_html language core list --status=active
wp --path=public_html option get permalink_structure
wp --path=public_html option list --search="*comment_status" --fields=option_name,option_value
wp --path=public_html option get users_can_register && wp --path=public_html option get blog_public
wp --path=public_html plugin list && wp --path=public_html theme list
wp --path=public_html config get DISALLOW_FILE_EDIT
git ls-files public_html | wc -l
cat public_html/wp-content/debug.log 2>/dev/null | tail -5
```

## 7. Prompt para o `wp-05-developer`

```
/wp-05-developer Implementa specs/wp-04-01-01-ambiente-base.md.
Cria o vhost aisa.wordpress.test no Laragon a apontar para C:/var/www/WordPress/aisa/public_html (não mexas no aisa-01), instala o WordPress com WP-CLI conforme §3/§4, aplica o .gitignore e tira o núcleo do índice do Git com git rm -r --cached (ficheiros ficam no disco). Pede-me confirmação antes do commit. Corre os comandos de §6 e mostra o resultado. Esta spec não tem itens de backlog próprios para marcar ✅.
```
