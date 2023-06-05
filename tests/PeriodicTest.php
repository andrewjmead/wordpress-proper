<?php

namespace Proper;

/**
 * Tests for Periodically
 */
class PeriodicTest extends \WP_UnitTestCase
{
    public function test_should_run_by_default()
    {
        $should_verify_database = Periodic::check('database_last_verified_at', 'PT5S');

        $this->assertTrue($should_verify_database);
    }

    public function test_accepts_date_interval()
    {
        $interval               = new \DateInterval('PT5S');
        $should_verify_database = Periodic::check('database_last_verified_at', $interval);

        $this->assertTrue($should_verify_database);
    }

    public function test_should_not_run_after_complete()
    {
        $should_verify_database = Periodic::check('database_last_verified_at', 'PT5S');
        $this->assertTrue($should_verify_database);
        $should_verify_database = Periodic::check('database_last_verified_at', 'PT5S');
        $this->assertFalse($should_verify_database);
    }

    public function test_should_run_if_invalid_timestamp_stored()
    {
        $should_verify_database = Periodic::check('database_last_verified_at', 'PT5S');
        $this->assertTrue($should_verify_database);
        update_option('database_last_verified_at', 'Michael');
        $should_verify_database = Periodic::check('database_last_verified_at', 'PT5S');
        $this->assertTrue($should_verify_database);
    }

    public function test_should_not_run_unless_interval_passed()
    {
        $now           = new \DateTime();
        $three_seconds = new \DateInterval('PT3S');

        $now = $now->sub($three_seconds);
        update_option('database_last_verified_at', $now->format('c'));
        $should_verify_database = Periodic::check('database_last_verified_at', 'PT5S');
        $this->assertFalse($should_verify_database);

        $now = $now->sub($three_seconds);
        update_option('database_last_verified_at', $now->format('c'));
        $should_verify_database = Periodic::check('database_last_verified_at', 'PT5S');
        $this->assertTrue($should_verify_database);
    }
}
