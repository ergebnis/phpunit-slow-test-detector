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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Fixture;

final class Sleeper
{
    /**
     * @var int
     */
    private $milliseconds;

    private function __construct(int $milliseconds)
    {
        $this->milliseconds = $milliseconds;
    }

    /**
     * @throws \InvalidArgumentException
     */
    public static function fromMilliseconds(int $milliseconds): self
    {
        if (0 > $milliseconds) {
            throw new \InvalidArgumentException(\sprintf(
                'Value for milliseconds should be greater than or equal to 0, but %d is not.',
                $milliseconds
            ));
        }

        return new self($milliseconds);
    }

    public function milliseconds(): int
    {
        return $this->milliseconds;
    }

    public function sleep()
    {
        \usleep($this->milliseconds * 1000);
    }
}
