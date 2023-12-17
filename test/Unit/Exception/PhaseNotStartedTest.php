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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit\Exception;

use Ergebnis\PHPUnit\SlowTestDetector\Exception;
use Ergebnis\PHPUnit\SlowTestDetector\PhaseIdentifier;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Exception\PhaseNotStarted
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\PhaseIdentifier
 */
final class PhaseNotStartedTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testFromPhaseIdentifierReturnsException(): void
    {
        $phaseIdentifier = PhaseIdentifier::fromString(self::faker()->word());

        $exception = Exception\PhaseNotStarted::fromPhaseIdentifier($phaseIdentifier);

        $message = \sprintf(
            'Phase identified by "%s" has not been started.',
            $phaseIdentifier->toString(),
        );

        self::assertSame($message, $exception->getMessage());
    }
}
