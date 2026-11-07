# wp-04-02-04 — Páginas restantes

> Gerado por `wp-04-project-manager` em 2026-11-07. Fase de implementação 02 (beta M1). Depende de: 01-05 (modelos e padrões); 02-03 (formulários em P24 e P25).

## 1. Tipo

**Blocos / tema** (composição de páginas com os modelos e padrões existentes, mais os padrões novos abaixo).

## 2. Páginas / requisitos cobertos

`wp-03-product-backlog.md`:
- **P02**, **P04**, **P06**–**P19**, **P24**–**P26**, **P28**–**P30**;
- **G02** (conteúdo completo do rodapé);
- **G03** (menu completo).

A **P27** é tratada no 02-02. O **texto** destas páginas vem do 02-05 (RF07) ou é recolocado aqui a partir do site actual. Esta spec garante a **estrutura**, os templates, os padrões e a criação das páginas com os slugs certos.

## 3. Âmbito técnico

**Padrões novos** (`themes/aisa/patterns/`):

| Padrão | Uso | Notas |
|---|---|---|
| `servicos-grupos` | P02 | Um grupo por fase da vida: `.valencia-icon` + `h2` + frase + cartões `.service-card` (com fotografia 4:3 e faixa da valência). Layout assimétrico por grupo (por ex. 1 cartão largo + 2 estreitos), **não** uma grelha uniforme. Apelo final "Não sabe qual escolher? Ligue-nos" (`.section--muted` com A1 = Base) |
| `indice-ancoras` | P10 | Lista de ligações para as secções `#…` de cada terapia |
| `pessoas` | P14, P15 | Grelha de pessoas: fotografia opcional (com fallback para iniciais **não**; sem fotografia = só o nome e a função, ou só a função); `h3` com nome ou função |
| `logotipos` | P16 | Logótipos dos parceiros, `alt` = nome |
| `irs-donativo` | P19 | NIF em destaque (copiável), explicação de 1% (L27), IBAN opcional + nota do recibo (L28). Sem pressão |
| `contacto-completo` | P25 | `aisa/telefone` grande, e-mail, morada, horário, **imagem estática do mapa** (`assets/img/mapa-aisa.png` com `alt` "Mapa: AISA, …", gerada uma vez e guardada no tema; com ligação "Abrir no Google Maps" `target=_blank` e o aviso "abre noutra janela"), formulário de contacto, informação do prestador (L17), Livro de Reclamações (se A9) |
| `legal-reservado` | P26, P28 | Bloco de aviso visível "Texto em validação pela AISA" + estrutura de `h2` mínima de L14/L16 (identificação, finalidades, prazos, subcontratantes, direitos, CNPD, data). **A P26 não se publica no lançamento sem a A10** (DoR) |
| `pagina-vazia-404` | P30 | Usado em `404.html` / `search.html` (01-02 já tem uma versão mínima; aqui fica a final) |

**Páginas a criar ou confirmar** (slug, template, valência), conforme `wp-02` §3.3 e `wp-03` §4.1:

| ID | Título | Slug | Template | Valência |
|---|---|---|---|---|
| P02 | Serviços | `servicos` | `page.html` + `servicos-grupos` | — |
| P04 | Serviço de Apoio Domiciliário | `servico-apoio-domiciliario` | `page-servico` | `sad` |
| P06 | Novas instalações: Lar e Centro de Dia | `novas-instalacoes` | `page.html` (imagem `assets/capa_facebook.jpg`) | `generico` |
| P07 | Actividades de Animação e de Apoio à Família (AAAF) | `atividades-de-animacao-e-de-apoio-a-familia` | `page-servico` | `infancia` |
| P08 | Componente de Apoio à Família (CAF) | `componente-de-apoio-a-familia` | `page-servico` | `infancia` |
| P09 | Actividades de Enriquecimento Curricular (AEC) | `atividades-de-enriquecimento-curricular` | `page-servico` | `infancia` |
| P10 | Saúde e Reabilitação | `saude-e-reabilitacao` | `page-servico` + `indice-ancoras` | `generico` |
| P11 | Candidaturas | `candidaturas` | `page-servico` (sem a caixa de horário) | `generico` |
| P12 | Protocolos | `protocolos` | `page.html` | `generico` |
| P13 | Quem Somos | `quem-somos` | `page.html` + `aisa/provas` | — |
| P14 | Equipa Técnica | `equipa-tecnica` | `page.html` + `pessoas` | — |
| P15 | Órgãos Sociais | `orgaos-sociais` | `page.html` + `pessoas` | — |
| P16 | Parcerias | `parcerias` | `page.html` + `logotipos` | — |
| P17 | Documentos | `documentos` | `page.html` + `aisa/documentos` | — |
| P18 | Torne-se Sócio | `torne-se-socio` | `page.html` + `contacto` | — |
| P19 | Apoie a Nossa Causa | `apoie-a-nossa-causa` | `page.html` + `irs-donativo` | — |
| P24 | Voluntariado | `voluntariado` | `page.html` + formulário (02-03) | — |
| P25 | Contactos | `contactos` | `page.html` + `contacto-completo` | — |
| P26 | Política de Privacidade | `politica-de-privacidade` | `page.html` + `legal-reservado` | — |
| P28 | Termos de Utilização | `termos` | `page.html` + `legal-reservado` | — |
| P29 | Declaração de Acessibilidade | `declaracao-de-acessibilidade` | `page.html` | — |
| P30 | 404 / Pesquisa | — | `404.html`, `search.html` | — |

**P29:**
- Gerada no gerador oficial (https://www.acessibilidade.gov.pt/gerador/).
- Estado: "parcialmente conforme" até à avaliação do M2.
- Conteúdo não acessível: os PDFs antigos (relatórios de 2017 a 2025).
- Mecanismo de contacto: o e-mail da A6 e o telefone.
- Avaliação: AccessMonitor + manual.
- Data.
- Volta a gerar-se depois do `wp-08`.

**Rodapé completo (G02):**
- o texto [A CONFIRMAR — BQ2] é substituído quando a AISA responder (até lá fica);
- as ligações legais apontam para P26–P29.

**Menu completo (G03):** todos os itens de `wp-03` §5.2 apontam para páginas reais, sem `#`.

**Regras de conteúdo** (DoD #8):
- sem valores de comparticipação;
- sem datas nem moradas das novas instalações;
- sem nomes de utentes;
- a Equipa Técnica só com quem deu acordo (A11): até lá, **só as funções**;
- o CEE não é mencionado até à A2.

## 4. Tarefas ordenadas

1. Padrões novos (§3).
2. Criar ou confirmar as páginas da tabela (slug, template, valência, imagem destacada). Se o 02-05 já tiver importado as páginas antigas, **reutilizá-las** (mesmo ID) em vez de duplicar.
3. Recolocar o texto actual nas secções. Se o 02-05 ainda não tiver corrido, copiar do site público.
4. Mapa estático da P25.
5. P29 pelo gerador oficial.
6. Rodapé e menu completos.
7. Verificar §6.
8. Marcar ✅ em P02, P04, P06–P19, P24, P25, P28 (com espaço reservado aceite pelo operador), P29, P30, G02 e G03. A **P26** fica 🔄 até à A10.

## 5. Critérios de aceitação

- Todas as páginas da tabela existem com o slug exacto, o template e a valência correctos, e com uma imagem no topo (critério 1 do PRD).
- Cada página de serviço tem telefone clicável, regulamentos ligados (se existirem documentos) e a frase da comparticipação, sem valores (critério 2 do PRD).
- O menu e o rodapé não têm ligações `#` nem 404.
- P25: não carrega nenhum iframe nem pedido a `maps.googleapis.com` (só a imagem local).
- P14: nenhum nome nem fotografia sem acordo (A11).
- Critério 12 do PRD: as regras de conteúdo de §3 cumprem-se.
- axe: 0 erros críticos ou graves em todas as páginas da tabela; 360 px sem deslocamento horizontal.

## 6. Comandos de verificação

```bash
cd /c/var/www/WordPress/aisa
for s in servicos servico-apoio-domiciliario novas-instalacoes atividades-de-animacao-e-de-apoio-a-familia componente-de-apoio-a-familia atividades-de-enriquecimento-curricular saude-e-reabilitacao candidaturas protocolos quem-somos equipa-tecnica orgaos-sociais parcerias documentos torne-se-socio apoie-a-nossa-causa voluntariado contactos politica-de-privacidade termos declaracao-de-acessibilidade; do echo "$s $(curl -s -o /dev/null -w '%{http_code}' http://aisa.wordpress.test/$s/)"; done
for s in servicos centro-de-dia contactos documentos voluntariado; do npx --yes @axe-core/cli "http://aisa.wordpress.test/$s/" --exit || echo "FALHA $s"; done
curl -s http://aisa.wordpress.test/contactos/ | grep -c "maps.googleapis\|<iframe"   # 0
curl -s http://aisa.wordpress.test/ | grep -o 'href="#"' | wc -l   # só o "Preferências de cookies"
curl -s http://aisa.wordpress.test/servico-apoio-domiciliario/ | grep -iE "€|euros" | head   # vazio
```

## 7. Prompt para o `wp-05-developer`

```
/wp-05-developer Implementa specs/wp-04-02-04-paginas-restantes.md.
Cria os padrões de §3 (sem grelhas uniformes) e todas as páginas da tabela com slug/template/valência exactos, reutilizando as páginas importadas no 02-05 se já existirem. Texto actual do site; mapa estático em Contactos; Declaração de Acessibilidade pelo gerador oficial; privacidade/termos com espaço reservado. Menu e rodapé completos. Corre §6 e marca ✅ em wp-03-product-backlog.md conforme §4.8 (P26 fica 🔄 até A10).
```
