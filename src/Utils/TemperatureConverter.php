<?php
/**
 * @author Jean-Kevin KPADEY
 */

namespace PHPMetarTafParser\Utils;


class TemperatureConverter
{
    /**
     * TemperatureConverter constructor.
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    /**
     * Converts the string temperature to integer
     * @param string $code
     * @return int
     */
    public static function convertTemperature(string $code) : int
    {
        $temp = null;
        if($code[0] == 'M'){
            $temp = - intval(substr($code,1));
        } else {
            $temp = intval($code);
        }
        return $temp;
    }
}