<?php

declare(strict_types=1);

/**
 * Copyright (c) 2021-2023 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/phpunit-slow-test-detector
 */

use Ergebnis\License;
use Ergebnis\PhpCsFixer;

$license = License\Type\MIT::markdown(
    __DIR__ . '/LICENSE.md',
    License\Range::since(
        License\Year::fromString('2021'),
        new \DateTimeZone('UTC'),
    ),
    License\Holder::fromString('Andreas Möller'),
    License\Url::fromString('https://github.com/ergebnis/phpunit-slow-test-detector'),
);

$license->save();

$ruleSet = PhpCsFixer\Config\RuleSet\Php81::create()
    ->withHeader($license->header())
    ->withRules(PhpCsFixer\Config\Rules::fromArray([
        'mb_str_functions' => false,
    ]));

$config = PhpCsFixer\Config\Factory::fromRuleSet($ruleSet);

$config->getFinder()
    ->exclude([
        '.build/',
        '.github/',
        '.note/',
    ])
    ->ignoreDotFiles(false)
    ->in(__DIR__)
    ->name('.php-cs-fixer.php');

$config->setCacheFile(__DIR__ . '/.build/php-cs-fixer/.php-cs-fixer.cache');

return $config;
