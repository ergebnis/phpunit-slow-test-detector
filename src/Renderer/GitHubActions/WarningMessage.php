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

namespace Ergebnis\PHPUnit\SlowTestDetector\Renderer\GitHubActions;

use Ergebnis\PHPUnit\SlowTestDetector\File;
use Ergebnis\PHPUnit\SlowTestDetector\Line;

/**
 * @internal
 *
 * @see https://docs.github.com/en/actions/reference/workflows-and-actions/workflow-commands#setting-a-warning-message
 */
final class WarningMessage implements WorkflowCommand
{
    private Title $title;
    private Message $message;
    private ?File $file = null;
    private ?Line $line = null;

    private function __construct(
        Title $title,
        Message $message
    ) {
        $this->title = $title;
        $this->message = $message;
    }

    public static function create(
        Title $title,
        Message $message
    ): self {
        return new self(
            $title,
            $message,
        );
    }

    public function withFileAndLine(
        File $file,
        Line $line
    ): self {
        $instance = clone $this;

        $instance->file = $file;
        $instance->line = $line;

        return $instance;
    }

    public function command(): string
    {
        return 'warning';
    }

    public function parameters(): array
    {
        $parameters = [
            'title' => $this->title->toString(),
        ];

        if ($this->file instanceof File) {
            $parameters['file'] = $this->file->toString();
        }

        if ($this->line instanceof Line) {
            $parameters['line'] = (string) $this->line->toInt();
        }

        return $parameters;
    }

    public function value(): string
    {
        return $this->message->toString();
    }
}
