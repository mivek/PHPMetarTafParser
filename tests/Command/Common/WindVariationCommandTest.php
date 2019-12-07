<?php

namespace PHPMetarTafParser\Tests\Command\Common;

use PHPMetarTafParser\Command\Common\WindVariationCommand;
use PHPMetarTafParser\Model\Metar;
use PHPMetarTafParser\Model\Wind;
use PHPUnit\Framework\TestCase;

/**
 * Class WindVariationCommandTest
 * @package PHPMetarTafParser\Tests\Command\Common
 */
class WindVariationCommandTest extends TestCase
{

    public function testExecute()
    {
        $sut = new WindVariationCommand();
        $m = new Metar();
        $m->setWind(new Wind());
        $sut->execute($m, '120V240');

        self::assertEquals(120, $m->getWind()->getVariableWind()['min']);
        self::assertEquals(240, $m->getWind()->getVariableWind()['max']);
    }
    public function testCanParse()
    {
        $sut = new WindVariationCommand();
        self::assertTrue($sut->canParse('330V345'));
    }

    public function testCanParseWrongValue()
    {
        $sut = new WindVariationCommand();
        self::assertFalse($sut->canParse('3030V345'));
    }
}
