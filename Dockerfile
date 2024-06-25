# Apache ve PHP'yi içeren bir görüntüden baþlýyoruz
FROM php:7.4-apache

# Apache'nin yapýlandýrmasýný güncelle
RUN a2enmod rewrite

# PHP'nin PostgreSQL uzantýsýný yükle
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pgsql pdo_pgsql

# PHP kodlarýnýn kopyalanacaðý dizini belirtin
COPY . /var/www/html

# Kendi php.ini dosyamýzý kopyalayalým
COPY php.ini /usr/local/etc/php/

# Session save path'in var olduðundan emin olalým
RUN mkdir -p /var/lib/php/sessions && chmod -R 777 /var/lib/php/sessions


# Apache'nin 80 portundan hizmet vereceðini belirtin
EXPOSE 80