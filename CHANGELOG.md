
# Changelog

## [Unreleased]

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


[1.3.1]: https://github.com/hofff/contao-consent-bridge/compare/1.3.0...1.3.1
[1.3.0]: https://github.com/hofff/contao-consent-bridge/compare/1.2.0...1.3.0
[1.2.0]: https://github.com/hofff/contao-consent-bridge/compare/1.1.1...1.2.0
[1.1.1]: https://github.com/hofff/contao-consent-bridge/compare/1.1.0...1.1.1
[1.1.0]: https://github.com/hofff/contao-consent-bridge/compare/1.0.0...1.1.0
