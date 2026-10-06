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
 * @see https://docs.github.com/en/actions/reference/workflows-and-actions/workflow-commands#about-workflow-commands
 */
interface WorkflowCommand
{
    public function command(): string;

    /**
     * @return array<string, string>
     */
    public function parameters(): array;

    public function value(): string;
}
