# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/), and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## Unreleased

For a full diff see [`2.3.0...main`][2.3.0...main].

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

[7afa59c...1.0.0]: https://github.com/ergebnis/phpunit-slow-test-detector/compare/7afa59c...1.0.0
[1.0.0...2.0.0]: https://github.com/ergebnis/phpunit-slow-test-detector/compare/1.0.0...2.0.0
[2.0.0...2.1.0]: https://github.com/ergebnis/phpunit-slow-test-detector/compare/2.0.0...2.1.0
[2.1.0...2.1.1]: https://github.com/ergebnis/phpunit-slow-test-detector/compare/2.1.0...2.1.1
[2.1.1...2.2.0]: https://github.com/ergebnis/phpunit-slow-test-detector/compare/2.1.1...2.2.0
[2.2.0...2.3.0]: https://github.com/ergebnis/phpunit-slow-test-detector/compare/2.2.0...2.3.0
[2.3.0...main]: https://github.com/ergebnis/phpunit-slow-test-detector/compare/2.3.0...main

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
[#342]: https://github.com/ergebnis/phpunit-slow-test-detector/pull/342

[@localheinz]: https://github.com/localheinz
