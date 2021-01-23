<?php

declare(strict_types=1);

/**
 * Copyright (c) 2021 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/phpunit-slow-test-collector
 */

namespace Ergebnis\PHPUnit\SlowTestCollector;

use PHPUnit\Event;

final class SlowTestCollector
{
    private Event\Telemetry\Duration $maximumDuration;

    /**
     * @var array<string, SlowTest>
     */
    private array $slowTests = [];

    public function __construct(Event\Telemetry\Duration $maximumDuration)
    {
        $this->maximumDuration = $maximumDuration;
    }

    public function collect(SlowTest $slowTest): void
    {
        $duration = $slowTest->duration();

        if (!$duration->isGreaterThan($this->maximumDuration)) {
            return;
        }

        $test = $slowTest->test();

        $key = \sprintf(
            '%s::%s',
            $test->className(),
            $test->methodNameWithDataSet(),
        );

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
}
