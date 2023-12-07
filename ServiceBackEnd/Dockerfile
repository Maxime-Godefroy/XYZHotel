# Utilise l'image httpd:latest
FROM httpd:latest

# Active le module Apache pour les URL conviviales
RUN a2enmod rewrite

# Copie les fichiers de ton service Symfony dans le conteneur
COPY ./ServiceBackend /usr/local/apache2/htdocs

# Installe les dépendances avec Composer
RUN apt-get update && apt-get install -y curl \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && cd /usr/local/apache2/htdocs && composer install

# Expose le port 80 pour Apache
EXPOSE 80

# Commande pour démarrer le serveur Apache
CMD ["httpd-foreground"]
