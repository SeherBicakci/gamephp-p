# Apache ve PHP'yi i�eren bir g�r�nt�den ba�l�yoruz
FROM php:7.4-apache

# Apache'nin yap�land�rmas�n� g�ncelle
RUN a2enmod rewrite

# PHP'nin PostgreSQL uzant�s�n� y�kle
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pgsql pdo_pgsql

# PHP kodlar�n�n kopyalanaca�� dizini belirtin
COPY . /var/www/html

# Kendi php.ini dosyam�z� kopyalayal�m
COPY php.ini /usr/local/etc/php/

# Session save path'in var oldu�undan emin olal�m
RUN mkdir -p /var/lib/php/sessions && chmod -R 777 /var/lib/php/sessions


# Apache'nin 80 portundan hizmet verece�ini belirtin
EXPOSE 80