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

namespace Ergebnis\PHPUnit\SlowTestDetector\Renderer\GitHubActions;

use Ergebnis\PHPUnit\SlowTestDetector\Exception;

/**
 * @internal
 */
final class Message
{
    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * @throws Exception\InvalidMessage
     */
    public static function fromString(string $value): self
    {
        if ('' === \trim($value)) {
            throw Exception\InvalidMessage::blankOrEmpty();
        }

        return new self($value);
    }

    public function toString(): string
    {
        return $this->value;
    }
}
