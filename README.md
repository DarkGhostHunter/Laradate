![Aron Visuals - Unsplash (UL) #BXOXnQ26B7o](https://images.unsplash.com/photo-1501139083538-0139583c060f?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&h=400&q=80)

[![Latest Stable Version](https://poser.pugx.org/darkghosthunter/laradate/v/stable)](https://packagist.org/packages/darkghosthunter/laradate) [![License](https://poser.pugx.org/darkghosthunter/laradate/license)](https://packagist.org/packages/darkghosthunter/laradate) ![](https://img.shields.io/packagist/php-v/darkghosthunter/laradate.svg) ![](https://github.com/DarkGhostHunter/Laradate/workflows/PHP%20Composer/badge.svg) [![Coverage Status](https://coveralls.io/repos/github/DarkGhostHunter/Laradate/badge.svg?branch=master)](https://coveralls.io/github/DarkGhostHunter/Laradate?branch=master) [![Laravel Octane Compatible](https://img.shields.io/badge/Laravel%20Octane-Compatible-success?style=flat&logo=laravel)](https://github.com/laravel/octane) [![Laravel Jetstream Compatible](https://img.shields.io/badge/Laravel%20Jetstream-Compatible-success?style=flat&logo=laravel)](https://jetstream.laravel.com/) 

# Laradate

Parse a date from the URL, receive it as a `Carbon` instance in your controller.

## Requirements

* Laravel 8.x or later
* PHP 8.0 or later.

> For older versions support, consider helping by sponsoring or donating.

## Installation

You can install the package via composer:

```bash
composer require darkghosthunter/laradate
```

## Usage

Simply set the `date` parameter to any route. In your controller, you will get a `Carbon` instance if the name of the variable is `$date`.

```php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Carbon;

Route::get('matches/{date}', function (Carbon $date) {
    return $date;
});
```

> A date must be formatted as `YYYY-MM-DD` to reach the route, otherwise it won't be found.

Behind the scenes, Laradate will use the `DateFactory`, which is the default factory in your application, to create instances of `DateTimeInterface`. By default, your application uses the Carbon library.

> If the datetime cannot be parsed, the route will return HTTP 404.

### Using formats

You can also use custom formatting for your routes with `{date:format}`. The format follows the same [Datetime formats](https://php.net/manual/datetime.createfromformat.php). If the string doesn't follow the format, the route will return an HTTP 404.

```php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Carbon;

// birthdays/2015_07_04
Route::get('birthdays/{date:Y_m_d}', function (Carbon $date) {
    return $date;
});
```

> Because of limitations of Laravel Router parameters for bindings, use underscore `_` as separator while using formats.

### Date between middleware

To avoid having to fallback to the Laravel Validator inside the controller, you can use the `date` middleware which accepts a minimum, maximum, or both, dates to compare (inclusive). If the date is not inside the dates, an HTTP 404 code will be returned.

Since the dates are passed to `DateTime`, you can use words like `today 00:00` or `3 months 23:59:59` for relative dates.

```php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('birthdays/{date}', function (Request $request, Carbon $date) {
    // ...
})->middleware('date:today 00:00,3 months 23:59:59');
```

## Security

If you discover any security related issues, please email darkghosthunter@gmail.com instead of using the issue tracker.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
