# AyeCode Flarum Restrictions

A Flarum extension to restrict content based on EDD licenses.

## Installation

```bash
composer require ayecode/flarum-restrictions
```

## Features

- Restrict access to specific tags based on EDD product licenses
- Controls viewing and replying permissions
- Integrates with WordPress and EDD license system

## Configuration

1. Install the extension via composer
2. Enable it in the Flarum admin panel
3. Configure tag restrictions in the settings

## Release
- Run 'npm run build'
- Update version number in composer.json
- Update version number in README.md

## License

This extension is licensed under the MIT License.

## Changelog

= 0.1.14 =
* Home page start discussion button should be disabled for users with no licenses.

= 0.1.11 =
* Fixed bug where forum access was not being checked.

= 0.1.10 =
* Removed posting access to all forums unless a paid member has a license.

= 0.1.9 =
* added GD core forum to open forums

= 0.1.7 =
* Alert notice shows on general forum and topics - FIXED
* Remove debugging - CHANGED