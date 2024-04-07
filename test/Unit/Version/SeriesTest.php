<?php

declare(strict_types=1);

/**
 * Copyright (c) 2021-2024 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/phpunit-slow-test-detector
 */

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit\Version;

use Ergebnis\DataProvider;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use Ergebnis\PHPUnit\SlowTestDetector\Version;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Version\Series
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Version\Major
 */
final class SeriesTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testCreateReturnsSeries(): void
    {
        $major = Version\Major::fromInt(self::faker()->numberBetween(0));

        $series = Version\Series::create($major);

        self::assertSame($major, $series->major());
    }

    /**
     * @dataProvider \Ergebnis\PHPUnit\SlowTestDetector\Test\DataProvider\StringProvider::arbitrary
     * @dataProvider provideInvalidValue
     */
    public function testFromStringRejectsInvalidValue(string $value): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(\sprintf(
            'Value "%s" does not appear to be a valid value for a semantic version.',
            $value
        ));

        Version\Series::fromString($value);
    }

    /**
     * @return \Generator<string, array{0: string}>
     */
    public static function provideInvalidValue(): iterable
    {
        $faker = self::faker();

        $invalidMajors = [
            'major-minus-one' => '-1',
            'major-letter' => $faker->randomLetter(),
            'major-word' => $faker->word(),
        ];

        foreach ($invalidMajors as $key => $invalidMajor) {
            yield $key => [
                $invalidMajor,
            ];
        }

        $invalidMinors = [
            'minor-minus-one' => '-1',
            'minor-letter' => $faker->randomLetter(),
            'minor-word' => $faker->word(),
        ];

        foreach ($invalidMajors as $invalidMajorKey => $invalidMajor) {
            foreach ($invalidMinors as $invalidMinorKey => $invalidMinor) {
                $key = \sprintf(
                    '%s-%s',
                    $invalidMajorKey,
                    $invalidMinorKey
                );

                yield $key => [
                    \sprintf(
                        '%s.%s',
                        $invalidMajor,
                        $invalidMinor
                    ),
                ];
            }
        }

        $invalidSeparators = [
            'separator-space' => ' ',
            'separator-dash' => ' ',
            'separator-dots-two' => '..',
        ];

        $majors = [
            'major-zero' => '0',
            'major-one' => '1',
            'major-greater-than-one' => (string) $faker->numberBetween(2),
        ];

        $minors = [
            'minor-zero' => '0',
            'minor-one' => '1',
            'minor-greater-than-one' => (string) $faker->numberBetween(2),
        ];

        foreach ($invalidSeparators as $invalidSeparatorKey => $invalidSeparator) {
            foreach ($majors as $majorKey => $major) {
                foreach ($minors as $minorKey => $minor) {
                    $key = \sprintf(
                        '%s-%s-%s',
                        $invalidSeparatorKey,
                        $majorKey,
                        $minorKey
                    );

                    yield $key => [
                        \implode(
                            $invalidSeparator,
                            [
                                $major,
                                $minor,
                            ]
                        ),
                    ];
                }
            }
        }

        $patches = [
            'minor-zero' => '0',
            'minor-one' => '1',
            'minor-greater-than-one' => (string) $faker->numberBetween(2),
        ];

        foreach ($majors as $majorKey => $major) {
            foreach ($minors as $minorKey => $minor) {
                foreach ($patches as $patchKey => $patch) {
                    $key = \sprintf(
                        '%s-%s-%s',
                        $majorKey,
                        $minorKey,
                        $patchKey
                    );

                    yield $key => [
                        \sprintf(
                            '%s.%s.%s',
                            $major,
                            $minor,
                            $patch
                        ),
                    ];
                }
            }
        }
    }

    /**
     * @dataProvider provideValidValueAndMajor
     */
    public function testFromStringReturnsSeries(
        string $value,
        Version\Major $major
    ): void {
        $series = Version\Series::fromString($value);

        self::assertEquals($major, $series->major());
    }

    /**
     * @return \Generator<string, array{0: string, 1: Version\Major}>
     */
    public static function provideValidValueAndMajor(): iterable
    {
        $values = [
            'major-zero-minor-zero' => [
                '0.0',
                Version\Major::fromInt(0),
            ],
            'major-one-minor-zero' => [
                '1.0',
                Version\Major::fromInt(1),
            ],
            'major-minor' => [
                '123.456',
                Version\Major::fromInt(123),
            ],
        ];

        foreach ($values as $key => [$value, $major]) {
            yield $key => [
                $value,
                $major,
            ];
        }
    }
}
