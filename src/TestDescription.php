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
final class TestDescription
{
    /**
     * @var string
     */
    private $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * @throws Exception\InvalidTestDescription
     */
    public static function fromString(string $value): self
    {
        if ('' === \trim($value)) {
            throw Exception\InvalidTestDescription::blankOrEmpty();
        }

        return new self($value);
    }

    public function truncatedTo(Width $maximumWidth): self
    {
        $width = $this->width();

        if (!$width->isGreaterThan($maximumWidth)) {
            return $this;
        }

        $ellipsis = '…';

        $availableWidth = $maximumWidth->toInt() - Width::fromString($ellipsis)->toInt();

        $widthOfHead = (int) \ceil($availableWidth / 2);
        $widthOfTail = $availableWidth - $widthOfHead;

        $characters = \preg_split(
            '//u',
            $this->value,
            -1,
            \PREG_SPLIT_NO_EMPTY
        );

        if (!\is_array($characters)) {
            $characters = \str_split($this->value);
        }

        $head = '';
        $remainingWidthOfHead = $widthOfHead;

        foreach ($characters as $character) {
            $widthOfCharacter = Width::fromString($character)->toInt();

            if ($widthOfCharacter > $remainingWidthOfHead) {
                break;
            }

            $head .= $character;
            $remainingWidthOfHead -= $widthOfCharacter;
        }

        $charactersOfTail = [];
        $remainingWidthOfTail = $widthOfTail;

        foreach (\array_reverse($characters) as $character) {
            $widthOfCharacter = Width::fromString($character)->toInt();

            if ($widthOfCharacter > $remainingWidthOfTail) {
                break;
            }

            \array_unshift(
                $charactersOfTail,
                $character
            );

            $remainingWidthOfTail -= $widthOfCharacter;
        }

        while (
            [] !== $charactersOfTail
            && 0 === Width::fromString($charactersOfTail[0])->toInt()
        ) {
            \array_shift($charactersOfTail);
        }

        return new self(\sprintf(
            '%s%s%s',
            $head,
            $ellipsis,
            \implode(
                '',
                $charactersOfTail
            )
        ));
    }

    public function width(): Width
    {
        return Width::fromString($this->value);
    }

    public function toString(): string
    {
        return $this->value;
    }
}
