<?php

declare(strict_types=1);

/**
 * Copyright (c) 2021-2026 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/phpunit-slow-test-detector
 */

use Ergebnis\Rector;

$ruleSet = Rector\Config\RuleSet\Php74::create()->withRules(Rector\Config\Rules::fromArray([
    Rector\Rules\Files\ReferenceNamespacedSymbolsRelativeToNamespacePrefixRector::class => [
        'discoverNamespacePrefixes' => true,
        'parentNamespacePrefixes' => [
            'Ergebnis\PHPUnit\SlowTestDetector',
            'Symfony\Component',
        ],
    ],
]));

return Rector\Config\Factory::fromRuleSet($ruleSet)
    ->withCache(__DIR__ . '/.build/rector/')
    ->withPaths([
        __DIR__ . '/src/',
        __DIR__ . '/test/',
        __DIR__ . '/.php-cs-fixer.php',
        __DIR__ . '/rector.php',
    ])
    ->withSkip([
        Rector\Rules\Expressions\Arrays\SortAssociativeArrayByKeyRector::class => [
            __DIR__ . '/test/Unit/Renderer/GitHubActions/WarningMessageTest.php',
            __DIR__ . '/test/Unit/Renderer/GitHubActions/WorkflowCommandRendererTest.php',
        ],
    ]);
