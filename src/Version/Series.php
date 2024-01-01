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

namespace Ergebnis\PHPUnit\SlowTestDetector\Version;

/**
 * @internal
 */
final class Series
{
    private Major $major;

    private function __construct(Major $major)
    {
        $this->major = $major;
    }

    public static function create(Major $major): self
    {
        return new self($major);
    }

    /**
     * @throws \InvalidArgumentException
     */
    public static function fromString(string $value): self
    {
        if (0 === \preg_match('/^(?P<major>(0|[1-9]\d*))\.(?P<minor>(0|[1-9]\d*))?$/', $value, $matches)) {
            throw new \InvalidArgumentException(\sprintf(
                'Value "%s" does not appear to be a valid value for a semantic version.',
                $value,
            ));
        }

        $major = Major::fromInt((int) $matches['major']);

        return self::create($major);
    }

    public function major(): Major
    {
        return $this->major;
    }
}
