#!/usr/bin/env bash

set -o errexit

cp --archive /app/src/. /app/work
cd /app/work

composer remove ergebnis/php-cs-fixer-config --ansi --dev --no-interaction --no-progress --quiet
composer require phpunit/phpunit:^7.5.0 --ansi --no-interaction --no-progress --quiet --update-with-all-dependencies

vendor/bin/phpunit --colors=always --configuration=test/Unit/phpunit.xml --coverage-text
