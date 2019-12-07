<?php
/**
 * @author Jean-Kevin KPADEY
 */

namespace PHPMetarTafParser\Command\Metar;


class MetarCommandSupplier
{
    /**
     * @var Command[]
     */
    private $commands;


    public function get(string $code)
    {
        foreach ($this->commands as $command) {
            if($command->canParse($code)){
                return $command;
            }
        }
        return null;
    }

    public function __construct()
    {
        $this->buildCommands();
    }

    private function buildCommands()
    {
        $this->commands = array(
            new RunwayInfoCommand(),
            new TemperatureCommand(),
            new AltimeterCommand(),
            new AltimeterMercuryCommand()
        );
    }
}