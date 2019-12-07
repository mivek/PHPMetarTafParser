<?php
/**
 * @author Jean-Kevin KPADEY
 */

namespace PHPMetarTafParser\Tests\Utils;

use PHPMetarTafParser\Utils\TemperatureConverter;
use PHPUnit\Framework\TestCase;

/**
 * Class TemperatureConverterTest
 * @package PHPMetarTafParser\Tests\Utils
 */
class TemperatureConverterTest extends TestCase
{

    public function testConvertTemperatureWithMinus()
    {
        self::assertEquals(-15, TemperatureConverter::convertTemperature('M15'));
    }

    public function testConvertTemperature()
    {
        self::assertEquals(12, TemperatureConverter::convertTemperature('12'));
    }
}
