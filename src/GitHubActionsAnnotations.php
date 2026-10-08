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

namespace Ergebnis\PHPUnit\SlowTestDetector;

/**
 * @internal
 */
final class GitHubActionsAnnotations
{
    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function auto(): self
    {
        return new self('auto');
    }

    public static function enabled(): self
    {
        return new self('true');
    }

    public static function disabled(): self
    {
        return new self('false');
    }

    /**
     * @throws Exception\InvalidGitHubActionsAnnotations
     */
    public static function fromString(string $value): self
    {
        $allowedValues = [
            'auto',
            'false',
            'true',
        ];

        if (!\in_array($value, $allowedValues, true)) {
            throw Exception\InvalidGitHubActionsAnnotations::notOneOf(
                $value,
                ...$allowedValues,
            );
        }

        return new self($value);
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function isOneOf(self ...$others): bool
    {
        foreach ($others as $other) {
            if ($this->value === $other->value) {
                return true;
            }
        }

        return false;
    }
}
