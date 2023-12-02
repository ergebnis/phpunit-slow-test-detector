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
final class Version
{
    private Major $major;
    private ?Minor $minor;
    private ?Patch $patch;

    private function __construct(
        Major $major,
        ?Minor $minor,
        ?Patch $patch
    ) {
        $this->major = $major;
        $this->minor = $minor;
        $this->patch = $patch;
    }

    /**
     * @throws \InvalidArgumentException
     */
    public static function create(
        Major $major,
        ?Minor $minor = null,
        ?Patch $patch = null
    ): self {
        if (
            $patch instanceof Patch
            && !$minor instanceof Minor
        ) {
            throw new \InvalidArgumentException('Patch version requires minor version.');
        }

        return new self(
            $major,
            $minor,
            $patch,
        );
    }

    /**
     * @throws \InvalidArgumentException
     */
    public static function fromString(string $value): self
    {
        if (0 === \preg_match('/^(?P<major>(0|[1-9]\d*))(\.(?P<minor>(0|[1-9]\d*))(\.(?P<patch>(0|[1-9]\d*)))?)?$/', $value, $matches)) {
            throw new \InvalidArgumentException(\sprintf(
                'Value "%s" does not appear to be a valid value for a semantic version.',
                $value,
            ));
        }

        $major = Major::fromInt((int) $matches['major']);
        $minor = null;
        $patch = null;

        if (\array_key_exists('minor', $matches)) {
            $minor = Minor::fromInt((int) $matches['minor']);
        }

        if (\array_key_exists('patch', $matches)) {
            $patch = Patch::fromInt((int) $matches['patch']);
        }

        return self::create(
            $major,
            $minor,
            $patch,
        );
    }

    public function major(): Major
    {
        return $this->major;
    }

    public function minor(): ?Minor
    {
        return $this->minor;
    }

    public function patch(): ?Patch
    {
        return $this->patch;
    }

    public function toString(): string
    {
        if (!$this->minor instanceof Minor) {
            return (string) $this->major->toInt();
        }

        if (!$this->patch instanceof Patch) {
            return \sprintf(
                '%d.%d',
                $this->major->toInt(),
                $this->minor->toInt(),
            );
        }

        return \sprintf(
            '%d.%d.%d',
            $this->major->toInt(),
            $this->minor->toInt(),
            $this->patch->toInt(),
        );
    }

    public function compare(self $other): int
    {
        $normalizedThis = self::normalize($this);
        $normalizedOther = self::normalize($other);

        if ($normalizedThis->major->toInt() < $normalizedOther->major->toInt()) {
            return -1;
        }

        if ($normalizedThis->major->toInt() > $normalizedOther->major->toInt()) {
            return 1;
        }

        \assert($normalizedThis->minor instanceof Minor);
        \assert($normalizedOther->minor instanceof Minor);

        if ($normalizedThis->minor->toInt() < $normalizedOther->minor->toInt()) {
            return -1;
        }

        if ($normalizedThis->minor->toInt() > $normalizedOther->minor->toInt()) {
            return 1;
        }

        \assert($normalizedThis->patch instanceof Patch);
        \assert($normalizedOther->patch instanceof Patch);

        if ($normalizedThis->patch->toInt() < $normalizedOther->patch->toInt()) {
            return -1;
        }

        if ($normalizedThis->patch->toInt() > $normalizedOther->patch->toInt()) {
            return 1;
        }

        return 0;
    }

    private static function normalize(self $version): self
    {
        if (!$version->minor instanceof Minor) {
            return new self(
                $version->major,
                Minor::fromInt(0),
                Patch::fromInt(0),
            );
        }

        if (!$version->patch instanceof Patch) {
            return new self(
                $version->major,
                $version->minor,
                Patch::fromInt(0),
            );
        }

        return $version;
    }
}
