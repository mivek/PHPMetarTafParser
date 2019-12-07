<?php

namespace PHPMetarTafParser\Tests\Command\Common;

use PHPMetarTafParser\Command\Common\WindCommand;
use PHPMetarTafParser\Model\Metar;
use PHPUnit\Framework\TestCase;

/**
 * Class WindCommandTest
 * @package PHPMetarTafParser\Tests\Command\Common
 */
class WindCommandTest extends TestCase
{
    private $sut;
    
    public function setUp(): void
    {
        parent::setUp();
        $this->sut = new WindCommand();
    }

    public function testParseWindSimple() {
        $windPart = "34008KT";
        $metar = new Metar();
        $this->sut->execute($metar, $windPart);

        self::assertNotNull($metar->getWind());
        self::assertEquals('N', $metar->getWind()->getCardinalDirection());
        self::assertEquals(340, $metar->getWind()->getDirection());
        self::assertEquals(8, $metar->getWind()->getSpeed());
        self::assertEquals(0, $metar->getWind()->getGust());
        self::assertEquals("KT", $metar->getWind()->getUnit());

    }

    public function testParseWindWithGusts() {
        $windPart = "12017G20KT";

        $metar = new Metar();
        $this->sut->execute($metar, $windPart);

        self::assertNotNull($metar->getWind());
        self::assertEquals('SE',$metar->getWind()->getCardinalDirection());
        self::assertEquals(120,$metar->getWind()->getDirection());
        self::assertEquals(17,$metar->getWind()->getSpeed());
        self::assertEquals(20,$metar->getWind()->getGust());
        self::assertEquals('KT',$metar->getWind()->getUnit());
    }

    public function testParseWindVariable() {
        $windPart = "VRB08KT";

        $metar = new Metar();
        $this->sut->execute($metar, $windPart);

        self::assertNotNull($metar->getWind());
        self::assertEquals('VRB',$metar->getWind()->getCardinalDirection());
        self::assertNull($metar->getWind()->getDirection());
        self::assertEquals(8,$metar->getWind()->getSpeed());
        self::assertEquals(0,$metar->getWind()->getGust());
        self::assertEquals('KT',$metar->getWind()->getUnit());
    }
}
