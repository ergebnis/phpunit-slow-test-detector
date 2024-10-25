# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/), and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## Unreleased

For a full diff see [`2.16.0...main`][2.16.0...main].

### Fixed

- Explicitly included `vendor/composer/installed.php` and `vendor/composer/InstalledVersions.php` when building PHAR ([#621]), by [@dantleech]

## [`2.16.0`][2.16.0]

For a full diff see [`2.15.1...2.16.0`][2.15.1...2.16.0].

### Changed

- Allowed installation on PHP 8.4 ([#604]), by [@localheinz]

## [`2.15.1`][2.15.1]

For a full diff see [`2.15.0...2.15.1`][2.15.0...2.15.1].

### Fixed

- Explicitly included `src/` directory when building PHAR ([#598]), by [@localheinz]

## [`2.15.0`][2.15.0]

For a full diff see [`2.14.0...2.15.0`][2.14.0...2.15.0].

### Changed

- Started showing data provider details in list of slow tests ([#559]), by [@mvorisek]

## [`2.14.0`][2.14.0]

For a full diff see [`2.13.0...2.14.0`][2.13.0...2.14.0].

### Changed

- Added support for `phpunit/phpunit:^6.5.0` ([#533]), by [@localheinz]
- Added support for PHP 7.0 ([#534]), by [@localheinz]

## [`2.13.0`][2.13.0]

For a full diff see [`2.12.0...2.13.0`][2.12.0...2.13.0].

### Changed

- Added support for PHP 7.1 ([#532]), by [@localheinz]

## [`2.12.0`][2.12.0]

For a full diff see [`2.11.0...2.12.0`][2.11.0...2.12.0].

### Changed

- Added support for PHP 7.2 ([#531]), by [@localheinz]

## [`2.11.0`][2.11.0]

For a full diff see [`2.10.0...2.11.0`][2.10.0...2.11.0].

### Changed

- Added support for PHP 7.3 ([#476]), by [@localheinz]

## [`2.10.0`][2.10.0]

For a full diff see [`2.9.0...2.10.0`][2.9.0...2.10.0].

### Changed

- Added support for `phpunit/phpunit:^11.0.0` ([#485]), by [@localheinz]
- Added support for using `phpunit-slow-test-detector.phar` with `phpunit/phpunit:^9.0.0` ([#491]), by [@localheinz]
- Added support for using `phpunit-slow-test-detector.phar` with `phpunit/phpunit:^8.5.19` ([#494]), by [@localheinz]
- Added support for using `phpunit-slow-test-detector.phar` with `phpunit/phpunit:^7.5.0` ([#495]), by [@localheinz]

## [`2.9.0`][2.9.0]

For a full diff see [`2.8.0...2.9.0`][2.8.0...2.9.0].

### Changed

- Consistently included test setup and teardown in duration measurement ([#380]), by [@localheinz] and [@mvorisek]

### Fixed

- Required at least `phpunit/phpunit:^7.5.0` ([#448]), by [@localheinz]

## [`2.8.0`][2.8.0]

For a full diff see [`2.7.0...2.8.0`][2.7.0...2.8.0].

### Added

- Added support for `phpunit/phpunit:^7.2.0` ([#447]), by [@localheinz]

## [`2.7.0`][2.7.0]

For a full diff see [`2.6.0...2.7.0`][2.6.0...2.7.0].

### Changed

- Widened version constraints to allow installation with `phpunit/phpunit:^8.5.19`, `phpunit/phpunit:^9.0.0`, and `phpunit/phpunit:^10.0.0` ([#396]), by [@localheinz]

## [`2.6.0`][2.6.0]

For a full diff see [`2.5.0...2.6.0`][2.5.0...2.6.0].

### Added

- Added support for `phpunit/phpunit:^8.5.36` ([#394]), by [@localheinz]

## [`2.5.0`][2.5.0]

For a full diff see [`2.4.0...2.5.0`][2.4.0...2.5.0].

### Added

- Added `Attribute\MaximumDuration` to allow configuration of maximum duration with attributes on test method level ([#367]), by [@HypeMC]
- Added support for PHP 8.0 ([#375]), by [@localheinz] and [@mvorisek]
- Added support for PHP 7.4 ([#390]), by [@localheinz] and [@mvorisek]

### Changed

- Improved detection of PHPUnit version ([#393]), by [@localheinz] and [@mvorisek]

## [`2.4.0`][2.4.0]

For a full diff see [`2.3.2...2.4.0`][2.3.2...2.4.0].

### Added

- Added support for `phpunit/phpunit:^9.6.0` ([#341]), by [@localheinz]

### Changed

- Extracted `Duration` ([#351]), by [@localheinz]
- Merged `MaximumDuration` into `Duration` ([#352]), by [@localheinz]
- Renamed `MaximumCount` to `Count` ([#353]), by [@localheinz]
- Extracted `Time` ([#354]), by [@localheinz]
- Extracted `TestIdentifier` ([#355]), by [@localheinz]
- Required `phpunit/phpunit:^10.4.2` ([#357]), by [@localheinz]

### Fixed

- Marked `DefaultDurationFormatter` as internal ([#350]), by [@localheinz]

## [`2.3.2`][2.3.2]

For a full diff see [`2.3.1...2.3.2`][2.3.1...2.3.2].

### Fixed

- Adjusted version in `manifest.xml` ([#343]), by [@localheinz]

## [`2.3.1`][2.3.1]

For a full diff see [`2.3.0...2.3.1`][2.3.0...2.3.1].

### Fixed

- Prevented inclusion of `phpunit/phpunit` in PHAR ([#342]), by [@localheinz]

## [`2.3.0`][2.3.0]

For a full diff see [`2.2.0...2.3.0`][2.2.0...2.3.0].

### Changed

- Added support for installing extension as a PHAR ([#273]), by [@localheinz]
- Added support for PHP 8.3 ([#340]), by [@localheinz]

## [`2.2.0`][2.2.0]

For a full diff see [`2.1.1...2.2.0`][2.1.1...2.2.0].

### Changed

- Suggested and required `phpunit/phpunit` as a development dependency to allow usage with `phpunit/phpunit` when installed as PHAR ([#272]), by [@localheinz]

## [`2.1.1`][2.1.1]

For a full diff see [`2.1.0...2.1.1`][2.1.0...2.1.1].

### Fixed

- Stopped registering extension when running `phpunit` with the `--no-output` option ([#243]), by [@localheinz]

## [`2.1.0`][2.1.0]

For a full diff see [`2.0.0...2.1.0`][2.0.0...2.1.0].

### Changed

- Started rendering slow tests as ordered list ([#224]), by [@localheinz]

## [`2.0.0`][2.0.0]

For a full diff see [`1.0.0...2.0.0`][1.0.0...2.0.0].

### Changed

- Allowed configuring the maximum duration via `maximum-duration` parameter ([#212]), by [@localheinz]
- Allowed configuring the maximum count via `maximum-count` parameter ([#217]), by [@localheinz]
- Marked classes and interfaces as internal ([#219]), by [@localheinz]
- Brought duration formatting in line with `phpunit/php-timer` ([#220]), by [@localheinz]
- Allowed configuring the maximum duration via `@maximumDuration` annotation ([#222]), by [@localheinz]

### Fixed

- Removed possibility to configure maximum count of reported tests using the `MAXIMUM_NUMBER` environment variable ([#211]), by [@localheinz]
- Increased default maximum count from `3` to `10` and default maximum duration from `125` to `500` milliseconds ([#218]), by [@localheinz]
- Fixed resolving maximum duration from `@slowThreshold` annotation ([#221]), by [@localheinz]

## [`1.0.0`][1.0.0]

For a full diff see [`7afa59c...1.0.0`][7afa59c...1.0.0].

### Added

- Added `SlowTest` ([#6]), by [@localheinz]
- Added `SlowTestCollector` ([#8]), by [@localheinz]
- Added `Subscriber\TestPreparedSubscriber` ([#12]), by [@localheinz]
- Added `Subscriber\TestPassedSubscriber` ([#13]), by [@localheinz]
- Added `Formatter\ToMillisecondsDurationFormatter` ([#17]), by [@localheinz]
- Added `Comparator\DurationComparator` ([#18]), by [@localheinz]
- Added `SlowTestReporter` ([#19]), by [@localheinz]
- Extracted `TimeKeeper` ([#22]), by [@localheinz]
- Extracted `Collector` ([#23]), by [@localheinz]
- Added `Subscriber\TestSuiteFinishedSubscriber` ([#34]), by [@localheinz]
- Added `MaximumDuration` ([#46]), by [@localheinz]
- Added `MaximumCount` ([#47]), by [@localheinz]
- Allowed configuring the maximum duration for a test with a `@slowThreshold` annotation ([#49]), by [@localheinz]

### Changed

- Renamed `SlowTestReporter` to `Reporter\Reporter` ([#20]), by [@localheinz]
- Renamed `Reporter\Reporter` to `Reporter\DefaultReporter` and extracted `Reporter\Reporter` interface ([#21]), by [@localheinz]
- Renamed `Collector` to `Collector\DefaultCollector` and extracted `Collector\Collector` interface ([#24]), by [@localheinz]
- Used `TimeKeeper` instead of `SlowTestCollector` in `Subscriber\TestPreparedSubscriber` ([#25]), by [@localheinz]
- Used `TimeKeeper` and `Collector\Collector` instead of `SlowTestCollector` in `Subscriber\TestPassedSubscriber` ([#26]), by [@localheinz]
- Composed maximum duration into `SlowTest` ([#37]), by [@localheinz]
- Rendered maximum duration in report created by `DefaultReporter` ([#38]), by [@localheinz]

### Removed

- Removed `SlowTestCollector` ([#36]), by [@localheinz]

[1.0.0]: https://github.com/ergebnis/phpunit-slow-test-detector/releases/tag/1.0.0
[2.0.0]: https://github.com/ergebnis/phpunit-slow-test-detector/releases/tag/2.0.0
[2.1.0]: https://github.com/ergebnis/phpunit-slow-test-detector/releases/tag/2.1.0
[2.1.1]: https://github.com/ergebnis/phpunit-slow-test-detector/releases/tag/2.1.1
[2.2.0]: https://github.com/ergebnis/phpunit-slow-test-detector/releases/tag/2.2.0
[2.3.0]: https://github.com/ergebnis/phpunit-slow-test-detector/releases/tag/2.3.0
[2.3.1]: https://github.com/ergebnis/phpunit-slow-test-detector/releases/tag/2.3.1
[2.3.2]: https://github.com/ergebnis/phpunit-slow-test-detector/releases/tag/2.3.2
[2.4.0]: https://github.com/ergebnis/phpunit-slow-test-detector/releases/tag/2.4.0
[2.5.0]: https://github.com/ergebnis/phpunit-slow-test-detector/releases/tag/2.5.0
[2.6.0]: https://github.com/ergebnis/phpunit-slow-test-detector/releases/tag/2.6.0
[2.7.0]: https://github.com/ergebnis/phpunit-slow-test-detector/releases/tag/2.7.0
[2.8.0]: https://github.com/ergebnis/phpunit-slow-test-detector/releases/tag/2.8.0
[2.9.0]: https://github.com/ergebnis/phpunit-slow-test-detector/releases/tag/2.9.0
[2.10.0]: https://github.com/ergebnis/phpunit-slow-test-detector/releases/tag/2.10.0
[2.11.0]: https://github.com/ergebnis/phpunit-slow-test-detector/releases/tag/2.11.0
[2.12.0]: https://github.com/ergebnis/phpunit-slow-test-detector/releases/tag/2.12.0
[2.13.0]: https://github.com/ergebnis/phpunit-slow-test-detector/releases/tag/2.13.0
[2.14.0]: https://github.com/ergebnis/phpunit-slow-test-detector/releases/tag/2.14.0
[2.15.0]: https://github.com/ergebnis/phpunit-slow-test-detector/releases/tag/2.15.0
[2.15.1]: https://github.com/ergebnis/phpunit-slow-test-detector/releases/tag/2.15.1
[2.16.0]: https://github.com/ergebnis/phpunit-slow-test-detector/releases/tag/2.16.0

[7afa59c...1.0.0]: https://github.com/ergebnis/phpunit-slow-test-detector/compare/7afa59c...1.0.0
[1.0.0...2.0.0]: https://github.com/ergebnis/phpunit-slow-test-detector/compare/1.0.0...2.0.0
[2.0.0...2.1.0]: https://github.com/ergebnis/phpunit-slow-test-detector/compare/2.0.0...2.1.0
[2.1.0...2.1.1]: https://github.com/ergebnis/phpunit-slow-test-detector/compare/2.1.0...2.1.1
[2.1.1...2.2.0]: https://github.com/ergebnis/phpunit-slow-test-detector/compare/2.1.1...2.2.0
[2.2.0...2.3.0]: https://github.com/ergebnis/phpunit-slow-test-detector/compare/2.2.0...2.3.0
[2.3.0...2.3.1]: https://github.com/ergebnis/phpunit-slow-test-detector/compare/2.3.0...2.3.1
[2.3.1...2.3.2]: https://github.com/ergebnis/phpunit-slow-test-detector/compare/2.3.1...2.3.2
[2.3.2...2.4.0]: https://github.com/ergebnis/phpunit-slow-test-detector/compare/2.3.2...2.4.0
[2.4.0...2.5.0]: https://github.com/ergebnis/phpunit-slow-test-detector/compare/2.4.0...2.5.0
[2.5.0...2.6.0]: https://github.com/ergebnis/phpunit-slow-test-detector/compare/2.5.0...2.6.0
[2.6.0...2.7.0]: https://github.com/ergebnis/phpunit-slow-test-detector/compare/2.6.0...2.7.0
[2.7.0...2.8.0]: https://github.com/ergebnis/phpunit-slow-test-detector/compare/2.7.0...2.8.0
[2.8.0...2.9.0]: https://github.com/ergebnis/phpunit-slow-test-detector/compare/2.8.0...2.9.0
[2.9.0...2.10.0]: https://github.com/ergebnis/phpunit-slow-test-detector/compare/2.9.0...2.10.0
[2.10.0...2.11.0]: https://github.com/ergebnis/phpunit-slow-test-detector/compare/2.10.0...2.11.0
[2.11.0...2.12.0]: https://github.com/ergebnis/phpunit-slow-test-detector/compare/2.11.0...2.12.0
[2.12.0...2.13.0]: https://github.com/ergebnis/phpunit-slow-test-detector/compare/2.12.0...2.13.0
[2.13.0...2.14.0]: https://github.com/ergebnis/phpunit-slow-test-detector/compare/2.13.0...2.14.0
[2.14.0...2.15.0]: https://github.com/ergebnis/phpunit-slow-test-detector/compare/2.14.0...2.15.0
[2.15.0...2.15.1]: https://github.com/ergebnis/phpunit-slow-test-detector/compare/2.15.0...2.15.1
[2.15.1...2.16.0]: https://github.com/ergebnis/phpunit-slow-test-detector/compare/2.15.1...2.16.0
[2.16.0...main]: https://github.com/ergebnis/phpunit-slow-test-detector/compare/2.16.0...main

[#6]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/6
[#8]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/8
[#12]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/12
[#13]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/13
[#17]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/17
[#18]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/18
[#19]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/19
[#20]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/20
[#21]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/21
[#22]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/22
[#23]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/23
[#24]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/24
[#25]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/25
[#26]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/26
[#34]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/34
[#36]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/36
[#37]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/37
[#38]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/38
[#46]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/46
[#47]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/47
[#49]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/49
[#211]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/211
[#212]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/212
[#217]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/217
[#218]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/218
[#219]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/219
[#220]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/220
[#221]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/221
[#222]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/222
[#224]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/224
[#243]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/243
[#272]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/272
[#273]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/273
[#340]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/340
[#341]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/341
[#342]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/342
[#343]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/343
[#350]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/350
[#351]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/351
[#352]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/352
[#353]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/353
[#354]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/354
[#355]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/355
[#357]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/357
[#367]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/367
[#375]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/375
[#390]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/390
[#393]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/393
[#394]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/394
[#396]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/396
[#447]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/447
[#448]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/448
[#476]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/476
[#485]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/485
[#491]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/491
[#494]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/494
[#495]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/495
[#531]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/531
[#532]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/532
[#533]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/533
[#534]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/534
[#559]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/559
[#598]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/598
[#604]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/604

[@dantleech]: https://github.com/dantleech
[@HypeMC]: https://github.com/HypeMC
[@localheinz]: https://github.com/localheinz
[@mvorisek]: https://github.com/mvorisek
