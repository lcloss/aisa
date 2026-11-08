# Páginas restantes — beta local, 08/11/2026

Implementação de `specs/wp-04-02-04-paginas-restantes.md`. **A fatia tem três pendências de conteúdo: P15, P16 e P29.** P26 continua dependente de A10. Não é uma aprovação para lançamento.

## Implementado

- Oito padrões de §3, com blocos nativos editáveis: serviços por grupos assimétricos (2:1:1, 1:2:1, resposta em destaque e 5:7), índice de terapias, pessoas, logótipos, IRS/donativos, contactos completos, texto legal reservado e ajuda em 404/pesquisa. Fotografias 4:3 e faixas de valência nos cartões; fundo Base no apelo final.
- As 21 páginas da tabela existem com título, slug, template e valência exactos. `default` é a atribuição nativa de `page.html`. Cabeçalho com imagem destacada ou fallback existente; P06 tem a imagem de `assets/capa_facebook.jpg` importada como anexo.
- Reutilizados os IDs **89 (Contactos)**, **87 (Voluntariado)** e **3 (rascunho inicial de Privacidade, renomeado para o slug exigido)**. O provisionamento procura também os slugs antigos de Saúde e das novas instalações. Guarda o estado anterior em `aisa_remaining_manifest`; não cria uma segunda página para um slug existente.
- Texto recuperado das versões públicas indexadas do site, reorganizado em blocos. O acesso HTTP directo ao site falhou; não se afirma ter feito um rastreio integral actualizado. Fontes e alternativas abaixo.
- Serviços com telefone e frase de comparticipação sem valores. O bloco existente `aisa/regulamentos` continua a apresentar documentos quando forem importados. Candidaturas não tem horário na caixa lateral.
- Equipa Técnica só com as quatro funções identificadas na fonte, sem nomes, biografias ou retratos da equipa (A11). O cabeçalho usa a imagem genérica já existente no tema.
- Contactos com telefone, e-mail, morada confirmada, horário público, CF7 existente e **PNG local esquemático, sem escala**, em `assets/img/mapa-aisa.png`. A ligação ao Google Maps anuncia a nova janela. O desenho é reproduzível por `tools/build-map.py`; não contém geografia ou percursos inventados.
- NIF seleccionável **502 369 345**, recuperado da página pública de apoio; consignação de 1% sem custo para o contribuinte. Sem IBAN inferido. NIPC do prestador mantém `[A CONFIRMAR — BQ2]` até confirmação. Livro de Reclamações continua dependente de A9.
- Menu completo e rodapé com os quatro destinos legais e Parcerias. `aisa_page_url()` deixa de devolver `#`. A política de cookies existente foi tornada acessível **apenas na beta local** para não deixar uma ligação legal em 404; não foi gerada uma declaração de cookies nem alterado o estado RF03/P27.
- Privacidade e Termos têm o aviso e as secções reservadas; P28 aceite como reservado pelo pedido do operador. O script só publica estes espaços na instalação `local`, `.test`, não indexável; termina com erro fora dela. O lançamento com formulários continua dependente de A10 e da configuração de produção de 02-03.
- Versão do tema 0.2.4: invalida as caches dos padrões e dos recursos, incluindo a cache distinta observada entre PHP CLI e HTTP no Windows.

## Verificação

Comandos executados da raiz do projecto:

```powershell
wp --quiet --path=public_html eval-file public_html/wp-content/themes/aisa/tools/provision-remaining.php
php public_html/wp-content/themes/aisa/tools/verify-remaining.php
node public_html/wp-content/themes/aisa/tools/verify-remaining.cjs
node public_html/wp-content/themes/aisa/tools/verify-remaining.cjs --section6
```

- **116 verificações PHP passaram**, antes e depois de repetir o provisionamento: matriz, templates, taxonomias, unicidade dos slugs, padrões registados e IDs reutilizados.
- §6: **21/21 URLs HTTP 200**; zero correspondências a `maps.googleapis`/`<iframe` em Contactos; zero `href="#"` no início; zero `€`/`euros` no SAD.
- A execução literal de `npx --yes @axe-core/cli … --exit` falhou ao obter o pacote (`EACCES`, rede restrita). As cinco tentativas offline confirmaram que o CLI não está em cache (`ENOTCACHED`). Foi usado **axe-core 4.10.3 já disponível localmente**, injectado em Chromium headless, sem alterar o HTML ou desactivar regras para obter um resultado favorável.
- **50 avaliações WCAG A/AA**: 21 páginas da tabela, Centro de Dia, 404, pesquisa vazia e pesquisa com resultados, em **360 e 1280 px**. Zero violações, zero overflow, um H1, hierarquia sem saltos, sem imagens locais quebradas. As cinco páginas de §6 foram ainda verificadas com as regras axe por omissão, incluindo boas práticas, em 360 px: **zero violações** após identificar a barra de contactos do topo como região acessível com nome.
- **27 destinos internos do cabeçalho/rodapé respondem 200**; zero ligações vazias. Páginas 404 continuam a devolver 404, com mensagem, pesquisa, telefone e alternativas úteis.
- Padrões e templates passaram na validação nativa do parser Gutenberg (`tools/validate-native.php`), sem blocos inválidos. Sintaxe PHP válida; `git diff --check` passou.
- Inspeccionadas as capturas de Serviços em desktop e Contactos em 360 px. Resultados completos em `tools/remaining-verification.json` e `tools/remaining-section6.json`.
- Não foram enviados formulários. Não substitui a avaliação manual com leitor de ecrã, AccessMonitor nem wp-08.
- O verificador existente de interações passou: teclado, foco do menu, slideshow manual, cartões e eventos. Confirmado por comparação com o Git que nenhuma coluna além de Estado foi alterada no backlog.

O verificador JS segue os caminhos locais de Playwright/Chromium usados pelos verificadores existentes. `AISA_AXE_PATH` permite indicar outra cópia local de `axe.min.js`.

## Pendências e backlog

- **P15 🔄**: não foi possível obter a composição nominativa dos Órgãos Sociais. Estão presentes os três órgãos estatutários e a ligação a Documentos; nenhum nome foi inventado.
- **P16 🔄**: não foi possível recuperar o texto e os logótipos de Parcerias. O padrão aceita anexos com meta `_aisa_partner_name` e usa o nome como `alt`; não foram fabricados logótipos.
- **P29 🔄**: página explicitamente provisória com estado parcial, PDFs 2017–2025, contactos, data e avaliação prevista. **Não é HTML exportado do gerador oficial.** A consulta indexada do [gerador oficial](https://www.acessibilidade.gov.pt/gerador/) foi possível, mas o acesso directo falhou. O navegador incorporado estava indisponível e a automação do Chrome foi recusada: `Computer Use was not approved to use Google Chrome`. Falta obter o HTML oficial e regenerá-lo depois da avaliação wp-08. Nenhum relatório AccessMonitor ou avaliação manual foi inventado.
- **P26 🔄 até A10**, conforme solicitado.
- P17 está concluída **quanto à estrutura abrangida por 02-04**; os ficheiros e registos de documentos, incluindo os regulamentos por serviço, continuam a ser RF08/02-05. Não foram marcados RF06–RF10 como concluídos.
- Só a coluna Estado dos itens abrangidos foi alterada no backlog. P15/P16/P29 não receberam ✅ porque faltam requisitos materiais; os restantes itens previstos em §4.8 receberam ✅, com P26 em 🔄.

## Fontes de conteúdo

Recuperadas pela pesquisa pública em 08/11/2026; a cache consultada indicava rastreios anteriores. Não foi obtido WXR.

- [SAD](https://www.aisaipss.pt/servico-apoio-domiciliario/), [AAAF](https://www.aisaipss.pt/atividades-de-animacao-e-de-apoio-a-familia/), [CAF](https://www.aisaipss.pt/componente-de-apoio-a-familia/), [Saúde, anteriormente Qualidade de Vida](https://www.aisaipss.pt/qualidade-de-vida/).
- [Candidaturas](https://www.aisaipss.pt/candidaturas/), [Protocolos](https://www.aisaipss.pt/protocolos/), [Quem Somos](https://www.aisaipss.pt/quem-somos/), [Equipa Técnica](https://www.aisaipss.pt/equipa-tecnica/).
- [Contactos e horário](https://www.aisaipss.pt/contactos/), [Apoio, NIF, IRS e voluntariado](https://www.aisaipss.pt/apoie-a-nossa-causa/).
- AEC: descrição da [página inicial](https://www.aisaipss.pt/) e do [Regulamento de Infância e Juventude](https://www.aisaipss.pt/wp-content/uploads/2022/12/Regulamento-Interno-Resposta-Infancia-e-Juventude.pdf). Torne-se Sócio: texto publicado na página inicial; não foram inferidas quotas.
- Órgãos estatutários: [Estatutos](https://www.aisaipss.pt/wp-content/uploads/2022/04/Estatutos-AISA.pdf); a composição nominativa continua em falta. O site novo deve receber os documentos da migração 02-05, sem depender destas ligações como substituição do arquivo local.
