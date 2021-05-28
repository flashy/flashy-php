# Flashy PHP

Flashy offers the best marketing automation and personalization tools to SMBs and the agencies that serve them. We empower your small team to be as effective as the tech giants at building loyal, personal customer relationships that skyrocket sales.

## How this package will help you?

The Flashy SDK for PHP helps developers to easily connect to Flashy API, through the integration you can:
* Manage Contacts
* Manage Lists
* Send Email Messages
* Send SMS
* Send Push Notifications
* Send Tracking Events

## Installation

You can install the package via composer:

```bash
composer require flashy/flashy-php
```

Or you can download the "src" folder and manually add it to your project, you will need to include Flashy before you can use it:
```bash
require_once("your_path/Flashy.php");
```

## Usage

``` php
$flashy = new Flashy\Flashy($config);

$flashy->contacts->create($contact);
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email hello@flashyapp.com instead of using the issue tracker.

## Credits

- [Rafael Mor](https://github.com/flashy)
- [All Contributors](../../contributors)
