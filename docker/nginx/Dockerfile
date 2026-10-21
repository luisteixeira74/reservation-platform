# Compila o frontend.
FROM node:22-bookworm-slim AS frontend

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY . .
RUN npm run build


# Imagem final do Nginx.
FROM nginx:1.28-alpine

COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# Copia os arquivos públicos, incluindo os assets compilados.
COPY --from=frontend /app/public/ /var/www/html/public/