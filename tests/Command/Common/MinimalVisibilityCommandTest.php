<?php

namespace PHPMetarTafParser\Tests\Command\Common;

use PHPMetarTafParser\Command\Common\MinimalVisibilityCommand;
use PHPMetarTafParser\Model\Metar;
use PHPUnit\Framework\TestCase;

/**
 * Class MinimalVisibilityCommandTest
 * @package PHPMetarTafParser\Tests\Command\Common
 */
class MinimalVisibilityCommandTest extends TestCase
{
    private $sut;

    public function setUp(): void
    {
        $this->sut = new MinimalVisibilityCommand();
    }

    public function testCanParseWithInvalidDirection()
    {
        self::assertFalse($this->sut->canParse('1234PO'));
    }

    public function testCanParseWithInvalidDistance()
    {
        self::assertFalse($this->sut->canParse('78888w'));
    }

    public function testCanParse()
    {
        self::assertTrue($this->sut->canParse('5000w'));
    }

    public function testExecute()
    {
        $m = new Metar();
        $this->sut->execute($m, '5000w');
        self::assertEquals(5000, $m->getVisibility()->getMinVisibility()['visibility']);
        self::assertEquals('w', $m->getVisibility()->getMinVisibility()['direction']);
    }
}
