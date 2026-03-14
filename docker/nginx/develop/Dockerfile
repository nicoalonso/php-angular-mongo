ARG NGINX_VERSION=1.25

### NGINX
FROM nginx:${NGINX_VERSION}-alpine

COPY ./docker/nginx/develop/conf.d /etc/nginx/conf.d

# Core
RUN mkdir -p /srv/app/core
COPY ./core/public /srv/app/core/public
