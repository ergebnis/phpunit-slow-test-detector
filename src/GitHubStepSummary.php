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
 *
 * @see https://docs.github.com/en/actions/reference/workflows-and-actions/variables#default-environment-variables
 * @see https://docs.github.com/en/actions/reference/workflows-and-actions/workflow-commands#adding-a-job-summary
 */
final class GitHubStepSummary
{
    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * @throws Exception\InvalidGitHubStepSummary
     */
    public static function fromString(string $value): self
    {
        if ('' === \trim($value)) {
            throw Exception\InvalidGitHubStepSummary::blankOrEmpty();
        }

        return new self($value);
    }

    /**
     * @param array<string, string> $environmentVariables
     */
    public static function fromEnvironmentVariables(array $environmentVariables): ?self
    {
        if (!\array_key_exists('GITHUB_STEP_SUMMARY', $environmentVariables)) {
            return null;
        }

        $value = $environmentVariables['GITHUB_STEP_SUMMARY'];

        if ('' === \trim($value)) {
            return null;
        }

        return new self($value);
    }

    public function toString(): string
    {
        return $this->value;
    }
}
