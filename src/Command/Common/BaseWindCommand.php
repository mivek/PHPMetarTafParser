<?php


namespace PHPMetarTafParser\Command\Common;


use PHPMetarTafParser\Model\Wind;
use PHPMetarTafParser\Utils\DirectionConverter;

/**
 * Class BaseWindCommand
 * @package PHPMetarTafParser\Command\Common
 */
abstract class BaseWindCommand implements Command
{

    /**
     * @param Wind $wind
     * @param string $direction
     * @param string $speed
     * @param string $gust
     * @param string $unit
     */
    function setWindElement(Wind $wind, string $direction, string $speed, string $gust, string $unit)
    {
        $wind
            ->setCardinalDirection(DirectionConverter::degreesToDirection($direction))
            ->setSpeed($speed)
            ->setGust(intval($gust))
            ->setUnit($unit);
        if ($direction != 'VRB') {
            $wind->setDirection($direction);
        }
    }
}