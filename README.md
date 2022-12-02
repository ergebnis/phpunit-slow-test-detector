# phpunit-slow-test-detector

[![Integrate](https://github.com/ergebnis/phpunit-slow-test-detector/workflows/Integrate/badge.svg)](https://github.com/ergebnis/phpunit-slow-test-detector/actions)
[![Merge](https://github.com/ergebnis/phpunit-slow-test-detector/workflows/Merge/badge.svg)](https://github.com/ergebnis/phpunit-slow-test-detector/actions)
[![Prune](https://github.com/ergebnis/phpunit-slow-test-detector/workflows/Prune/badge.svg)](https://github.com/ergebnis/phpunit-slow-test-detector/actions)
[![Release](https://github.com/ergebnis/phpunit-slow-test-detector/workflows/Release/badge.svg)](https://github.com/ergebnis/phpunit-slow-test-detector/actions)
[![Renew](https://github.com/ergebnis/phpunit-slow-test-detector/workflows/Renew/badge.svg)](https://github.com/ergebnis/phpunit-slow-test-detector/actions)

[![Code Coverage](https://codecov.io/gh/ergebnis/phpunit-slow-test-detector/branch/main/graph/badge.svg)](https://codecov.io/gh/ergebnis/phpunit-slow-test-detector)
[![Type Coverage](https://shepherd.dev/github/ergebnis/phpunit-slow-test-detector/coverage.svg)](https://shepherd.dev/github/ergebnis/phpunit-slow-test-detector)

[![Latest Stable Version](https://poser.pugx.org/ergebnis/phpunit-slow-test-detector/v/stable)](https://packagist.org/packages/ergebnis/phpunit-slow-test-detector)
[![Total Downloads](https://poser.pugx.org/ergebnis/phpunit-slow-test-detector/downloads)](https://packagist.org/packages/ergebnis/phpunit-slow-test-detector)

Provides an extension for detecting slow tests in [`phpunit/phpunit`](https://github.com/sebastianbergmann/phpunit).

## Installation

Run

```sh
composer require --dev ergebnis/phpunit-slow-test-detector
```

## Usage

### Activating the extension

This extension provides three event subscribers for `phpunit/phpunit`:

- `Subscriber\TestPreparedSubscriber`
- `Subscriber\TestPassedSubscriber`
- `Subscriber\TestSuiteFinishedSubscriber`

These subscribers depend on the following:

- a `TimeKeeper` for keeping test prepared and passed times
- a `MaximumDuration`
- a `Collector\Collector` for collecting slow tests
- a `Reporter\Reporter` for reporting slow tests

To activate this extension, you need to register these subscribers with the event system of `phpunit/phpunit`. As of the moment, this is only possible with a `bootstrap.php` script:

```php
<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use Ergebnis\PHPUnit\SlowTestDetector;
use PHPUnit\Event;

$timeKeeper = new SlowTestDetector\TimeKeeper();

Event\Facade::registerSubscriber(new SlowTestDetector\Subscriber\TestPreparedSubscriber($timeKeeper));

$maximumDuration = SlowTestDetector\MaximumDuration::fromMilliseconds(500);

$collector = new SlowTestDetector\Collector\DefaultCollector();

Event\Facade::registerSubscriber(new SlowTestDetector\Subscriber\TestPassedSubscriber(
    $maximumDuration,
    $timeKeeper,
    $collector
));

$maximumCount = SlowTestDetector\MaximumCount::fromInt(10);

$reporter = new SlowTestDetector\Reporter\DefaultReporter(
    new SlowTestDetector\Formatter\ToMillisecondsDurationFormatter(),
    $maximumDuration,
    $maximumCount
);

Event\Facade::registerSubscriber(new SlowTestDetector\Subscriber\TestSuiteFinishedSubscriber(
    $collector,
    $reporter
));
```

:exclamation: Currently, this is a bit verbose. [@sebastianbergmann](https://github.com/sebastianbergmann), [@theseer](https://github.com/theseer), and I are going to meet to talk about how we can improve this.

### Configuring maximum duration per test case

When necessary, you can configure the maximum duration for a test with a `@slowThreshold` annotation in the DocBlock.

This example configures the maximum duration for a single test to 5.000 ms:

```php
<?php

declare(strict_types=1);

use PHPUnit\Framework;

final class ExtraSlowTest extends Framework\TestCase
{
    /**
     * @slowThreshold 5000
     */
    public function testExtraExtraSlow(): void
    {
        // ...
    }
}
```

### Running tests

When you have activated the extension, you can run your tests as usually:

```sh
$ vendor/bin/phpunit
```

When the extension has detected slow tests, it will report them:

```sh
PHPUnit 10.0-dev by Sebastian Bergmann and contributors.

Runtime:       PHP 7.4.14
Configuration: test/Example/phpunit.xml
Random Seed:   1611649366

.....                                                                                                                                                                                                                                                                                                             5 / 5 (100%)

Detected 4 tests that took longer than expected.

1,012 ms (500 ms) Ergebnis\PHPUnit\SlowTestDetector\Test\Example\SleeperTest::testSleeperSleepsOneSecond
  755 ms (500 ms) Ergebnis\PHPUnit\SlowTestDetector\Test\Example\SleeperTest::testSleeperSleepsThreeQuartersOfASecond
  503 ms (500 ms) Ergebnis\PHPUnit\SlowTestDetector\Test\Example\SleeperTest::testSleeperSleepsHalfASeconds

There is one additional slow test that is not listed here.

Time: 00:02.563, Memory: 10.00 MB

OK (5 tests, 5 assertions)
```

## Changelog

Please have a look at [`CHANGELOG.md`](CHANGELOG.md).

## Contributing

Please have a look at [`CONTRIBUTING.md`](.github/CONTRIBUTING.md).

## Code of Conduct

Please have a look at [`CODE_OF_CONDUCT.md`](https://github.com/ergebnis/.github/blob/main/CODE_OF_CONDUCT.md).

## License

This package is licensed using the MIT License.

Please have a look at [`LICENSE.md`](LICENSE.md).

## Credits

This project is inspired by [`johnkary/phpunit-speedtrap`](https://github.com/johnkary/phpunit-speedtrap).

## Curious what I am building?

:mailbox_with_mail: [Subscribe to my list](https://localheinz.com/projects/), and I will occasionally send you an email to let you know what I am working on.
