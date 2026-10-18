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

use Ergebnis\PHPUnit\SlowTestDetector\Exception;
use Ergebnis\PHPUnit\SlowTestDetector\Renderer;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Renderer\GitHubActions\Message
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Exception\InvalidMessage
 */
final class MessageTest extends Framework\TestCase
{
    use Test\Util\Helper;

    /**
     * @dataProvider \Ergebnis\PHPUnit\SlowTestDetector\Test\DataProvider\StringProvider::blank
     * @dataProvider \Ergebnis\PHPUnit\SlowTestDetector\Test\DataProvider\StringProvider::empty
     */
    public function testFromStringThrowsInvalidMessageWhenValueIsBlankOrEmpty(string $value): void
    {
        $this->expectException(Exception\InvalidMessage::class);

        Renderer\GitHubActions\Message::fromString($value);
    }

    public function testFromStringReturnsMessageWhenValueIsNotBlankOrEmpty(): void
    {
        $value = self::faker()->sentence();

        $message = Renderer\GitHubActions\Message::fromString($value);

        self::assertSame($value, $message->toString());
    }
}
