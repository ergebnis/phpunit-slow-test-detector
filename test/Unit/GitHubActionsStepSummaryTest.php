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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit;

use Ergebnis\PHPUnit\SlowTestDetector\Exception;
use Ergebnis\PHPUnit\SlowTestDetector\GitHubActionsStepSummary;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\GitHubActionsStepSummary
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Exception\InvalidGitHubActionsStepSummary
 */
final class GitHubActionsStepSummaryTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testFromStringThrowsInvalidGitHubActionsStepSummaryWhenValueIsUnknown(): void
    {
        $value = self::faker()->word();

        $this->expectException(Exception\InvalidGitHubActionsStepSummary::class);

        GitHubActionsStepSummary::fromString($value);
    }

    public function testFromStringReturnsAutoWhenValueIsAuto(): void
    {
        $gitHubActionsStepSummary = GitHubActionsStepSummary::fromString('auto');

        self::assertEquals(GitHubActionsStepSummary::auto(), $gitHubActionsStepSummary);
    }

    public function testFromStringReturnsEnabledWhenValueIsTrue(): void
    {
        $gitHubActionsStepSummary = GitHubActionsStepSummary::fromString('true');

        self::assertEquals(GitHubActionsStepSummary::enabled(), $gitHubActionsStepSummary);
    }

    public function testFromStringReturnsDisabledWhenValueIsFalse(): void
    {
        $gitHubActionsStepSummary = GitHubActionsStepSummary::fromString('false');

        self::assertEquals(GitHubActionsStepSummary::disabled(), $gitHubActionsStepSummary);
    }

    public function testEqualsReturnsFalseWhenValueIsDifferent(): void
    {
        $one = GitHubActionsStepSummary::auto();
        $two = GitHubActionsStepSummary::enabled();

        self::assertFalse($one->equals($two));
    }

    public function testEqualsReturnsTrueWhenValueIsSame(): void
    {
        $one = GitHubActionsStepSummary::auto();
        $two = GitHubActionsStepSummary::auto();

        self::assertTrue($one->equals($two));
    }

    public function testIsOneOfReturnsFalseWhenAllValuesAreDifferent(): void
    {
        $one = GitHubActionsStepSummary::auto();
        $two = GitHubActionsStepSummary::disabled();
        $three = GitHubActionsStepSummary::enabled();

        self::assertFalse($one->isOneOf($two, $three));
    }

    public function testIsOneOfReturnsTrueWhenOneOfTheValuesIsSame(): void
    {
        $one = GitHubActionsStepSummary::auto();
        $two = GitHubActionsStepSummary::auto();
        $three = GitHubActionsStepSummary::enabled();

        self::assertTrue($one->isOneOf($two, $three));
    }
}
