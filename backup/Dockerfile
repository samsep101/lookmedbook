FROM alpine:3.19

# Установка необходимых пакетов
RUN apk add --no-cache mysql-client bash findutils curl

# Скачиваем Supercronic (последнюю версию)

ENV SUPERCRONIC_URL=https://github.com/aptible/supercronic/releases/download/v0.2.46/supercronic-linux-amd64 \
    SUPERCRONIC_SHA1SUM=5bcefed628e32adc08e32634db2d10e9230dbca0 \
    SUPERCRONIC=supercronic-linux-amd64

RUN curl -fsSLO "$SUPERCRONIC_URL" \
 && echo "${SUPERCRONIC_SHA1SUM}  ${SUPERCRONIC}" | sha1sum -c - \
 && chmod +x "$SUPERCRONIC" \
 && mv "$SUPERCRONIC" "/usr/local/bin/${SUPERCRONIC}" \
 && ln -s "/usr/local/bin/${SUPERCRONIC}" /usr/local/bin/supercronic


# Копируем скрипты и crontab
COPY backup.sh prune.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/backup.sh /usr/local/bin/prune.sh
COPY crontab /etc/crontab

# Создаём директорию для логов (если нужно)
RUN mkdir -p /var/log

CMD ["supercronic", "/etc/crontab"]
