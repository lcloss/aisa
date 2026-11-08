# Verificação — fatia 01-04

Executada em 2026-11-08 (Europe/Lisbon), WordPress 7.0, PHP 8.5.

## Passou

- Desativação e reativação sem erros; plugin ativo no final.
- Sete blocos `aisa/*`, API v3, registo dinâmico e dependências do editor explícitas.
- Os sete endpoints ServerSideRender REST devolvem 200 com `context=edit`.
- Lint PHP dos sete renders, manifestos de dependências, bootstrap e cabeçalho;
  sintaxe JavaScript e JSON válidos.
- Integração WordPress: dois depoimentos não produzem HTML nem título; três
  produzem depoimentos sem o título interno. Filtro de valência respeita o limiar global.
- Eventos passados excluídos, futuros por início crescente, estado vazio com Notícias.
- Documentos por ano descendente, filtro por tipo, extensão e tamanho reais (PDF de 15 B),
  descrição acessível, classes `aisa-download`; regulamentos SAD + Geral, sem Centro de Dia.
- Provas só são publicadas com o ano visível; telefone admite filtro `aisa_telefone`.
- Teste JS do controlador real: sem autoplay, anterior/seguinte, ciclo, 6000 ms,
  reproduzir/pausar, paragem por foco/rato/aba oculta, instâncias independentes,
  controlos ocultos com uma imagem. CSS estrutural inclui reduced-motion e fallback sem JS.
- Teste JS dos sete editores: registo, controlos nativos e ServerSideRender, sem build.
- Rascunho temporário 59 com os sete blocos, consultado por HTTP com sessão de teste:
  três depoimentos, evento futuro presente, passado ausente, quatro ligações `tel:`
  com `aisa-tel`, slideshow e CSS estrutural carregados, sem scripts do editor no frontend.
- Cabeçalho usa `aisa/telefone` no pattern referenciado por `parts/header.html`,
  incluindo o telefone do painel móvel. Rótulo compacto apenas no botão do cabeçalho.
- Pedidos HTTP finais acrescentaram **0 bytes** ao debug.log.
- T03 marcado ✅; restantes estados do backlog preservados.

## Limites da verificação

- O comando axe da §6 foi tentado também no URL do rascunho, mas npm devolveu
  `EACCES` ao contactar registry.npmjs.org. Não se declara axe sem erros.
- Computer Use devolveu `Computer Use was not approved to use Google Chrome`.
  Ficam pendentes inspeção visual, teclado em navegador real, NVDA e emulação real
  de reduced-motion/JavaScript desligado; os testes locais não substituem estas verificações.
- O histórico de debug.log foi preservado: contém erros de auxiliares de teste entretanto
  corrigidos, avisos anteriores de rede WordPress.org e depreciações do WP-CLI/PHP 8.5.
  Não são apresentados como um log historicamente vazio.
- Dados, anexos, rascunho e sessão temporários removidos após os testes.

## Repetir em desenvolvimento

```powershell
wp --path=public_html eval-file public_html/wp-content/plugins/aisa-core/tests/blocks.php
wp --path=public_html eval-file public_html/wp-content/plugins/aisa-core/tests/blocks-http.php
node public_html/wp-content/plugins/aisa-core/tests/blocks-editor.cjs
# blocks-fixtures.json é temporário; contém o URL e sessão de teste para o preview HTTP.
# Executar axe/teclado/NVDA no preview autenticado antes da limpeza.
wp --path=public_html eval-file public_html/wp-content/plugins/aisa-core/tests/blocks-cleanup.php
```

A integração exige uma base de desenvolvimento sem depoimentos publicados e não
altera depoimentos existentes. O cleanup elimina apenas os IDs criados pelo teste.
