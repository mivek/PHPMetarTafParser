<?php

namespace PHPMetarTafParser\Tests\Command\Common;

use PHPMetarTafParser\Command\Common\MainVisibilityNauticalMilesCommand;
use PHPMetarTafParser\Model\Metar;
use PHPUnit\Framework\TestCase;

/**
 * Class MainVisibilityNauticalMilesCommandTest
 * @package PHPMetarTafParser\Tests\Command\Common
 */
class MainVisibilityNauticalMilesCommandTest extends TestCase
{
    private $sut;

    public function setUp(): void
    {
        $this->sut = new MainVisibilityNauticalMilesCommand();
    }

    public function testCanParseSimple()
    {
        $input = '6SM';
        self::assertTrue($this->sut->canParse($input));
    }

    public function testCanParse()
    {
        $input = '1/2SM';
        self::assertTrue($this->sut->canParse($input));
    }

    public function testCanParseWithHalf()
    {
        $input = '6 1/2SM';
        self::assertTrue($this->sut->canParse($input));
    }

    public function testCanParseMetric()
    {
        $input = '9999';
        self::assertFalse($this->sut->canParse($input));
    }

    public function testExecute()
    {
        $input = '1/2SM';
        $m = new Metar();

        $this->sut->execute($m, $input);

        self::assertEquals('1/2', $m->getVisibility()->getMainVisibility()['visibility']);
        self::assertEquals('SM', $m->getVisibility()->getMainVisibility()['unit']);
    }

    public function testExecuteWithDecimal()
    {
        $input = '5 1/2SM';
        $m = new Metar();

        $this->sut->execute($m, $input);

        self::assertEquals('5 1/2', $m->getVisibility()->getMainVisibility()['visibility']);
        self::assertEquals('SM', $m->getVisibility()->getMainVisibility()['unit']);
    }

    public function testExecuteBasic()
    {
        $input = '5SM';
        $m = new Metar();

        $this->sut->execute($m, $input);

        self::assertEquals('5', $m->getVisibility()->getMainVisibility()['visibility']);
        self::assertEquals('SM', $m->getVisibility()->getMainVisibility()['unit']);
    }
}
