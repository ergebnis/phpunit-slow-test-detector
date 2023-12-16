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

namespace Ergebnis\PHPUnit\SlowTestDetector;

/**
 * @internal
 */
final class PhaseIdentifier
{
    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * @throws Exception\InvalidPhaseIdentifier
     */
    public static function fromString(string $value): self
    {
        if ('' === \trim($value)) {
            throw Exception\InvalidPhaseIdentifier::blankOrEmpty();
        }

        return new self($value);
    }

    public function toString(): string
    {
        return $this->value;
    }
}
