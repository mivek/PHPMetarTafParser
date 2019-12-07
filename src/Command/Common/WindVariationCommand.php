<?php


namespace PHPMetarTafParser\Command\Common;


use PHPMetarTafParser\Model\AbstractWeatherContainer;

class WindVariationCommand implements Command
{
    private const WIND_VARIATION_PATTERN = "/^(\d{3})V(\d{3})$/";

    /**
     * @param AbstractWeatherContainer $container The container to update
     * @param string $code The string to parse
     */
    public function execute(AbstractWeatherContainer $container, string $code)
    {
        preg_match_all(self::WIND_VARIATION_PATTERN, $code, $matches);
        $container->getWind()->setVariableWind($matches[1][0], $matches[2][0]);
    }

    /**
     * Indicates if a command can parse the $code
     * @param string $code The code to parse
     * @return bool
     */
    function canParse(string $code): bool
    {
        return !empty(preg_grep(self::WIND_VARIATION_PATTERN, explode('\n', $code)));
    }
}