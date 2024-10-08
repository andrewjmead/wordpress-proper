<?php

namespace Proper;

use DateTimeZone;

class TimezoneTest extends \WP_UnitTestCase
{
    protected function setUp(): void
    {
        delete_option('timezone_string', 'America/New_York');
    }

    public function test_utc_timezone()
    {
        $this->assertEquals(new DateTimeZone('UTC'), Timezone::utc_timezone());
    }

    public function test_utc_offset_in_hours()
    {
        $this->assertEquals(0, Timezone::utc_offset_in_hours());
    }

    public function test_utc_offset_in_seconds()
    {
        $this->assertEquals(0, Timezone::utc_offset_in_seconds());
    }

    public function test_utc_offset()
    {
        $this->assertEquals('+00:00', Timezone::utc_offset());
    }

    public function test_site_timezone()
    {
        update_option('timezone_string', 'America/New_York');
        $this->assertEquals(new DateTimeZone('America/New_York'), Timezone::site_timezone());
    }

    public function test_site_offset()
    {
        update_option('timezone_string', 'America/New_York');
        $this->assertEquals('-04:00', Timezone::site_offset());
    }

    public function test_site_offset_in_hours()
    {
        update_option('timezone_string', 'America/New_York');
        $this->assertEquals(-4, Timezone::site_offset_in_hours());
    }

    public function test_site_offset_in_seconds()
    {
        update_option('timezone_string', 'America/New_York');
        $this->assertEquals(-14400, Timezone::site_offset_in_seconds());
    }

    public function test_site_timezone_with_offset_timezone()
    {
        update_option('gmt_offset', '8.75');
        $this->assertEquals(new DateTimeZone('+08:45'), Timezone::site_timezone());
    }

    public function test_site_offset_in_hours_with_offset_timezone()
    {
        update_option('gmt_offset', '8.75');
        $this->assertEquals(8.75, Timezone::site_offset_in_hours());
    }

    public function test_site_offset_in_seconds_with_offset_timezone()
    {
        update_option('gmt_offset', '8.75');
        $this->assertEquals(31500, Timezone::site_offset_in_seconds());
    }

    public function test_site_offset_with_offset_timezone()
    {
        update_option('gmt_offset', '8.75');
        $this->assertEquals('+08:45', Timezone::site_offset());
    }
}
