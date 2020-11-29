FROM nginx:alpine

# get npm
RUN apk --no-cache add nodejs npm

COPY . /var/www/

COPY docker/nginx/config/etc/nginx/nginx.conf /etc/nginx/nginx.conf

COPY docker/nginx/config/etc/nginx/ngx_conf /etc/nginx/ngx_conf

COPY docker/nginx/config/etc/ssl /etc/ssl

WORKDIR /var/www/

RUN npm install

CMD ["nginx", "-g", "daemon off;"]
EXPOSE 80 443