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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit;

use Ergebnis\PHPUnit\SlowTestDetector\Environment;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Environment
 */
final class EnvironmentTest extends Framework\TestCase
{
    public function testIsGitHubActionsReturnsFalseWhenEnvironmentVariableIsNotSet()
    {
        $previousValue = \getenv('GITHUB_ACTIONS');

        \putenv('GITHUB_ACTIONS');

        try {
            self::assertFalse(Environment::isGitHubActions());
        } finally {
            if (false !== $previousValue) {
                \putenv('GITHUB_ACTIONS=' . $previousValue);
            }
        }
    }

    public function testIsGitHubActionsReturnsTrueWhenEnvironmentVariableIsSet()
    {
        $previousValue = \getenv('GITHUB_ACTIONS');

        \putenv('GITHUB_ACTIONS=true');

        try {
            self::assertTrue(Environment::isGitHubActions());
        } finally {
            if (false === $previousValue) {
                \putenv('GITHUB_ACTIONS');
            } else {
                \putenv('GITHUB_ACTIONS=' . $previousValue);
            }
        }
    }
}
