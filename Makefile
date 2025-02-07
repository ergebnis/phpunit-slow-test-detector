.PHONY: it
it: refactoring coding-standards security-analysis static-code-analysis tests ## Runs the refactoring, coding-standards, security-analysis, static-code-analysis, and tests targets

.PHONY: code-coverage
code-coverage: vendor ## Collects code coverage from running unit tests with phpunit/phpunit
	composer config platform.php --unset; composer remove ergebnis/php-cs-fixer-config --dev --no-interaction --no-progress; composer require phpunit/phpunit:^7.2.0 --no-interaction --quiet --update-with-all-dependencies; vendor/bin/phpunit --configuration=test/Unit/phpunit.xml --coverage-text; git checkout HEAD -- composer.json composer.lock

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
	composer remove phpstan/extension-installer --dev --no-interaction
	composer remove phpunit/phpunit --no-interaction --quiet
	.phive/box compile --config=box.json
	git checkout HEAD -- composer.json composer.lock
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
tests: phar ## Runs unit, end-to-end, and phar tests with phpunit/phpunit
	composer config platform.php --unset; composer require phpunit/phpunit:^7.5.0 --no-interaction --quiet --update-with-all-dependencies; vendor/bin/phpunit --configuration=test/Unit/phpunit.xml; git checkout HEAD -- composer.json composer.lock
	composer config platform.php --unset; composer require phpunit/phpunit:^7.5.0 --no-interaction --quiet --update-with-all-dependencies; vendor/bin/phpunit --configuration=test/EndToEnd/Version07/phpunit.xml; git checkout HEAD -- composer.json composer.lock
	composer config platform.php --unset; composer require phpunit/phpunit:^8.5.19 --no-interaction --quiet --update-with-all-dependencies; vendor/bin/phpunit --configuration=test/EndToEnd/Version08/phpunit.xml; git checkout HEAD -- composer.json composer.lock
	composer config platform.php --unset; composer require phpunit/phpunit:^9.0.0 --no-interaction --quiet --update-with-all-dependencies; vendor/bin/phpunit --configuration=test/EndToEnd/Version09/phpunit.xml; git checkout HEAD -- composer.json composer.lock
	composer config platform.php --unset; composer require phpunit/phpunit:^10.0.0 --no-interaction --quiet --update-with-all-dependencies; vendor/bin/phpunit --configuration=test/EndToEnd/Version10/phpunit.xml; git checkout HEAD -- composer.json composer.lock
	composer config platform.php --unset; composer require phpunit/phpunit:11.0.x-dev --no-interaction --quiet --update-with-all-dependencies; vendor/bin/phpunit --configuration=test/EndToEnd/Version11/phpunit.xml; git checkout HEAD -- composer.json composer.lock
	composer config platform.php --unset; composer require phpunit/phpunit:^7.5.0 --no-interaction --quiet --update-with-all-dependencies; composer install --no-autoloader --no-interaction --quiet; vendor/bin/phpunit --configuration=test/Phar/Version07/phpunit.xml; git checkout HEAD -- composer.json composer.lock
	composer config platform.php --unset; composer require phpunit/phpunit:^8.5.19 --no-interaction --quiet --update-with-all-dependencies; composer install --no-autoloader --no-interaction --quiet; vendor/bin/phpunit --configuration=test/Phar/Version08/phpunit.xml; git checkout HEAD -- composer.json composer.lock
	composer config platform.php --unset; composer require phpunit/phpunit:^9.0.0 --no-interaction --quiet --update-with-all-dependencies; composer install --no-autoloader --no-interaction --quiet; vendor/bin/phpunit --configuration=test/Phar/Version09/phpunit.xml; git checkout HEAD -- composer.json composer.lock
	composer config platform.php --unset; composer require phpunit/phpunit:^10.0.0 --no-interaction --quiet --update-with-all-dependencies; composer install --no-autoloader --no-interaction --quiet; vendor/bin/phpunit --configuration=test/Phar/Version10/phpunit.xml; git checkout HEAD -- composer.json composer.lock

vendor: composer.json composer.lock
	composer validate --strict
	composer install --no-interaction --no-progress
