# Megafon notifications channel for Laravel 5.3+

This package makes it easy to send notifications using megafon with Laravel 5.3+.

## Contents

- [Installation](#installation)
    - [Setting up the Megafon service](#setting-up-the-megafon-service)
- [Usage](#usage)
    - [Available Message methods](#available-message-methods)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)


## Installation

You can install the package via composer:

```bash
composer require meshgroup/megafon-notification-channel
```

Then you must install the service provider:
```php
// config/app.php
'providers' => [
    ...
    Meshgroup\Megafon\MegafonServiceProvider::class,
],
```

### Setting up the Megafon service

Add your Megafon login, password and default sender name (or phone number) to your `config/services.php`:

```php
// config/services.php
...
'megafon' => [
    'login'  => env('MEGAFON_LOGIN'),
    'password' => env('MEGAFON_PASSWORD'),
    'sender' => 'John_Doe'
],
...
```

> If you want use other host than `https://a2p-api.megalabs.ru/`, you MUST set custom host WITH trailing slash.

```
// .env
...
MEGAFON_HOST=https://a2p-api.megalabs.ru/
...
```

```php
// config/services.php
...
'megafon' => [
    ...
    'host' => env('MEGAFON_HOST'),
    ...
],
...
```

## Usage

You can use the channel in your `via()` method inside the notification:

```php
use Illuminate\Notifications\Notification;
use Meshgroup\Megafon\MegafonMessage;
use Meshgroup\Megafon\MegafonChannel;

class AccountApproved extends Notification
{
    public function via($notifiable)
    {
        return [MegafonChannel::class];
    }

    public function toMegafon($notifiable)
    {
        return MegafonMessage::create("Task #{$notifiable->id} is complete!");
    }
}
```

In your notifiable model, make sure to include a `routeNotificationForMegafon()` method, which returns a phone number
or an array of phone numbers.

```php
public function routeNotificationForMegafon()
{
    return $this->phone;
}
```

### Available methods

`from()`: Sets the sender's name or phone number.

`content()`: Set a content of the notification message.

`sendAt()`: Set a time for scheduling the notification message.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```
