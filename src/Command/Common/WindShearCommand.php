<?php


namespace PHPMetarTafParser\Command\Common;


use PHPMetarTafParser\Model\AbstractWeatherContainer;
use PHPMetarTafParser\Model\WindShear;

final class WindShearCommand extends BaseWindCommand
{
    private const WIND_SHEAR_REGEX = "/WS(\d{3})\/(\w{3})(\d{2})G?(\d{2})?(KT|MPS|KM\/H)$/";


    /**
     * @param AbstractWeatherContainer $container The container to update
     * @param string $code The string to parse
     */
    public function execute(AbstractWeatherContainer $container, string $code)
    {
        preg_match_all(self::WIND_SHEAR_REGEX, $code, $matches);
        $ws = new WindShear();
        $ws->setHeight(100*intval($matches[1][0]));
        $this->setWindElement($ws, $matches[2][0], $matches[3][0], $matches[4][0], $matches[5][0]);
        $container->setWindShear($ws);
    }

    /**
     * Indicates if a command can parse the $code
     * @param string $code The code to parse
     * @return bool
     */
    function canParse(string $code): bool
    {
        return !empty(preg_grep(self::WIND_SHEAR_REGEX, explode('\n', $code)));
    }
}