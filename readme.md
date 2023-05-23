# WordPress Proper

A set of classes you may find useful for WordPress development.

## Gatekeeper

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
