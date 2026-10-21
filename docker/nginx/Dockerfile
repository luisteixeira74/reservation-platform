# Imagem final do Nginx.
FROM nginx:1.28-alpine

COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# Copia os assets compilados previamente pelo workflow.
COPY public/ /var/www/html/public/