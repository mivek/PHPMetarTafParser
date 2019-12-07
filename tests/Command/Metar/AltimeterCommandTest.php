<?php
/**
 * @author Jean-Kevin KPADEY
 */

namespace PHPMetarTafParser\Tests\Command\Metar;

use PHPMetarTafParser\Command\Metar\AltimeterCommand;
use PHPMetarTafParser\Model\Metar;
use PHPUnit\Framework\TestCase;

/**
 * Class AltimeterCommandTest
 * @package PHPMetarTafParser\Tests\Command\Metar
 */
class AltimeterCommandTest extends TestCase
{

    public function testExecute()
    {
        $m = new Metar();
        $code = 'Q1006';

        $sut = new AltimeterCommand();
        $sut->execute($m, $code);

        self::assertNotNull($m->getAltimeter());
        self::assertEquals(1006, $m->getAltimeter()['value']);
        self::assertEquals('hPa', $m->getAltimeter()['unit']);
    }
}
