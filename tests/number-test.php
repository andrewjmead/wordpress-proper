<?php

use Proper\Number;

/**
 * Tests for Number
 */
class NumberTest extends WP_UnitTestCase {

    public function test_small_numbers() {
        $actual   = Number::abbreviate( 1 );
        $expected = '1';

        $this->assertEquals( $expected, $actual );
    }

    public function test_thousands() {
        $actual   = Number::abbreviate( 133300 );
        $expected = '133.3K';

        $this->assertEquals( $expected, $actual );
    }

    public function test_billions() {
        $actual   = Number::abbreviate( 999000000000 );
        $expected = '999B';

        $this->assertEquals( $expected, $actual );
    }

    public function test_trillions() {
        $actual   = Number::abbreviate( 1000000000000 );
        $expected = '1T';

        $this->assertEquals( $expected, $actual );
    }

    public function test_millions() {
        $actual   = Number::abbreviate( 1300000 );
        $expected = '1.3M';

        $this->assertEquals( $expected, $actual );
    }

    public function test_rounding_up() {
        $actual   = Number::abbreviate( 1250000 );
        $expected = '1.3M';

        $this->assertEquals( $expected, $actual );
    }

    public function test_rounding_down() {
        $actual   = Number::abbreviate( 1249999 );
        $expected = '1.2M';

        $this->assertEquals( $expected, $actual );
    }
}
