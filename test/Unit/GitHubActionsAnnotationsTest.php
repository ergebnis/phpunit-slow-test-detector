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
use Ergebnis\PHPUnit\SlowTestDetector\GitHubActionsAnnotations;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\GitHubActionsAnnotations
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Exception\InvalidGitHubActionsAnnotations
 */
final class GitHubActionsAnnotationsTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testFromStringThrowsInvalidGitHubActionsAnnotationsWhenValueIsUnknown(): void
    {
        $value = self::faker()->word();

        $this->expectException(Exception\InvalidGitHubActionsAnnotations::class);

        GitHubActionsAnnotations::fromString($value);
    }

    public function testFromStringReturnsAutoWhenValueIsAuto(): void
    {
        $gitHubActionsAnnotations = GitHubActionsAnnotations::fromString('auto');

        self::assertEquals(GitHubActionsAnnotations::auto(), $gitHubActionsAnnotations);
    }

    public function testFromStringReturnsEnabledWhenValueIsTrue(): void
    {
        $gitHubActionsAnnotations = GitHubActionsAnnotations::fromString('true');

        self::assertEquals(GitHubActionsAnnotations::enabled(), $gitHubActionsAnnotations);
    }

    public function testFromStringReturnsDisabledWhenValueIsFalse(): void
    {
        $gitHubActionsAnnotations = GitHubActionsAnnotations::fromString('false');

        self::assertEquals(GitHubActionsAnnotations::disabled(), $gitHubActionsAnnotations);
    }

    public function testEqualsReturnsFalseWhenValueIsDifferent(): void
    {
        $one = GitHubActionsAnnotations::auto();
        $two = GitHubActionsAnnotations::enabled();

        self::assertFalse($one->equals($two));
    }

    public function testEqualsReturnsTrueWhenValueIsSame(): void
    {
        $one = GitHubActionsAnnotations::auto();
        $two = GitHubActionsAnnotations::auto();

        self::assertTrue($one->equals($two));
    }

    public function testIsOneOfReturnsFalseWhenAllValuesAreDifferent(): void
    {
        $one = GitHubActionsAnnotations::auto();
        $two = GitHubActionsAnnotations::disabled();
        $three = GitHubActionsAnnotations::enabled();

        self::assertFalse($one->isOneOf($two, $three));
    }

    public function testIsOneOfReturnsTrueWhenOneOfTheValuesIsSame(): void
    {
        $one = GitHubActionsAnnotations::auto();
        $two = GitHubActionsAnnotations::auto();
        $three = GitHubActionsAnnotations::enabled();

        self::assertTrue($one->isOneOf($two, $three));
    }
}
