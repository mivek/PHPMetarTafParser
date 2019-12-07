<?php


namespace PHPMetarTafParser\Command\Metar;

use PHPMetarTafParser\Model\Metar;

/**
 * Interface for the command related to Metar.
 * Interface Command
 * @package PHPMetarTafParser\Command\Metar
 */
interface Command
{

    /**
     * @param string $code
     * @return bool true if the command can parse the code.
     */
    public function canParse(string $code): bool;

    /**
     * @param Metar $metar The metar to update
     * @param string $code the code to parse
     */
    public function execute(Metar $metar, string $code): void;
}