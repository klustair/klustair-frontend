FROM nginx:1.20-alpine

# get npm
RUN apk --no-cache add nodejs npm

COPY --chown=nginx:nginx . /var/www/

COPY --chown=nginx:nginx docker/nginx/config/etc/nginx/nginx.conf /etc/nginx/nginx.conf

COPY --chown=nginx:nginx docker/nginx/config/etc/nginx/conf.d /etc/nginx/conf.d

COPY --chown=nginx:nginx docker/nginx/config/etc/nginx/templates /etc/nginx/templates

COPY --chown=nginx:nginx docker/nginx/config/etc/ssl /etc/ssl

RUN chown -R nginx:nginx /etc/nginx/conf.d /var/www /var/cache/nginx /etc/nginx/conf.d

USER nginx
WORKDIR /var/www/

RUN npm install

#CMD ["nginx", "-g", "daemon off;"]
EXPOSE 8080 8443