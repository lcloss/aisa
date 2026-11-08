# wp-04-02-03 — Formulários (voluntariado e contacto)

> Gerado por `wp-04-project-manager` em 2026-11-07. Fase de implementação 02 (beta M1). Depende de: 01-02 (estilos `forms-cf7.css`) e 02-01 (ouvinte `form_submit`). **Depende de A6** (caixa de destino e credenciais SMTP).

## 1. Tipo

**Configuração de plugin.**

## 2. Páginas / requisitos cobertos

`wp-03-product-backlog.md`: **RF01**, **RF02**, **T07**, **T08**; os formulários de **P24** e **P25**.

- `core-06` L1–L6, L13.
- `wp-02` §4.1 e §7.1 (#1.1, #1.2, #4.1, #6.1).
- Critério 6 do PRD.

## 3. Âmbito técnico

**Plugins** (todos gratuitos, `wp-02` §4.1):
- **Contact Form 7**;
- **WP Mail SMTP**;
- **WP Armour – Honeypot Anti Spam**;
- Turnstile pela **integração nativa do CF7** (Contacto → Integração → Cloudflare Turnstile). Se a versão instalada não a tiver: "Simple Cloudflare Turnstile" (AP5 do `wp-02`; regista-se e actualiza-se o T14).

**Definições globais:**
- CF7: `define('WPCF7_LOAD_CSS', false);` (o tema dá o estilo em `forms-cf7.css`) e `WPCF7_AUTOP` = false.
- Turnstile: chaves (Site Key e Secret) da conta Cloudflare (AQ1), em `wp-config.php` se o plugin o suportar, senão na base de dados; nunca no Git. Modo "Managed" ou "Invisible".
- WP Armour: activo para o CF7.
- WP Mail SMTP: "Other SMTP" ou o fornecedor da caixa da AISA; constantes `WPMS_ON`, `WPMS_SMTP_HOST`, `WPMS_SMTP_PORT`, `WPMS_SSL`, `WPMS_SMTP_AUTH`, `WPMS_SMTP_USER`, `WPMS_SMTP_PASS`, `WPMS_MAIL_FROM`, `WPMS_MAIL_FROM_NAME` em `wp-config.php` (fora do Git). From = a caixa institucional; From Name = "Site AISA".
- **Registo de e-mails desligado** (não guardar dados pessoais no WordPress, L5). O CF7 não guarda envios. **Não instalar** o Flamingo nem nenhum armazenamento de envios.

**Formulário "Voluntariado"** (`html_class: form-voluntariado`), RF01:

```
<fieldset> <legend>Os seus dados</legend>
[text* nome autocomplete:name] "Nome"
[tel telefone autocomplete:tel] "Telefone"
[email email autocomplete:email] "E-mail"     ← ajuda: "Indique pelo menos um: telefone ou e-mail."
</fieldset>
[checkbox* disponibilidade use_label_element "Dias úteis de manhã" "Dias úteis de tarde" "Fins de semana" "Pontualmente, em eventos"]
[radio area use_label_element default:1 "Envelhecimento" "Infância e Juventude" "Eventos" "Outra"]   ← obrigatório por validação
[textarea mensagem] "Mensagem (opcional)"
[radio maior18 use_label_element "Sim" "Não"] "Tem 18 anos ou mais?"   ← ajuda fixa: "Se tiver menos de 18 anos, precisamos da autorização dos pais; entramos em contacto."
[acceptance consentimento] Li a informação sobre os meus dados e aceito que a AISA me contacte sobre voluntariado. [/acceptance]
<p class="form-rgpd">A AISA usa estes dados só para responder à sua candidatura a voluntário e guarda-os até 24 meses ou até ao fim da colaboração. Saiba mais na <a href="/politica-de-privacidade/">Política de Privacidade</a>.</p>
[turnstile] (se for por shortcode)
[submit "Enviar candidatura"]
```

- **Validação personalizada** (filtro `wpcf7_validate` em `aisa-core` só se não houver alternativa; preferir os campos obrigatórios do CF7):
  - pelo menos um de telefone e e-mail;
  - `area` obrigatória;
  - `maior18` obrigatório.
  - Se for preciso código: snippet em `aisa-core/includes/class-forms.php`, registado como desvio mínimo deste tipo de spec.
- **Não pede** saúde, NIF, data de nascimento nem morada (L2).
- **E-mail 1** (para a AISA): Para a caixa da A6; Assunto "Candidatura a voluntário — [nome]"; Reply-To = e-mail do candidato, se houver; corpo com todos os campos.
- **Sem e-mail de confirmação** ao candidato no MVP (evita abuso como relay de spam).
- **Mensagens:** sucesso "Obrigado! Recebemos a sua candidatura. Vamos entrar em contacto em breve, por telefone ou e-mail."; erros em pt-PT, claros, ligados ao campo.

**Formulário "Contacto"** (`html_class: form-contacto`), RF02:
- campos: `nome*`, `telefone`, `email` (pelo menos um), `mensagem*`, `acceptance` com o texto adaptado ("…para responder ao seu pedido… guarda-os até 12 meses…");
- Turnstile; botão "Enviar mensagem";
- ao lado, no padrão da P25: "Prefere falar? Ligue 214 857 700";
- e-mail para a caixa da A6, com o assunto "Contacto pelo site — [nome]".

**Acessibilidade (KS3):**
- `<label>` em todos os campos (o CF7 com `use_label_element`; nos campos de texto, a etiqueta explícita no HTML do formulário);
- `aria-required`;
- erros: o CF7 já aplica `aria-invalid` e `aria-describedby`, verificar;
- a mensagem de resposta tem `role="status"` / `aria-live`;
- o foco vai para o primeiro campo com erro;
- a caixa de consentimento **não vem pré-marcada**;
- alvos ≥ 44 px (estilos do 01-02).

## 4. Tarefas ordenadas

1. Instalar CF7, WP Mail SMTP e WP Armour.
2. WP Mail SMTP com as credenciais da A6. **Sem a A6:** usar o Mailpit ou o Mailhog do Laragon (SMTP local) e avisar que o teste real fica pendente.
3. Turnstile: chaves (AQ1, ou uma conta de teste) e integração no CF7.
4. Criar os dois formulários (§3); `WPCF7_LOAD_CSS` = false.
5. Validação "pelo menos um contacto" (configuração; código só se for preciso).
6. Inserir os formulários nas páginas P24 e P25. Se as páginas ainda não existirem, criar rascunhos (02-04 completa-as).
7. Testes de §6, incluindo o envio real.
8. Actualizar a lista de plugins (T14, documento iniciado em 03-02; aqui basta registar no fim desta spec).
9. Marcar ✅ em RF01, RF02, T07 e T08 **só com o envio real para a caixa da A6**. Com SMTP local: 🔄.

## 5. Critérios de aceitação

- Os dois formulários chegam à caixa definida na A6, por SMTP autenticado (cabeçalhos SPF/DKIM `pass` ou, pelo menos, sem cair no spam). Testado com envio real (critério 6 do PRD).
- Não há registo dos envios no WordPress (`wp db query "SHOW TABLES LIKE '%flamingo%'"` vazio; nenhum tipo de conteúdo de envios).
- Sem telefone e sem e-mail → erro "Indique pelo menos um contacto" associado aos campos.
- A caixa de consentimento não vem pré-marcada; sem ela, o envio falha com erro claro.
- Spam: um envio por script sem token do Turnstile é rejeitado; o honeypot do WP Armour está presente.
- Teclado e leitor de ecrã: as etiquetas são lidas; os erros são anunciados; o foco vai para o primeiro erro.
- O `dataLayer` recebe `form_submit` com `form_id` = `form-voluntariado` / `form-contacto` (com o 02-01).
- axe: 0 erros críticos ou graves em `/voluntariado/` e `/contactos/`.

## 6. Comandos de verificação

```bash
cd /c/var/www/WordPress/aisa
wp --path=public_html plugin list --status=active --fields=name,version | grep -E "contact-form-7|wp-mail-smtp|wp-armour"
wp --path=public_html config get WPCF7_LOAD_CSS
wp --path=public_html db query "SHOW TABLES LIKE '%flamingo%'"
curl -s http://aisa.wordpress.test/voluntariado/ | grep -c 'challenges.cloudflare.com/turnstile'
curl -s http://aisa.wordpress.test/voluntariado/ | grep -o 'type="checkbox"[^>]*name="consentimento"[^>]*' | grep -c checked   # 0
npx --yes @axe-core/cli http://aisa.wordpress.test/voluntariado/ --exit
npx --yes @axe-core/cli http://aisa.wordpress.test/contactos/ --exit
```

Manual: envio real dos dois formulários; ver a caixa de destino e os cabeçalhos; enviar vazio, só nome e sem consentimento.

## 7. Prompt para o `wp-05-developer`

```
/wp-05-developer Implementa specs/wp-04-02-03-formularios.md.
CF7 + WP Mail SMTP (credenciais em wp-config, fora do Git) + WP Armour + Turnstile nativo do CF7. Cria os formulários Voluntariado e Contacto exactamente com os campos e textos RGPD de §3 (sem dados de saúde/NIF/nascimento/morada, consentimento não pré-marcado, "pelo menos um contacto"), sem guardar envios (nada de Flamingo/log de e-mails). Sem a A6, usa o SMTP local do Laragon e deixa RF01/RF02/T07/T08 em 🔄; com envio real, marca-os ✅ em wp-03-product-backlog.md.
```

## 8. Registo de plugins e implementação — 08/11/2026

| Plugin | Versão local | Configuração nesta fatia |
|---|---|---|
| Contact Form 7 | 6.1.7 | Ativo; Voluntariado e Contacto; Turnstile nativo com chaves de teste; sem armazenamento nem autoresposta |
| WP Mail SMTP | 4.10.3 | Ativo; SMTP Mailpit `127.0.0.1:1025`; constantes fora do Git; registo/conteúdo de e-mails e diagnósticos impedidos |
| WP Armour (`honeypot`) | 2.4.02 | Ativo; integração automática com CF7; mensagem de erro em PT-PT |

Sem A6: RF01/RF02/T07/T08 em 🔄. P24/P25 em rascunho. Snippet mínimo em `aisa-core/includes/class-forms.php` para validação conjunta, Reply-To condicional, acessibilidade e ausência de diagnósticos persistentes do SMTP; foco em `assets/js/forms.js`. Procedimento e evidência: [`VERIFICATION-FORMS.md`](../public_html/wp-content/plugins/aisa-core/VERIFICATION-FORMS.md). A lista global de hardening T14 continua pendente.
