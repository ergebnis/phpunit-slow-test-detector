
DOCKER_COMPOSER:=docker run --rm --volume .:/app/ --workdir=/app/ composer:2.8.9

DOCKER_PHP70:=docker run --rm --volume .:/app/ --workdir=/app/ php:7.0.33-cli
DOCKER_PHP71:=docker run --rm --volume .:/app/ --workdir=/app/ php:7.1.33-cli
DOCKER_PHP72:=docker run --rm --volume .:/app/ --workdir=/app/ php:7.2.33-cli
DOCKER_PHP81:=docker run --rm --volume .:/app/ --workdir=/app/ php:8.1.32-cli
DOCKER_PHP82:=docker run --rm --volume .:/app/ --workdir=/app/ php:8.2.28-cli
DOCKER_PHP83:=docker run --rm --volume .:/app/ --workdir=/app/ php:8.3.21-cli

COMPOSER_REMOVE_INCOMPATIBLE_DEPENDENCIES:=${DOCKER_COMPOSER} config platform.php --unset && ${DOCKER_COMPOSER} remove ergebnis/composer-normalize ergebnis/license ergebnis/php-cs-fixer-config fakerphp/faker phpstan/extension-installer phpstan/phpstan phpstan/phpstan-deprecation-rules phpstan/phpstan-strict-rules phpstan/phpstan-phpunit rector/rector --ansi --dev --no-interaction --quiet
COMPOSER_CONFIGURE_PLATFORM:=${DOCKER_COMPOSER} config platform.php
COMPOSER_REQUIRE:=${DOCKER_COMPOSER} require --ansi --no-interaction --quiet --update-with-all-dependencies
COMPOSER_REVERT:=git checkout HEAD -- composer.json composer.lock

.PHONY: it
it: refactoring coding-standards security-analysis static-code-analysis tests ## Runs the refactoring, coding-standards, security-analysis, static-code-analysis, and tests targets

.PHONY: code-coverage
code-coverage: vendor ## Collects code coverage from running unit tests with phpunit/phpunit
	composer config platform.php --unset; composer remove ergebnis/php-cs-fixer-config --dev --no-interaction --no-progress; composer require phpunit/phpunit:^7.2.0 --no-interaction --quiet --update-with-all-dependencies; vendor/bin/phpunit --configuration=test/Unit/phpunit.xml --coverage-text; ${COMPOSER_REVERT}

.PHONY: coding-standards
coding-standards: vendor ## Lints YAML files with yamllint, normalizes composer.json with ergebnis/composer-normalize, and fixes code style issues with friendsofphp/php-cs-fixer
	yamllint -c .yamllint.yaml --strict .
	composer normalize
	vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.php --diff --show-progress=dots --verbose

.PHONY: dependency-analysis
dependency-analysis: phive vendor ## Runs a dependency analysis with maglnet/composer-require-checker
	.phive/composer-require-checker check --config-file=$(shell pwd)/composer-require-checker.json --verbose

.PHONY: help
help: ## Displays this list of targets with descriptions
	@grep -E '^[a-zA-Z0-9_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}'

.PHONY: phar
phar: phive ## Builds a phar with humbug/box
	.phive/box validate box.json
	composer remove phpstan/extension-installer --dev --no-interaction --quiet
	composer remove phpunit/phpunit --no-interaction --quiet
	.phive/box compile --config=box.json
	${COMPOSER_REVERT}
	.phive/box info .build/phar/phpunit-slow-test-detector.phar --list

.PHONY: phive
phive: .phive ## Installs dependencies with phive
	PHIVE_HOME=.build/phive phive install --trust-gpg-keys 0x2DF45277AEF09A2F,0x033E5F8D801A2F8D

.PHONY: refactoring
refactoring: vendor ## Runs automated refactoring with rector/rector
	vendor/bin/rector process --config=rector.php

.PHONY: security-analysis
security-analysis: vendor ## Runs a security analysis with composer
	composer audit

.PHONY: static-code-analysis
static-code-analysis: vendor ## Runs a static code analysis with phpstan/phpstan
	vendor/bin/phpstan clear-result-cache --configuration=phpstan.neon
	vendor/bin/phpstan --configuration=phpstan.neon --memory-limit=-1

.PHONY: static-code-analysis-baseline
static-code-analysis-baseline: vendor ## Generates a baseline for static code analysis with phpstan/phpstan
	vendor/bin/phpstan clear-result-cache --configuration=phpstan.neon
	vendor/bin/phpstan --allow-empty-baseline --configuration=phpstan.neon --generate-baseline=phpstan-baseline.neon --memory-limit=-1

.PHONY: tests
tests: phar tests-unit tests-end-to-end tests-phar ## Runs unit, end-to-end, and phar tests with phpunit/phpunit on PHP 7.4

.PHONY: tests-end-to-end
tests-end-to-end: ## Runs end-to-end tests with phpunit/phpunit on PHP 7.4
	${COMPOSER_REMOVE_INCOMPATIBLE_DEPENDENCIES} && ${COMPOSER_CONFIGURE_PLATFORM} 7.0 && ${COMPOSER_REQUIRE} phpunit/phpunit:^6.5.0  --prefer-lowest && ${DOCKER_PHP70} vendor/bin/phpunit --colors=always --configuration=test/EndToEnd/Version06/phpunit.xml; ${COMPOSER_REVERT}
	${COMPOSER_REMOVE_INCOMPATIBLE_DEPENDENCIES} && ${COMPOSER_CONFIGURE_PLATFORM} 7.0 && ${COMPOSER_REQUIRE} phpunit/phpunit:^6.5.0                  && ${DOCKER_PHP70} vendor/bin/phpunit --colors=always --configuration=test/EndToEnd/Version06/phpunit.xml; ${COMPOSER_REVERT}
	${COMPOSER_REMOVE_INCOMPATIBLE_DEPENDENCIES} && ${COMPOSER_CONFIGURE_PLATFORM} 7.1 && ${COMPOSER_REQUIRE} phpunit/phpunit:^7.5.0  --prefer-lowest && ${DOCKER_PHP71} vendor/bin/phpunit --colors=always --configuration=test/EndToEnd/Version07/phpunit.xml; ${COMPOSER_REVERT}
	${COMPOSER_REMOVE_INCOMPATIBLE_DEPENDENCIES} && ${COMPOSER_CONFIGURE_PLATFORM} 7.1 && ${COMPOSER_REQUIRE} phpunit/phpunit:^7.5.0                  && ${DOCKER_PHP71} vendor/bin/phpunit --colors=always --configuration=test/EndToEnd/Version07/phpunit.xml; ${COMPOSER_REVERT}
	${COMPOSER_REMOVE_INCOMPATIBLE_DEPENDENCIES} && ${COMPOSER_CONFIGURE_PLATFORM} 7.2 && ${COMPOSER_REQUIRE} phpunit/phpunit:^8.5.19 --prefer-lowest && ${DOCKER_PHP72} vendor/bin/phpunit --colors=always --configuration=test/EndToEnd/Version08/phpunit.xml; ${COMPOSER_REVERT}
	${COMPOSER_REMOVE_INCOMPATIBLE_DEPENDENCIES} && ${COMPOSER_CONFIGURE_PLATFORM} 7.2 && ${COMPOSER_REQUIRE} phpunit/phpunit:^8.5.19                 && ${DOCKER_PHP72} vendor/bin/phpunit --colors=always --configuration=test/EndToEnd/Version08/phpunit.xml; ${COMPOSER_REVERT}
	${COMPOSER_REMOVE_INCOMPATIBLE_DEPENDENCIES} && ${COMPOSER_CONFIGURE_PLATFORM} 7.3 && ${COMPOSER_REQUIRE} phpunit/phpunit:^9.0.0  --prefer-lowest && ${DOCKER_PHP73} vendor/bin/phpunit --colors=always --configuration=test/EndToEnd/Version09/phpunit.xml; ${COMPOSER_REVERT}
	${COMPOSER_REMOVE_INCOMPATIBLE_DEPENDENCIES} && ${COMPOSER_CONFIGURE_PLATFORM} 7.3 && ${COMPOSER_REQUIRE} phpunit/phpunit:^9.0.0                  && ${DOCKER_PHP73} vendor/bin/phpunit --colors=always --configuration=test/EndToEnd/Version09/phpunit.xml; ${COMPOSER_REVERT}
	${COMPOSER_REMOVE_INCOMPATIBLE_DEPENDENCIES} && ${COMPOSER_CONFIGURE_PLATFORM} 8.1 && ${COMPOSER_REQUIRE} phpunit/phpunit:^10.0.0 --prefer-lowest && ${DOCKER_PHP81} vendor/bin/phpunit --colors=always --configuration=test/EndToEnd/Version10/phpunit.xml; ${COMPOSER_REVERT}
	${COMPOSER_REMOVE_INCOMPATIBLE_DEPENDENCIES} && ${COMPOSER_CONFIGURE_PLATFORM} 8.1 && ${COMPOSER_REQUIRE} phpunit/phpunit:^10.0.0                 && ${DOCKER_PHP81} vendor/bin/phpunit --colors=always --configuration=test/EndToEnd/Version10/phpunit.xml; ${COMPOSER_REVERT}
	${COMPOSER_REMOVE_INCOMPATIBLE_DEPENDENCIES} && ${COMPOSER_CONFIGURE_PLATFORM} 8.2 && ${COMPOSER_REQUIRE} phpunit/phpunit:^11.0.0 --prefer-lowest && ${DOCKER_PHP82} vendor/bin/phpunit --colors=always --configuration=test/EndToEnd/Version11/phpunit.xml; ${COMPOSER_REVERT}
	${COMPOSER_REMOVE_INCOMPATIBLE_DEPENDENCIES} && ${COMPOSER_CONFIGURE_PLATFORM} 8.2 && ${COMPOSER_REQUIRE} phpunit/phpunit:^11.0.0                 && ${DOCKER_PHP82} vendor/bin/phpunit --colors=always --configuration=test/EndToEnd/Version11/phpunit.xml; ${COMPOSER_REVERT}
	${COMPOSER_REMOVE_INCOMPATIBLE_DEPENDENCIES} && ${COMPOSER_CONFIGURE_PLATFORM} 8.3 && ${COMPOSER_REQUIRE} phpunit/phpunit:^12.0.0 --prefer-lowest && ${DOCKER_PHP83} vendor/bin/phpunit --colors=always --configuration=test/EndToEnd/Version12/phpunit.xml; ${COMPOSER_REVERT}
	${COMPOSER_REMOVE_INCOMPATIBLE_DEPENDENCIES} && ${COMPOSER_CONFIGURE_PLATFORM} 8.3 && ${COMPOSER_REQUIRE} phpunit/phpunit:^12.0.0                 && ${DOCKER_PHP83} vendor/bin/phpunit --colors=always --configuration=test/EndToEnd/Version12/phpunit.xml; ${COMPOSER_REVERT}

.PHONY: tests-phar
tests-phar: ## Runs phar tests with phpunit/phpunit
	${COMPOSER_REMOVE_INCOMPATIBLE_DEPENDENCIES} && ${COMPOSER_CONFIGURE_PLATFORM} 7.1 && ${COMPOSER_REQUIRE} phpunit/phpunit:^7.5.0  && ${DOCKER_PHP71} vendor/bin/phpunit --colors=always --configuration=test/Phar/Version07/phpunit.xml; ${COMPOSER_REVERT}
	${COMPOSER_REMOVE_INCOMPATIBLE_DEPENDENCIES} && ${COMPOSER_CONFIGURE_PLATFORM} 7.2 && ${COMPOSER_REQUIRE} phpunit/phpunit:^8.5.19 && ${DOCKER_PHP72} vendor/bin/phpunit --colors=always --configuration=test/Phar/Version08/phpunit.xml; ${COMPOSER_REVERT}
	${COMPOSER_REMOVE_INCOMPATIBLE_DEPENDENCIES} && ${COMPOSER_CONFIGURE_PLATFORM} 7.3 && ${COMPOSER_REQUIRE} phpunit/phpunit:^9.0.0  && ${DOCKER_PHP73} vendor/bin/phpunit --colors=always --configuration=test/Phar/Version09/phpunit.xml; ${COMPOSER_REVERT}
	${COMPOSER_REMOVE_INCOMPATIBLE_DEPENDENCIES} && ${COMPOSER_CONFIGURE_PLATFORM} 8.1 && ${COMPOSER_REQUIRE} phpunit/phpunit:^10.0.0 && ${DOCKER_PHP81} vendor/bin/phpunit --colors=always --configuration=test/Phar/Version10/phpunit.xml; ${COMPOSER_REVERT}
	${COMPOSER_REMOVE_INCOMPATIBLE_DEPENDENCIES} && ${COMPOSER_CONFIGURE_PLATFORM} 8.2 && ${COMPOSER_REQUIRE} phpunit/phpunit:^11.0.0 && ${DOCKER_PHP82} vendor/bin/phpunit --colors=always --configuration=test/Phar/Version11/phpunit.xml; ${COMPOSER_REVERT}
	${COMPOSER_REMOVE_INCOMPATIBLE_DEPENDENCIES} && ${COMPOSER_CONFIGURE_PLATFORM} 8.3 && ${COMPOSER_REQUIRE} phpunit/phpunit:^12.0.0 && ${DOCKER_PHP83} vendor/bin/phpunit --colors=always --configuration=test/Phar/Version12/phpunit.xml; ${COMPOSER_REVERT}

.PHONY: tests-unit
tests-unit: ## Runs unit tests with phpunit/phpunit
	${COMPOSER_REMOVE_INCOMPATIBLE_DEPENDENCIES} && ${COMPOSER_CONFIGURE_PLATFORM} 7.1 && ${COMPOSER_REQUIRE} fakerphp/faker:~1.20.0 phpunit/phpunit:^7.5.0  && ${DOCKER_PHP71} vendor/bin/phpunit --colors=always --configuration=test/Unit/phpunit.xml; ${COMPOSER_REVERT}


vendor: composer.json composer.lock
	composer validate --strict
	composer install --no-interaction --no-progress
