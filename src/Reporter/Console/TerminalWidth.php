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

namespace Ergebnis\PHPUnit\SlowTestDetector\Reporter\Console;

use Ergebnis\PHPUnit\SlowTestDetector\Exception;
use Ergebnis\PHPUnit\SlowTestDetector\Width;

/**
 * @internal
 */
final class TerminalWidth
{
    /**
     * @var Width
     */
    private $width;

    private function __construct(Width $width)
    {
        $this->width = $width;
    }

    /**
     * @throws Exception\InvalidTerminalWidth
     */
    public static function fromWidth(Width $width): self
    {
        if (!$width->isGreaterThan(Width::fromInt(0))) {
            throw Exception\InvalidTerminalWidth::notGreaterThanZero($width->toInt());
        }

        return new self($width);
    }

    public static function default(): self
    {
        return new self(Width::fromInt(80));
    }

    public function toWidth(): Width
    {
        return $this->width;
    }
}
