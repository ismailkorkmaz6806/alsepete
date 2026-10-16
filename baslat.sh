#!/bin/bash

# Proje klasörüne geç
cd /home/ismail/Desktop/pazaryeri

# Sunucu zaten çalışmıyorsa arka planda başlat
if ! pgrep -f "artisan serve" > /dev/null; then
    php artisan serve > /dev/null 2>&1 &
    sleep 1
fi

# Tarayıcıyı otomatik aç
xdg-open http://127.0.0.1:8000 > /dev/null 2>&1 &
