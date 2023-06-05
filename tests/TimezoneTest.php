<?php

namespace Proper;

use DateTimeZone;

class TimezoneTest extends \WP_UnitTestCase {
    protected function setUp(): void {
        delete_option('timezone_string', 'America/New_York');
    }

    public function test_utc_timezone() {
        $this->assertEquals( new DateTimeZone( 'UTC' ), Timezone::utc_timezone() );
    }

    public function test_utc_offset() {
        $this->assertEquals( '+00:00', Timezone::utc_offset() );
    }

    public function test_site_timezone() {
        update_option('timezone_string', 'America/New_York');
        $this->assertEquals(new DateTimeZone('America/New_York'), Timezone::site_timezone());
    }
    public function test_site_offset() {
        update_option('timezone_string', 'America/New_York');
        $this->assertEquals('-04:00', Timezone::site_offset());
    }

    public function test_site_timezone_with_offset_timezone() {
        update_option('gmt_offset', '8.75');
        $this->assertEquals(new DateTimeZone('+08:45'), Timezone::site_timezone());
    }

    public function test_site_offset_with_offset_timezone() {
        update_option('gmt_offset', '8.75');
        $this->assertEquals('+08:45', Timezone::site_offset());
    }
}