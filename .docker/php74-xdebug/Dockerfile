# hadolint ignore=DL3006
FROM phpunit-slow-test-detector-php74

RUN pecl install xdebug-3.1.6 \
    && docker-php-ext-enable xdebug

ENV XDEBUG_MODE=coverage

ENTRYPOINT ["/bin/bash"]
