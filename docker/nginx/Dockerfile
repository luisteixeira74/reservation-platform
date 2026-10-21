
FROM node:22-bookworm-slim AS frontend

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY . .
RUN npm run build


FROM nginx:1.28-alpine

COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

COPY --from=frontend /app/public/ /var/www/html/public/
