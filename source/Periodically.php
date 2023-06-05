<?php

namespace Proper;

use DateInterval;
use DateTime;
use Exception;
use Throwable;

class Periodically
{
    private $option_name;
    private $interval;

    /**
     * @param string $option_name
     * @param string|DateInterval $interval A DateInterval or a period pattern for a date interval
     *
     * @throws Exception An exception is thrown if $interval is a string but isn't a valid period
     */
    private function __construct(string $option_name, $interval)
    {
        $this->option_name = $option_name;
        $this->interval    = $interval;

        if (is_string($interval)) {
            $this->interval = new DateInterval($interval);
        }
    }

    /**
     * @return bool True if it's time for the task to run.
     */
    private function should_run(): bool
    {
        $last_execution = $this->get_last_execution();

        if (is_null($last_execution)) {
            return true;
        }

        $is_past_interval_time = $last_execution->add($this->interval) < new DateTime();

        if ($is_past_interval_time) {
            return true;
        }

        return false;
    }

    private function get_last_execution(): ?DateTime
    {
        $option_value = get_option($this->option_name, false);

        if (! $option_value) {
            return null;
        }

        try {
            return new DateTime($option_value);
        } catch (Throwable $e) {
            return null;
        }
    }

    /**
     * Mark task as complete
     *
     * @return void
     */
    private function complete(): void
    {
        $now = new DateTime();
        update_option($this->option_name, $now->format('c'));
    }

    /**
     * @throws Exception
     */
    public static function check(string $option_name, $interval): bool
    {
        $periodically = new Periodically($option_name, $interval);
        $should_run   = $periodically->should_run();
        $periodically->complete();

        return $should_run;
    }
}
