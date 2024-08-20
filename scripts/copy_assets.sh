#!/bin/bash

# Caminhos de origem e destino
BOOTSTRAP_CSS_SOURCE="./vendor/twbs/bootstrap/dist/css/bootstrap.min.css"
BOOTSTRAP_JS_SOURCE="./vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"

DEST_CSS="./public/assets/lib/bootstrap/css"
DEST_JS="./public/assets/lib/bootstrap/js"

echo 'copiando...'

# Copiar arquivos CSS
if cp $BOOTSTRAP_CSS_SOURCE $DEST_CSS/; then
    echo "Arquivo CSS copiado com sucesso!"
else
    echo "Falha ao copiar o arquivo CSS."
fi

# Copiar arquivos JS
if cp $BOOTSTRAP_JS_SOURCE $DEST_JS/; then
    echo "Arquivo JS copiado com sucesso!"
else
    echo "Falha ao copiar o arquivo JS."
fi