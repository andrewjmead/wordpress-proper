[![Build Status](https://app.travis-ci.com/andrewjmead/wordpress-proper.svg?branch=main)](https://app.travis-ci.com/andrewjmead/wordpress-proper)

# WordPress Proper

A set of classes you may find useful for WordPress development.

Zero dependencies.

* [Periodic](#periodic)
  * [::check($option_name, $date_interval): bool](#periodic)
* [Number](#number)
  * [::abbreviate($number): string](#abbreviate)
* [Timezone](#timezone)
  * [::utc_offset(): string](#utc_offset)
  * [::utc_timezone(): DateTimeZone](#utc_offset)
  * [::site_offset(): string](#site_offset)
  * [::site_timezone(): DateTimeZone](#site_timezone)

## Periodic

Gatekeeper lets you run some code at a specific interval. It's perfect for situations where you have something you want to do, but you only want to do it every so often, such as once every 10 minutes or once every 12 hours.

Gatekeeper is backed by WordPress options. The option value stores the time a given task was last completed. No option value is saved until a task is first marked as complete. You can call `should_run` to see if it's time to run a task, and you can call `complete` to mark a task as complete. Completed tasks won't rerun until the defined interval has passed.

Feel free to change the interval as needed. You might realize you need a more or less frequent interval. Simply change the interval argument and the gatekeeper will compare the new interval with the time it was last completed to see if the task should run.

The constructor accepts 2 arguments:

1. *Task name (string)* - Name for the WordPress option that will store when the task was last completed
2. *Interval (string or `DateInterval`)* - A `DateInterval` or a valid [string duration](https://www.php.net/manual/en/dateinterval.construct.php#refsect1-dateinterval.construct-parameters) that `DateInterval` would accept


```php
<?php

use Proper\Gatekeeper;

// Create a new gatekeeper that will run every 30 minutes
$gatekeeper = new Gatekeeper('option_name', 'PT30M');

// Check if it's time for the task to run
if ($gatekeeper->should_run()) {
    // Run some code you want to run on an interval
    
    // Mark task as complete, so it won't run until the specified interval of time has passed
    $gatekeeper->complete();
}
```

You can also define the interval using `DateInterval`:

```php
// Define the interval with a string duration
$gatekeeper = new Gatekeeper('option_name', 'PT30M');

// Define the interval using a DateInterval
$period = new DateInterval('PT30M')
$gatekeeper = new Gatekeeper('option_name', $period);
```

**Why not use WordPress cron?**

WordPress ships with built-in support for cron tasks, but it's a cumbersome API that requires the manual scheduling and unscheduling of tasks. Gatekeeper is designed to be a lightweight version that you can easily add, remove, and modify as needed. There's no need to add separate code for scheduling an unscheduling tasks. 

## Number

### Abbreviate

The `abbreviate` methods abbreviates large numbers such as `742898` into shorter strings such as `743K`.

It provides abbreviations for:

1. Thousands - `Number::abbreviate(133300)` returns the string `133.3K`
2. Millions - `Number::abbreviate( 1300000 )` returns the string `1.3M`
3. Billions - `Number::abbreviate( 999000000000 )` returns the string `999B`
4. Trillions - `Number::abbreviate( 1000000000000 )` returns the string `1T`

Numbers below one thousand are not abbreviated. That means `Number::abbreviate(978)` would return the string `978`.

Numbers at or above one quadrillion are not abbreviated. That means `Number::abbreviate(1000000000000000)` would return the string `1,000,000,000,000,000`.

Behind the scenes, `abbreviate` uses `number_format_i18n` from WordPress to internationalize abbreviations. This ensures that `Number::abbreviate(1500)` returns the string `1.5K` for `en_US` and `1,5K` for `de_DE`.

```php
<?php

use Proper\Number;

Number::abbreviate(1260000); // 1.3M

Number::abbreviate(133800); // 133.8K
```
