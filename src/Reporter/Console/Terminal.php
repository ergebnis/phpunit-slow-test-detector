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

namespace Ergebnis\PHPUnit\SlowTestDetector\Reporter\Console;

use Ergebnis\PHPUnit\SlowTestDetector\Reporter;
use Ergebnis\PHPUnit\SlowTestDetector\Width;

/**
 * @internal
 */
final class Terminal
{
    /**
     * @see https://github.com/sebastianbergmann/environment/blob/5.1.5/src/Console.php#L84-L95
     */
    public static function width(): Reporter\Console\TerminalWidth
    {
        if (!self::isInteractive()) {
            return Reporter\Console\TerminalWidth::default();
        }

        if (self::isWindows()) {
            return self::widthOnWindows();
        }

        return self::widthOnUnix();
    }

    /**
     * @see https://github.com/sebastianbergmann/environment/blob/5.1.5/src/Console.php#L105-L129
     */
    private static function isInteractive(): bool
    {
        if (!\defined('STDIN')) {
            return false;
        }

        if (\function_exists('stream_isatty')) {
            return \stream_isatty(\STDIN);
        }

        if (\function_exists('posix_isatty')) {
            return \posix_isatty(\STDIN);
        }

        return false;
    }

    /**
     * @see https://github.com/sebastianbergmann/environment/blob/5.1.5/src/OperatingSystem.php#L20-L27
     */
    private static function isWindows(): bool
    {
        return \DIRECTORY_SEPARATOR === '\\';
    }

    /**
     * @see https://github.com/sebastianbergmann/environment/blob/5.1.5/src/Console.php#L132-L147
     */
    private static function widthOnUnix(): Reporter\Console\TerminalWidth
    {
        if (!\function_exists('shell_exec')) {
            return Reporter\Console\TerminalWidth::default();
        }

        $sizeOutput = \shell_exec('stty size 2>/dev/null');

        if (
            \is_string($sizeOutput)
            && 1 === \preg_match('/\d+ (?P<numberOfColumns>\d+)/', $sizeOutput, $matches)
            && 0 < (int) $matches['numberOfColumns']
        ) {
            return Reporter\Console\TerminalWidth::fromWidth(Width::fromInt((int) $matches['numberOfColumns']));
        }

        $output = \shell_exec('stty 2>/dev/null');

        if (
            \is_string($output)
            && 1 === \preg_match('/columns = (?P<numberOfColumns>\d+);/', $output, $matches)
            && 0 < (int) $matches['numberOfColumns']
        ) {
            return Reporter\Console\TerminalWidth::fromWidth(Width::fromInt((int) $matches['numberOfColumns']));
        }

        return Reporter\Console\TerminalWidth::default();
    }

    /**
     * @see https://github.com/sebastianbergmann/environment/blob/5.1.5/src/Console.php#L152-L187
     */
    private static function widthOnWindows(): Reporter\Console\TerminalWidth
    {
        $ansicon = \getenv('ANSICON');

        if (
            \is_string($ansicon)
            && 1 === \preg_match('/^(?P<numberOfColumns>\d+)x\d+ \(\d+x\d+\)$/', \trim($ansicon), $matches)
            && 0 < (int) $matches['numberOfColumns']
        ) {
            return Reporter\Console\TerminalWidth::fromWidth(Width::fromInt((int) $matches['numberOfColumns']));
        }

        return Reporter\Console\TerminalWidth::default();
    }
}
