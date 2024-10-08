
# Changelog

## [Unreleased]

## [1.6.0] - (2024-09-27)

### Added

 - Add support to select a placeholder template

## [1.5.0] - (2024-02-07)

### Changed

 - Dump dependency of Contao to `^4.13 || ^5.2`
 - Dump symfony dependencies to `^5.4 || ^6.4`
 - Require at least PHP 8.1

## [1.4.3] - (2022-01-27)

### Fixed

 - Fix compatibility with symfony/translation v5

## [1.4.2] - (2022-01-18)

### Changed

 - Dump dependency of Contao to `^4.9`
 - Dump symfony dependencies
 - Allow `doctrine/dbal ^3.1`

## [1.4.1] - (2021-05-19)

### Fixed

 - Fix issue with empty google web fonts, especially with Contao 4.11

## [1.4.0] - (2021-02-12)

### Added

 - Support auto configuration for plugins and consent tools ([#3](https://github.com/hofff/contao-consent-bridge/pull/3) by [@richardhj](https://github.com/richardhj))
 - Allow PHP 8.0
 - Allow symfony 5 components where possible

### Changed

 - Updated coding standard an tool change

### Fixed

 - Prevent that fields are added multiple times in the page data container

## [1.3.1] - (2020-10-08)

### Fixed

 - Remove final from `ServiceConsentId::__construct` to avoid breaking changes

## [1.3.0] - (2020-10-07)

### Added

 - Allow to parse a consent id from the active consent tool
 
### Breaking

 - Make `ServiceConsentId::__construct` final to prevent breaking of `fromSerialize()` 
 
### Deprecated

 - Deprecate the `TemplateHelper` as it does not work within Contao templates

## [1.2.0] - (2020-08-07)

### Added

- Add basic support for rocksolid custom elements
- Add configuration option to adjust supported content elements and frontend modules

### Fixed

- Fix license information in the composer.json

## [1.1.1] - (2020-05-14)

### Fixed

 - Fix google web fonts integration
 
## [1.1.0] - (2020-04-01)

### Added

- Add info messages if no consent tag is assign for supported frontend modules and content elements


[1.4.0]: https://github.com/hofff/contao-consent-bridge/compare/1.3.1...1.4.0
[1.3.1]: https://github.com/hofff/contao-consent-bridge/compare/1.3.0...1.3.1
[1.3.0]: https://github.com/hofff/contao-consent-bridge/compare/1.2.0...1.3.0
[1.2.0]: https://github.com/hofff/contao-consent-bridge/compare/1.1.1...1.2.0
[1.1.1]: https://github.com/hofff/contao-consent-bridge/compare/1.1.0...1.1.1
[1.1.0]: https://github.com/hofff/contao-consent-bridge/compare/1.0.0...1.1.0
