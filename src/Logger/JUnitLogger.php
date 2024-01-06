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

use Ergebnis\PHPUnit\SlowTestDetector\FileWriter;
use Ergebnis\PHPUnit\SlowTestDetector\Formatter;
use Ergebnis\PHPUnit\SlowTestDetector\SlowTest;

/**
 * @internal
 */
final class JUnitLogger implements Logger
{
    private Formatter\DurationFormatter $durationFormatter;
    private FileWriter\FileWriter $fileWriter;

    public function __construct(
        Formatter\DurationFormatter $durationFormatter,
        FileWriter\FileWriter $fileWriter
    ) {
        $this->durationFormatter = $durationFormatter;
        $this->fileWriter = $fileWriter;
    }

    public function log(SlowTest ...$slowTests): void
    {
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $testsuites = $dom->appendChild($dom->createElement('testsuites'));

        /** @var \DOMElement $testsuite */
        $testsuite = $testsuites->appendChild($dom->createElement('testsuite'));
        $testsuite->setAttribute('name', 'Slow Tests');

        $this->createSlowTestCases($dom, $testsuite, $slowTests);

        $this->fileWriter->write($dom->saveXML());
    }

    /**
     * @param array<SlowTest> $slowTests
     */
    private function createSlowTestCases(\DOMDocument $dom, \DOMElement $testsuite, array $slowTests): void
    {
        foreach ($slowTests as $slowTest) {
            $testcase = $this->createSlowTestCase($dom, $slowTest);
            $testsuite->appendChild($testcase);
        }

        $slowTestsCount = \count($slowTests);

        $testsuite->setAttribute('tests', (string) $slowTestsCount);
        $testsuite->setAttribute('failures', (string) $slowTestsCount);
        $testsuite->setAttribute('errors', '0');
    }

    private function createSlowTestCase(\DOMDocument $dom, SlowTest $slowTest): \DOMElement
    {
        $testcase = $dom->createElement('testcase');
        $testcase->setAttribute('name', $slowTest->testIdentifier()->toString());
        $testcase->setAttribute('file', $slowTest->testFile()->filename());

        if (null !== $line = $slowTest->testFile()->line()) {
            $testcase->setAttribute('line', (string) $line);
        }

        $failure = $dom->createElement('failure');
        $failure->setAttribute('type', 'slow_test');
        $testcase->appendChild($failure);

        $failure->appendChild($dom->createCDATASection(\sprintf(
            'The actual duration of %s exceeds the maximum allowed duration of %s.',
            $this->durationFormatter->format($slowTest->duration()),
            $this->durationFormatter->format($slowTest->maximumDuration()),
        )));

        return $testcase;
    }
}
