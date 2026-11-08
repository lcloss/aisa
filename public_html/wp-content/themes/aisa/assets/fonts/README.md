# Fontes locais pendentes

Os binários e as licenças ainda não puderam ser descarregados: o ambiente
bloqueou o acesso externo. `theme.json` já declara os seis ficheiros WOFF2
esperados. O tema usa as fontes de fallback até serem instalados.

Com acesso aos repositórios oficiais:

    python -m pip install "fonttools[woff]"
    python public_html/wp-content/themes/aisa/tools/fetch-fonts.py

O importador obtém Fraunces e Atkinson Hyperlegible de google/fonts, com as
licenças OFL, conserva a cobertura completa Latin/Latin Extended e converte
para WOFF2. Nenhum pedido a serviços de fontes é feito pelo tema em execução.

Repetir as verificações de VERIFICATION.md antes de concluir T01.
