"""Development only: download official OFL fonts and convert to local WOFF2.

Requires fonttools[woff]. Run from any directory: python tools/fetch-fonts.py
No external requests are made by the installed theme.
"""
from io import BytesIO
from pathlib import Path
from urllib.parse import quote
from urllib.request import urlopen
from fontTools.ttLib import TTFont

DESTINATION = Path(__file__).resolve().parents[1] / 'assets' / 'fonts'
ORIGIN = 'https://raw.githubusercontent.com/google/fonts/main/ofl/'
FILES = {
    'fraunces-normal': 'fraunces/Fraunces[SOFT,WONK,opsz,wght].ttf',
    'fraunces-italic': 'fraunces/Fraunces-Italic[SOFT,WONK,opsz,wght].ttf',
    'atkinson-400-normal': 'atkinsonhyperlegible/AtkinsonHyperlegible-Regular.ttf',
    'atkinson-400-italic': 'atkinsonhyperlegible/AtkinsonHyperlegible-Italic.ttf',
    'atkinson-700-normal': 'atkinsonhyperlegible/AtkinsonHyperlegible-Bold.ttf',
    'atkinson-700-italic': 'atkinsonhyperlegible/AtkinsonHyperlegible-BoldItalic.ttf',
}


def download(relative):
    with urlopen(ORIGIN + quote(relative, safe='/'), timeout=30) as response:
        return response.read()


def main():
    # Download everything before writing to avoid partially installed families.
    fonts = {name: download(source) for name, source in FILES.items()}
    licenses = {family: download(family + '/OFL.txt') for family in ('fraunces', 'atkinsonhyperlegible')}
    DESTINATION.mkdir(parents=True, exist_ok=True)
    for name, data in fonts.items():
        font = TTFont(BytesIO(data))
        # Full official character coverage includes Latin and Latin Extended.
        font.flavor = 'woff2'
        font.save(DESTINATION / (name + '.woff2'))
    for family, data in licenses.items():
        (DESTINATION / (family + '-OFL.txt')).write_bytes(data)
    print('Six local WOFF2 fonts and both OFL licenses installed.')


if __name__ == '__main__':
    main()
