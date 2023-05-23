<?php

namespace Proper;

use DateInterval;
use DateTime;
use Exception;

/**
 * Gatekeeper
 *
 * Gatekeeper provides a mechanism for running some code at most once per interval. This is great
 * for performing checks that don't need to run on every single request. The gatekeeper below
 * limits execution to at most once per hour.
 *
 * $gatekeeper = new Gatekeeper('option_name', 'PT1H');
 *
 * if ( $gatekeeper->should_run() ) {
 *   // Do some task
 *
 *   $gatekeeper->complete();
 * }
 *
 */
class Gatekeeper
{
    private $option_name;
    private $interval;

    /**
     * @param string $option_name
     * @param string|DateInterval $interval A DateInterval or a period pattern for a date interval
     *
     * @throws Exception An exception is thrown if $interval is a string but isn't a valid period
     */
    public function __construct(string $option_name, $interval)
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
    public function should_run(): bool
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

    /**
     * Mark task as complete
     *
     * @return void
     */
    public function complete(): void
    {
        $now = new DateTime();
        update_option($this->option_name, $now->format('c'));
    }

    private function get_last_execution(): ?DateTime
    {
        $option_value = get_option($this->option_name, false);

        if (! $option_value) {
            return null;
        }

        try {
            return new DateTime($option_value);
        } catch (\Throwable $e) {
            return null;
        }
    }
}