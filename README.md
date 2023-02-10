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

### Bootstrapping the extension

To bootstrap the extension, adjust your `phpunit.xml` configuration file:

```diff
 <phpunit
     xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
     xsi:noNamespaceSchemaLocation="vendor/phpunit/phpunit/phpunit.xsd"
     bootstrap="vendor/autoload.php"
 >
+    <extensions>
+        <bootstrap class="Ergebnis\PHPUnit\SlowTestDetector\Extension"/>
+    </extensions>
     <testsuites>
         <testsuite name="unit">
             <directory>test/Unit/</directory>
         </testsuite>
     </testsuites>
 </phpunit>
```

### Configuring the extension

You can configure the extension with the following parameters in your `phpunit.xml` configuration file:

- `maximum-count`, an `int`, the maximum count of slow test that should be listed, defaults to `3`
- `maximum-duration`, an `int`, the maximum duration in milliseconds for all tests, defaults to `250`

The following example configures the maximum count of slow tests to three, and the maximum duration for all tests to 250 milliseconds:

```xml
<phpunit
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:noNamespaceSchemaLocation="vendor/phpunit/phpunit/phpunit.xsd"
    bootstrap="vendor/autoload.php"
>
    <extensions>
        <bootstrap class="Ergebnis\PHPUnit\SlowTestDetector\Extension">
            <parameter name="maximum-duration" value="250"/>
        </boostrap>
    </extensions>
    <testsuites>
        <testsuite name="unit">
            <directory>test/Unit/</directory>
       </testsuite>
    </testsuites>
</phpunit>
```

#### Configuring the maximum duration per test case

You can configure the maximum duration for a single test with a `@slowThreshold` annotation in the DocBlock.

The following example configures the maximum duration for a single test to 5.000 ms:

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
vendor/bin/phpunit
```

When the extension has detected slow tests, it will report them:

```sh
PHPUnit 10.0.0 by Sebastian Bergmann and contributors.

Runtime:       PHP 8.1.0
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
