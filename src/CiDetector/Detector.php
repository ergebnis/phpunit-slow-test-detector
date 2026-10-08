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

namespace Ergebnis\PHPUnit\SlowTestDetector\CiDetector;

/**
 * @internal
 */
final class Detector
{
    /**
     * GitHub Actions always sets GITHUB_ACTIONS to "true"; any other value, including an empty one, does not count.
     *
     * @see https://docs.github.com/en/actions/reference/workflows-and-actions/variables#default-environment-variables
     *
     * @param array<string, string> $environmentVariables
     */
    public function isRunningOnGitHubActions(array $environmentVariables): bool
    {
        if (!\array_key_exists('GITHUB_ACTIONS', $environmentVariables)) {
            return false;
        }

        return 'true' === $environmentVariables['GITHUB_ACTIONS'];
    }
}
