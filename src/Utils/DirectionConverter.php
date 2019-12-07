<?php


namespace PHPMetarTafParser\Utils;

/**
 * Class DirectionConverter
 * @package PHPMetarTafParser\Utils
 * @author Jean-Kevin KPADEY
 */
class DirectionConverter
{
    const NORTH_NORTH_EAST = 22.5;
    const EAST_NORTH_EAST = 67.5;
    const EAST_SOUTH_EAST = 112.5;
    const SOUTH_SOUTH_EAST = 157.5;
    const SOUTH_SOUTH_WEST = 202.5;
    const WEST_SOUTH_WEST = 247.5;
    const WEST_NORTH_WEST = 292.5;
    const NORTH_NORTH_WEST = 337.5;


    public static function degreesToDirection(string $degrees) : string
    {
        if($degrees == 'VRB') {
            return $degrees;
        }
        $fDegrees = floatval($degrees);
        if (self::isBetween(self::NORTH_NORTH_EAST, self::SOUTH_SOUTH_WEST, $fDegrees)) {
            if (self::isBetween(self::NORTH_NORTH_EAST, self::EAST_NORTH_EAST, $fDegrees)) {
                $result = 'NE';
            } elseif (self::isBetween(self::EAST_NORTH_EAST, self::EAST_SOUTH_EAST, $fDegrees)) {
                $result = 'E';
            } elseif (self::isBetween(self::EAST_SOUTH_EAST, self::SOUTH_SOUTH_EAST, $fDegrees)) {
                $result = 'SE';
            } else {
                $result = 'S';
            }
        } else {
            if(self::isBetween(self::SOUTH_SOUTH_WEST, self::WEST_SOUTH_WEST, $fDegrees)) {
                $result = 'SW';
            } elseif (self::isBetween(self::WEST_SOUTH_WEST, self::WEST_NORTH_WEST, $fDegrees)) {
                $result = 'W';
            } elseif (self::isBetween(self::WEST_NORTH_WEST, self::NORTH_NORTH_WEST, $fDegrees)) {
                $result = 'NW';
            } else {
                $result = 'N';
            }
        }
        return $result;
    }

    /**
     * Test if value is between min and max
     * @param float $min
     * @param float $max
     * @param float $value
     * @return bool
     */
    private static function isBetween(float $min, float $max, float $value) : bool
    {
        return $min <= $value && $max > $value;
    }

    /**
     * DirectionConverter constructor.
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }
}