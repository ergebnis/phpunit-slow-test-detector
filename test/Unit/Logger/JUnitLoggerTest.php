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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit\Logger;

use Ergebnis\PHPUnit\SlowTestDetector\Duration;
use Ergebnis\PHPUnit\SlowTestDetector\FileWriter;
use Ergebnis\PHPUnit\SlowTestDetector\Formatter;
use Ergebnis\PHPUnit\SlowTestDetector\Logger;
use Ergebnis\PHPUnit\SlowTestDetector\SlowTest;
use Ergebnis\PHPUnit\SlowTestDetector\TestFile;
use Ergebnis\PHPUnit\SlowTestDetector\TestIdentifier;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Logger\JUnitLogger
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Duration
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\FileWriter\FileWriter
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Formatter\DefaultDurationFormatter
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\SlowTest
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\TestFile
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\TestIdentifier
 */
final class JUnitLoggerTest extends Framework\TestCase
{
    /**
     * @dataProvider provideSlowTestsCases
     */
    public function testLogIsCreated(
        string $expectedContents,
        SlowTest ...$slowTests
    ): void {
        $fileWriter = self::createMock(FileWriter\FileWriter::class);
        $fileWriter->expects(self::once())
            ->method('write')
            ->with(self::callback(static function (string $contents) use ($expectedContents): bool {
                return $contents === $expectedContents;
            }));

        $logger = new Logger\JUnitLogger(
            new Formatter\DefaultDurationFormatter(),
            $fileWriter,
        );

        $logger->log(...$slowTests);
    }

    /**
     * @return \Generator<string, array{0: string, 1?: SlowTest, 2?: SlowTest}>
     */
    public static function provideSlowTestsCases(): iterable
    {
        yield 'with-lines' => [
            <<<'TXT'
<?xml version="1.0" encoding="UTF-8"?>
<testsuites><testsuite name="Slow Tests" tests="2" failures="2" errors="0"><testcase name="FooTest::test" file="/some/filename.php" line="3"><failure type="slow_test"><![CDATA[The actual duration of 0.300 exceeds the maximum allowed duration of 0.100.]]></failure></testcase><testcase name="BarTest::test" file="/some/other/filename.php" line="45"><failure type="slow_test"><![CDATA[The actual duration of 0.275 exceeds the maximum allowed duration of 0.100.]]></failure></testcase></testsuite></testsuites>

TXT,
            SlowTest::create(
                TestIdentifier::fromString('FooTest::test'),
                TestFile::fromFilenameAndLine('/some/filename.php', 3),
                Duration::fromMilliseconds(300),
                Duration::fromMilliseconds(100),
            ),
            SlowTest::create(
                TestIdentifier::fromString('BarTest::test'),
                TestFile::fromFilenameAndLine('/some/other/filename.php', 45),
                Duration::fromMilliseconds(275),
                Duration::fromMilliseconds(100),
            ),
        ];

        yield 'without-lines' => [
            <<<'TXT'
<?xml version="1.0" encoding="UTF-8"?>
<testsuites><testsuite name="Slow Tests" tests="2" failures="2" errors="0"><testcase name="FooTest::test" file="/some/filename.php"><failure type="slow_test"><![CDATA[The actual duration of 0.300 exceeds the maximum allowed duration of 0.100.]]></failure></testcase><testcase name="BarTest::test" file="/some/other/filename.php"><failure type="slow_test"><![CDATA[The actual duration of 0.275 exceeds the maximum allowed duration of 0.100.]]></failure></testcase></testsuite></testsuites>

TXT,
            SlowTest::create(
                TestIdentifier::fromString('FooTest::test'),
                TestFile::fromFilename('/some/filename.php'),
                Duration::fromMilliseconds(300),
                Duration::fromMilliseconds(100),
            ),
            SlowTest::create(
                TestIdentifier::fromString('BarTest::test'),
                TestFile::fromFilename('/some/other/filename.php'),
                Duration::fromMilliseconds(275),
                Duration::fromMilliseconds(100),
            ),
        ];

        yield 'without-slow-tests' => [
            <<<'TXT'
<?xml version="1.0" encoding="UTF-8"?>
<testsuites><testsuite name="Slow Tests" tests="0" failures="0" errors="0"/></testsuites>

TXT,
        ];
    }
}
