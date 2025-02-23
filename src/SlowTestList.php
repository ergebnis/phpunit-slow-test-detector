<?php

declare(strict_types=1);

/**
 * Copyright (c) 2021-2025 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/phpunit-slow-test-detector
 */

namespace Ergebnis\PHPUnit\SlowTestDetector;

use Ergebnis\PHPUnit\SlowTestDetector\Comparator\DurationComparator;

/**
 * @internal
 */
final class SlowTestList
{
    /**
     * @var list<SlowTest>
     */
    private $slowTests;

    private function __construct(SlowTest ...$slowTests)
    {
        $this->slowTests = $slowTests;
    }

    public static function create(SlowTest ...$slowTests): self
    {
        return new self(...$slowTests);
    }

    public function count(): Count
    {
        return Count::fromInt(\count($this->slowTests));
    }

    /**
     * @throws Exception\SlowTestListIsEmpty
     */
    public function first(): SlowTest
    {
        if ([] === $this->slowTests) {
            throw Exception\SlowTestListIsEmpty::create();
        }

        return \reset($this->slowTests);
    }

    public function isEmpty(): bool
    {
        return [] === $this->slowTests;
    }

    public function limitTo(MaximumCount $maximumCount): self
    {
        return self::create(...\array_slice(
            $this->slowTests,
            0,
            $maximumCount->toCount()->toInt()
        ));
    }

    public function sortByDurationDescending(): self
    {
        $durationComparator = new DurationComparator();

        $slowTests = $this->slowTests;

        \usort($slowTests, static function (SlowTest $one, SlowTest $two) use ($durationComparator): int {
            return $durationComparator->compare(
                $two->duration(),
                $one->duration()
            );
        });

        return self::create(...$slowTests);
    }

    public function sortByMaximumDurationDescending(): self
    {
        $durationComparator = new DurationComparator();

        $slowTests = $this->slowTests;

        \usort($slowTests, static function (SlowTest $one, SlowTest $two) use ($durationComparator): int {
            return $durationComparator->compare(
                $two->maximumDuration()->toDuration(),
                $one->maximumDuration()->toDuration()
            );
        });

        return self::create(...$slowTests);
    }

    /**
     * @return list<SlowTest>
     */
    public function toArray(): array
    {
        return $this->slowTests;
    }
}
