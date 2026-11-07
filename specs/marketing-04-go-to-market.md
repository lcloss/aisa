# Go-to-market — AISA IPSS

> Subprocesso `marketing`, passo 4. Gerado por `marketing-04-go-to-market` em 2026-11-07.
> Inputs: `specs/marketing-01-personas.md` e `specs/marketing-02-value-proposition.md`. Contexto de âmbito e fases lido em `specs/core-01-briefing.md` e `specs/business-03-risks.md`.
> **`marketing-03-pricing.md` não existe:** o passo foi saltado por decisão do operador (2026-11-07). A AISA não vende nada online e os serviços já têm preço definido internamente (comparticipação segundo as regras da Segurança Social). Este plano não tem componente de receita.
> Canais e plano de lançamento confirmados pelo operador em 2026-11-07.
> Este documento não constrói campanhas de anúncios (`ads-*`, `meta-ads-*`), não decide orçamento e não define identidade visual nem tom de voz (`brand-*`).

---

## 1. Canais de aquisição

| Prioridade | Canal | Persona | Comportamento de descoberta actual | Racional e notas de âmbito |
|---|---|---|---|---|
| 1 | **Pesquisa no Google + Google Business Profile** | P1, P2 | A Ana pesquisa "centro de dia Cascais" à noite no telemóvel. A Sofia pesquisa ATL na zona. | É onde começa a jornada da P1. Os serviços de SEO e a produção de conteúdo estão fora do âmbito (`core-01-briefing.md`); entra só o SEO técnico básico do ramo `wp-*`. **Recomendação ao cliente:** actualizar as fotografias do Google Business Profile (2020, W4) e o link para o site novo. A gestão do perfil é do cliente. |
| 2 | **Telefone** (destino e não origem) | P1, P2 | Telefonam a várias instituições e comparam de memória. | Depois do telefone a conversão é alta (F6). O telefone 214 857 700 é clicável em todas as páginas (R-SEN) e os cliques medem-se no GA4. |
| 3 | **Boca-a-boca e WhatsApp** | P2, P1 | A Sofia ouve falar da AISA no grupo de pais da escola. A Ana mostra a página ao irmão por WhatsApp. | Páginas de serviço partilháveis: endereço limpo e pré-visualização com imagem e título (Open Graph). |
| 4 | **Parceiros que encaminham famílias** (Junta de Freguesia de Alcabideche, CM Cascais, ISS, escolas, pediatras) | P1, P2 (variante CEE) | As famílias chegam encaminhadas por técnicos (público secundário de `marketing-01-personas.md`). | E-mail aos parceiros com o site novo e as ligações directas às páginas de serviço. É acção da AISA, a agência prepara o texto se for pedido. |
| 5 | **Facebook e Instagram** | P3, P2 | A Teresa segue a AISA no Facebook e sabe das festas pelas publicações. | **Fase 1:** a Mercês publica à mão, com ligação para o site. **Fase 2:** publicação automática no Facebook; Instagram só com ferramenta estável (R5). |
| 6 | **Newsletter aos associados (Brevo)** | P3 | Hoje só sabe das festas em cima da hora; não há newsletter. | **Fase 2**, depois da campanha de confirmação de consentimento (R11) e da verificação do limite de envios do plano gratuito (R10). Ritmo mensal (R1). |
| 7 | **Presencial:** festas, aniversário, balcão | P3, P1 | Boca-a-boca na paróquia e no café. | O endereço do site aparece em cartazes, folhetos e materiais das festas. |

---

## 2. Plano de lançamento

| Fase | Data | Marcos |
|---|---|---|
| **Versão demo / alpha** | **o mais cedo possível** (indicação do operador, 2026-11-07) | Versão navegável do site para o cliente validar a estrutura, as páginas-chave e o tom antes do conteúdo final. Acelera as aprovações, que são o maior risco do prazo (R2). Como encurtar o caminho até lá fica para `core-00-orchestrator` e para o ramo `wp-*`. |
| **Preparação** | até 14/11 | Redirecionamentos 301 dos endereços antigos (R9); contentor GTM com GA4 e Consent Mode v2, e eventos de clique no telefone, envio de formulários e descarga de documentos; agenda preenchida com as festas conhecidas; 3 depoimentos migrados (R1); checklist dos documentos obrigatórios. |
| **Lançamento discreto** | **15/11** | Site no ar e verificado, sem anúncio público. |
| **Apresentação pública** | **18/11** (aniversário da AISA) | Publicação no Facebook e no Instagram; anúncio na festa; e-mail aos parceiros com o site novo. O aniversário **não serve de margem**: se o site atrasar, faz-se a festa sem o anúncio do site (`business-03-risks.md`). |
| **Fase 2** | dezembro de 2026 a janeiro de 2027 | Ligação ao Brevo e campanha de confirmação aos associados (*opt-in*); publicação automática no Facebook; Instagram só se houver ferramenta estável. |
| **Consignação do IRS** | a partir de fevereiro de 2027 | Destaque sazonal no site, nas redes e na newsletter (UC11). |
| **Balanço** | 30 e 90 dias depois do lançamento | Leitura do GA4: pesquisas e páginas que trazem visitas, cliques no telefone, envios de formulários e descargas. Serve para validar a hipótese O7 (pesquisas locais com pouca concorrência) e para decidir sobre anúncios (§4). |

---

## 3. Funil e retenção

### 3.1 Famílias (P1, P2)

| Etapa | O que acontece | Medição (GA4) |
|---|---|---|
| Descobrir | Google, WhatsApp, encaminhamento de um parceiro | Origem do tráfego; páginas de entrada |
| Perceber | Página de serviço organizada por necessidade | Visitas às páginas de serviço |
| Confiar | Depoimentos, equipa, documentos | Descargas de documentos |
| **Contactar** | Telefone (principal) ou formulário | **Cliques em `tel:`** e envios do formulário de contacto. É o indicador principal. |

### 3.2 Comunidade (P3)

| Etapa | O que acontece | Medição (GA4) |
|---|---|---|
| Descobrir | Facebook, festas, boca-a-boca | Tráfego vindo do Facebook |
| Explorar | Agenda e blog | Visitas à agenda e ao blog |
| **Participar** | Voluntariado; consignação do IRS (sazonal) | Envios do formulário de voluntariado |
| Ficar | Newsletter (fase 2) | Subscrições confirmadas no Brevo |

### 3.3 Retenção

- **Agenda e blog actualizados**, com um ritmo realista para uma só pessoa (R1). Sem calendário editorial ambicioso no lançamento.
- **Newsletter mensal** aos associados, a partir da fase 2.
- **Momentos sazonais:** festas da Malveira, São João, aniversário (18/11) e IRS (fevereiro).

---

## 4. Proposta de anúncios (para decisão humana)

**Recomendação:** sem anúncios pagos no lançamento. O orçamento (2 000 a 5 000 €) e o prazo não comportam campanhas, e primeiro é preciso medir o tráfego natural (balanço aos 30 e 90 dias).

| Opção | O que é | Persona | Notas |
|---|---|---|---|
| **A — Google Ad Grants** | Crédito de anúncios gratuito no Google para organizações sem fins lucrativos, através do programa Google for Nonprofits. | P1, P2 | **Por confirmar:** elegibilidade da AISA e se já existe candidatura. Encaixa nas pesquisas da Ana ("centro de dia", "apoio domiciliário" na zona). Exige manutenção das campanhas. |
| **B — Meta Ads** | Pequeno orçamento para impulsionar 2 ou 3 publicações (aniversário, IRS), com segmentação geográfica na zona. | P3, P2 | Custo baixo e controlado. Só faz sentido com o site já no ar. |

**Quem decide:** o operador e o cliente decidem se avançam e com que orçamento. A construção das campanhas fica para os workflows `ads-*` e `meta-ads-*`.
