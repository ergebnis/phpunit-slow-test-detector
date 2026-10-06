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

/**
 * @internal
 *
 * @see https://docs.github.com/en/actions/reference/workflows-and-actions/workflow-commands#setting-a-warning-message
 */
final class WarningMessage implements WorkflowCommand
{
    private string $title;
    private string $message;

    private function __construct(
        string $title,
        string $message
    ) {
        $this->title = $title;
        $this->message = $message;
    }

    public static function create(
        string $title,
        string $message
    ): self {
        return new self(
            $title,
            $message,
        );
    }

    public function command(): string
    {
        return 'warning';
    }

    public function parameters(): array
    {
        return [
            'title' => $this->title,
        ];
    }

    public function value(): string
    {
        return $this->message;
    }
}
