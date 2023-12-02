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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit\Version;

use Ergebnis\DataProvider;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use Ergebnis\PHPUnit\SlowTestDetector\Version;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Version\Version
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Version\Major
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Version\Minor
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Version\Patch
 */
final class VersionTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testCreateReturnsVersionWithMajor(): void
    {
        $major = Version\Major::fromInt(self::faker()->numberBetween(0));

        $version = Version\Version::create($major);

        self::assertSame($major, $version->major());
        self::assertNull($version->minor());
        self::assertNull($version->patch());

        $expected = (string) $major->toInt();

        self::assertSame($expected, $version->toString());
    }

    public function testCreateReturnsVersionWithMajorAndMinor(): void
    {
        $faker = self::faker();

        $major = Version\Major::fromInt($faker->numberBetween(0));
        $minor = Version\Minor::fromInt($faker->numberBetween(0));

        $version = Version\Version::create(
            $major,
            $minor,
        );

        self::assertSame($major, $version->major());
        self::assertSame($minor, $version->minor());
        self::assertNull($version->patch());

        $expected = \sprintf(
            '%d.%d',
            $major->toInt(),
            $minor->toInt(),
        );

        self::assertSame($expected, $version->toString());
    }

    public function testCreateRejectsPatchWhenMinorIsNull(): void
    {
        $faker = self::faker();

        $major = Version\Major::fromInt($faker->numberBetween(0));
        $patch = Version\Patch::fromInt($faker->numberBetween(0));

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Patch version requires minor version.');

        Version\Version::create(
            $major,
            null,
            $patch,
        );
    }

    public function testCreateReturnsVersionWithMajorMinorAndPatch(): void
    {
        $faker = self::faker();

        $major = Version\Major::fromInt($faker->numberBetween(0));
        $minor = Version\Minor::fromInt($faker->numberBetween(0));
        $patch = Version\Patch::fromInt($faker->numberBetween(0));

        $version = Version\Version::create(
            $major,
            $minor,
            $patch,
        );

        self::assertSame($major, $version->major());
        self::assertSame($minor, $version->minor());
        self::assertSame($patch, $version->patch());

        $expected = \sprintf(
            '%d.%d.%d',
            $major->toInt(),
            $minor->toInt(),
            $patch->toInt(),
        );

        self::assertSame($expected, $version->toString());
    }

    /**
     * @dataProvider \Ergebnis\DataProvider\StringProvider::arbitrary()
     * @dataProvider provideInvalidValue
     */
    public function testFromStringRejectsInvalidValue(string $value): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(\sprintf(
            'Value "%s" does not appear to be a valid value for a semantic version.',
            $value,
        ));

        Version\Version::fromString($value);
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
                    $invalidMinorKey,
                );

                yield $key => [
                    \sprintf(
                        '%s.%s',
                        $invalidMajor,
                        $invalidMinor,
                    ),
                ];
            }
        }

        $invalidPatches = [
            'patch-minus-one' => '-1',
            'patch-letter' => $faker->randomLetter(),
            'patch-word' => $faker->word(),
        ];

        foreach ($invalidMajors as $invalidMajorKey => $invalidMajor) {
            foreach ($invalidMinors as $invalidMinorKey => $invalidMinor) {
                foreach ($invalidPatches as $invalidPatchKey => $invalidPatch) {
                    $key = \sprintf(
                        '%s-%s-%s',
                        $invalidMajorKey,
                        $invalidMinorKey,
                        $invalidPatchKey,
                    );

                    yield $key => [
                        \sprintf(
                            '%s.%s.%s',
                            $invalidMajor,
                            $invalidMinor,
                            $invalidPatch,
                        ),
                    ];
                }
            }
        }

        $separators = [
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

        $patches = [
            'minor-zero' => '0',
            'minor-one' => '1',
            'minor-greater-than-one' => (string) $faker->numberBetween(2),
        ];

        foreach ($separators as $separatorKey => $separator) {
            foreach ($majors as $majorKey => $major) {
                foreach ($minors as $minorKey => $minor) {
                    foreach ($patches as $patchKey => $patch) {
                        $key = \sprintf(
                            '%s-%s-%s-%s',
                            $separatorKey,
                            $majorKey,
                            $minorKey,
                            $patchKey,
                        );

                        yield $key => [
                            \implode(
                                $separator,
                                [
                                    $major,
                                    $minor,
                                    $patch,
                                ],
                            ),
                        ];
                    }
                }
            }
        }
    }

    /**
     * @dataProvider provideValidValue
     */
    public function testFromStringReturnsVersion(string $value): void
    {
        $version = Version\Version::fromString($value);

        self::assertSame($value, $version->toString());
    }

    /**
     * @return \Generator<string, array{0: string}>
     */
    public static function provideValidValue(): iterable
    {
        $faker = self::faker();

        $majors = [
            'major-zero' => '0',
            'major-one' => '1',
            'major-greater-than-one' => (string) $faker->numberBetween(2),
        ];

        foreach ($majors as $key => $major) {
            yield $key => [
                $major,
            ];
        }

        $minors = [
            'minor-zero' => '0',
            'minor-one' => '1',
            'minor-greater-than-one' => (string) $faker->numberBetween(2),
        ];

        foreach ($majors as $majorKey => $major) {
            foreach ($minors as $minorKey => $minor) {
                $key = \sprintf(
                    '%s-%s',
                    $majorKey,
                    $minorKey,
                );

                yield $key => [
                    \sprintf(
                        '%s.%s',
                        $major,
                        $minor,
                    ),
                ];
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
                        $patchKey,
                    );

                    yield $key => [
                        \sprintf(
                            '%s.%s.%s',
                            $major,
                            $minor,
                            $patch,
                        ),
                    ];
                }
            }
        }
    }

    /**
     * @dataProvider provideVersion
     */
    public function testCompareReturnsZeroWhenOtherVersionIsIdentical(Version\Version $version): void
    {
        self::assertSame(0, $version->compare($version));
    }

    /**
     * @return \Generator<string, array{0: Version\Version}>
     */
    public static function provideVersion(): iterable
    {
        $values = [
            'major' => Version\Version::fromString('1'),
            'major-minor' => Version\Version::fromString('1.0'),
            'major-minor-patch' => Version\Version::fromString('1.0.0'),
        ];

        foreach ($values as $key => $version) {
            yield $key => [
                $version,
            ];
        }
    }

    /**
     * @dataProvider provideSmallerVersionAndGreaterVersion
     */
    public function testCompareReturnsMinusOneWhenOtherVersionIsGreater(
        Version\Version $smaller,
        Version\Version $greater
    ): void {
        self::assertSame(-1, $smaller->compare($greater));
    }

    /**
     * @return \Generator<string, array{0: Version\Version, 1: Version\Version}>
     */
    public static function provideSmallerVersionAndGreaterVersion(): iterable
    {
        foreach (self::smallerVersionAndGreaterVersion() as [$smaller, $greater]) {
            $key = \sprintf(
                '%s-is-smaller-than-%s',
                $smaller->toString(),
                $greater->toString(),
            );

            yield $key => [
                $smaller,
                $greater,
            ];
        }
    }

    /**
     * @dataProvider provideGreaterVersionAndSmallerVersion
     */
    public function testCompareReturnsPlusOneWhenOtherVersionIsSmaller(
        Version\Version $smaller,
        Version\Version $greater
    ): void {
        self::assertSame(1, $smaller->compare($greater));
    }

    /**
     * @return \Generator<string, array{0: Version\Version, 1: Version\Version}>
     */
    public static function provideGreaterVersionAndSmallerVersion(): iterable
    {
        foreach (self::smallerVersionAndGreaterVersion() as [$smaller, $greater]) {
            $key = \sprintf(
                '%s-is-greater-than-%s',
                $greater->toString(),
                $smaller->toString(),
            );

            yield $key => [
                $greater,
                $smaller,
            ];
        }
    }

    /**
     * @return array<int, array{0: Version\Version, 1: Version\Version}>
     */
    private static function smallerVersionAndGreaterVersion(): array
    {
        return [
            [
                Version\Version::fromString('1'),
                Version\Version::fromString('2'),
            ],
            [
                Version\Version::fromString('1'),
                Version\Version::fromString('1.1'),
            ],
            [
                Version\Version::fromString('1'),
                Version\Version::fromString('1.0.1'),
            ],
            [
                Version\Version::fromString('1.0'),
                Version\Version::fromString('1.1'),
            ],
            [
                Version\Version::fromString('1.0'),
                Version\Version::fromString('2'),
            ],
            [
                Version\Version::fromString('1.0'),
                Version\Version::fromString('1.0.1'),
            ],
            [
                Version\Version::fromString('1.0.0'),
                Version\Version::fromString('1.0.1'),
            ],
            [
                Version\Version::fromString('1.0.0'),
                Version\Version::fromString('2'),
            ],
            [
                Version\Version::fromString('1.0.0'),
                Version\Version::fromString('1.1'),
            ],
        ];
    }
}
