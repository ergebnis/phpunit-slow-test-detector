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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\Unit\Renderer\GitHubActions;

use Ergebnis\PHPUnit\SlowTestDetector\File;
use Ergebnis\PHPUnit\SlowTestDetector\Line;
use Ergebnis\PHPUnit\SlowTestDetector\Renderer;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Renderer\GitHubActions\WarningMessage
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\File
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Line
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Renderer\GitHubActions\Message
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Renderer\GitHubActions\Title
 */
final class WarningMessageTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testCreateReturnsWarningMessage(): void
    {
        $faker = self::faker();

        $title = Renderer\GitHubActions\Title::fromString($faker->word());
        $message = Renderer\GitHubActions\Message::fromString($faker->sentence());

        $warningMessage = Renderer\GitHubActions\WarningMessage::create(
            $title,
            $message,
        );

        self::assertSame('warning', $warningMessage->command());
        self::assertSame([
            'title' => $title->toString(),
        ], $warningMessage->parameters());
        self::assertSame($message->toString(), $warningMessage->value());
    }

    public function testWithFileAndLineReturnsWarningMessageWithFileAndLine(): void
    {
        $faker = self::faker();

        $title = $faker->word();
        $message = $faker->sentence();
        $file = $faker->slug();
        $line = $faker->numberBetween(1);

        $warningMessage = Renderer\GitHubActions\WarningMessage::create(
            Renderer\GitHubActions\Title::fromString($title),
            Renderer\GitHubActions\Message::fromString($message),
        );

        $mutated = $warningMessage->withFileAndLine(
            File::fromString($file),
            Line::fromInt($line),
        );

        self::assertNotSame($warningMessage, $mutated);
        self::assertSame('warning', $mutated->command());
        self::assertSame([
            'title' => $title,
            'file' => $file,
            'line' => (string) $line,
        ], $mutated->parameters());
        self::assertSame($message, $mutated->value());
        self::assertSame([
            'title' => $title,
        ], $warningMessage->parameters());
    }
}
