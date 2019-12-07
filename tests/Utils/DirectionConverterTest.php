<?php

namespace PHPMetarTafParser\Tests\Utils;

use PHPMetarTafParser\Utils\DirectionConverter;
use PHPUnit\Framework\TestCase;

/**
 * Class DirectionConverterTest
 * @package PHPMetarTafParser\Tests\Utils
 */
class DirectionConverterTest extends TestCase
{

    public function testDegreesToDirectionWithNorth()
    {
        self::assertEquals('N', DirectionConverter::degreesToDirection('5'));
        self::assertEquals('N', DirectionConverter::degreesToDirection('345'));
    }

    public function testDegreesToDirectionWithNorthEast()
    {
        self::assertEquals('NE', DirectionConverter::degreesToDirection('50.5'));
        self::assertEquals('NE', DirectionConverter::degreesToDirection('30.5'));
    }

    public function testDegreesToDirectionWithEast()
    {
        self::assertEquals('E', DirectionConverter::degreesToDirection('70.5'));
        self::assertEquals('E', DirectionConverter::degreesToDirection('110'));
    }

    public function testDegreesToDirectionWithSouthEast()
    {
        self::assertEquals('SE', DirectionConverter::degreesToDirection('115'));
        self::assertEquals('SE', DirectionConverter::degreesToDirection('145'));
    }

    public function testDegreesToDirectionWithSouth()
    {
        self::assertEquals('S', DirectionConverter::degreesToDirection('170'));
        self::assertEquals('S', DirectionConverter::degreesToDirection('200'));
    }

    public function testDegreesToDirectionWithSouthWest()
    {
        self::assertEquals('SW', DirectionConverter::degreesToDirection('215'));
        self::assertEquals('SW', DirectionConverter::degreesToDirection('240'));
    }

    public function testDegreesToDirectionWithWest()
    {
        self::assertEquals('W', DirectionConverter::degreesToDirection('250'));
        self::assertEquals('W', DirectionConverter::degreesToDirection('280'));
    }

    public function testDegreesToDirectionWitNorthhWest()
    {
        self::assertEquals('NW', DirectionConverter::degreesToDirection('300'));
        self::assertEquals('NW', DirectionConverter::degreesToDirection('320'));
    }

}
