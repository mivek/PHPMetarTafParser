<?php
/**
 * @author Jean-Kevin KPADEY
 */

namespace PHPMetarTafParser\Tests\Parser;

use Hamcrest\Util;
use PHPMetarTafParser\Parser\MetarParser;
use PHPUnit\Framework\TestCase;

/**
 * Class MetarParserTest
 * @package PHPMetarTafParser\Tests\Parser
 */
class MetarParserTest extends TestCase
{

    private $sut;

    public function setUp(): void
    {
        parent::setUp();
        $this->sut = new MetarParser();
        Util::registerGlobalFunctions();
    }

    public function testParse()
    {
        $code = "LFPG 170830Z 00000KT 0350 R27L/0375N R09R/0175N R26R/0500D R08L/0400N R26L/0275D R08R/0250N R27R/0300N R09L/0200N FG SCT000 M01/M01 Q1026 NOSIG";

        $m = $this->sut->parse($code);

        self::assertNotNull($m);
        self::assertEquals('LFPG', $m->getIcao());
        self::assertEquals(17, $m->getDay());
        self::assertEquals(8, $m->getTime()->getHours());
        self::assertEquals(30, $m->getTime()->getMinutes());
        self::assertEquals(0, $m->getWind()->getSpeed());
        self::assertEquals('N', $m->getWind()->getCardinalDirection());
        self::assertEquals('KT', $m->getWind()->getUnit());
        self::assertEquals(350, $m->getVisibility()->getMainVisibility()['visibility']);
        self::assertEquals('m', $m->getVisibility()->getMainVisibility()['unit']);
        self::assertEquals(8, count($m->getRunwaysInfo()));
        self::assertEquals('27L', $m->getRunwaysInfo()[0]->getName());
        self::assertEquals(375, $m->getRunwaysInfo()[0]->getMinRange());
        self::assertEquals('N', $m->getRunwaysInfo()[0]->getTrend());
        self::assertEquals($code, $m->getMessage());
    }

    public function testParseWithTempo()
    {
        $code = "LFBG 081130Z AUTO 23012KT 9999 SCT022 BKN072 BKN090 22/16 Q1011 TEMPO 26015G25KT 3000 TSRA SCT025CB BKN050";

        $m = $this->sut->parse($code);
        self::assertTrue($m->isAuto());
        self::assertEquals(3, count($m->getClouds()));
        self::assertEquals(1, count($m->getTrends()));
        self::assertEquals("TEMPO", $m->getTrends()[0]->getType());
        self::assertNotNull($m->getTrends()[0]->getWind());
        self::assertNotNull(260, $m->getTrends()[0]->getWind()->getDirection());
        self::assertEquals(15, $m->getTrends()[0]->getWind()->getSpeed());
        self::assertTrue($m->getTrends()[0]->getWind()->hasGusts());
        self::assertEquals(25, $m->getTrends()[0]->getWind()->getGust());
        self::assertEquals(0, count($m->getTrends()[0]->getTimes()));
        self::assertNotNull($m->getTrends()[0]->getVisibility());
        self::assertEquals(3000, $m->getTrends()[0]->getVisibility()->getMainVisibility()['visibility']);
        self::assertEquals('m', $m->getTrends()[0]->getVisibility()->getMainVisibility()['unit']);
        self::assertEquals(1, count($m->getTrends()[0]->getWeatherConditions()));

        $wc = $m->getTrends()[0]->getWeatherConditions()[0];
        self::assertEquals('TS', $wc->getDescriptor());
        self::assertEquals(1, count($wc->getPhenomenons()));
        self::assertEquals('RA', $wc->getPhenomenons()[0]);

        self::assertEquals(2, count($m->getTrends()[0]->getClouds()));
        $c1 = $m->getTrends()[0]->getClouds()[0];
        self::assertEquals('SCT', $c1->getQuantity());
        self::assertEquals(2500, $c1->getHeight());
        self::assertEquals('CB', $c1->getType());

        $c2 = $m->getTrends()[0]->getClouds()[1];
        self::assertEquals('BKN', $c2->getQuantity());
        self::assertEquals(5000, $c2->getHeight());
        self::assertEmpty($c2->getType());
    }

    public function testParseWithTempoAndBecmg()
    {
        $metarString = "LFRM 081630Z AUTO 30007KT 260V360 9999 24/15 Q1008 TEMPO SHRA BECMG SKC";

        $m = $this->sut->parse($metarString);

        self::assertNotNull($m);
        self::assertEquals(2, count($m->getTrends()));
        self::assertEquals('TEMPO', $m->getTrends()[0]->getType());
        self::assertEquals(1, count($m->getTrends()[0]->getWeatherConditions()));
        $wc = $m->getTrends()[0]->getWeatherConditions()[0];
        self::assertEquals('SH', $wc->getDescriptor());
        self::assertEquals(1, count($wc->getPhenomenons()));
        self::assertEquals('BECMG',$m->getTrends()[1]->getType());
        self::assertEquals(1, count($m->getTrends()[1]->getClouds()));
    }

    public function testParseWithTempoAndAT()
    {
        $metarString = "LFRM 081630Z AUTO 30007KT 260V360 9999 24/15 Q1008 TEMPO AT0800 SHRA ";

        $m = $this->sut->parse($metarString);

        self::assertNotNull($m);
        assertThat($m->getTrends(), arrayWithSize(1));
        assertThat($m->getTrends()[0]->getType(), is('TEMPO'));
        assertThat($m->getTrends()[0]->getWeatherConditions(), arrayWithSize(1));
        $trend = $m->getTrends()[0];
        $wc = $trend->getWeatherConditions()[0];
        self::assertEquals('SH', $wc->getDescriptor());
        assertThat($wc->getPhenomenons(), arrayWithSize(1));
        assertThat($trend->getTimes(), arrayWithSize(1));
        self::assertEquals('AT', $trend->getTimes()[0]->getType());
        self::assertEquals(8, $trend->getTimes()[0]->getTime()['hour']);
        self::assertEquals(0, $trend->getTimes()[0]->getTime()['minute']);
    }

    public function testParseWithTempoAndTL()
    {
        $metarString = "LFRM 081630Z AUTO 30007KT 260V360 9999 24/15 Q1008 TEMPO TL1830 SHRA ";

        $m = $this->sut->parse($metarString);

        self::assertNotNull($m);
        assertThat($m->getTrends(), arrayWithSize(1));
        assertThat($m->getTrends()[0]->getType(), is('TEMPO'));
        assertThat($m->getTrends()[0]->getWeatherConditions(), arrayWithSize(1));
        $trend = $m->getTrends()[0];
        $wc = $trend->getWeatherConditions()[0];
        self::assertEquals('SH', $wc->getDescriptor());
        assertThat($wc->getPhenomenons(), arrayWithSize(1));
        assertThat($trend->getTimes(), arrayWithSize(1));
        self::assertEquals('TL', $trend->getTimes()[0]->getType());
        self::assertEquals(18, $trend->getTimes()[0]->getTime()['hour']);
        self::assertEquals(30, $trend->getTimes()[0]->getTime()['minute']);
    }

    public function testParseWithTempoAndFM()
    {
        $metarString = "LFRM 081630Z AUTO 30007KT 260V360 9999 24/15 Q1008 TEMPO FM1830 SHRA ";

        $m = $this->sut->parse($metarString);

        self::assertNotNull($m);
        assertThat($m->getTrends(), arrayWithSize(1));
        assertThat($m->getTrends()[0]->getType(), is('TEMPO'));
        assertThat($m->getTrends()[0]->getWeatherConditions(), arrayWithSize(1));
        $trend = $m->getTrends()[0];
        $wc = $trend->getWeatherConditions()[0];
        self::assertEquals('SH', $wc->getDescriptor());
        assertThat($wc->getPhenomenons(), arrayWithSize(1));
        assertThat($trend->getTimes(), arrayWithSize(1));
        self::assertEquals('FM', $trend->getTimes()[0]->getType());
        self::assertEquals(18, $trend->getTimes()[0]->getTime()['hour']);
        self::assertEquals(30, $trend->getTimes()[0]->getTime()['minute']);
    }

    public function testParseWithTempoAndFMAndTL()
    {
        $metarString = "LFRM 081630Z AUTO 30007KT 260V360 9999 24/15 Q1008 TEMPO FM1700 TL1830 SHRA ";

        $m = $this->sut->parse($metarString);

        self::assertNotNull($m);
        assertThat($m->getTrends(), arrayWithSize(1));
        assertThat($m->getTrends()[0]->getType(), is('TEMPO'));
        assertThat($m->getTrends()[0]->getWeatherConditions(), arrayWithSize(1));
        $trend = $m->getTrends()[0];
        $wc = $trend->getWeatherConditions()[0];
        self::assertEquals('SH', $wc->getDescriptor());
        self::assertEquals('RA', $wc->getPhenomenons()[0]);
        assertThat($wc->getPhenomenons(), arrayWithSize(1));
        assertThat($trend->getTimes(), arrayWithSize(2));
        self::assertEquals('FM', $trend->getTimes()[0]->getType());
        self::assertEquals(17, $trend->getTimes()[0]->getTime()['hour']);
        self::assertEquals(0, $trend->getTimes()[0]->getTime()['minute']);
        self::assertEquals('TL', $trend->getTimes()[1]->getType());
        self::assertEquals(18, $trend->getTimes()[1]->getTime()['hour']);
        self::assertEquals(30, $trend->getTimes()[1]->getTime()['minute']);
        self::assertNotNull($m->getVisibility());
        self::assertEquals("9999", $m->getVisibility()->getMainVisibility()['visibility']);
        self::assertEquals("m", $m->getVisibility()->getMainVisibility()['unit']);
    }

    public function testParseWithMinVisibility()
    {
        $code = "LFPG 161430Z 24015G25KT 5000 1100w";

        $m = $this->sut->parse($code);

        self::assertNotNull($m);
        self::assertEquals(16, $m->getDay());
        self::assertEquals(14, $m->getTime()->getHours());
        self::assertEquals(30, $m->getTime()->getMinutes());
        self::assertNotNull($m->getWind());
        $w = $m->getWind();
        self::assertEquals(240, $w->getDirection());
        self::assertEquals(15, $w->getSpeed());
        self::assertEquals(25, $w->getGust());
        self::assertNotNull($m->getVisibility());
        $v = $m->getVisibility();
        self::assertEquals("5000", $v->getMainVisibility()['visibility']);
        self::assertEquals("m", $v->getMainVisibility()['unit']);
        self::assertEquals(1100, $v->getMinVisibility()['visibility']);
        self::assertEquals("w", $v->getMinVisibility()['direction']);
    }

    public function testParseWithMaximalWind()
    {
        // Given a code with wind variation->
        $code = "LFPG 161430Z 24015G25KT 180V300";
        //WHEN parsing the code->
        $m = $this->sut->parse($code);
        // THEN the wind contains information on variation
        self::assertNotNull($m);
        self::assertEquals(240, $m->getWind()->getDirection());
        self::assertEquals(15, $m->getWind()->getSpeed());
        self::assertEquals(25, $m->getWind()->getGust());
        self::assertEquals("KT", $m->getWind()->getUnit());
        self::assertTrue($m->getWind()->hasVariableWind());
        self::assertEquals(180, $m->getWind()->getVariableWind()['min']);
        self::assertEquals(300, $m->getWind()->getVariableWind()['max']);
    }

    public function testParseWithVerticalVisibility()
    {
        $code = "LFLL 160730Z 28002KT 0350 FG VV002";

        $m = $this->sut->parse($code);

        self::assertNotNull($m);
        self::assertEquals(16, $m->getDay());
        self::assertEquals(7, $m->getTime()->getHours());
        self::assertEquals(30, $m->getTime()->getMinutes());
        self::assertNotNull($m->getWind());
        $w = $m->getWind();
        self::assertEquals(280, $w->getDirection());
        self::assertEquals(2, $w->getSpeed());

        self::assertNotNull($m->getVisibility());
        self::assertEquals(350, $m->getVisibility()->getMainVisibility()['visibility']);
        self::assertEquals("m", $m->getVisibility()->getMainVisibility()['unit']);
        assertThat($m->getWeatherConditions(), arrayWithSize(1));
        self::assertEquals('FG', $m->getWeatherConditions()[0]->getPhenomenons()[0]);
        self::assertNotNull($m->getVerticalVisibility());
        self::assertEquals(200, $m->getVerticalVisibility());
    }

    public function testParseVisibilityWithNDV()
    {
        $code = "LSZL 300320Z AUTO 00000KT 9999NDV BKN060 OVC074 00/M04 Q1001\nRMK=";
        $m = $this->sut->parse($code);
        self::assertNotNull($m);
        self::assertEquals("9999", $m->getVisibility()->getMainVisibility()['visibility']);
        self::assertEquals("m", $m->getVisibility()->getMainVisibility()['unit']);
    }

    public function testParseWithCavok()
    {
        // GIVEN a metar with token CAVOK
        $code = "LFPG 212030Z 03003KT CAVOK 09/06 Q1031 NOSIG";
        // WHEN parsing the metar->
        $m = $this->sut->parse($code);
        // THEN the attribute cavok is true and the main visibility is > 10km->
        self::assertNotNull($m);
        self::assertTrue($m->isCavok());
        self::assertEquals("9999", $m->getVisibility()->getMainVisibility()['visibility']);
        self::assertEquals("m", $m->getVisibility()->getMainVisibility()['unit']);
        self::assertEquals(9, $m->getTemperature());
        self::assertEquals(6, $m->getDewPoint());
        self::assertEquals(1031, $m->getAltimeter()['value']);
        self::assertTrue($m->isNosig());
    }

    public function testParseWithAltimeterInMercury()
    {
        // GIVEN a metar with altimeter in inches of mercury
        $code = "KTTN 051853Z 04011KT 9999 VCTS SN FZFG BKN003 OVC010 M02/M02 A3006";
        // WHEN parsing the metar
        $m = $this->sut->parse($code);
        // THEN the altimeter is converted in HPa
        self::assertNotNull($m);
        self::assertEquals(30.06, $m->getAltimeter()['value']);
        self::assertEquals('inHg', $m->getAltimeter()['unit']);
        assertThat($m->getWeatherConditions(), is(notNullValue()));
        assertThat($m->getWeatherConditions(), arrayWithSize(3));
    }
}
