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

namespace Ergebnis\PHPUnit\SlowTestDetector\Renderer;

/**
 * Ported from PHPUnit, licensed under the BSD 3-Clause License:
 *
 * Copyright (c) 2001-2026, Sebastian Bergmann
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 * 1. Redistributions of source code must retain the above copyright notice, this
 *    list of conditions and the following disclaimer.
 *
 * 2. Redistributions in binary form must reproduce the above copyright notice,
 *    this list of conditions and the following disclaimer in the documentation
 *    and/or other materials provided with the distribution.
 *
 * 3. Neither the name of the copyright holder nor the names of its
 *    contributors may be used to endorse or promote products derived from
 *    this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE
 * FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
 * OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @internal
 *
 * @see https://github.com/sebastianbergmann/phpunit/blob/13.4.0/LICENSE
 * @see https://github.com/sebastianbergmann/phpunit/blob/13.4.0/src/Util/Sanitizer.php
 */
final class Sanitizer
{
    /**
     * Replaces control characters with their visible \u{NNNN} escape sequence:
     *
     * - C0 control characters (U+0000-U+001F) and DEL (U+007F), except for line feed, horizontal tab,
     *   and carriage return when it is followed by line feed
     * - C1 control characters (U+0080-U+009F)
     * - Unicode bidirectional formatting characters (U+202A-U+202E and U+2066-U+2069)
     */
    public function sanitize(string $value): string
    {
        $sanitized = \preg_replace_callback(
            '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]|\r(?!\n)|\xC2[\x80-\x9F]|\xE2\x80[\xAA-\xAE]|\xE2\x81[\xA6-\xA9]/',
            static function (array $matches): string {
                return \sprintf(
                    '\u{%04X}',
                    \mb_ord(
                        $matches[0],
                        'UTF-8',
                    ),
                );
            },
            $value,
        );

        if (!\is_string($sanitized)) {
            return $value;
        }

        return $sanitized;
    }
}
