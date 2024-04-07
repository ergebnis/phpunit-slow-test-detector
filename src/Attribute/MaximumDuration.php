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

namespace Ergebnis\PHPUnit\SlowTestDetector\Attribute;

use Ergebnis\PHPUnit\SlowTestDetector\Exception;

#[\Attribute(\Attribute::TARGET_METHOD)]
final class MaximumDuration
{
    /**
     * @var int
     */
    private $milliseconds;

    /**
     * @throws Exception\InvalidMilliseconds
     */
    public function __construct(int $milliseconds)
    {
        if (0 >= $milliseconds) {
            throw Exception\InvalidMilliseconds::notGreaterThanZero($milliseconds);
        }

        $this->milliseconds = $milliseconds;
    }

    public function milliseconds(): int
    {
        return $this->milliseconds;
    }
}
