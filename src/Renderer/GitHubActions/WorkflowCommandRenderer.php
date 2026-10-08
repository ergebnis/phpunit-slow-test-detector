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
final class WorkflowCommandRenderer
{
    public function render(WorkflowCommand $workflowCommand): string
    {
        $parameters = $workflowCommand->parameters();

        if ([] === $parameters) {
            return \sprintf(
                '::%s::%s%s',
                $workflowCommand->command(),
                self::escapeData($workflowCommand->value()),
                "\n",
            );
        }

        $formattedParameters = \array_map(static function (string $name, string $data): string {
            return \sprintf(
                '%s=%s',
                $name,
                self::escapeProperty($data),
            );
        }, \array_keys($parameters), $parameters);

        return \sprintf(
            '::%s %s::%s%s',
            $workflowCommand->command(),
            \implode(
                ',',
                $formattedParameters,
            ),
            self::escapeData($workflowCommand->value()),
            "\n",
        );
    }

    /**
     * @see https://github.com/actions/toolkit/blob/bcae5eca3f432f591cf9f052db4533fc585fb2ac/packages/core/src/command.ts#L103-L108
     */
    private static function escapeData(string $data): string
    {
        return \str_replace(
            [
                '%',
                "\r",
                "\n",
            ],
            [
                '%25',
                '%0D',
                '%0A',
            ],
            $data,
        );
    }

    /**
     * @see https://github.com/actions/toolkit/blob/bcae5eca3f432f591cf9f052db4533fc585fb2ac/packages/core/src/command.ts#L110-L117
     */
    private static function escapeProperty(string $value): string
    {
        return \str_replace(
            [
                '%',
                "\r",
                "\n",
                ':',
                ',',
            ],
            [
                '%25',
                '%0D',
                '%0A',
                '%3A',
                '%2C',
            ],
            $value,
        );
    }
}
