<?php

namespace PHPMetarTafParser\Tests\Command\Common;

use PHPMetarTafParser\Command\Common\WindShearCommand;
use PHPMetarTafParser\Model\Metar;
use PHPUnit\Framework\TestCase;

/**
 * Class WindShearCommandTest
 * @package PHPMetarTafParser\Tests\Command\Common
 */
class WindShearCommandTest extends TestCase
{
    public function testExecute()
    {
        $code = 'WS020/24045KT';
        $sut = new WindShearCommand();

        $m = new Metar();

        $sut->execute($m, $code);

        self::assertNotNull($m->getWindShear());
        self::assertEquals(2000, $m->getWindShear()->getHeight());
        self::assertEquals(45, $m->getWindShear()->getSpeed());
        self::assertEquals('KT', $m->getWindShear()->getUnit());
        self::assertEquals(240, $m->getWindShear()->getDirection());
    }
}
