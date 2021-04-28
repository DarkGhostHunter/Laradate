![Aron Visuals - Unsplash (UL) #BXOXnQ26B7o](https://images.unsplash.com/photo-1501139083538-0139583c060f?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&h=400&q=80)

[![Latest Stable Version](https://poser.pugx.org/darkghosthunter/laradate/v/stable)](https://packagist.org/packages/darkghosthunter/laradate) [![License](https://poser.pugx.org/darkghosthunter/laradate/license)](https://packagist.org/packages/darkghosthunter/laradate) ![](https://img.shields.io/packagist/php-v/darkghosthunter/laradate.svg) ![](https://github.com/DarkGhostHunter/Laradate/workflows/PHP%20Composer/badge.svg) [![Coverage Status](https://coveralls.io/repos/github/DarkGhostHunter/Laradate/badge.svg?branch=master)](https://coveralls.io/github/DarkGhostHunter/Laradate?branch=master) [![Laravel Octane Compatible](https://img.shields.io/badge/Laravel%20Octane-Compatible-success?style=flat&logo=laravel)](https://github.com/laravel/octane) [![Laravel Jetstream Compatible](https://img.shields.io/badge/Laravel%20Jetstream-Compatible-success?style=flat&logo=laravel)](https://jetstream.laravel.com/) 

# Laradate

Parse a date from the URL, receive it as a `Carbon` instance in your controller.

## Requirements

* Laravel 7.x or later
* PHP 7.4 or later.

> For older versions support, consider helping by sponsoring or donating.

## Installation

You can install the package via composer:

```bash
composer require darkghosthunter/laradate
```

## Usage

Simply set the `datetime` to any route. In your controller, you will get a `Carbon` instance if the name of the variable is `$datetime`. 

```php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Carbon;

Route::get('matches/{datetime}', function (Carbon $datetime) {
    return $datetime;
});
```

```http request
GET http://myapp.com/matches/1992-01-01%2016%3A30%3A45
```

```http request
GET http://myapp.com/matches/today
```

Behind the scenes, Laradate will use the `DateFactory`, which is the default factory in your application, to create instances of `DateTimeInterface`. By default, your application uses Carbon.

> If the datetime cannot be parsed, the route will return HTTP 404.

### Using formats

You can also use custom formatting for your routes with `{datetime:format}`. The format follows the same [Datetime formats](https://php.net/manual/datetime.createfromformat.php). If the string doesn't follow the format, the route will return an HTTP 404.

```php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Carbon;

Route::get('birthdays/{datetime:Y_m_d}', function (Carbon $datetime) {
    return $datetime;
});
```

> Because of limitations of Laravel Router parameters for bindings, use underscore `_` as separator while using formats.

### Using dates

If you use a format, the datetime parser will default the date to the actual time of the day instead of the beginning of it.

To avoid this, you can use the `date` binding, which works the same as `datetime`, but it will forcefully move the time to the beginning of the day, even if the format includes time.

```php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Carbon;

Route::get('matches/{date}', function (Carbon $datetime) {
    return $datetime;
});

Route::get('birthdays/{date:Y_m_d}', function (Carbon $datetime) {
    return $datetime;
});
```

```http request
GET http://myapp.com/matches/now
```
```http request
GET http://myapp.com/birthdays/2020_01_01
```

### Further validation

The datetime binding doesn't support validation to dynamically check if the date should be inside/outside bounds, like "after today" or "between hours". In that case, you submit a form and [validate the request](https://laravel.com/docs/validation#available-validation-rules).

```php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('birthdays', function (Request $request) {
    $request->validate(['date' => 'required|date_format:Y-m-d|before_or_equal:today']);
});
```

## Security

If you discover any security related issues, please email darkghosthunter@gmail.com instead of using the issue tracker.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
