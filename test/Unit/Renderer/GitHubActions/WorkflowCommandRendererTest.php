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

use Ergebnis\PHPUnit\SlowTestDetector\Renderer;
use Ergebnis\PHPUnit\SlowTestDetector\Test;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\PHPUnit\SlowTestDetector\Renderer\GitHubActions\WorkflowCommandRenderer
 *
 * @uses \Ergebnis\PHPUnit\SlowTestDetector\Renderer\GitHubActions\WarningMessage
 */
final class WorkflowCommandRendererTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testRenderReturnsWorkflowCommandWithoutParametersWhenWorkflowCommandHasNoParameters(): void
    {
        $faker = self::faker();

        $command = $faker->word();
        $value = $faker->sentence();

        $workflowCommandRenderer = new Renderer\GitHubActions\WorkflowCommandRenderer();

        $expected = \sprintf(
            '::%s::%s%s',
            $command,
            $value,
            "\n",
        );

        self::assertSame($expected, $workflowCommandRenderer->render(self::workflowCommand(
            $command,
            [],
            $value,
        )));
    }

    public function testRenderReturnsWorkflowCommandWithParametersInOrderWhenWorkflowCommandHasParameters(): void
    {
        $faker = self::faker();

        $command = $faker->word();
        $value = $faker->sentence();
        $title = $faker->word();
        $file = $faker->word();

        $workflowCommandRenderer = new Renderer\GitHubActions\WorkflowCommandRenderer();

        $expected = \sprintf(
            '::%s title=%s,file=%s::%s%s',
            $command,
            $title,
            $file,
            $value,
            "\n",
        );

        self::assertSame($expected, $workflowCommandRenderer->render(self::workflowCommand(
            $command,
            [
                'title' => $title,
                'file' => $file,
            ],
            $value,
        )));
    }

    public function testRenderReturnsWarningMessageWithTitleFileAndLineWhenWarningMessageHasFileAndLine(): void
    {
        $faker = self::faker();

        $title = $faker->word();
        $message = $faker->sentence();
        $file = \sprintf(
            '%s/%s.php',
            $faker->word(),
            $faker->word(),
        );
        $line = $faker->numberBetween(1);

        $workflowCommandRenderer = new Renderer\GitHubActions\WorkflowCommandRenderer();

        $warningMessage = Renderer\GitHubActions\WarningMessage::create(
            $title,
            $message,
        )->withFileAndLine(
            $file,
            $line,
        );

        $expected = \sprintf(
            '::warning title=%s,file=%s,line=%d::%s%s',
            $title,
            $file,
            $line,
            $message,
            "\n",
        );

        self::assertSame($expected, $workflowCommandRenderer->render($warningMessage));
    }

    public function testRenderEscapesValue(): void
    {
        $workflowCommandRenderer = new Renderer\GitHubActions\WorkflowCommandRenderer();

        $expected = "::warning::100%25 done%0D%0Anext: line, with comma\n";

        self::assertSame($expected, $workflowCommandRenderer->render(self::workflowCommand(
            'warning',
            [],
            "100% done\r\nnext: line, with comma",
        )));
    }

    public function testRenderEscapesParameterValues(): void
    {
        $workflowCommandRenderer = new Renderer\GitHubActions\WorkflowCommandRenderer();

        $expected = "::warning title=100%25 done%0D%0Anext%3A line%2C with comma::message\n";

        self::assertSame($expected, $workflowCommandRenderer->render(self::workflowCommand(
            'warning',
            [
                'title' => "100% done\r\nnext: line, with comma",
            ],
            'message',
        )));
    }

    /**
     * @param array<string, string> $parameters
     */
    private static function workflowCommand(
        string $command,
        array $parameters,
        string $value
    ): Renderer\GitHubActions\WorkflowCommand {
        return new class($command, $parameters, $value) implements Renderer\GitHubActions\WorkflowCommand {
            private string $command;

            /**
             * @var array<string, string>
             */
            private array $parameters;
            private string $value;

            /**
             * @param array<string, string> $parameters
             */
            public function __construct(
                string $command,
                array $parameters,
                string $value
            ) {
                $this->command = $command;
                $this->parameters = $parameters;
                $this->value = $value;
            }

            public function command(): string
            {
                return $this->command;
            }

            public function parameters(): array
            {
                return $this->parameters;
            }

            public function value(): string
            {
                return $this->value;
            }
        };
    }
}
