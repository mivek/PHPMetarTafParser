<?php


namespace PHPMetarTafParser\Command\Common;


use PHPMetarTafParser\Model\AbstractWeatherContainer;

/**
 * Interface Command
 * @package PHPMetarTafParser\Command\Common
 */
interface Command
{
    /**
     * @param AbstractWeatherContainer $container The container to update
     * @param string $code The string to parse
     */
    public function execute(AbstractWeatherContainer $container, string $code);

    /**
     * Indicates if a command can parse the $code
     * @param string $code The code to parse
     * @return bool
     */
    function canParse(string $code) : bool;
}