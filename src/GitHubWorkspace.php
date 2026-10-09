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
 */
final class GitHubWorkspace
{
    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * @throws Exception\InvalidGitHubWorkspace
     */
    public static function fromString(string $value): self
    {
        if ('' === \trim($value)) {
            throw Exception\InvalidGitHubWorkspace::blankOrEmpty();
        }

        return new self($value);
    }

    /**
     * @param array<string, string> $environmentVariables
     */
    public static function fromEnvironmentVariables(array $environmentVariables): ?self
    {
        if (!\array_key_exists('GITHUB_WORKSPACE', $environmentVariables)) {
            return null;
        }

        $value = $environmentVariables['GITHUB_WORKSPACE'];

        if ('' === \trim($value)) {
            return null;
        }

        return new self($value);
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function relativePathOf(File $file): ?File
    {
        $directory = $this->value;

        $realPath = \realpath($directory);

        if (\is_string($realPath)) {
            $directory = $realPath;
        }

        $prefix = \rtrim(
            $directory,
            \DIRECTORY_SEPARATOR,
        ) . \DIRECTORY_SEPARATOR;

        if (0 !== \strpos($file->toString(), $prefix)) {
            return null;
        }

        $relativePath = \str_replace(
            \DIRECTORY_SEPARATOR,
            '/',
            (string) \substr(
                $file->toString(),
                \strlen($prefix),
            ),
        );

        if ('' === \trim($relativePath)) {
            return null;
        }

        return File::fromString($relativePath);
    }
}
