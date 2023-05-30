<?php

namespace Proper;

/**
 * Tests for Number
 */
class NumberTest extends \WP_UnitTestCase {
    protected function setUp(): void {
        switch_to_locale( 'en_US' );
    }

    public static function tearDownAfterClass(): void {
        switch_to_locale( 'en_US' );
    }

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

    public function test_abbreviations_are_internationalized() {
        switch_to_locale( 'de_DE' );
        $actual   = Number::abbreviate( 1200 );
        $expected = '1,2K';

        $this->assertEquals( $expected, $actual );
    }

    public function test_do_nothing_past_trillions() {
        // setlocale works with number_format which is from php
        // switch_to_locale is wp and works with number_format_i18n which is also wordprsss

        $actual   = Number::abbreviate( 1000000000000000 );
        $expected = '1,000,000,000,000,000';

        $this->assertEquals( $expected, $actual );
    }

    public function test_values_past_trillions_are_internationalized() {
        switch_to_locale( 'de_DE' );
        $actual   = Number::abbreviate( 1000000000000000 );
        $expected = '1.000.000.000.000.000';

        $this->assertEquals( $expected, $actual );
    }
}
