#!/usr/bin/env bash

set -o errexit

PHPUNIT_VERSION="${1}"
DEPENDENCIES="${2}"

if [ -z "${PHPUNIT_VERSION}" ] || [ -z "${DEPENDENCIES}" ]; then
    echo "Usage: tests-end-to-end.sh <phpunit-version> <dependencies>"
    echo "  phpunit-version: e.g. 6.5.0, 7.5.0, 8.5.19, 9.0.0, 10.0.0, 11.0.0, 12.0.0, 13.0.0"
    echo "  dependencies:    lowest or highest"
    exit 1
fi

if [ "${DEPENDENCIES}" != "lowest" ] && [ "${DEPENDENCIES}" != "highest" ]; then
    echo "Invalid dependencies strategy: ${DEPENDENCIES}"
    echo "Must be 'lowest' or 'highest'"
    exit 1
fi

# Map PHPUnit version to end-to-end test directory
case "${PHPUNIT_VERSION}" in
    6.5.0)  DIRECTORY="PHPUnit06" ;;
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

cp --archive /app/src/. /app/work
cd /app/work

composer remove ergebnis/composer-normalize ergebnis/license ergebnis/php-cs-fixer-config phpstan/extension-installer phpstan/phpstan phpstan/phpstan-deprecation-rules phpstan/phpstan-phpunit phpstan/phpstan-strict-rules rector/rector --ansi --dev --no-interaction --no-progress --quiet
composer config platform.php --unset

if [ "${PHPUNIT_VERSION}" = "6.5.0" ]; then
    composer require fakerphp/faker:^1.9.2 "phpunit/phpunit:^${PHPUNIT_VERSION}" --ansi --no-interaction --no-progress --quiet --update-with-all-dependencies
else
    composer require "phpunit/phpunit:^${PHPUNIT_VERSION}" --ansi --no-interaction --no-progress --quiet --update-with-all-dependencies
fi

if [ "${DEPENDENCIES}" = "lowest" ]; then
    composer update --prefer-lowest --ansi --no-interaction --no-progress --quiet
else
    composer update --ansi --no-interaction --no-progress --quiet
fi

PHP_MAJOR=$(php --run 'echo PHP_MAJOR_VERSION;')
PHP_MINOR=$(php --run 'echo PHP_MINOR_VERSION;')

if [ "${PHPUNIT_VERSION}" = "9.0.0" ] && [ "${DEPENDENCIES}" = "lowest" ]; then
    if [ "${PHP_MAJOR}" -ge 8 ] && [ "${PHP_MINOR}" -ge 3 ]; then
        cd vendor/phpunit/phpunit
        wget --output-document=gh-4486.patch --quiet https://github.com/sebastianbergmann/phpunit/commit/0a488f22925b3c8732338ef0fbfe7f13cb4cf1d2.patch
        patch --strip=1 < gh-4486.patch
        cd /app/work
    fi
fi

if [ "${PHPUNIT_VERSION}" = "12.0.0" ] && [ "${DEPENDENCIES}" = "lowest" ]; then
    if [ "${PHP_MAJOR}" -ge 8 ] && [ "${PHP_MINOR}" -ge 5 ]; then
        cd vendor/phpunit/phpunit
        wget --output-document=report-memleaks.patch --quiet https://github.com/sebastianbergmann/phpunit/commit/0eae11435093a25c88b5269de9481f32b26dbe20.patch
        sed -i 's|src/Runner/PhptTestCase.php|src/Runner/PHPT/PhptTestCase.php|g' report-memleaks.patch
        patch --strip=1 < report-memleaks.patch
        cd /app/work
    fi
fi

vendor/bin/phpunit --colors=always --configuration="test/EndToEnd/${DIRECTORY}/phpunit.xml"
