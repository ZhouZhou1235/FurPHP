#!/bin/bash

if php -v; then
    echo "PINKCANDY: have php. run test web server..."
    php -S localhost:80
else
    echo "PINKCANDY: not have php."
fi
