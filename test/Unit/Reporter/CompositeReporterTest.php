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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit\Reporter;

use Ergebnis\PHPUnit\SlowTestDetector\Duration;
use Ergebnis\PHPUnit\SlowTestDetector\MaximumDuration;
use Ergebnis\PHPUnit\SlowTestDetector\Renderer;
use Ergebnis\PHPUnit\SlowTestDetector\Reporter;
use Ergebnis\PHPUnit\SlowTestDetector\SlowTest;
use Ergebnis\PHPUnit\SlowTestDetector\SlowTestList;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use Ergebnis\PHPUnit\SlowTestDetector\TestDescription;
use Ergebnis\PHPUnit\SlowTestDetector\TestIdentifier;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Reporter\CompositeReporter
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Duration
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\MaximumDuration
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Renderer\Printer
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\SlowTest
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\SlowTestList
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\TestDescription
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\TestIdentifier
 */
final class CompositeReporterTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testReportReportsSlowTestListWithEachReporterInOrder(): void
    {
        $faker = self::faker();

        $one = $faker->sentence();
        $two = $faker->sentence();
        $three = $faker->sentence();

        $output = self::output();

        $printer = new Renderer\Printer($output);

        $slowTestList = SlowTestList::create(SlowTest::create(
            TestIdentifier::fromString($faker->word()),
            TestDescription::fromString($faker->sentence()),
            Duration::fromMilliseconds($faker->numberBetween(1)),
            MaximumDuration::fromDuration(Duration::fromMilliseconds($faker->numberBetween(1))),
        ));

        $reporter = new Reporter\CompositeReporter(
            self::printingReporter(
                $printer,
                $slowTestList,
                $one,
            ),
            self::printingReporter(
                $printer,
                $slowTestList,
                $two,
            ),
            self::printingReporter(
                $printer,
                $slowTestList,
                $three,
            ),
        );

        $reporter->report($slowTestList);

        self::assertOutputIsIdenticalTo($one . $two . $three, $output);
    }

    private static function printingReporter(
        Renderer\Printer $printer,
        SlowTestList $expectedSlowTestList,
        string $text
    ): Reporter\Reporter {
        return new class($printer, $expectedSlowTestList, $text) implements Reporter\Reporter {
            private Renderer\Printer $printer;
            private SlowTestList $expectedSlowTestList;
            private string $text;

            public function __construct(
                Renderer\Printer $printer,
                SlowTestList $expectedSlowTestList,
                string $text
            ) {
                $this->printer = $printer;
                $this->expectedSlowTestList = $expectedSlowTestList;
                $this->text = $text;
            }

            public function report(SlowTestList $slowTestList): void
            {
                Framework\Assert::assertSame(
                    $this->expectedSlowTestList,
                    $slowTestList,
                );

                $this->printer->print($this->text);
            }
        };
    }
}
