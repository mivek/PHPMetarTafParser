<?php


namespace PHPMetarTafParser\Command\Common;


use PHPMetarTafParser\Model\AbstractWeatherContainer;
use PHPMetarTafParser\Model\Wind;

final class WindCommand extends BaseWindCommand
{
    /**
     * Pattern for the wind.
     */
    private const WIND_PATTERN = '/(\w{3})(\d{2})G?(\d{2})?(KT|MPS|KM\/H)/';


    /**
     * @param AbstractWeatherContainer $container The container to update
     * @param string $code The string to parse
     */
    public function execute(AbstractWeatherContainer $container, string $code)
    {
        preg_match_all(self::WIND_PATTERN, $code, $matches);
        $wind = new Wind();
        $this->setWindElement($wind, $matches[1][0], $matches[2][0], $matches[3][0],$matches[4][0]);
        $container->setWind($wind);
    }

    /**
     * Indicates if a command can parse the $code
     * @param string $code The code to parse
     * @return bool
     */
    function canParse(string $code): bool
    {
        return !empty(preg_grep(self::WIND_PATTERN, explode('\n',$code)));
    }
}