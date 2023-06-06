[![Build Status](https://app.travis-ci.com/andrewjmead/wordpress-proper.svg?branch=main)](https://app.travis-ci.com/andrewjmead/wordpress-proper)

# WordPress Proper

A dependency-free set of classes you may find useful for WordPress development.

## Docs

* [Getting Started](#getting-started)
* [Periodic](#periodic)
  * [::check($option_name, $date_interval): bool](#periodic)
* [Number](#number)
  * [::abbreviate($number): string](#abbreviate)
* [Timezone](#timezone)
  * [::site_timezone(): DateTimeZone](#site_timezone)
  * [::site_offset(): string](#site_offset)
  * [::site_decimal_offset(): float](#site_decimal_offset)
  * [::utc_timezone(): DateTimeZone](#utc_timezone)
  * [::utc_offset(): string](#utc_offset)
  * [::utc_decimal_offset(): float](#utc_decimal_offset)

## Getting Started

WordPress Proper can be installed via composer:

```
composer require andrewmead/wordpress-proper
```

From there, you can pull in whatever module you happen to need:

```php
use Proper\Number;

Number::abbreviate(654201); // 654.2K
```

## Periodic

**Periodic::check(string $option_name, string|DateInterval $interval): bool**

`Periodic` gives you a way to periodically do something. It's powered by WordPress options and PHP's `DateInterval` class.

It's perfect when you need to do something, but only every once in a while. In the example below, `Periodic` is used to run some code once every 30 minutes.

```php
<?php

use Proper\Periodic;

if (Periodic::check('verify_geo_database_file', 'PT30M')) {
    // Run some code every 30 minutes
}
```

The first argument is name of the option you want to use to back a periodic task. This option will store the last time the periodic task was run.

The second argument is where you define the period you want to wait. This can be represented as a `DateInterval` or a valid [string duration](https://www.php.net/manual/en/dateinterval.construct.php#refsect1-dateinterval.construct-parameters) that the `DateInterval` constructor would accept.

```php
// Define the period using a string
$should_verify = Periodic::check('verify_geo_database_file', 'PT3S');

// Define the period using a DateInterval
$interval = new DateInterval('PT3S');
$should_verify = Periodic::check('verify_geo_database_file', $interval);
```

Calls to `check` will always return a boolean value. The value will be `true` if it's time to run the task. The value will be `false` if the period of time hasn't passed since the task was last completed. 

## Number

### Abbreviate

**Number::abbreviate(int|float $number): string**

The `abbreviate` methods abbreviates large numbers such as `742898` into shorter strings such as `743K`.

```php
<?php

use Proper\Number;

Number::abbreviate(1260000); // 1.3M

Number::abbreviate(133800); // 133.8K
```

It provides abbreviations for:

1. Thousands - `Number::abbreviate(133300)` returns the string `133.3K`
2. Millions - `Number::abbreviate( 1300000 )` returns the string `1.3M`
3. Billions - `Number::abbreviate( 999000000000 )` returns the string `999B`
4. Trillions - `Number::abbreviate( 1000000000000 )` returns the string `1T`

Numbers below one thousand are not abbreviated. That means `Number::abbreviate(978)` would return the string `978`.

Numbers at or above one quadrillion are not abbreviated. That means `Number::abbreviate(1000000000000000)` would return the string `1,000,000,000,000,000`.

Behind the scenes, `abbreviate` uses `number_format_i18n` from WordPress to internationalize abbreviations. This ensures that `Number::abbreviate(1500)` returns the string `1.5K` for `en_US` and `1,5K` for `de_DE`.

## Timezone

A small set of function that make it a bit easier to work with a WordPress site's timezone.

### site_timezone

**::site_timezone(): DateTimeZone**

Get the WordPress site's timezone represent as a PHP `DateTimeZone`.

```php
<?php

use Proper\Timezone;

Timezone::site_timezone();
```

### site_offset

**::site_offset(): string**

Get the offset for the WordPress site's timezone. This is represented as a string. Examples include `"-04:00"`, `"+08:45"`, and `"-11:30"`.

```php
<?php

use Proper\Timezone;

Timezone::site_offset();
```

### site_decimal_offset

**::site_decimal_offset(): float**

Get the decimal offset for the WordPress site's timezone. This is represented as a float. Examples include `-4`, `8.75`, and `-11.5`.

```php
<?php

use Proper\Timezone;

Timezone::site_decimal_offset();
```

### utc_timezone

**::utc_timezone(): DateTimeZone**

Get a `DateTimeZone` instance that represents UTC. This will always return the same value, but serves as a handy counterpart to `site_timezone` 

```php
<?php

use Proper\Timezone;

Timezone::utc_timezone();
```

### utc_offset

**::utc_offset(): string**

Get the offset for UTC. This will always return `"+00:00"`. This will always return the same value, but serves as a handy counterpart to `site_offset`.

```php
<?php

use Proper\Timezone;

Timezone::utc_offset(); // Will always return "+00:00"
```

### utc_decimal_offset

**::utc_decimal_offset(): float**

Get the decimal offset for UTC. This will always return `0`. This will always return the same value, but serves as a handy counterpart to `site_decimal_offset`.

```php
<?php

use Proper\Timezone;

Timezone::utc_decimal_offset(); // Will always return 0
```