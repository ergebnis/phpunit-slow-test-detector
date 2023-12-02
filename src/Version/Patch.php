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

namespace Ergebnis\PHPUnit\SlowTestDetector\Version;

/**
 * @internal
 */
final class Patch
{
    private int $value;

    private function __construct(int $value)
    {
        $this->value = $value;
    }

    /**
     * @throws \InvalidArgumentException
     */
    public static function fromInt(int $value): self
    {
        if (0 > $value) {
            throw new \InvalidArgumentException(\sprintf(
                'Value "%d" does not appear to be a valid value for a patch version.',
                $value,
            ));
        }

        return new self($value);
    }

    public function toInt(): int
    {
        return $this->value;
    }
}
