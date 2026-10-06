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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit\CiDetector;

use Ergebnis\PHPUnit\SlowTestDetector\CiDetector;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\CiDetector\Detector
 */
final class DetectorTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testIsRunningOnGitHubActionsReturnsFalseWhenGitHubActionsIsNotSet(): void
    {
        $faker = self::faker();

        $environmentVariables = [
            \strtoupper($faker->word()) => $faker->word(),
        ];

        $detector = new CiDetector\Detector();

        self::assertFalse($detector->isRunningOnGitHubActions($environmentVariables));
    }

    /**
     * @dataProvider provideValueThatIsNotTrue
     */
    public function testIsRunningOnGitHubActionsReturnsFalseWhenGitHubActionsIsNotTrue(string $value): void
    {
        $environmentVariables = [
            'GITHUB_ACTIONS' => $value,
        ];

        $detector = new CiDetector\Detector();

        self::assertFalse($detector->isRunningOnGitHubActions($environmentVariables));
    }

    /**
     * @return \Generator<string, array{0: string}>
     */
    public static function provideValueThatIsNotTrue(): iterable
    {
        $values = [
            'empty' => '',
            'false' => 'false',
            'one' => '1',
            'true-upper-case' => 'TRUE',
            'word' => self::faker()->word(),
        ];

        foreach ($values as $key => $value) {
            yield $key => [
                $value,
            ];
        }
    }

    public function testIsRunningOnGitHubActionsReturnsTrueWhenGitHubActionsIsTrue(): void
    {
        $environmentVariables = [
            'GITHUB_ACTIONS' => 'true',
        ];

        $detector = new CiDetector\Detector();

        self::assertTrue($detector->isRunningOnGitHubActions($environmentVariables));
    }
}
