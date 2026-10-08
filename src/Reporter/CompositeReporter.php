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

namespace Ergebnis\PHPUnit\SlowTestDetector\Reporter;

use Ergebnis\PHPUnit\SlowTestDetector\SlowTestList;

/**
 * @internal
 */
final class CompositeReporter implements Reporter
{
    /**
     * @var list<Reporter>
     */
    private array $reporters;

    public function __construct(Reporter ...$reporters)
    {
        $this->reporters = $reporters;
    }

    public function report(SlowTestList $slowTestList): void
    {
        foreach ($this->reporters as $reporter) {
            $reporter->report($slowTestList);
        }
    }
}
