<?php


namespace PHPMetarTafParser\Command\Common;


use PHPMetarTafParser\Model\AbstractWeatherContainer;

class VerticalVisibilityCommand implements Command
{

    private const VERTICAL_VISIBILITY = "/^VV(\d{3})$/";
    /**
     * @param AbstractWeatherContainer $container The container to update
     * @param string $code The string to parse
     */
    public function execute(AbstractWeatherContainer $container, string $code)
    {
        preg_match_all(self::VERTICAL_VISIBILITY, $code, $matches);
        $container->setVerticalVisibility(100*intval($matches[1][0]));
    }

    /**
     * Indicates if a command can parse the $code
     * @param string $code The code to parse
     * @return bool
     */
    function canParse(string $code): bool
    {
        return !empty(preg_grep(self::VERTICAL_VISIBILITY, explode('\n', $code)));
    }
}