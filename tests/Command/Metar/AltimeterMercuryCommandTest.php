<?php
/**
 * @author Jean-Kevin KPADEY
 */

namespace PHPMetarTafParser\Tests\Command\Metar;

use PHPMetarTafParser\Command\Metar\AltimeterMercuryCommand;
use PHPMetarTafParser\Model\Metar;
use PHPUnit\Framework\TestCase;

/**
 * Class AltimeterMercuryCommandTest
 * @package PHPMetarTafParser\Tests\Command\Metar
 */
class AltimeterMercuryCommandTest extends TestCase
{
    public function testExecute()
    {
        $code = 'A2980';
        $m = new Metar();

        $sut = new AltimeterMercuryCommand();

        $sut->execute($m, $code);

        self::assertNotNull($m->getAltimeter());
        self::assertEquals(29.80, $m->getAltimeter()['value']);
        self::assertEquals('inHg', $m->getAltimeter()['unit']);
    }
}
