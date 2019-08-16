# Laravel Slack API Token Notification Channel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/beyondcode/slack-notification-channel.svg?style=flat-square)](https://packagist.org/packages/beyondcode/slack-notification-channel)
[![Build Status](https://img.shields.io/travis/beyondcode/slack-notification-channel/master.svg?style=flat-square)](https://travis-ci.org/beyondcode/slack-notification-channel)
[![Quality Score](https://img.shields.io/scrutinizer/g/beyondcode/slack-notification-channel.svg?style=flat-square)](https://scrutinizer-ci.com/g/beyondcode/slack-notification-channel)
[![Total Downloads](https://img.shields.io/packagist/dt/beyondcode/slack-notification-channel.svg?style=flat-square)](https://packagist.org/packages/beyondcode/slack-notification-channel)

This is the Laravel Slack notification channel, but instead of using incoming webhooks, this channel makes use of OAuth access tokens. It also allows replies to thread messages.


[![https://phppackagedevelopment.com](https://beyondco.de/courses/phppd.jpg)](https://phppackagedevelopment.com)

If you want to learn how to create reusable PHP packages yourself, take a look at my upcoming [PHP Package Development](https://phppackagedevelopment.com) video course.

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

### Replying to Message Threads

Assuming you want to keep track of orders and have your team/bot respond to a single thread of per order placed, this channel allows you to retrieve the API response from the chat.postMessage method inside your notifications. With this you could post messages on order paid, shipped, closed, etc. events to the same thread.

In your order placed event you can have

```php
public function toSlack($notifiable)
{
    return (new SlackMessage)
        ->content('A new order has been placed');
}

public function response($response)
{
    $response = $response->getBody()->getContents();
    $this->order->data('slack.thread_ts', json_decode($response, true)['ts']);
}
```

And in your order paid event you can have

```php
public function toSlack($notifiable)
{
    $order = $this->order;
    return (new SlackMessage)
        ->success()
        ->content('Order paid')
        ->threadTimestamp($order->data('slack.thread_ts'))
           ->attachment(function ($attachment) use ($order) {
               $attachment->title("Order $order->reference has been paid for.")
                          ->content('Should now be processed.')
                          ->action('View Order', route('orders', $order->reference));
           });
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
