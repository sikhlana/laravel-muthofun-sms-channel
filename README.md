# Muthofun SMS notifications channel for Laravel 5

This package makes it easy to send SMS notifications via [Muthofun](http://www.muthofun.com/) for Laravel 5.
Muthofun only provides SMS service for Bangladeshi mobile operators.

## Contents

- [Installation](#installation)
	- [Setting up the Muthofun service](#setting-up-the-muthofun-service)
- [Usage](#usage)
	- [Available Message methods](#available-message-methods)
- [Changelog](#changelog)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)

## Installation

You can install the package via composer:

``` bash
composer require sikhlana/laravel-muthofun-sms-channel
```

First you must install the service provider (skip for Laravel >= 5.5):

``` php
// config/app.php
'providers' => [
    ...
    Sikhlana\MuthofunSmsChannel\ServiceProvider::class,
],
```

### Setting up the Muthofun service

Add your generated Muthofun SMS API key in your `.env` file:

``` dotenv
...
MUTHOFUN_SMS_USERNAME=
MUTHOFUN_SMS_PASSWORD=
```

## Usage

Now you can use the channel in your `via()` method inside the notification:

``` php
use Sikhlana\MuthofunSmsChannel\MuthofunChannel;
use Sikhlana\MuthofunSmsChannel\MuthofunMessage;
use Illuminate\Notifications\Notification;

class AccountApproved extends Notification
{
    public function via($notifiable)
    {
        return [MuthofunChannel::class];
    }

    public function toMuthofun($notifiable)
    {
        return (new MuthofunMessage())
            ->content("Your {$notifiable->service} account was approved!");
    }
}
```

In order to let your Notification know which phone are you sending/calling to, the channel will look for the `phone_number` attribute of the Notifiable model. If you want to override this behaviour, add the `routeNotificationForMuthofun` method to your Notifiable model.

``` php
public function routeNotificationForMuthofun()
{
    return '01765432109';
}
```

### Available Message methods

#### MuthofunMessage

- `content(string)`: Sets the message content.
- `line(string)`: Adds a line of text to the notification.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Security

If you discover any security related issues, please email xoxo@saifmahmud.name instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Saif Mahmud](https://github.com/sikhlana)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
