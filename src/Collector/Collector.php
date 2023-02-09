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

namespace Ergebnis\PHPUnit\SlowTestDetector\Collector;

use Ergebnis\PHPUnit\SlowTestDetector\SlowTest;

interface Collector
{
    public function collect(SlowTest $slowTest): void;

    /**
     * @phpstan-return list<SlowTest>
     *
     * @psalm-return list<SlowTest>
     *
     * @return array<int, SlowTest>
     */
    public function collected(): array;
}
