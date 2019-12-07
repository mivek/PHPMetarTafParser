<?php

namespace PHPMetarTafParser\Tests\Command\Common;

use PHPMetarTafParser\Command\Common\CloudCommand;
use PHPMetarTafParser\Model\Metar;
use PHPUnit\Framework\TestCase;

/**
 * Class CloudCommandTest
 * @package PHPMetarTafParser\Tests\Command\Common
 */
class CloudCommandTest extends TestCase
{
    /**
     * @var CloudCommand
     */
    private $sut;
    public function setUp(): void
    {
        parent::setUp();
        $this->sut = new CloudCommand();
    }

    public function testParseCloudNullCloudQuantity()
    {
        $cloudStr = "FEW015";
        $metar = new Metar();
        $this->sut->execute($metar, $cloudStr);
        $this->assertEquals(1, count($metar->getClouds()));
        $cloud = $metar->getClouds()[0];
        $this->assertEquals('FEW', $cloud->getQuantity());
        $this->assertEquals(1500, $cloud->getHeight());
        $this->assertEmpty($cloud->getType());
    }

    public function testParseCloudSkyClear()
    {
        $cloud = "SKC";

        $metar = new Metar();
        $this->sut->execute($metar, $cloud);

        self::assertEquals(1, count($metar->getClouds()));
        self::assertEquals('SKC', $metar->getClouds()[0]->getQuantity());
        self::assertEquals(0, $metar->getClouds()[0]->getHeight());
        self::assertEmpty($metar->getClouds()[0]->getType());
    }

    public function testParseCloudWithType() {
        $cloud = "SCT026CB";

        $metar = new Metar();

        $this->sut->execute($metar, $cloud);

        self::assertEquals(1, count($metar->getClouds()));
        self::assertEquals('SCT', $metar->getClouds()[0]->getQuantity());
        self::assertEquals(2600, $metar->getClouds()[0]->getHeight());
        self::assertEquals('CB', $metar->getClouds()[0]->getType());
    }

    public function testCanParse()
    {
        $cloud = "SCT026CB";
        self::assertTrue($this->sut->canParse($cloud));
    }

    public function testCanParseWithInvalidCloud()
    {
        self::assertFalse($this->sut->canParse('ADR789DRTY'));
    }
}
