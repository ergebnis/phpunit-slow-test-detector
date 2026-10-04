FROM php:8.0-cli

# Debian bullseye has reached its end of life, so its packages are only available from archive.debian.org
# hadolint ignore=DL3008
RUN printf '%s\n' \
        'deb http://archive.debian.org/debian bullseye main' \
        'deb http://archive.debian.org/debian bullseye-updates main' \
        'deb http://archive.debian.org/debian-security bullseye-security main' \
        > /etc/apt/sources.list \
    && apt-get update \
    && apt-get install --no-install-recommends --yes \
        git \
        libcurl4-openssl-dev \
        libonig-dev \
        libxml2-dev \
        patch \
        unzip \
        wget \
    && docker-php-ext-install \
        dom \
        mbstring \
        xmlwriter \
    && apt-get clean \
    && rm --force --recursive /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

ENTRYPOINT ["/bin/bash"]
