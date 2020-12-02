FROM nginx:alpine

# get npm
RUN apk --no-cache add nodejs npm

COPY . /var/www/

COPY docker/nginx/config/etc/nginx/nginx.conf /etc/nginx/nginx.conf

COPY docker/nginx/config/etc/nginx/conf.d /etc/nginx/conf.d

COPY docker/nginx/config/etc/nginx/templates /etc/nginx/templates

COPY docker/nginx/config/etc/ssl /etc/ssl

WORKDIR /var/www/

RUN npm install

RUN mkdir -p /var/cache/nginx && \
    chown -R nobody:root /var/cache/nginx

#CMD ["nginx", "-g", "daemon off;"]
EXPOSE 80 443