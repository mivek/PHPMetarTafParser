<?php
/**
 * @author Jean-Kevin KPADEY
 */

namespace PHPMetarTafParser\Tests\Parser;

use Hamcrest\Util;
use PHPMetarTafParser\Exception\ErrorCodes;
use PHPMetarTafParser\Exception\ParseException;
use PHPMetarTafParser\Parser\TAFParser;
use PHPUnit\Framework\TestCase;

/**
 * Class TAFParserTest
 * @package PHPMetarTafParser\Tests\Parser
 */
class TAFParserTest extends TestCase
{
    private $sut;

    public function setUp(): void
    {
        parent::setUp();
        $this->sut = new TAFParser();
        Util::registerGlobalFunctions();
    }


    public function testParseValidWithInvalidLineBreaks()
    {
        $taf = "TAF LFPG 150500Z 1506/1612 17005KT 6000 SCT012 \n"
                . "TEMPO 1506/1509 3000 BR BKN006 PROB40 \n"
                . "TEMPO 1506/1508 0400 BCFG BKN002 PROB40 \n"
                . "TEMPO 1512/1516 4000 -SHRA FEW030TCU BKN040 \n"
                . "BECMG 1520/1522 CAVOK \n"
                . "TEMPO 1603/1608 3000 BR BKN006 PROB40 \n"
                . "TEMPO 1604/1607 0400 BCFG BKN002 TX17/1512Z TN07/1605Z";

        try {
            $res = $this->sut->parse($taf);
        } catch (ParseException $e) {
            self::fail('Test should not fail');
        }

        assertThat($res, is(not(nullValue())));
        self::assertEquals("LFPG", $res->getIcao());
        // Check on time delivery.
        self::assertEquals(15, $res->getDay());
        self::assertEquals(5, $res->getTime()->getHours());
        self::assertEquals(0, $res->getTime()->getMinutes());
        // Checks on validity.
        self::assertEquals(15, $res->getValidity()->getStartDay());
        self::assertEquals(6, $res->getValidity()->getStartHour());
        self::assertEquals(16, $res->getValidity()->getEndDay());
        self::assertEquals(12, $res->getValidity()->getEndHour());
        // Checks on wind.
        assertThat($res->getWind()->getDirection(), is("170"));
        assertThat($res->getWind()->getSpeed(), is(5));
        assertThat($res->getWind()->getGust(), is(0));
        assertThat($res->getWind()->getUnit(), is("KT"));
        // Checks on visibility.
        self::assertEquals(6000, $res->getVisibility()->getMainVisibility()['visibility']);
        self::assertEquals('m', $res->getVisibility()->getMainVisibility()['unit']);
        //Check on clouds.
        assertThat($res->getClouds(), arrayWithSize(1));
        assertThat($res->getClouds()[0]->getQuantity(), is('SCT'));
        self::assertEquals(1200, $res->getClouds()[0]->getHeight());
        self::assertEmpty($res->getClouds()[0]->getType());

        assertThat($res->getMaxTemperature(), notNullValue());
        assertThat($res->getMinTemperature(), notNullValue());
        self::assertEquals(17, $res->getMaxTemperature()->getTemperature());
        self::assertEquals(15, $res->getMaxTemperature()->getDay());
        self::assertEquals(12, $res->getMaxTemperature()->getHour());
        self::assertEquals(7, $res->getMinTemperature()->getTemperature());
        self::assertEquals(16, $res->getMinTemperature()->getDay());
        self::assertEquals(5, $res->getMinTemperature()->getHour());

        // Check that no weatherCondition
        assertThat($res->getWeatherConditions(), arrayWithSize(0));
        // Checks on trends without probability.
        assertThat($res->getTrends(), arrayWithSize(3));
        assertThat($res->getProbTrends(), arrayWithSize(3));
        // First tempo without probability
        self::assertEquals('TEMPO', $res->getTrends()[0]->getType());
        assertThat($res->getTrends()[0]->getValidity()->getStartDay(), is(15));
        assertThat($res->getTrends()[0]->getValidity()->getStartHour(), is(6));
        assertThat($res->getTrends()[0]->getValidity()->getEndDay(), is(15));
        assertThat($res->getTrends()[0]->getValidity()->getEndHour(), is(9));
        assertThat($res->getTrends()[0]->getVisibility()->getMainVisibility()['visibility'], is("3000"));
        assertThat($res->getTrends()[0]->getVisibility()->getMainVisibility()['unit'], is("m"));
        assertThat($res->getTrends()[0]->getWeatherConditions(), arrayWithSize(1));
        assertThat($res->getTrends()[0]->getWeatherConditions()[0]->getIntensity(), is(nullValue()));
        assertThat($res->getTrends()[0]->getWeatherConditions()[0]->getDescriptor(), is(nullValue()));
        assertThat($res->getTrends()[0]->getWeatherConditions()[0]->getPhenomenons(), arrayWithSize(1));
        assertThat($res->getTrends()[0]->getWeatherConditions()[0]->getPhenomenons()[0], is('BR'));
        assertThat($res->getTrends()[0]->getClouds(), arrayWithSize(1));
        assertThat($res->getTrends()[0]->getClouds()[0]->getQuantity(), is('BKN'));
        assertThat($res->getTrends()[0]->getClouds()[0]->getHeight(), is(600));
        self::assertEmpty($res->getTrends()[0]->getClouds()[0]->getType());
        // The first tempo with ptobability
        self::assertEquals('TEMPO', $res->getProbTrends()[0]->getType());
        assertThat($res->getProbTrends()[0]->getValidity()->getStartDay(), is(15));
        assertThat($res->getProbTrends()[0]->getValidity()->getStartHour(), is(6));
        assertThat($res->getProbTrends()[0]->getValidity()->getEndDay(), is(15));
        assertThat($res->getProbTrends()[0]->getValidity()->getEndHour(), is(8));
        assertThat($res->getProbTrends()[0]->getWind(), nullValue());
        assertThat($res->getProbTrends()[0]->getVisibility()->getMainVisibility()['visibility'], is(400));
        assertThat($res->getProbTrends()[0]->getVisibility()->getMainVisibility()['unit'], is("m"));
        assertThat($res->getProbTrends()[0]->getWeatherConditions(), arrayWithSize(1));
        assertThat($res->getProbTrends()[0]->getWeatherConditions()[0]->getIntensity(), is(nullValue()));
        assertThat($res->getProbTrends()[0]->getWeatherConditions()[0]->getDescriptor(), is('BC'));
        assertThat($res->getProbTrends()[0]->getWeatherConditions()[0]->getPhenomenons(), arrayWithSize(1));
        assertThat($res->getProbTrends()[0]->getWeatherConditions()[0]->getPhenomenons()[0], is('FG'));
        assertThat($res->getProbTrends()[0]->getClouds(), arrayWithSize(1));
        assertThat($res->getProbTrends()[0]->getClouds()[0]->getQuantity(), is('BKN'));
        assertThat($res->getProbTrends()[0]->getClouds()[0]->getHeight(), is(200));
        assertThat($res->getProbTrends()[0]->getProbability(), is(40));
        // Second tempo with probability
        self::assertEquals('TEMPO', $res->getProbTrends()[1]->getType());
        assertThat($res->getProbTrends()[1]->getValidity()->getStartDay(), is(15));
        assertThat($res->getProbTrends()[1]->getValidity()->getStartHour(), is(12));
        assertThat($res->getProbTrends()[1]->getValidity()->getEndDay(), is(15));
        assertThat($res->getProbTrends()[1]->getValidity()->getEndHour(), is(16));
        assertThat($res->getProbTrends()[1]->getVisibility()->getMainVisibility()['visibility'], is(4000));
        assertThat($res->getProbTrends()[1]->getVisibility()->getMainVisibility()['unit'], is("m"));
        assertThat($res->getProbTrends()[1]->getWeatherConditions(), arrayWithSize(1));
        assertThat($res->getProbTrends()[1]->getWeatherConditions()[0]->getIntensity(), is('-'));
        assertThat($res->getProbTrends()[1]->getWeatherConditions()[0]->getDescriptor(), is('SH'));
        assertThat($res->getProbTrends()[1]->getWeatherConditions()[0]->getPhenomenons(), arrayWithSize(1));
        assertThat($res->getProbTrends()[1]->getWeatherConditions()[0]->getPhenomenons()[0], is('RA'));
        assertThat($res->getProbTrends()[1]->getClouds(), arrayWithSize(2));
        assertThat($res->getProbTrends()[1]->getClouds()[0]->getQuantity(), is('FEW'));
        assertThat($res->getProbTrends()[1]->getClouds()[0]->getHeight(), is(3000));
        assertThat($res->getProbTrends()[1]->getClouds()[0]->getType(), is('TCU'));
        assertThat($res->getProbTrends()[1]->getClouds()[1]->getQuantity(), is('BKN'));
        assertThat($res->getProbTrends()[1]->getClouds()[1]->getHeight(), is( 4000));
        self::assertEmpty($res->getProbTrends()[1]->getClouds()[1]->getType());
        assertThat($res->getProbTrends()[1]->getProbability(), is(40));

        // BECMG Trend
        $becmg = $res->getTrends()[1];
        self::assertEquals('BECMG', $res->getTrends()[1]->getType());
        self::assertEquals(15, $becmg->getValidity()->getStartDay());
        self::assertEquals(20, $becmg->getValidity()->getStartHour());
        self::assertEquals(15, $becmg->getValidity()->getEndDay());
        self::assertEquals(22, $becmg->getValidity()->getEndHour());

        // Second tempo without probability
        $tempo2withoutProb = $res->getTrends()[2];
        self::assertEquals('TEMPO', $tempo2withoutProb->getType());
        self::assertEquals(16, $tempo2withoutProb->getValidity()->getStartDay());
        self::assertEquals(3, $tempo2withoutProb->getValidity()->getStartHour());
        self::assertEquals(16, $tempo2withoutProb->getValidity()->getEndDay());
        self::assertEquals(8, $tempo2withoutProb->getValidity()->getEndHour());
        self::assertEquals(3000, $tempo2withoutProb->getVisibility()->getMainVisibility()['visibility']);
        self::assertEquals('m', $tempo2withoutProb->getVisibility()->getMainVisibility()['unit']);
        assertThat($tempo2withoutProb->getWeatherConditions(), arrayWithSize(1));
        self::assertNull($tempo2withoutProb->getWeatherConditions()[0]->getIntensity());
        self::assertNull($tempo2withoutProb->getWeatherConditions()[0]->getDescriptor());
        assertThat($tempo2withoutProb->getWeatherConditions()[0]->getPhenomenons(), arrayWithSize(1));
        self::assertEquals('BR', $tempo2withoutProb->getWeatherConditions()[0]->getPhenomenons()[0]);
        assertThat($tempo2withoutProb->getClouds(), arrayWithSize(1));
        self::assertEquals('BKN', $tempo2withoutProb->getClouds()[0]->getQuantity());
        self::assertEmpty($tempo2withoutProb->getClouds()[0]->getType());
        self::assertEquals(600, $tempo2withoutProb->getClouds()[0]->getHeight());

        // Fifth Tempo
        $tempo3withProb = $res->getProbTrends()[2];
        self::assertEquals('TEMPO', $tempo3withProb->getType());
        self::assertEquals(16, $tempo3withProb->getValidity()->getStartDay());
        self::assertEquals(4, $tempo3withProb->getValidity()->getStartHour());
        self::assertEquals(16, $tempo3withProb->getValidity()->getEndDay());
        self::assertEquals(7, $tempo3withProb->getValidity()->getEndHour());
        self::assertEquals(400, $tempo3withProb->getVisibility()->getMainVisibility()['visibility']);
        self::assertEquals('m', $tempo3withProb->getVisibility()->getMainVisibility()['unit']);
        assertThat($tempo3withProb->getWeatherConditions(), arrayWithSize(1));
        self::assertNull($tempo3withProb->getWeatherConditions()[0]->getIntensity());
        self::assertEquals('BC', $tempo3withProb->getWeatherConditions()[0]->getDescriptor());
        assertThat($tempo3withProb->getWeatherConditions()[0]->getPhenomenons(), arrayWithSize(1));
        self::assertEquals('FG', $tempo3withProb->getWeatherConditions()[0]->getPhenomenons()[0]);
        assertThat($tempo3withProb->getClouds(), arrayWithSize(1));
        self::assertEquals('BKN', $tempo3withProb->getClouds()[0]->getQuantity());
        self::assertEquals(200, $tempo3withProb->getClouds()[0]->getHeight());
        self::assertEmpty($tempo3withProb->getClouds()[0]->getType());
        assertThat($tempo3withProb->getProbability(), is(40));
    }

    public function testParseValidWithoutLineBreaks()
    {
        $taf = "TAF LSZH 292025Z 2921/3103 VRB03KT 9999 FEW020 BKN080 TX20/3014Z TN06/3003Z " .
                "PROB30 TEMPO 2921/2923 SHRA " .
                "BECMG 3001/3004 4000 MIFG NSC " .
                "PROB40 3003/3007 1500 BCFG SCT004 " .
                "PROB30 3004/3007 0800 FG VV003 " .
                "BECMG 3006/3009 9999 FEW030 " .
                "PROB40 TEMPO 3012/3017 30008KT";

        try {
            $res = $this->sut->parse($taf);
        } catch (ParseException $e) {
            self::fail('Test should not fail');
        }

        assertThat($res, is(not(nullValue())));
        self::assertEquals('LSZH', $res->getIcao());
        // Check on time delivery.
        self::assertEquals(29, $res->getDay());
        self::assertEquals(20, $res->getTime()->getHours());
        self::assertEquals(25, $res->getTime()->getMinutes());
        // Checks on validity.
        self::assertEquals(29, $res->getValidity()->getStartDay());
        self::assertEquals(21, $res->getValidity()->getStartHour());
        self::assertEquals(31, $res->getValidity()->getEndDay());
        self::assertEquals(3, $res->getValidity()->getEndHour());
        // Checks on wind.
        assertThat($res->getWind()->getDirection(), nullValue());
        assertThat($res->getWind()->getCardinalDirection(), is('VRB'));
        assertThat($res->getWind()->getSpeed(), is(3));
        assertThat($res->getWind()->getGust(), is(0));
        assertThat($res->getWind()->getUnit(), is("KT"));
        // Checks on visibility.
        assertThat($res->getVisibility()->getMainVisibility()['visibility'], is("9999"));
        assertThat($res->getVisibility()->getMainVisibility()['unit'], is("m"));
        //Check on clouds.
        assertThat($res->getClouds(), arrayWithSize(2));
        assertThat($res->getClouds()[0]->getQuantity(), is('FEW'));
        assertThat($res->getClouds()[0]->getHeight(), is(2000));
        self::assertEmpty($res->getClouds()[0]->getType());

        assertThat($res->getClouds()[1]->getQuantity(), is('BKN'));
        assertThat($res->getClouds()[1]->getHeight(), is(8000));
        self::assertEmpty($res->getClouds()[1]->getType());
        // Check that no weatherCondition
        self::assertEmpty($res->getWeatherConditions());
        // Check max temperature
        assertThat($res->getMaxTemperature()->getDay(), is(30));
        assertThat($res->getMaxTemperature()->getHour(), is(14));
        assertThat($res->getMaxTemperature()->getTemperature(), is(20));
        // Check min temperature
        assertThat($res->getMinTemperature()->getDay(), is(30));
        assertThat($res->getMinTemperature()->getHour(), is(03));
        assertThat($res->getMinTemperature()->getTemperature(), is(6));

        // Checks trends.
        assertThat($res->getTrends(), arrayWithSize(2));
        assertThat($res->getProbTrends(), arrayWithSize(4));

        // First TEMPO
        $tempo0 = $res->getProbTrends()[0];
        assertThat($tempo0->getValidity()->getStartDay(), is(29));
        assertThat($tempo0->getValidity()->getStartHour(), is(21));
        assertThat($tempo0->getValidity()->getEndDay(), is(29));
        assertThat($tempo0->getValidity()->getEndHour(), is(23));
        assertThat($tempo0->getWeatherConditions(), arrayWithSize(1));
        assertThat($tempo0->getWeatherConditions()[0]->getIntensity(), is(nullValue()));
        assertThat($tempo0->getWeatherConditions()[0]->getDescriptor(), is('SH'));
        assertThat($tempo0->getWeatherConditions()[0]->getPhenomenons(), arrayWithSize(1));
        assertThat($tempo0->getWeatherConditions()[0]->getPhenomenons()[0], is('RA'));
        assertThat($tempo0->getProbability(), is(30));

        // First BECOMG
        $becmg0 = $res->getTrends()[0];
        self::assertEquals('BECMG', $becmg0->getType());
        assertThat($becmg0->getValidity()->getStartDay(), is(30));
        assertThat($becmg0->getValidity()->getStartHour(), is(1));
        assertThat($becmg0->getValidity()->getEndDay(), is(30));
        assertThat($becmg0->getValidity()->getEndHour(), is(4));
        assertThat($becmg0->getVisibility()->getMainVisibility()['visibility'], is(4000));
        assertThat($becmg0->getVisibility()->getMainVisibility()['unit'], is("m"));
        assertThat($becmg0->getWeatherConditions()[0]->getIntensity(), is(nullValue()));
        assertThat($becmg0->getWeatherConditions()[0]->getDescriptor(), is('MI'));
        assertThat($becmg0->getWeatherConditions()[0]->getPhenomenons(), arrayWithSize(1));
        assertThat($becmg0->getWeatherConditions()[0]->getPhenomenons()[0], is('FG'));
        assertThat($becmg0->getClouds()[0]->getQuantity(), is('NSC'));

        // First PROB
        $prob0 = $res->getProbTrends()[1];
        self::assertEquals('PROB', $prob0->getType());
        assertThat($prob0->getValidity()->getStartDay(), is(30));
        assertThat($prob0->getValidity()->getStartHour(), is(3));
        assertThat($prob0->getValidity()->getEndDay(), is(30));
        assertThat($prob0->getValidity()->getEndHour(), is(7));
        assertThat($prob0->getVisibility()->getMainVisibility()['visibility'], is(1500));
        assertThat($prob0->getVisibility()->getMainVisibility()['unit'], is("m"));
        assertThat($prob0->getWeatherConditions()[0]->getIntensity(), is(nullValue()));
        assertThat($prob0->getWeatherConditions()[0]->getDescriptor(), is('BC'));
        assertThat($prob0->getWeatherConditions()[0]->getPhenomenons(), arrayWithSize(1));
        assertThat($prob0->getWeatherConditions()[0]->getPhenomenons()[0], is('FG'));
        assertThat($prob0->getClouds(), arrayWithSize(1));
        assertThat($prob0->getClouds()[0]->getQuantity(), is('SCT'));
        assertThat($prob0->getClouds()[0]->getHeight(), is(400));
        self::assertEmpty($prob0->getClouds()[0]->getType());
        assertThat($prob0->getProbability(), is(40));

        // Second PROB
        $prob1 = $res->getProbTrends()[2];
        self::assertEquals('PROB', $prob1->getType());
        assertThat($prob1->getValidity()->getStartDay(), is(30));
        assertThat($prob1->getValidity()->getStartHour(), is(4));
        assertThat($prob1->getValidity()->getEndDay(), is(30));
        assertThat($prob1->getValidity()->getEndHour(), is(7));
        assertThat($prob1->getVisibility()->getMainVisibility()['visibility'], is(800));
        assertThat($prob1->getVisibility()->getMainVisibility()['unit'], is("m"));
        assertThat($prob1->getWeatherConditions()[0]->getIntensity(), is(nullValue()));
        assertThat($prob1->getWeatherConditions()[0]->getDescriptor(), is(nullValue()));
        assertThat($prob1->getWeatherConditions()[0]->getPhenomenons(), arrayWithSize(1));
        assertThat($prob1->getWeatherConditions()[0]->getPhenomenons()[0], is('FG'));
        assertThat($prob1->getVerticalVisibility(), is(300));
        assertThat($prob1->getClouds(), arrayWithSize(0));
        assertThat($prob1->getProbability(), is(30));

        // Second BECOMG
        $becmg1 = $res->getTrends()[1];
        assertThat($becmg1->getValidity()->getStartDay(), is(30));
        assertThat($becmg1->getValidity()->getStartHour(), is(6));
        assertThat($becmg1->getValidity()->getEndDay(), is(30));
        assertThat($becmg1->getValidity()->getEndHour(), is(9));
        assertThat($becmg1->getVisibility()->getMainVisibility()['visibility'], is(9999));
        assertThat($becmg1->getVisibility()->getMainVisibility()['unit'], is('m'));
        assertThat($becmg1->getWeatherConditions(), arrayWithSize(0));
        assertThat($becmg1->getClouds(), arrayWithSize(1));
        assertThat($becmg1->getClouds()[0]->getQuantity(), is('FEW'));
        assertThat($becmg1->getClouds()[0]->getHeight(), is(3000));
        self::assertEmpty($becmg1->getClouds()[0]->getType());

        // Second TEMPO
        $tempo1 = $res->getProbTrends()[3];
        self::assertEquals('TEMPO', $tempo1->getType());
        assertThat($tempo1->getValidity()->getStartDay(), is(30));
        assertThat($tempo1->getValidity()->getStartHour(), is(12));
        assertThat($tempo1->getValidity()->getEndDay(), is(30));
        assertThat($tempo1->getValidity()->getEndHour(), is(17));
        assertThat($tempo1->getWeatherConditions(), arrayWithSize(0));
        assertThat($tempo1->getWind()->getDirection(), is(300));
        assertThat($tempo1->getWind()->getSpeed(), is(8));
        assertThat($tempo1->getWind()->getGust(), is(0));
        assertThat($tempo1->getWind()->getUnit(), is("KT"));
        assertThat($tempo1->getProbability(), is(40));
    }

    public function testParseInvalidMessage()
    {
        $message = "LFPG 191100Z 1912/2018 02010KT 9999 FEW040 PROB30 ";
        try {
            $this->sut->parse($message);
        } catch (ParseException $e) {
            self::assertEquals(ErrorCodes::INVALID_CODE, $e->getMessage());
        }
    }

    public function testParseWith2Taf()
    {
        $message = "TAF TAF LFPG 191100Z 1912/2018 02010KT 9999 FEW040 PROB30 ";

        try {
            $result = $this->sut->parse($message);
        } catch (ParseException $e) {
            self::fail('should not fail');
        }

        self::assertNotNull($result);
        assertThat($result->getProbTrends(), arrayWithSize(1));
        assertThat($result->getProbTrends()[0]->getProbability(), is(30));
    }

    public function testParseWithFM() 
    {
        $message = "TAF KLWT 211120Z 2112/2212 20008KT 9999 SKC \n" . "TEMPO 2112/2116 VRB06KT \n"
                . "FM212300 30012G22KT 9999 FEW050 SCT250 \n" . "FM220700 27008KT 9999 FEW030 FEW250";

        try {
            $res = $this->sut->parse($message);
        } catch (ParseException $e) {
            self::fail('Test should not fail');
        }
        self::assertNotNull($res);

        assertThat($res, is(not(nullValue())));
        assertThat($res->getIcao(), is("KLWT"));
        assertThat($res->getDay(), is(21));
        assertThat($res->getTime()->getHours(), is(11));
        assertThat($res->getTime()->getMinutes(), is(20));
        assertThat($res->getValidity()->getStartDay(), is(21));
        assertThat($res->getValidity()->getStartHour(), is(12));
        assertThat($res->getValidity()->getEndDay(), is(22));
        assertThat($res->getValidity()->getEndHour(), is(12));

        // Wind
        assertThat($res->getWind(), is(not(nullValue())));
        assertThat($res->getWind()->getDirection(), is(200));
        assertThat($res->getWind()->getSpeed(), is(8));
        assertThat($res->getWind()->getGust(), is(0));
        assertThat($res->getWind()->getUnit(), is("KT"));

        // Visibility
        assertThat($res->getVisibility(), is(not(nullValue())));
        assertThat($res->getVisibility()->getMainVisibility()['visibility'], is("9999"));
        assertThat($res->getVisibility()->getMainVisibility()['unit'], is("m"));
        self::assertFalse($res->getVisibility()->hasMinVisibility());
        //Clouds
        assertThat($res->getClouds(), arrayWithSize(1));
        assertThat($res->getClouds()[0]->getQuantity(), is('SKC'));

        assertThat($res->getTrends(), arrayWithSize(3));
        $fm1 = $res->getTrends()[1];
        self::assertEquals('FM', $fm1->getType());
        self::assertEquals(21, $fm1->getValidity()->getStartDay());
        self::assertEquals(23, $fm1->getValidity()->getStartHour());
        self::assertEquals(0, $fm1->getValidity()->getStartMinute());
        self::assertEquals(300, $fm1->getWind()->getDirection());
        self::assertEquals(12, $fm1->getWind()->getSpeed());
        self::assertEquals(22, $fm1->getWind()->getGust());
        assertThat($fm1->getClouds(), arrayWithSize(2));
        self::assertEquals('FEW', $fm1->getClouds()[0]->getQuantity());
        self::assertEquals(5000, $fm1->getClouds()[0]->getHeight());
        self::assertEmpty($fm1->getClouds()[0]->getType());
        self::assertEquals('SCT', $fm1->getClouds()[1]->getQuantity());
        self::assertEquals(25000, $fm1->getClouds()[1]->getHeight());
        self::assertEmpty($fm1->getClouds()[1]->getType());
        // Tempos
        assertThat($res->getTrends()[0]->getType(), is('TEMPO'));
        assertThat($res->getTrends()[0]->getValidity()->getStartDay(), is(21));
        assertThat($res->getTrends()[0]->getValidity()->getStartHour(), is(12));
        assertThat($res->getTrends()[0]->getValidity()->getEndDay(), is(21));
        assertThat($res->getTrends()[0]->getValidity()->getEndHour(), is(16));
        self::assertEquals('VRB', $res->getTrends()[0]->getWind()->getCardinalDirection());
        assertThat($res->getTrends()[0]->getWind()->getSpeed(), is(6));

        $fm2 = $res->getTrends()[2];
        self::assertEquals('FM', $fm2->getType());
        self::assertEquals(22, $fm2->getValidity()->getStartDay());
        self::assertEquals(7, $fm2->getValidity()->getStartHour());
        self::assertEquals(0, $fm2->getValidity()->getStartMinute());
        assertThat($fm2->getClouds(), arrayWithSize(2));
    }

    public function testParseWithAMDTAF()
    {
        //GIVEN an amended TAF
        $message = "TAF AMD LFPO 100742Z 1007/1112 21018G35KT 9999 BKN025 \r\n" .
                "      TEMPO 1007/1009 4000 RA BKN014 SCT020CB PROB40 \r\n" .
                "      TEMPO 1007/1009 22020G45KT \r\n" .
                "      TEMPO 1010/1017 24022G45KT SHRA SCT030CB PROB30 \r\n" .
                "      TEMPO 1012/1016 -TSRA \r\n" .
                "      BECMG 1020/1023 24013KT SCT023 \r\n" .
                "      TEMPO 1104/1112 4000 -SHRA BKN012 BKN020TCU";
        //WHEN parsing the message
        try {
            $result = $this->sut->parse($message);
        } catch (ParseException $e) {
            self::fail('Test should not fail');
        }
        //Then the taf is correctly parsed and the amendment attribute is true.
        self::assertTrue($result->isAmendment());
    }

    public function testParseWithRMK()
    {
        // GIVEN a TAF with remark.
        $message = "TAF CZBF 300939Z 3010/3022 VRB03KT 6SM -SN OVC015 RMK FCST BASED ON AUTO OBS. NXT FCST BY 301400Z\n" . "TEMPO 3010/3012 11/2SM -SN OVC009 FM301200 10008KT 2SM -SN OVC010 \n"
                . "TEMPO 3012/3022 3/4SM -SN VV007";
        // WHEN parsing the event.
        try {
            $result = $this->sut->parse($message);
        } catch (ParseException $e) {
            self::fail('Test should not fail');
        }
        // THEN the taf contains the remark
        self::assertNotNull($result);
        self::assertEquals('FCST BASED ON AUTO OBS. NXT FCST BY 301400Z', $result->getRemark());
    }

    public function testParseWithRMKInTempo()
    {
        // GIVEN a TAF with remark in second tempo.
        $message = "TAF CZBF 300939Z 3010/3022 VRB03KT 6SM -SN OVC015\n" . "TEMPO 3010/3012 11/2SM -SN OVC009 FM301200 10008KT 2SM -SN OVC010 \n"
                . "TEMPO 3012/3022 3/4SM -SN VV007 RMK FCST BASED ON AUTO OBS. NXT FCST BY 301400Z";
        // WHEN parsing the event.
        try {
            $result = $this->sut->parse($message);
        } catch (ParseException $e) {
            self::fail('Test should not fail');
        }
        // THEN the second tempo contains the remark.
        self::assertNotNull($result);
        self::assertNotNull($result->getTrends()[2]);
        self::assertNotNull($result->getTrends()[2]->getRemark());
        self::assertEquals('FCST BASED ON AUTO OBS. NXT FCST BY 301400Z', $result->getTrends()[2]->getRemark());
    }
}
