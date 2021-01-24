<?php

declare(strict_types=1);

/**
 * Copyright (c) 2021 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/phpunit-slow-test-detector
 */

namespace Ergebnis\PHPUnit\SlowTestDetector;

use PHPUnit\Event;

final class SlowTestCollector
{
    private Event\Telemetry\Duration $maximumDuration;

    /**
     * @var array<string, Event\Telemetry\HRTime>
     */
    private array $preparedTimes = [];

    /**
     * @var array<string, SlowTest>
     */
    private array $slowTests = [];

    public function __construct(Event\Telemetry\Duration $maximumDuration)
    {
        $this->maximumDuration = $maximumDuration;
    }

    public function testPrepared(
        Event\Code\Test $test,
        Event\Telemetry\HRTime $preparedTime
    ): void {
        $key = self::key($test);

        $this->preparedTimes[$key] = $preparedTime;
    }

    public function testPassed(
        Event\Code\Test $test,
        Event\Telemetry\HRTime $passedTime
    ): void {
        $key = self::key($test);

        if (!\array_key_exists($key, $this->preparedTimes)) {
            return;
        }

        $preparedTime = $this->preparedTimes[$key];

        unset($this->preparedTimes[$key]);

        $duration = $passedTime->duration($preparedTime);

        if (!$duration->isGreaterThan($this->maximumDuration)) {
            return;
        }

        $slowTest = SlowTest::fromTestAndDuration(
            $test,
            $duration
        );

        $key = self::key($test);

        if (\array_key_exists($key, $this->slowTests)) {
            $previousSlowTest = $this->slowTests[$key];

            if (!$duration->isGreaterThan($previousSlowTest->duration())) {
                return;
            }

            $this->slowTests[$key] = $slowTest;

            return;
        }

        $this->slowTests[$key] = $slowTest;
    }

    public function maximumDuration(): Event\Telemetry\Duration
    {
        return $this->maximumDuration;
    }

    /**
     * @phpstan-return list<SlowTest>
     * @psalm-return list<SlowTest>
     *
     * @return array<int, SlowTest>
     */
    public function slowTests(): array
    {
        return \array_values($this->slowTests);
    }

    private static function key(Event\Code\Test $test): string
    {
        return \sprintf(
            '%s::%s',
            $test->className(),
            $test->methodNameWithDataSet(),
        );
    }
}
