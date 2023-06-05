<?php

namespace Proper;

/**
 * Tests for Gatekeeper
 */
class GatekeeperTest extends \WP_UnitTestCase
{
    /**
     * A new gate should be open
     */
    public function test_gate_open_by_default()
    {
        $gatekeeper = new Gatekeeper('option_name', 'PT5S');

        $this->assertTrue($gatekeeper->should_run());
    }

    /**
     * Gate should accept DateInterval
     */
    public function test_accepts_date_interval()
    {
        $interval   = new \DateInterval('PT5S');
        $gatekeeper = new Gatekeeper('option_name', $interval);

        $this->assertTrue($gatekeeper->should_run());
    }

    /**
     * Gate should close after it's complete
     */
    public function test_gate_closes_after_complete()
    {
        $gatekeeper = new Gatekeeper('option_name', 'PT5S');

        $this->assertTrue($gatekeeper->should_run());
        $gatekeeper->complete();
        $this->assertFalse($gatekeeper->should_run());
    }

    /**
     * Gate should be open if invalid timestamp is stored in database
     */
    public function test_gate_open_in_invalid_timestamp_stored()
    {
        update_option('option_name', 'Michael');
        $gatekeeper = new Gatekeeper('option_name', 'PT5S');

        $this->assertTrue($gatekeeper->should_run());
    }

    /**
     * Gate should only open after the interval of time has passed
     */
    public function test_gate_closed_if_interval_not_passed()
    {
        $gatekeeper    = new Gatekeeper('option_name', 'PT5S');
        $now           = new \DateTime();
        $three_seconds = new \DateInterval('PT3S');

        $now = $now->sub($three_seconds);
        update_option('option_name', $now->format('c'));
        $this->assertFalse($gatekeeper->should_run());

        $now = $now->sub($three_seconds);
        update_option('option_name', $now->format('c'));
        $this->assertTrue($gatekeeper->should_run());
    }
}
