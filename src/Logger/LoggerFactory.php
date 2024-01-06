<?php

declare(strict_types=1);

/**
 * Copyright (c) 2021-2024 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/phpunit-slow-test-detector
 */

namespace Ergebnis\PHPUnit\SlowTestDetector\Logger;

use Ergebnis\PHPUnit\SlowTestDetector\FileWriter\DefaultFileWriter;
use Ergebnis\PHPUnit\SlowTestDetector\FileWriter\File;
use Ergebnis\PHPUnit\SlowTestDetector\Formatter;
use PHPUnit\Runner;
use PHPUnit\TextUI;

/**
 * @internal
 */
final class LoggerFactory
{
    private Formatter\DurationFormatter $durationFormatter;

    public function __construct(Formatter\DurationFormatter $durationFormatter)
    {
        $this->durationFormatter = $durationFormatter;
    }

    public function forConfiguration(
        TextUI\Configuration\Configuration $configuration,
        Runner\Extension\ParameterCollection $parameters
    ): Logger {
        if ($configuration->hasLogfileJunit() && $parameters->has('junit-file')) {
            return $this->createJUnitLogger(File::fromString($parameters->get('junit-file')));
        }

        return $this->createNullLogger();
    }

    public function forArguments(array $arguments, array $options): Logger
    {
        if (isset($options['junit-file']) && \in_array('--log-junit', $arguments, true)) {
            return $this->createJUnitLogger(File::fromString((string) $options['junit-file']));
        }

        return $this->createNullLogger();
    }

    private function createJUnitLogger(File $file): Logger
    {
        return new JUnitLogger($this->durationFormatter, new DefaultFileWriter($file));
    }

    private function createNullLogger(): Logger
    {
        return new NullLogger();
    }
}
