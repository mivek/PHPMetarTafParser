<?php

namespace PHPMetarTafParser\Tests\Command\Common;

use PHPMetarTafParser\Command\Common\MainVisibilityCommand;
use PHPMetarTafParser\Model\Metar;
use PHPUnit\Framework\TestCase;

/**
 * Class MainVisibilityCommandTest
 * @package PHPMetarTafParser\Tests\Command\Common
 */
class MainVisibilityCommandTest extends TestCase
{
    private $sut;
    public function setUp(): void
    {
        parent::setUp();
        $this->sut = new MainVisibilityCommand();
    }

    public function testExecute()
    {
        $visibility = '0350';
        $metar = new Metar();

        $this->sut->execute($metar, $visibility);

        self::assertNotNull($metar->getVisibility());
        self::assertEquals(350, $metar->getVisibility()->getMainVisibility()['visibility']);
        self::assertEquals('m', $metar->getVisibility()->getMainVisibility()['unit']);
    }

    public function testExecuteWithNDV()
    {
        $visibility = '0350NDV';
        $metar = new Metar();

        $this->sut->execute($metar, $visibility);

        self::assertNotNull($metar->getVisibility());
        self::assertEquals(350, $metar->getVisibility()->getMainVisibility()['visibility']);
        self::assertEquals('m', $metar->getVisibility()->getMainVisibility()['unit']);
    }

    public function testCanParse()
    {
        $visibility = '0350NDV';
        $metar = new Metar();

        $this->sut->execute($metar, $visibility);

        self::assertTrue($this->sut->canParse($visibility));
    }
}
