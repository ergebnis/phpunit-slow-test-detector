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

namespace Ergebnis\PHPUnit\SlowTestDetector\Attribute;

use Ergebnis\PHPUnit\SlowTestDetector\Exception;

#[\Attribute(\Attribute::TARGET_METHOD)]
final class MaximumDuration
{
    /**
     * @throws Exception\InvalidMilliseconds
     */
    public function __construct(private int $milliseconds)
    {
        if (0 >= $milliseconds) {
            throw Exception\InvalidMilliseconds::notGreaterThanZero($milliseconds);
        }
    }

    public function milliseconds(): int
    {
        return $this->milliseconds;
    }
}
