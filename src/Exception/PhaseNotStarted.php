<?php

declare(strict_types=1);

/**
 * Copyright (c) 2021-2023 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/phpunit-slow-test-detector
 */

namespace Ergebnis\PHPUnit\SlowTestDetector\Exception;

use Ergebnis\PHPUnit\SlowTestDetector\PhaseIdentifier;

/**
 * @internal
 */
final class PhaseNotStarted extends \InvalidArgumentException
{
    public static function fromPhaseIdentifier(PhaseIdentifier $phaseIdentifier): self
    {
        return new self(\sprintf(
            'Phase identified by "%s" has not been started.',
            $phaseIdentifier->toString(),
        ));
    }
}
