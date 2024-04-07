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

namespace Ergebnis\PHPUnit\SlowTestDetector\Collector;

use Ergebnis\PHPUnit\SlowTestDetector\SlowTest;

/**
 * @internal
 */
final class DefaultCollector implements Collector
{
    /**
     * @var array<string, SlowTest>
     */
    private $slowTests = [];

    public function collect(SlowTest $slowTest): void
    {
        $key = $slowTest->testIdentifier()->toString();

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
     * @return list<SlowTest>
     */
    public function collected(): array
    {
        return \array_values($this->slowTests);
    }
}
