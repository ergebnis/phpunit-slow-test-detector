#!/usr/bin/env bash

set -o errexit

PHPUNIT_VERSION="${1}"

if [ -z "${PHPUNIT_VERSION}" ]; then
    echo "Usage: tests-phar.sh <phpunit-version>"
    echo "  phpunit-version: e.g. 7.5.0, 8.5.19, 9.0.0, 10.0.0, 11.0.0, 12.0.0, 13.0.0"
    exit 1
fi

case "${PHPUNIT_VERSION}" in
    7.5.0)  DIRECTORY="PHPUnit07" ;;
    8.5.19) DIRECTORY="PHPUnit08" ;;
    9.0.0)  DIRECTORY="PHPUnit09" ;;
    10.0.0) DIRECTORY="PHPUnit10" ;;
    11.0.0) DIRECTORY="PHPUnit11" ;;
    12.0.0) DIRECTORY="PHPUnit12" ;;
    13.0.0) DIRECTORY="PHPUnit13" ;;
    *)
        echo "Unknown PHPUnit version: ${PHPUNIT_VERSION}"
        exit 1
        ;;
esac

PHP_MAJOR_MINOR=$(php --run 'echo PHP_MAJOR_VERSION . "." . PHP_MINOR_VERSION;')

cp --archive /app/src/. /app/work
cd /app/work

composer remove ergebnis/composer-normalize ergebnis/license ergebnis/php-cs-fixer-config phpstan/extension-installer phpstan/phpstan phpstan/phpstan-deprecation-rules phpstan/phpstan-phpunit phpstan/phpstan-strict-rules rector/rector --ansi --dev --no-interaction --no-progress --quiet
composer config platform.php --unset

composer require "phpunit/phpunit:^${PHPUNIT_VERSION}" --ansi --no-interaction --no-progress --quiet --update-with-all-dependencies
composer update --ansi --no-interaction --no-progress --quiet

php bin/remove-autoload-configuration.php
composer install --ansi --no-interaction --no-progress --quiet

vendor/bin/phpunit --colors=always --configuration="test/Phar/${DIRECTORY}/phpunit.xml"
