# Verificação — fatia 01-03

Executada em 2026-11-07, WordPress 7.0, PHP 8.5.4, pt_PT,
Europe/Lisbon. Plugin AISA Core 0.1.0 ativo.

## Resultado

- Ativação, desativação e reativação por WP-CLI.
- Três CPTs registados; apenas Evento público. Cinco valências e quatro tipos de documento.
- Editor pode atribuir termos, mas não criar, editar ou eliminar. Criação REST
  devolve 403 em ambas as taxonomias. A verificação adicional em `pre_insert_term`
  é necessária porque o core permite criar termos não hierárquicos com `assign_terms`.
- Eventos sem início, datas impossíveis e fim anterior ao início são recusados para publicação.
- Documento publicado com anexo PDF e ano válidos; helper devolve `pdf` e `15 B`
  para um ficheiro com exatamente 15 bytes. Anexo inválido e ano fora do intervalo recusados.
- Consulta principal da agenda contém apenas eventos desde as 00:00 de Lisboa,
  por início ascendente. Evento passado continua a devolver HTTP 200 no seu URL.
- Documento e depoimento publicados devolvem HTTP 404 nos URLs diretos.
- Notícia sem imagem fica em `draft`, tanto pelo comando da §6 como por REST.
  REST permite publicar quando a imagem chega no mesmo pedido; remover a imagem
  ou o início de um evento publicado faz regressar a rascunho.
- Script real do editor testado com as interfaces dos stores: bloqueia sem imagem,
  desbloqueia com imagem e volta a bloquear quando esta é retirada.
- Helper de valência, classe do body e `data-valencia` no bloco de âmbito verificados.
- JSON-LD HTTP analisado como JSON: exatamente um Event, início/fim com offset,
  Place, Organization, imagem, EventScheduled e OfflineEventAttendanceMode.
- Lint em todos os PHP e `node --check` nos três JS sem erros.
- Pedidos HTTP finais acrescentaram **0 bytes** ao debug.log. O histórico anterior
  foi preservado, incluindo um aviso de tradução antecipada corrigido nesta fatia.
  O comando final `wp plugin status` voltou a registar depreciações do próprio
  WP-CLI (`cli/Colors.php`, PHP 8.5); não provêm do plugin.
- Conteúdos, anexos e utilizadores temporários removidos depois das verificações.

## Limites e dependências

- O comando da §6 que conta `class="event` devolve **0**: a marcação visual da agenda
  depende dos blocos/templates de 01-04/01-05. O filtro da consulta foi testado
  diretamente com eventos futuros/passados e ordem ascendente.
- A morada oficial continua pendente de BQ2. Quando não existe local do evento,
  o schema usa AISA como Place e aceita a morada pelo filtro
  `aisa_organization_address`; não foi inventada uma morada.
- Validação externa Rich Results Test/validator.schema.org e inspeção manual do
  editor não executadas; validação estrutural local e teste automatizado do JS executados.
- PHPCS não está disponível no PATH; não se declara uma aprovação WPCS automática.
- T03 fica dependente do bloco de 01-04. A parte do plugin de T06 está implementada;
  o item completo fica dependente de 01-02/01-05. RF11/P23 não são marcados por esta fatia.
- A integração Rank Math remove entidades Event duplicadas; rever a configuração
  do plugin SEO em 02-06 quando estiver instalado.

## Repetir

```powershell
wp --path=public_html eval-file public_html/wp-content/plugins/aisa-core/tests/integration.php
node public_html/wp-content/plugins/aisa-core/tests/editor-featured.cjs
wp --path=public_html eval-file public_html/wp-content/plugins/aisa-core/tests/http-fixtures.php
# Verificar por HTTP os IDs de tests/fixtures.json; seguir redirecionamentos canónicos.
wp --path=public_html eval-file public_html/wp-content/plugins/aisa-core/tests/cleanup.php
```

O teste de integração cria conteúdos locais temporários e deve correr numa base
de desenvolvimento. `fixtures.json` é apenas um ficheiro temporário de execução.
