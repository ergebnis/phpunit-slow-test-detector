<?php

declare(strict_types=1);

/**
 * Copyright (c) 2021-2025 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/phpunit-slow-test-detector
 */

$composerJsonFile = __DIR__ . '/../composer.json';

$composerJson = \json_decode(
    \file_get_contents($composerJsonFile),
    false
);

$composerJson->autoload = new stdClass();

\file_put_contents($composerJsonFile, \json_encode(
    $composerJson,
    \JSON_PRETTY_PRINT | \JSON_PRESERVE_ZERO_FRACTION | \JSON_UNESCAPED_SLASHES
));
