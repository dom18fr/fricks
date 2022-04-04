FROM alpine AS certs

WORKDIR /certs
RUN apk add -U openssl && \
  openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout fricks.key -out fricks.crt -subj \
  "/C=FR/ST=France/L=Lyon/O=Clever Age/OU=Forge/CN=fricks.com"

FROM nginx:1.21.1
COPY --from=certs /certs /etc/nginx/certs
