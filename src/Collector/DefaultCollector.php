<?php

declare(strict_types=1);

/**
 * Copyright (c) 2021-2022 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/phpunit-slow-test-detector
 */

namespace Ergebnis\PHPUnit\SlowTestDetector\Collector;

use Ergebnis\PHPUnit\SlowTestDetector\SlowTest;
use PHPUnit\Event;

final class DefaultCollector implements Collector
{
    /**
     * @var array<string, SlowTest>
     */
    private array $slowTests = [];

    public function collect(SlowTest $slowTest): void
    {
        $key = self::key($slowTest->test());

        if (\array_key_exists($key, $this->slowTests)) {
            $previousSlowTest = $this->slowTests[$key];

            if (!$slowTest->duration()->isGreaterThan($previousSlowTest->duration())) {
                return;
            }

            $this->slowTests[$key] = $slowTest;

            return;
        }

        $this->slowTests[$key] = $slowTest;
    }

    /**
     * @phpstan-return list<SlowTest>
     *
     * @psalm-return list<SlowTest>
     *
     * @return array<int, SlowTest>
     */
    public function collected(): array
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
