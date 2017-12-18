# Change Log
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased]
### Removed
- removed default channel selection
- removed obsolete test assertions 
- removed config file

### Fixed
- fixed body type php doc accept string parameters

### Updated
- updated composer dependencies
- updated line formatting
- updated imports

## [1.1.1] - 2017-10-30
### Changed
- change custom grouping fields to private for constancy

## [1.1.0] - 2017-09-21
### Added
- add custom grouping support 

## [1.0.2] - 2017-06-29
### Fixed
- tests

### Added
- travis config
- travis and packagist badges

### Changed
- phpunit version to php ^4.0 to support php 5.4
- minimum php version support to 5.5

### Removed
- guzzle dependency from composer.lock

## [1.0.1] - 2017-06-29
### Fixed
- sending of single message
- default allowed channel being empty

## [1.0.0] - 2017-06-29
### Fixed
- a single channel will not be converted to an unusable nested array

### Added
- configurable api endpoint

## [0.0.1] - 2017-03-30
### Added
- initial code

[Unreleased]: https://github.com/CMTelecom/messaging-php/compare/1.1.1....HEAD
[1.1.1]: https://github.com/CMTelecom/messaging-php/compare/1.1.0...1.1.1
[1.1.0]: https://github.com/CMTelecom/messaging-php/compare/1.0.2...1.1.0
[1.0.2]: https://github.com/CMTelecom/messaging-php/compare/1.0.1...1.0.2
[1.0.1]: https://github.com/CMTelecom/messaging-php/compare/1.0.0...1.0.1
[1.0.0]: https://github.com/CMTelecom/messaging-php/compare/0.0.1...1.0.0
[0.0.1]: https://github.com/CMTelecom/messaging-php/compare/0.0.1...0.0.1