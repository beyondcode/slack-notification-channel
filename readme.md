# Laravel Slack API Token Notification Channel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/beyondcode/slack-notification-channel.svg?style=flat-square)](https://packagist.org/packages/beyondcode/slack-notification-channel)
[![Build Status](https://img.shields.io/travis/beyondcode/slack-notification-channel/master.svg?style=flat-square)](https://travis-ci.org/beyondcode/slack-notification-channel)
[![Quality Score](https://img.shields.io/scrutinizer/g/beyondcode/slack-notification-channel.svg?style=flat-square)](https://scrutinizer-ci.com/g/beyondcode/slack-notification-channel)
[![Total Downloads](https://img.shields.io/packagist/dt/beyondcode/slack-notification-channel.svg?style=flat-square)](https://packagist.org/packages/beyondcode/slack-notification-channel)

This is the Laravel Slack notification channel, but instead of using incoming webhooks, this channel makes use of OAuth access tokens.

### Usage

Install the package via composer:

```
composer require beyondcode/slack-notification-channel
```

The service provider gets registered automatically and you can use this package as a replacement of the core Laravel Slack notification channel.

### Notification Routing

Since this notification channel makes use of Slack API tokens instead of incoming webhook URLs, you need to return an array containing the API token and an optional channel. 
This channel will be used, if it is not provided in the `SlackMessage` that you send:

```php
public function routeNotificationForSlack()
{
    return [
        'token' => 'xoxp-slack-token',
        'channel' => '#general'
    ];
}
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email marcel@beyondco.de instead of using the issue tracker.

## Credits

- [Marcel Pociot](https://github.com/mpociot)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
