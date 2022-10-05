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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Double\Collector;

use Ergebnis\PHPUnit\SlowTestDetector\Collector;
use Ergebnis\PHPUnit\SlowTestDetector\SlowTest;

final class AppendingCollector implements Collector\Collector
{
    /**
     * @phpstan-var list<SlowTest>
     *
     * @psalm-var list<SlowTest>
     *
     * @var array<int, SlowTest>
     */
    private array $collected = [];

    public function collect(SlowTest $slowTest): void
    {
        $this->collected[] = $slowTest;
    }

    public function collected(): array
    {
        return $this->collected;
    }
}
