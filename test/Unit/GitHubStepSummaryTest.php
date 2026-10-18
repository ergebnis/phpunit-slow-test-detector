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
use Ergebnis\PHPUnit\SlowTestDetector\GitHubStepSummary;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\GitHubStepSummary
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Exception\InvalidGitHubStepSummary
 */
final class GitHubStepSummaryTest extends Framework\TestCase
{
    use Test\Util\Helper;

    /**
     * @dataProvider \Ergebnis\PHPUnit\SlowTestDetector\Test\DataProvider\StringProvider::blank
     * @dataProvider \Ergebnis\PHPUnit\SlowTestDetector\Test\DataProvider\StringProvider::empty
     */
    public function testFromStringThrowsInvalidGitHubStepSummaryWhenValueIsBlankOrEmpty(string $value): void
    {
        $this->expectException(Exception\InvalidGitHubStepSummary::class);

        GitHubStepSummary::fromString($value);
    }

    public function testFromStringReturnsGitHubStepSummaryWhenValueIsNotBlankOrEmpty(): void
    {
        $value = self::faker()->slug();

        $gitHubStepSummary = GitHubStepSummary::fromString($value);

        self::assertSame($value, $gitHubStepSummary->toString());
    }

    public function testFromEnvironmentVariablesReturnsNullWhenGitHubStepSummaryIsNotSet(): void
    {
        $environmentVariables = [
            'GITHUB_ACTIONS' => 'true',
        ];

        self::assertNull(GitHubStepSummary::fromEnvironmentVariables($environmentVariables));
    }

    /**
     * @dataProvider \Ergebnis\PHPUnit\SlowTestDetector\Test\DataProvider\StringProvider::blank
     * @dataProvider \Ergebnis\PHPUnit\SlowTestDetector\Test\DataProvider\StringProvider::empty
     */
    public function testFromEnvironmentVariablesReturnsNullWhenGitHubStepSummaryIsBlankOrEmpty(string $value): void
    {
        $environmentVariables = [
            'GITHUB_STEP_SUMMARY' => $value,
        ];

        self::assertNull(GitHubStepSummary::fromEnvironmentVariables($environmentVariables));
    }

    public function testFromEnvironmentVariablesReturnsGitHubStepSummaryWhenGitHubStepSummaryIsNotBlankOrEmpty(): void
    {
        $value = self::faker()->slug();

        $environmentVariables = [
            'GITHUB_STEP_SUMMARY' => $value,
        ];

        $gitHubStepSummary = GitHubStepSummary::fromEnvironmentVariables($environmentVariables);

        self::assertInstanceOf(GitHubStepSummary::class, $gitHubStepSummary);
        self::assertSame($value, $gitHubStepSummary->toString());
    }
}
