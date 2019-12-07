<?php
/**
 * @author Jean-Kevin KPADEY
 */

namespace PHPMetarTafParser\Tests\Command\Metar;

use PHPMetarTafParser\Command\Metar\RunwayInfoCommand;
use PHPMetarTafParser\Model\Metar;
use PHPUnit\Framework\TestCase;

/**
 * Class RunwayInfoCommandTest
 * @package PHPMetarTafParser\Tests\Command\Metar
 */
class RunwayInfoCommandTest extends TestCase
{
    public function testExecuteWithWrongCode()
    {
        $code = "R26R/AZEZFDFS";

        $metar = new Metar();

        $sut = new RunwayInfoCommand();

        $sut->execute($metar, $code);

        self::assertEquals(0, count($metar->getRunwaysInfo()));
    }

    public function testCanParseWithCode()
    {
        $code = "R26R/AZEZFDFS";

        $sut = new RunwayInfoCommand();

        self::assertTrue($sut->canParse($code));
    }

    public function testExecuteWithoutMaxRange()
    {
        $riString = "R26/0600U";
        $m = new Metar();
        $command = new RunwayInfoCommand();
        $command->execute($m, $riString);

        self::assertEquals(1,count($m->getRunwaysInfo()));
        $ri = $m->getRunwaysInfo()[0];
        self::assertNotNull($ri);
        self::assertEquals("26", $ri->getName());
        self::assertEquals(600, $ri->getMinRange());
        self::assertEquals('U', $ri->getTrend());
    }

    public function testExecuteWithMaxRange()
    {
        $riString = "R26L/0550V700U";
        $m = new Metar();
        $command = new RunwayInfoCommand();
        $command->execute($m, $riString);

        self::assertEquals(1, count($m->getRunwaysInfo()));
        $ri = $m->getRunwaysInfo()[0];
        self::assertNotNull($ri);
        self::assertEquals("26L", $ri->getName());
        self::assertEquals(550, $ri->getMinRange());
        self::assertEquals(700, $ri->getMaxRange());
        self::assertEquals('U', $ri->getTrend());
    }
}
