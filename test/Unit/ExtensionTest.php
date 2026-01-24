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

use Ergebnis\PHPUnit\SlowTestDetector\Extension;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Extension
 */
final class ExtensionTest extends Framework\TestCase
{
    /**
     * Tests that executeAfterTest handles test names without '::' separator gracefully.
     *
     * This can occur when:
     * - A test class has errors during setUpBeforeClass() or class loading
     * - PHPUnit generates warning-type test entries
     * - Tests are defined in unusual ways (anonymous classes, generated tests)
     * - Memory exhaustion or fatal errors occur during test execution
     */
    public function testExecuteAfterTestHandlesTestNameWithoutDoubleColonSeparator(): void
    {
        $phpUnitVersionSeries = \PHPUnit\Runner\Version::series();

        if (\version_compare($phpUnitVersionSeries, '7.0', '<') || \version_compare($phpUnitVersionSeries, '10.0', '>=')) {
            self::markTestSkipped('This test only applies to PHPUnit 7/8/9 where AfterTestHook is used.');
        }

        $extension = new Extension();

        /** @phpstan-ignore method.notFound (method only exists for PHPUnit 7/8/9) */
        $extension->executeBeforeFirstTest();

        $testNameWithoutDoubleColon = 'SomeWarningOrErrorMessage';

        /** @phpstan-ignore method.notFound (method only exists for PHPUnit 7/8/9) */
        $extension->executeAfterTest($testNameWithoutDoubleColon, 2.0);

        $this->expectOutputRegex('/.*/');

        /** @phpstan-ignore method.notFound (method only exists for PHPUnit 7/8/9) */
        $extension->executeAfterLastTest();
    }
}
