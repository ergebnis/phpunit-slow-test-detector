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

use Ergebnis\PHPUnit\SlowTestDetector\Exception;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use Ergebnis\PHPUnit\SlowTestDetector\TestDescription;
use Ergebnis\PHPUnit\SlowTestDetector\Width;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\TestDescription
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Exception\InvalidTestDescription
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Width
 */
final class TestDescriptionTest extends Framework\TestCase
{
    use Test\Util\Helper;

    /**
     * @dataProvider \Ergebnis\PHPUnit\SlowTestDetector\Test\DataProvider\StringProvider::blank
     * @dataProvider \Ergebnis\PHPUnit\SlowTestDetector\Test\DataProvider\StringProvider::empty
     */
    public function testFromStringRejectsInvalidValue(string $value)
    {
        $this->expectException(Exception\InvalidTestDescription::class);

        TestDescription::fromString($value);
    }

    public function testFromStringReturnsTestDescription()
    {
        $value = self::faker()->word();

        $testDescription = TestDescription::fromString($value);

        self::assertSame($value, $testDescription->toString());
    }

    public function testTruncatedToReturnsTestDescriptionWhenWidthIsNotGreaterThanMaximumWidth()
    {
        $value = self::faker()->sentence();

        $testDescription = TestDescription::fromString($value);

        $maximumWidth = Width::fromInt($testDescription->width()->toInt() + self::faker()->randomDigit());

        $truncated = $testDescription->truncatedTo($maximumWidth);

        self::assertSame($value, $truncated->toString());
    }

    /**
     * @dataProvider provideValueMaximumWidthAndTruncatedValue
     */
    public function testTruncatedToReturnsTruncatedTestDescriptionWhenWidthIsGreaterThanMaximumWidth(
        string $value,
        Width $maximumWidth,
        string $truncatedValue
    ) {
        $testDescription = TestDescription::fromString($value);

        $truncated = $testDescription->truncatedTo($maximumWidth);

        self::assertSame($truncatedValue, $truncated->toString());
    }

    /**
     * @return \Generator<string, array{0: string, 1: Width, 2: string}>
     */
    public static function provideValueMaximumWidthAndTruncatedValue(): \Generator
    {
        $values = [
            'maximum-width-of-one' => [
                'FooTest::testSleeperSleepsLongerThanMaximumDuration with data set #0 (1000)',
                Width::fromInt(1),
                '…',
            ],
            'even-width-for-head-and-tail' => [
                'FooTest::testSleeperSleepsLongerThanMaximumDuration with data set #0 (1000)',
                Width::fromInt(29),
                'FooTest::testS… set #0 (1000)',
            ],
            'odd-width-for-head-and-tail' => [
                'FooTest::testSleeperSleepsLongerThanMaximumDuration with data set #0 (1000)',
                Width::fromInt(30),
                'FooTest::testSl… set #0 (1000)',
            ],
            'multibyte' => [
                'FooTest::testWithÄÖÜÄÖÜÄÖÜÄÖÜÄÖÜÄÖÜÄÖÜÄÖÜ',
                Width::fromInt(29),
                'FooTest::testW…ÖÜÄÖÜÄÖÜÄÖÜÄÖÜ',
            ],
            'wide-characters' => [
                'FooTest::testWith日本語日本語日本語日本語',
                Width::fromInt(29),
                'FooTest::testW…語日本語日本語',
            ],
            'wide-character-not-fitting-into-head' => [
                'FooTest::test日本語日本語日本語日本語日本語',
                Width::fromInt(29),
                'FooTest::test…語日本語日本語',
            ],
            'wide-character-not-fitting-into-head-and-tail' => [
                'FooTest::test日本語日本語日本語日本語日本語',
                Width::fromInt(28),
                'FooTest::test…日本語日本語',
            ],
            'combining-mark-at-end-of-head' => [
                "FooTest::testCafe\xCC\x81Sleeper with data set #0 (1000)",
                Width::fromInt(34),
                "FooTest::testCafe\xCC\x81…ta set #0 (1000)",
            ],
            'combining-mark-at-start-of-tail' => [
                "FooTest::testSleeperSleeps with data set \"cafe\xCC\x81 with milk\"",
                Width::fromInt(24),
                'FooTest::tes… with milk"',
            ],
        ];

        foreach ($values as $key => list($value, $maximumWidth, $truncatedValue)) {
            yield $key => [
                $value,
                $maximumWidth,
                $truncatedValue,
            ];
        }
    }
}
