# Business risks — AISA IPSS

> Subprocesso `business`, passo 3. Este é um documento vivo: pode ser actualizado sempre que surgirem riscos novos.
> Gerado por `business-03-risks` em 2026-11-02.
> Inputs: `specs/core-01-briefing.md` e `specs/business-02-proposal.md` (riscos R1 a R8). Os riscos R9 a R11 são novos e foram identificados nesta análise.
> Matriz confirmada pelo operador em 2026-11-02.
> As mitigações abaixo dizem o que fazer, não como. A forma técnica de as concretizar fica para `wp-02-architecture`.

---

## Decisões de projecto tomadas por causa dos riscos

| Decisão | Origem | Data |
|---|---|---|
| **O lançamento a 15/11/2026 é fixo.** O aniversário (18/11) não serve de margem. | R2 | 2026-11-02 |
| **O lançamento é feito em duas fases.** A publicação automática na Meta (Facebook e Instagram) e a ligação ao Brevo passam para a fase 2, depois de 15/11. | R2, R5, R10, R11 | 2026-11-02 |

**Fase 1, lançamento a 15/11:**
- estrutura e navegação novas;
- páginas de serviço;
- Sobre, Equipa e Órgãos Sociais;
- documentos legais e parcerias;
- contactos;
- artigos migrados;
- agenda de eventos;
- depoimentos (os 3 existentes);
- formulário de voluntariado;
- Cookiebot com GA4/GTM (Consent Mode v2);
- WCAG 2.1 AA e Declaração de Acessibilidade;
- redirecionamentos 301.

**Fase 2, depois do lançamento:**
- formulário da newsletter com ligação ao Brevo e campanha de confirmação aos associados;
- publicação automática no Facebook;
- Instagram, se houver ferramenta estável;
- novos depoimentos, à medida que forem recolhidos.

## 1. Riscos kill-switch (top 3)

### KS1 · R2 — Prazo, âmbito e acessos
**Probabilidade: alta · Impacto: alto**

São 13 dias para cerca de 15 páginas, migração, integrações, agenda, depoimentos e WCAG AA, e a data de 15/11 é fixa. Ainda não temos os acessos (WordPress actual, alojamento, Meta, Brevo). Cada dia de espera corta tempo de desenvolvimento, e um atraso na aprovação do cliente pode inviabilizar a data.

**Mitigação:**
- Lançamento em duas fases (decisão acima).
- Pedir todos os acessos ao cliente a **03/11**.
- Pedir ao cliente uma ronda de revisão com prazo fechado.
- Tudo o que não for essencial para a fase 1 passa automaticamente para a fase 2, em vez de atrasar a data.

### KS2 · R4 — Consentimento RGPD para nomes e depoimentos
**Probabilidade: média · Impacto: alto**

O tom "o seu José" e os depoimentos expõem nomes reais de pessoas idosas, dependentes e menores. O consentimento já recolhido pode cobrir só a imagem. Um único caso de exposição sem autorização pode trazer uma queixa à CNPD e danos de reputação a uma IPSS que vive de confiança.

**Mitigação:**
- Criar uma declaração de consentimento específica para o depoimento, que inclua nome, texto e fotografia.
- **Nunca publicar o nome de menores.**
- No tipo de conteúdo dos depoimentos, impedir a publicação sem o registo do consentimento.
- Confirmar o âmbito do consentimento existente (questão Q4 de `business-02-proposal.md`) antes de publicar os 3 depoimentos migrados.

### KS3 · R3 — Falhar a WCAG 2.1 AA
**Probabilidade: média · Impacto: alto**

É uma obrigação legal (DL 83/2018), ligada aos apoios públicos. O design system aprovado já é AA. O risco está noutros pontos:
- nos componentes de terceiros (banner do Cookiebot, formulários CF7);
- nas cores por área com texto;
- nos PDFs antigos dos relatórios;
- no conteúdo introduzido depois do lançamento (texto alternativo das imagens, hierarquia de títulos).

**Mitigação:**
- Testes de acessibilidade automáticos e manuais antes do lançamento.
- Gate de contraste WCAG aplicado também às cores por área.
- Declaração de Acessibilidade publicada, com mecanismo de contacto.
- Formação da Mercês com um módulo de acessibilidade editorial.
- Avisar o cliente de que os PDFs antigos provavelmente não são acessíveis, e assumir isso na Declaração de Acessibilidade.

## 2. Matriz de risco

| ID | Risco | Categoria | Prob. | Impacto | Mitigação |
|---|---|---|---|---|---|
| R1 | **Blog, agenda e depoimentos ficam vazios ou parados** por dependerem de uma só pessoa (a Mercês). Um site parado parece pior do que o actual, como se vê na newsletter do CPE e no site da ABESA. | Operação / conteúdo | Alta | Alto | Lançar com os artigos migrados e a agenda já preenchida com as festas conhecidas. Esconder o bloco de depoimentos até haver pelo menos 3. Ritmo realista para a newsletter (mensal). A publicação automática no Facebook (fase 2) reduz o trabalho duplicado. |
| R2 | Prazo, âmbito e acessos | Projecto | Alta | Alto | Ver **KS1** |
| R3 | Falhar a WCAG 2.1 AA | Legal | Média | Alto | Ver **KS3** |
| R4 | Consentimento RGPD para nomes e depoimentos | Legal / reputação | Média | Alto | Ver **KS2** |
| R5 | **A publicação automática na Meta falha** ou deixa de funcionar com mudanças na API. | Técnico | Alta | Baixo | Tratar como extra e não como função crítica, na fase 2. Publicar à mão quando falhar. O Instagram só entra se houver ferramenta estável. |
| R6 | **Data de abertura das novas instalações (Lar e Centro de Dia) incerta.** Anunciar uma data e falhá-la contraria o valor "nunca promete o que não cumpre". | Reputação | Média | Médio | Não publicar datas até estarem confirmadas. Usar uma página "Novas instalações, em construção" com a imagem da obra (`assets/capa_facebook.jpg`). |
| R7 | **A manutenção fica com terceiros.** Plugins desactualizados tornam o site vulnerável e partem as integrações. | Técnico / segurança | Média | Alto | Usar o mínimo de plugins. Entregar um documento com a lista de plugins e as suas funções. Validar com o fornecedor quem actualiza o quê e quando. |
| R8 | **O GA regista dados antes do consentimento.** | Legal | Média | Médio | Consent Mode v2 com o Cookiebot e o GTM, testado antes do lançamento. |
| R9 | **A migração quebra os URLs.** Links antigos, partilhas no Facebook e resultados no Google dão erro 404. Há o risco de perder documentos obrigatórios por lei. | Técnico / reputação | Média | Médio | Fazer o inventário dos URLs actuais e configurar redirecionamentos 301. Fazer uma checklist para garantir que todos os documentos de AISA → Documentos chegam ao site novo. |
| R10 | **O plano gratuito do Brevo limita a 300 envios por dia.** Uma newsletter para toda a base de associados pode não sair num só dia. | Operação / custo | Média | Baixo | Confirmar o tamanho da base. Se passar de 300 contactos, dividir o envio por vários dias ou passar a um plano pago (decisão do cliente). Fica na fase 2. |
| R11 | **A base de associados pode não ter consentimento para receber a newsletter.** Fazer o envio sem base legal é uma infracção RGPD. | Legal | Média | Médio | Primeira campanha de confirmação (*opt-in*) aos associados. Dupla confirmação no formulário do site. Fica na fase 2. |

### Leitura da matriz (probabilidade × impacto)

| | Impacto baixo | Impacto médio | Impacto alto |
|---|---|---|---|
| **Prob. alta** | R5 | — | R1, **R2** |
| **Prob. média** | R10 | R6, R8, R9, R11 | **R3**, **R4**, R7 |

O R1 e o R7 têm impacto alto mas não são kill-switch. Não impedem o lançamento, mas desgastam o site ao longo do tempo. O R1 é o primeiro candidato a subir para kill-switch se não houver ritmo de publicação nos primeiros 2 meses.

## 3. Histórico de actualizações

| Data | Versão | Alteração | Origem |
|---|---|---|---|
| 2026-11-02 | 1.0 | Criação: R1 a R8 vindos de `business-02-proposal.md`, mais R9 a R11 novos. Top 3 kill-switch: R2, R4, R3. Decisões: data de 15/11 fixa e lançamento em duas fases. | `business-03-risks`, primeira execução |
