# wp-04-02-02 — Consentimento e medição

> Gerado por `wp-04-project-manager` em 2026-11-07. Fase de implementação 02 (beta M1). Depende de: 02-01. **Depende de A12** (plano do Cookiebot) e de **AQ1** (dono das contas GTM e GA4).

## 1. Tipo

**Configuração de plugin.**

## 2. Páginas / requisitos cobertos

`wp-03-product-backlog.md`:
- **RF03** (consentimento);
- **RF04** (medição GA4);
- **RF05** (conteúdo de terceiros bloqueado);
- **T09**;
- **P27** (declaração de cookies);
- ligação "Preferências de cookies" de **G02**.

`core-06` L15, L19–L24. KPI K10.

## 3. Âmbito técnico

### 3.1 Consentimento: **Cookiebot** (decisão do `wp-02` §4.1)

**Plugin:** "Cookiebot by Usercentrics" (oficial, wordpress.org). **Plano pago** conforme a A12. O gratuito cobre um número limitado de subpáginas, o que não chega ao site (L23).

**Painel Cookiebot (Usercentrics):**
- Domínio de produção (`aisaipss.pt`, `www.aisaipss.pt`) + domínio local / staging para os testes.
- Língua: pt-PT.
- Banner com "Aceitar" e "Rejeitar" **com o mesmo estilo** (L20) e "Personalizar".
- Categorias: necessários, estatísticas e marketing (preferências desligada se não houver uso). Nenhuma pré-marcada.
- Fechar = rejeitar.
- Estilo: cores e tipografia dos tokens (primária `#1B75BC`-equivalente do `theme.json`; texto antracite; Atkinson Hyperlegible). Contraste AA. Botões com ≥ 44 px.
- Registo de consentimentos ligado (L24).
- **Google Consent Mode v2:** ligado.

**Plugin no WordPress:**
- Cookiebot ID;
- bloqueio "auto" (ou "manual", se o auto partir os blocos do tema; testar);
- "Consent Mode" sem `default` próprio, porque o `default` já vem do 02-01. **Não duplicar:** desligar a opção do plugin que injecta o `gtag consent default`, ou desligar a do `aisa-core` (filtro) e ficar só com uma. A decisão regista-se no fim da spec.
- Não usar o GTM do plugin: o GTM vem do 02-01.

**Ligação "Preferências de cookies"** (`.aisa-cookie-prefs` no rodapé) → `Cookiebot.renew()` (pequeno JS no tema ou atributo `onclick="Cookiebot.renew()"` com `href="#"` e `role="button"`).

**P27 Política de Cookies:** página com o texto curto + shortcode `[cookie_declaration]` (declaração gerada, L15) + o botão "Alterar preferências".

**Alternativa (se a A12 recusar o plano pago): Complianz (gratuito).**
- Assistente com a região UE e Consent Mode v2.
- Banner com "Aceitar" e "Rejeitar" iguais.
- Bloqueio de iframes com área reservada; declaração de cookies própria.
- As mesmas exigências de §5.
- Regista-se aqui como "Variante B" e actualiza-se a lista de plugins (T14).

### 3.2 GTM e GA4

**Contas** (AQ1): a propriedade GA4 "AISA — site" (fuso Lisboa, moeda EUR, retenção de dados 14 meses) e o contentor GTM "aisaipss.pt", com o administrador decidido na AQ1.

**Contentor GTM:**

| Elemento | Configuração |
|---|---|
| Etiqueta "GA4 — Google Tag" | ID de medição; acionador "Initialization – All Pages"; **Consent Settings: requer `analytics_storage`** |
| Acionador "Clique tel" | Clique, só ligações; Click Classes contém `aisa-tel` (ou Click URL começa por `tel:`) |
| Etiqueta "GA4 Event — click_tel" | Evento `click_tel`; parâmetro `link_url` |
| Acionador "Download" | Clique, só ligações; Click URL corresponde à RegEx `\.(pdf|docx?|xlsx?)$` |
| Etiqueta "GA4 Event — file_download" | Evento `file_download`; parâmetros `file_name`, `file_extension` (é também evento automático do Enhanced Measurement: **desligar o automático** para não duplicar, ou não criar esta etiqueta; decide quem configurar, uma só fonte) |
| Acionador "form_submit" | Evento personalizado `form_submit` (do 02-01) |
| Etiqueta "GA4 Event — form_submit" | Parâmetro `form_id` |
| Modelo da CMP | "Cookiebot CMP" da galeria, **só se** a injecção do `default` não for feita pelo `aisa-core` (ver §3.1) |

**GA4:**
- conversões (eventos-chave) `click_tel` e `form_submit`;
- Enhanced Measurement: scroll e pesquisa no site ligados; downloads conforme a decisão acima;
- Google Signals desligado.

### 3.3 Conteúdo de terceiros (RF05, L22)

- **Inventário:** depois da migração (02-05), procurar nas notícias `iframe`, `wp:embed`, `youtube`, `facebook.com/plugins`, `instagram.com` e `maps.google`. O resultado é uma tabela (ID da notícia → tipo → acção) no fim desta spec.
- Com o Cookiebot: os `iframe` levam `data-cookieconsent="marketing"` e `data-cookieblock-src` (o bloqueio automático costuma tratar disto; verificar caso a caso) + área reservada acessível: um `<div class="cookieconsent-optout-marketing">` com o texto "Este conteúdo é do YouTube e só aparece se aceitar cookies de marketing. [Alterar preferências]".
- Os blocos `core/embed` do WordPress são cobertos pelo bloqueio automático ou por um filtro `render_block` no tema (se for preciso, regista-se como desvio).
- **Contactos (P25):** sem iframe; imagem estática do mapa (feita em 02-04).

## 4. Tarefas ordenadas

1. Confirmar a A12 (Cookiebot pago ou Complianz) e a AQ1 (contas). Sem resposta: avançar com a **conta de teste da agência** e a variante escolhida, e trocar o ID antes do M2.
2. Instalar e configurar o plugin de consentimento (§3.1); estilo do banner.
3. Resolver a duplicação do `consent default` (§3.1) e registar a decisão aqui.
4. Página P27 com a declaração.
5. Ligar "Preferências de cookies" no rodapé.
6. Contentor GTM e GA4 (§3.2); publicar a versão 1 do contentor.
7. `wp option update aisa_gtm_id GTM-…` (ou a constante).
8. Inventário e tratamento dos conteúdos incorporados (§3.3), depois do 02-05. Se o 02-05 ainda não tiver corrido, esta tarefa fica pendente e o RF05 em 🔄.
9. Testes de §6.
10. Marcar ✅ em RF03, RF04, T09, P27 e (depois da tarefa 8) RF05. RF04 e T09 fecham com o 02-01.

## 5. Critérios de aceitação

- **K10 = 0:** com a cache limpa e sem consentimento, o separador de rede **não mostra pedidos** a `google-analytics.com`, `googletagmanager.com/gtag`, `region1.google-analytics.com`, `facebook.com`, `youtube.com` nem `maps.googleapis.com`. O pedido a `googletagmanager.com/gtm.js` com consent default `denied` é aceitável se o contentor só disparar etiquetas com consentimento (Tag Assistant: "GA4 — not fired: consent").
- Depois de "Aceitar estatísticas": o GA4 recebe `page_view`; `click_tel`, `form_submit` e `file_download` aparecem no DebugView.
- "Rejeitar" tem o mesmo destaque que "Aceitar"; fechar = rejeitar; "Preferências de cookies" reabre o banner.
- O banner é operável só por teclado; o foco entra no banner; o leitor de ecrã anuncia-o; axe sem erros críticos com o banner visível.
- P27 mostra a declaração gerada.
- Os conteúdos incorporados nas notícias ficam bloqueados até ao consentimento, com área reservada acessível.

## 6. Comandos de verificação

```bash
cd /c/var/www/WordPress/aisa
wp --path=public_html plugin list --status=active | grep -i "cookiebot\|complianz"
curl -s http://aisa.wordpress.test/ | grep -o 'consent.cookiebot.com[^"]*' | head -1
curl -s http://aisa.wordpress.test/politica-de-cookies/ | grep -c 'CookieDeclaration'
wp --path=public_html post list --post_type=post --format=ids | xargs -n1 -I{} sh -c 'wp --path=public_html post get {} --field=post_content | grep -qiE "<iframe|wp:embed|youtube|facebook.com/plugins|instagram.com|maps.google" && echo {}'
```

Manuais (obrigatórios):
- **Chrome em modo anónimo** → DevTools → Network → filtro `google|facebook|youtube` → recarregar sem aceitar: 0 pedidos de recolha.
- **Tag Assistant** (tagassistant.google.com): o estado do consentimento antes e depois.
- **GA4 DebugView:** os três eventos.

## 7. Prompt para o `wp-05-developer`

```
/wp-05-developer Implementa specs/wp-04-02-02-consentimento-medicao.md.
Cookiebot (ou Complianz se a A12 for recusada — pergunta-me antes) com banner Aceitar/Rejeitar iguais, Consent Mode v2 sem duplicar o default do aisa-core, declaração em /politica-de-cookies/, "Preferências de cookies" no rodapé. Configura o contentor GTM e o GA4 conforme §3.2 (contas da AQ1; sem resposta usa a conta de teste da agência e avisa). Inventaria e bloqueia os embeds das notícias migradas. Prova K10 = 0 com o separador de rede e o Tag Assistant. Marca ✅ RF03, RF04, T09, P27 (e RF05 se o inventário estiver feito) em wp-03-product-backlog.md.
```

## 8. Execução parcial — 2026-11-08

**Não concluída; não há prova K10 nem configuração publicada de GTM/GA4.**

- A12 e AQ1 continuam sem resposta nesta execução. Foi pedida a decisão sobre o plano pago e os acessos/IDs. Não foi escolhida a variante Complianz. A conta de teste da agência também não está acessível: não se inventaram IDs nem se configurou uma conta alheia.
- A instalação oficial `wp --path=public_html plugin install cookiebot --activate` falhou no acesso ao WordPress.org; `curl.exe -I --max-time 15 https://downloads.wordpress.org/plugin/cookiebot.latest-stable.zip` devolveu erro 7 de ligação. Cookiebot não instalado/activado.
- O controlo de Computer Use recusou acesso ao Google Chrome (`Computer Use was not approved to use Google Chrome`). Network em modo anónimo, Tag Assistant, DebugView, teclado/leitor de ecrã e axe **não executados**.
- O `aisa-core` existente foi preservado: continua a ser a única fonte local de `consent default` e de GTM. Nenhuma segunda fonte foi acrescentada. A escolha final da opção/filtro para evitar a duplicação com o plugin fica pendente de inspeccionar e configurar a versão instalada; manter Consent Mode v2 e os updates da CMP. Não adicionar modelo CMP no GTM se o default continuar no core.
- `assets/js/cookie-preferences.js` no tema delega o clique de `.aisa-cookie-prefs` para `Cookiebot.renew()`. Sem CMP, a ligação leva a `/politica-de-cookies/`. Não implementa banner nem armazenamento de consentimento próprios.
- `specs/scripts/prepare-cookie-policy.php` prepara P27 em **rascunho**, com texto curto, `[cookie_declaration]` e ligação «Alterar preferências»; preserva uma página existente. Só publicar depois de validar a declaração gerada e o banner. A ligação do rodapé só terá um destino público disponível após essa publicação.
- `specs/scripts/inventory-embeds.php` é de leitura e produz o inventário abaixo. Repetir após 02-05: as 87 notícias ainda não estão migradas. Não havia incorporados para tratar no conteúdo local detectado; o bloqueio por CMP ainda não está validado.

### Inventário local (não é o inventário final de RF05)

| ID da notícia | Estado | Tipo | Acção |
|---|---|---|---|
| 1 | draft | Nenhum | Rascunho inicial; sem incorporados detectados |
| 68 | publish | Nenhum | Demonstração; sem incorporados detectados |
| 69 | publish | Nenhum | Demonstração; sem incorporados detectados |
| 70 | publish | Nenhum | Demonstração; sem incorporados detectados |
| 71 | publish | Nenhum | Demonstração; sem incorporados detectados |

### Verificação local realizada

- Lint PHP de `functions.php`, `patterns/footer.php` e dos dois scripts: aprovado.
- `node --check` do JS e teste isolado do clique (CMP ausente, `renew()` disponível, clique fora da ligação): aprovados.
- HTTP da página inicial: ligação de preferências com o URL correcto e JS enfileirado; sem GTM configurado. Isto **não prova K10 com o contentor real**.
- `git diff --check`: sem erros de whitespace.
- Backlog: apenas os estados de P27, RF03 e RF05 avançaram para 🔄 com os bloqueios; RF04 e T09 continuam 🔄. Nenhum ✅ atribuído sem os critérios de aceitação.

### Retoma

1. Resolver A12 e obter Domain Group ID/acesso Cookiebot; permitir a instalação oficial. Se A12 for recusada, confirmar a mudança para Complianz antes de instalar.
2. Aplicar §3.1 no painel e no plugin, confirmar uma única fonte de default e updates, testar declaração e publicar P27. Configurar Aceitar/Rejeitar com o mesmo estilo, categorias sem pré-selecção, fecho como rejeição, registos e acessibilidade.
3. Obter acesso AQ1 ou à conta de teste da agência. Criar/configurar propriedade e contentor conforme §3.2, incluindo consentimento adicional `analytics_storage` **em todas as etiquetas GA4**, não apenas na Google Tag. Usar uma só fonte de downloads (preferência: etiqueta GTM; desligar o automático no Enhanced Measurement). Confirmar que a concessão de estatísticas após Initialization também inicia a medição sem exigir navegação, antes de publicar a versão 1.
4. Executar a migração e repetir `wp --path=public_html eval-file specs/scripts/inventory-embeds.php`; tratar e verificar cada incorporado e o mapa estático de Contactos.
5. Capturar Network sem consentimento, após rejeição e após retirada; Tag Assistant antes/depois e DebugView dos eventos. Só então fechar os itens aplicáveis com ✅.
