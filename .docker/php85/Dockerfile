FROM php:8.5-cli

# hadolint ignore=DL3008
RUN apt-get update \
    && apt-get install --no-install-recommends --yes \
        git \
        libcurl4-openssl-dev \
        libonig-dev \
        libxml2-dev \
        patch \
        unzip \
        wget \
    && docker-php-ext-install \
        mbstring \
        xmlwriter \
    && apt-get clean \
    && rm --force --recursive /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

ENTRYPOINT ["/bin/bash"]
