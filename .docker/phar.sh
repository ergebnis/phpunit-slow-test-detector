#!/usr/bin/env bash

set -o errexit

composer remove phpstan/extension-installer --ansi --dev --no-interaction --no-progress --quiet
composer remove phpunit/phpunit --ansi --no-interaction --ignore-platform-reqs --no-progress --quiet
composer install --ansi --no-interaction --no-progress --quiet

jq 'del(.git)' box.json > box.json.tmp && mv box.json.tmp box.json

PHIVE_HOME=.build/phive phive install --trust-gpg-keys 0x2DF45277AEF09A2F,0x033E5F8D801A2F8D

.phive/box validate box.json --ansi
.phive/box compile --ansi --config=box.json
.phive/box info .build/phar/phpunit-slow-test-detector.phar --ansi --list

git checkout HEAD -- composer.json composer.lock box.json

composer install --ansi --no-interaction --no-progress --quiet
