<?php
/**
 * @author Jean-Kevin KPADEY
 */

namespace PHPMetarTafParser\Tests\Command\Metar;

use PHPMetarTafParser\Command\Metar\TemperatureCommand;
use PHPMetarTafParser\Model\Metar;
use PHPUnit\Framework\TestCase;

/**
 * Class TemperatureCommandTest
 * @package PHPMetarTafParser\Tests\Command\Metar
 */
class TemperatureCommandTest extends TestCase
{
    public function testExecute()
    {
        $temperaturePart = "M01/03";
        $m = new Metar();
        $command = new TemperatureCommand();
        $command->execute($m, $temperaturePart);

        self::assertEquals(-1, $m->getTemperature());
        self::assertEquals(3, $m->getDewPoint());
    }

    public function testExecuteDoubleMinus()
    {
        $tempPart = 'M10/M12';

        $m = new Metar();

        $command = new TemperatureCommand();
        $command->execute($m, $tempPart);

        self::assertEquals(-10, $m->getTemperature());
        self::assertEquals(-12, $m->getDewPoint());
    }
}
