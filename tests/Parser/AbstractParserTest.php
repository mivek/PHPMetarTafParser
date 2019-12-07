<?php
/**
 * @author Jean-Kevin KPADEY
 */

namespace PHPMetarTafParser\Tests\Parser;

use PHPMetarTafParser\Command\Common\CommandSupplier;
use PHPUnit\Framework\TestCase;

/**
 * Class AbstractParserTest
 * @package PHPMetarTafParser\Tests\Parser
 */
class AbstractParserTest extends TestCase
{
    private $stub;

    public function setUp(): void
    {
        $this->stub = $this->getMockForAbstractClass('PHPMetarTafParser\Parser\AbstractParser', array(new CommandSupplier()));
    }

    public function testParseWeatherCondition()
    {

        $wcPart = '-DZ';

        $wc = $this->stub->parseWeatherCondition($wcPart);

        self::assertNotNull($wc);
        self::assertEquals('-', $wc->getIntensity());
        self::assertNull($wc->getDescriptor());
        self::assertEquals(1, count($wc->getPhenomenons()));
        self::assertEquals('DZ', $wc->getPhenomenons()[0]);
    }

    public function testParseWeatherConditionWithMultiplePhenomenon()
    {
        $wcPart = 'SHRAGR';
        $wc = $this->stub->parseWeatherCondition($wcPart);

        self::assertNotNull($wc);
        self::assertNull($wc->getIntensity());
        self::assertNotNull($wc->getDescriptor());
        self::assertEquals(2, count($wc->getPhenomenons()));
        self::assertContains('RA', $wc->getPhenomenons());
        self::assertContains('GR', $wc->getPhenomenons());
    }

    public function testParseWeatherConditionWithNullPhenomenon()
    {
        $wcPart = '-SH';

        $wc = $this->stub->parseWeatherCondition($wcPart);

        self::assertNotNull($wc);
        self::assertEquals('-', $wc->getIntensity());
        self::assertEquals('SH', $wc->getDescriptor());
    }

    public function testTokenize()
    {
        $code = "METAR KTTN 051853Z 04011KT 1 1/2SM VCTS SN FZFG BKN003 OVC010 M02/M02 A3006 RMK AO2 TSB40 SLP176 P0002 T10171017=";
        $expected = [ "METAR", "KTTN", "051853Z", "04011KT", "1 1/2SM", "VCTS", "SN", "FZFG", "BKN003", "OVC010", "M02/M02", "A3006", "RMK", "AO2", "TSB40", "SLP176", "P0002", "T10171017", '' ];
        $tokens = $this->stub->tokenize($code);

        self::assertNotNull($tokens);
        self::assertIsArray($tokens);
        self::assertEquals($expected, $tokens);
    }

    public function testDeliveryTime()
    {
        $weatherCode = $this->getMockForAbstractClass('PHPMetarTafParser\Model\AbstractWeatherCode');
        $code = '051853Z';

        $this->stub->parseDeliveryTime($weatherCode, $code);

        self::assertEquals(5, $weatherCode->getDay());
        self::assertEquals(18, $weatherCode->getTime()->getHours());
        self::assertEquals(53, $weatherCode->getTime()->getMinutes());
    }

    public function testCommonParseWithCavok()
    {
        $code = 'CAVOK';
        $weatherContainer = $this->getMockForAbstractClass('PHPMetarTafParser\Model\AbstractWeatherContainer');

        $result = $this->stub->commonParse($weatherContainer, $code);
        self::assertTrue($result);
        self::assertNotNull($weatherContainer->getVisibility());
        self::assertEquals(9999, $weatherContainer->getVisibility()->getMainVisibility()['visibility']);
        self::assertEquals('m', $weatherContainer->getVisibility()->getMainVisibility()['unit']);
    }

    public function testCommonParseWithCommand()
    {
        $code = 'FEW020CB';
        $weatherContainer = $this->getMockForAbstractClass('PHPMetarTafParser\Model\AbstractWeatherContainer');

        $result = $this->stub->commonParse($weatherContainer, $code);
        self::assertTrue($result);
        self::assertEquals(1, count($weatherContainer->getClouds()));
        self::assertEquals('FEW', $weatherContainer->getClouds()[0]->getQuantity());
        self::assertEquals('CB', $weatherContainer->getClouds()[0]->getType());
        self::assertEquals(2000, $weatherContainer->getClouds()[0]->getHeight());
    }

    public function testCommonParseWithWeatherCondition()
    {
        $code = 'SHRAGR';
        $weatherContainer = $this->getMockForAbstractClass('PHPMetarTafParser\Model\AbstractWeatherContainer');

        $result = $this->stub->commonParse($weatherContainer, $code);
        self::assertTrue($result);
        self::assertEquals(1, count($weatherContainer->getWeatherConditions()));
        self::assertEquals('SH', $weatherContainer->getWeatherConditions()[0]->getDescriptor());
        self::assertNull($weatherContainer->getWeatherConditions()[0]->getIntensity());
        self::assertEquals(2, count($weatherContainer->getWeatherConditions()[0]->getPhenomenons()));
        self::assertContains('RA', $weatherContainer->getWeatherConditions()[0]->getPhenomenons());
        self::assertContains('GR', $weatherContainer->getWeatherConditions()[0]->getPhenomenons());
    }

    public function testParseWeatherConditionWithTempo()
    {
        self::assertNull($this->stub->parseWeatherCondition('TEMPO'));
    }
}
