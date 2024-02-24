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

namespace Ergebnis\PHPUnit\SlowTestDetector\Test\DataProvider\Exception;

/**
 * @see https://github.com/ergebnis/data-provider/blob/3.2.0/src/Exception/EmptyValues.php
 */
final class EmptyValues extends \InvalidArgumentException
{
    public static function create(): self
    {
        return new self('Values can not be empty.');
    }

    public static function filtered(): self
    {
        return new self('Filtered values can not be empty.');
    }
}
