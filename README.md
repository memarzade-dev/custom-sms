# CustomSMS Notification Channel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/memarzade-dev/custom-sms.svg?style=flat-square)](https://packagist.org/packages/memarzade-dev/custom-sms)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/memarzade-dev/custom-sms/master.svg?style=flat-square)](https://travis-ci.org/memarzade-dev/custom-sms)
[![StyleCI](https://styleci.io/repos/339892204/shield)](https://styleci.io/repos/339892204)
[![Total Downloads](https://img.shields.io/packagist/dt/memarzade-dev/custom-sms.svg?style=flat-square)](https://packagist.org/packages/memarzade-dev/custom-sms)

📲  [CustomSMS](https://customsms.com.au) Notifications Channel for Laravel

## Contents

- [Installation](#installation)
	- [Setting up the CustomSms service](#setting-up-the-CustomSms-service)
- [Usage](#usage)
	- [Available Message methods](#available-message-methods)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)


## Installation

```bash
composer require memarzade-dev/custom-sms
```

Add the configuration to your `services.php` config file:

```php
'customsms' => [
    'token_id' => env('SMS_TOKEN_ID'),
    'access_token' => env('SMS_ACCESS_TOKEN'),
    'default_sender' => env('SMS_DEFAULT_SENDER', null),
]
```

### Setting up the CustomSms service

You'll need a CustomSMS account. Head over to their [website](https://www.customsms.com.au/) and create or login to your account.

Head to `Settings` and then `API keys` in the sidebar to generate a set of API keys.

## Usage

You can use the channel in your `via()` method inside the notification:

```php
use Illuminate\Notifications\Notification;
use \NotificationChannels\CustomSms\CustomSmsMessage;
use \NotificationChannels\CustomSms\CustomSmsChannel;

class AccountApproved extends Notification
{
    public function via($notifiable)
    {
        return [CustomSmsChannel::class];
    }

    public function toCustomsms($notifiable)
    {
        return (new CustomSmsMessage)
            ->content("Task #{$notifiable->id} is complete!");
    }
}
```

In your notifiable model, make sure to include a `routeNotificationForCustomsms()` method, which returns an australian or new zeland phone number in the international format.

```php
public function routeNotificationForCustomsms()
{
    return $this->phone; // 6142345678
}
```

### Available methods

`sender()`: Sets the sender's name or phone number.

`content()`: Set a content of the notification message.

`reference()`: Set the SMS reference code (included with replies/delivery receipt callbacks)

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email support@customsms.com.au instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [CustomSms](https://github.com/customsms)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
