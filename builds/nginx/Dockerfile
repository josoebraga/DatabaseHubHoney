FROM nginx:latest

LABEL maintainer="Josoé Schmidt Braga"

RUN apt-get update -y

COPY ./builds/nginx/nginx.conf /etc/nginx/

COPY ./builds/nginx/databasehubhoney.conf /etc/nginx/conf.d/

RUN echo "upstream php-upstream { server 127.0.0.1:9000; }" > /etc/nginx/conf.d/upstream.conf

CMD ["nginx"]

EXPOSE 80
EXPOSE 443

