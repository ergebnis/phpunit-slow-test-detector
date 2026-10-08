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
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Exception\InvalidGitHubActionsAnnotations
 */
final class InvalidGitHubActionsAnnotationsTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testNotOneOfReturnsInvalidGitHubActionsAnnotations(): void
    {
        $faker = self::faker();

        $value = $faker->word();
        $one = $faker->word();
        $two = $faker->word();

        $exception = Exception\InvalidGitHubActionsAnnotations::notOneOf(
            $value,
            $one,
            $two,
        );

        $message = \sprintf(
            'Value should be one of "%s", "%s", but "%s" is not.',
            $one,
            $two,
            $value,
        );

        self::assertSame($message, $exception->getMessage());
    }
}
