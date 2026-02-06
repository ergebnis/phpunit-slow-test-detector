FROM php:7.4-cli

# hadolint ignore=DL3008,DL3013
RUN apt-get update \
    && apt-get install --no-install-recommends --yes \
        git \
        gnupg \
        jq \
        libcurl4-openssl-dev \
        libonig-dev \
        libxml2-dev \
        patch \
        python3-pip \
        unzip \
        wget \
    && pip3 install --no-cache-dir yamllint \
    && docker-php-ext-install \
        dom \
        mbstring \
        opcache \
        pcntl \
        posix \
        xmlwriter \
    && apt-get clean \
    && rm --force --recursive /var/lib/apt/lists/*

RUN echo "phar.readonly=0" >> /usr/local/etc/php/conf.d/phar.ini \
    && echo "memory_limit=-1" >> /usr/local/etc/php/conf.d/memory.ini

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN wget --quiet --output-document=/usr/local/bin/phive https://phar.io/releases/phive.phar \
    && chmod +x /usr/local/bin/phive

WORKDIR /app

ENTRYPOINT ["/bin/bash"]
