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
use Ergebnis\PHPUnit\SlowTestDetector\File;
use Ergebnis\PHPUnit\SlowTestDetector\GitHubWorkspace;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\GitHubWorkspace
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Exception\InvalidGitHubWorkspace
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\File
 */
final class GitHubWorkspaceTest extends Framework\TestCase
{
    use Test\Util\Helper;

    /**
     * @dataProvider \Ergebnis\PHPUnit\SlowTestDetector\Test\DataProvider\StringProvider::blank
     * @dataProvider \Ergebnis\PHPUnit\SlowTestDetector\Test\DataProvider\StringProvider::empty
     */
    public function testFromStringThrowsInvalidGitHubWorkspaceWhenValueIsBlankOrEmpty(string $value): void
    {
        $this->expectException(Exception\InvalidGitHubWorkspace::class);

        GitHubWorkspace::fromString($value);
    }

    public function testFromStringReturnsGitHubWorkspaceWhenValueIsNotBlankOrEmpty(): void
    {
        $value = self::faker()->slug();

        $gitHubWorkspace = GitHubWorkspace::fromString($value);

        self::assertSame($value, $gitHubWorkspace->toString());
    }

    public function testFromEnvironmentVariablesReturnsNullWhenGitHubWorkspaceIsNotSet(): void
    {
        $environmentVariables = [
            'GITHUB_ACTIONS' => 'true',
        ];

        self::assertNull(GitHubWorkspace::fromEnvironmentVariables($environmentVariables));
    }

    /**
     * @dataProvider \Ergebnis\PHPUnit\SlowTestDetector\Test\DataProvider\StringProvider::blank
     * @dataProvider \Ergebnis\PHPUnit\SlowTestDetector\Test\DataProvider\StringProvider::empty
     */
    public function testFromEnvironmentVariablesReturnsNullWhenGitHubWorkspaceIsBlankOrEmpty(string $value): void
    {
        $environmentVariables = [
            'GITHUB_WORKSPACE' => $value,
        ];

        self::assertNull(GitHubWorkspace::fromEnvironmentVariables($environmentVariables));
    }

    public function testFromEnvironmentVariablesReturnsGitHubWorkspaceWhenGitHubWorkspaceIsNotBlankOrEmpty(): void
    {
        $value = self::faker()->slug();

        $environmentVariables = [
            'GITHUB_WORKSPACE' => $value,
        ];

        $gitHubWorkspace = GitHubWorkspace::fromEnvironmentVariables($environmentVariables);

        self::assertInstanceOf(GitHubWorkspace::class, $gitHubWorkspace);
        self::assertSame($value, $gitHubWorkspace->toString());
    }

    public function testRelativePathOfReturnsNullWhenFileIsNotInGitHubWorkspace(): void
    {
        $gitHubWorkspace = GitHubWorkspace::fromString('/home/runner/work/foo/foo');

        self::assertNull($gitHubWorkspace->relativePathOf(File::fromString('/home/runner/work/foo/foobar/test/FooTest.php')));
    }

    public function testRelativePathOfReturnsNullWhenFileIsGitHubWorkspace(): void
    {
        $gitHubWorkspace = GitHubWorkspace::fromString('/home/runner/work/foo/foo');

        self::assertNull($gitHubWorkspace->relativePathOf(File::fromString('/home/runner/work/foo/foo/')));
    }

    /**
     * @dataProvider provideValueThatContainsFile
     */
    public function testRelativePathOfReturnsRelativePathWhenFileIsInGitHubWorkspace(string $value): void
    {
        $gitHubWorkspace = GitHubWorkspace::fromString($value);

        $file = File::fromString('/home/runner/work/foo/foo/test/FooTest.php');

        self::assertEquals(File::fromString('test/FooTest.php'), $gitHubWorkspace->relativePathOf($file));
    }

    /**
     * @return \Generator<string, array{0: string}>
     */
    public static function provideValueThatContainsFile(): iterable
    {
        $values = [
            'without-trailing-directory-separator' => '/home/runner/work/foo/foo',
            'with-trailing-directory-separator' => '/home/runner/work/foo/foo/',
        ];

        foreach ($values as $key => $value) {
            yield $key => [
                $value,
            ];
        }
    }

    public function testRelativePathOfReturnsRelativePathWhenGitHubWorkspaceIsSymbolicLinkToDirectoryContainingFile(): void
    {
        $directory = \sprintf(
            '%s/%s',
            \sys_get_temp_dir(),
            \uniqid('github-workspace-'),
        );

        $symbolicLink = \sprintf(
            '%s-link',
            $directory,
        );

        \mkdir($directory);
        \symlink(
            $directory,
            $symbolicLink,
        );

        try {
            $gitHubWorkspace = GitHubWorkspace::fromString($symbolicLink);

            $file = File::fromString(\sprintf(
                '%s/test/FooTest.php',
                \realpath($directory),
            ));

            self::assertEquals(File::fromString('test/FooTest.php'), $gitHubWorkspace->relativePathOf($file));
        } finally {
            \unlink($symbolicLink);
            \rmdir($directory);
        }
    }
}
