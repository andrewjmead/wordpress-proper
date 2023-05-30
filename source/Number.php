<?php

namespace Proper;

class Number {
    /**
     * @param int|float $number
     *
     * @return string
     */
    static public function abbreviate( $number ): string {
        $number        = (int) $number;
        $abbreviations = [
            ''  => 1,
            'K' => 1000,
            'M' => 1000000,
            'B' => 1000000000,
            'T' => 1000000000000
        ];

        foreach ( $abbreviations as $abbreviation => $abbreviation_value ) {
            $upper_range = $abbreviation_value * 1000;

            if ( $number < $upper_range ) {
                $decimals = $number < 1000 ? 0 : 1;
                $result   = $number / $abbreviation_value;
                $result   = number_format_i18n( $result, $decimals ) . $abbreviation;

                // Strip out decimals that are 0 so 1.0T becomes 1T
                $result = strpos( $result, '.0' ) === false ? $result : str_replace( '.0', '', $result );

                return $result;
            }
        }

        // Do nothing for numbers past the trillions
        return number_format_i18n($number, 0);
    }
}