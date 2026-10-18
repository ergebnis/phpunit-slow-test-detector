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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit\Exception;

use Ergebnis\PHPUnit\SlowTestDetector\Exception;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Exception\InvalidGitHubStepSummary
 */
final class InvalidGitHubStepSummaryTest extends Framework\TestCase
{
    public function testBlankOrEmptyReturnsInvalidGitHubStepSummary(): void
    {
        $exception = Exception\InvalidGitHubStepSummary::blankOrEmpty();

        self::assertSame('Value cannot be blank or empty.', $exception->getMessage());
    }
}
