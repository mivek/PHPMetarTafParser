<?php

namespace PHPMetarTafParser\Tests\Command\Common;

use PHPMetarTafParser\Command\Common\VerticalVisibilityCommand;
use PHPMetarTafParser\Model\Metar;
use PHPUnit\Framework\TestCase;

/**
 * Class VerticalVisibilityCommandTest
 * @package PHPMetarTafParser\Tests\Command\Common
 */
class VerticalVisibilityCommandTest extends TestCase
{
    private $sut;

    protected function setUp(): void
    {
        $this->sut = new VerticalVisibilityCommand();
    }

    public function testExecute()
    {
        $m = new Metar();

        $this->sut->execute($m, 'VV030');

        self::assertEquals(3000, $m->getVerticalVisibility());

    }

    public function testCanParse()
    {
        self::assertTrue($this->sut->canParse('VV300'));
    }

    public function testCanParseWithWrongValue()
    {
        self::assertFalse($this->sut->canParse('V300'));
    }
}
