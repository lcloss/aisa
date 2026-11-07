# wp-04-03-02 — Login, permissões e lista de plugins

> Gerado por `wp-04-project-manager` em 2026-11-07. Fase de implementação 03 (M2). Depende de: todas as fatias que instalam plugins (02-02, 02-03, 02-06) e 03-01.

## 1. Tipo

**Configuração de plugin.**

## 2. Páginas / requisitos cobertos

`wp-03-product-backlog.md`:
- **RF11** (permissões e editabilidade, parte das contas);
- **T14** (hardening + lista de plugins).

`wp-02` §7.1:
- #2.4 login;
- #2.5 política de actualizações;
- #4.2 salts (produção);
- #7.2 menor privilégio.

Também o R7.

## 3. Âmbito técnico

**Plugins** (gratuitos):
- **Limit Login Attempts Reloaded**: 4 tentativas → bloqueio de 20 min; 4 bloqueios → 24 h; GDPR compliance ligada (IPs pseudonimizados); **sem** a "Cloud App" (dados ficam locais); notificações por e-mail desligadas ou só para a agência.
- **Two Factor** (plugin oficial da comunidade WordPress): TOTP para **todos os administradores** (obrigatório na prática: cada conta admin configura-o e regista-se a verificação); recomendado para o Editor, com códigos de recuperação entregues à Mercês em mão.

**Contas:**

| Conta | Papel | Notas |
|---|---|---|
| Agência | Administrador | 2FA TOTP; e-mail da agência |
| Fornecedor de manutenção | Administrador | Criada no `wp-09`, com 2FA (só se o fornecedor confirmar) |
| **Mercês** (`comunicacao@aisaipss.pt`) | **Editor** | Sem `manage_options`, `install_plugins`, `edit_theme_options` (menus: o Editor **não** edita menus por omissão; o RF11 diz "menus (opcional)" → **não** se dá esta capacidade no MVP) |
| Contas partilhadas | — | Proibidas |

**Verificação do RF11 com a conta Editor:**
- cria e edita notícias, eventos, depoimentos, documentos e páginas;
- não vê Plugins, Aparência → Temas nem Definições;
- não consegue escolher cor nem tamanho livres no editor;
- não publica uma notícia sem imagem destacada;
- não cria termos de valência.

**Política de actualizações** (`wp-02` §7.1 #2.5):
- `WP_AUTO_UPDATE_CORE` = `minor` em `wp-config.php` (produção);
- actualizações automáticas dos plugins **de segurança** ligadas por plugin (Limit Login Attempts Reloaded, Two Factor, WP Armour); os restantes ficam manuais, com revisão do fornecedor.

**Lista de plugins (T14, R7):** `docs/plugins.md` na raiz do repositório (versionado), com uma tabela por plugin:
- nome;
- versão instalada;
- função no site;
- spec que o configurou;
- gratuito ou pago;
- conta ou chave associada (onde está, sem o segredo);
- política de actualização;
- o que parte se for desactivado.

Inclui o `aisa-core` e o `aisa-hardening` (próprios) e regista os removidos (Akismet, Hello Dolly, WordPress Importer).

## 4. Tarefas ordenadas

1. Instalar e configurar o Limit Login Attempts Reloaded.
2. Instalar o Two Factor; configurar o TOTP na conta da agência.
3. Criar a conta Editor da Mercês (sem enviar e-mail de boas-vindas antes da formação; a palavra-passe é entregue na formação).
4. Teste completo com a conta Editor (§3).
5. Actualizações automáticas conforme a política.
6. Escrever `docs/plugins.md` (verificar as versões com `wp plugin list`).
7. Verificar §6.
8. Marcar ✅ em RF11 e T14 no `wp-03-product-backlog.md`.

## 5. Critérios de aceitação

- 5 tentativas erradas de login → bloqueio; mensagem sem revelar se o utilizador existe.
- O administrador da agência tem 2FA activo (pedido de código no login).
- O Editor não acede a `plugins.php`, `themes.php`, `options-general.php` nem `nav-menus.php` / site editor (403 / "não tem permissão").
- Os testes do RF11 de §3 passam.
- O `docs/plugins.md` lista **todos** os plugins activos (o mesmo número que `wp plugin list --status=active`) e os próprios.

## 6. Comandos de verificação

```bash
cd /c/var/www/WordPress/aisa
wp --path=public_html plugin list --status=active --fields=name,version,auto_update
wp --path=public_html user list --fields=user_login,roles
wp --path=public_html user meta get <id-admin> _two_factor_enabled_providers
wp --path=public_html cap list editor | grep -E "manage_options|install_plugins|edit_theme_options"   # vazio
grep -c "^|" docs/plugins.md
```

Manual: sessão como Editor e percorrer os testes de §3; 5 logins errados em janela anónima.

## 7. Prompt para o `wp-05-developer`

```
/wp-05-developer Implementa specs/wp-04-03-02-login-permissoes.md.
Limit Login Attempts Reloaded (sem Cloud App) + Two Factor (TOTP para administradores), conta Editor para a Mercês sem capacidades extra, política de actualizações, e docs/plugins.md com todos os plugins (activos, próprios e removidos). Testa a conta Editor contra RF11 e corre §6. Marca ✅ RF11 e T14 em wp-03-product-backlog.md.
```
