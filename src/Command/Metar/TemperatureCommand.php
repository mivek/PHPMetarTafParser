<?php
/**
 * @author Jean-Kevin KPADEY
 */

namespace PHPMetarTafParser\Command\Metar;


use PHPMetarTafParser\Model\Metar;
use PHPMetarTafParser\Utils\TemperatureConverter;

class TemperatureCommand implements Command
{
    private const TEMPERATURE_REGEX = "#^(M?\d{2})/(M?\d{2})$#";
    /**
     * @param string $code
     * @return bool true if the command can parse the code.
     */
    public function canParse(string $code): bool
    {
        return !empty(preg_grep(self::TEMPERATURE_REGEX, explode('\n', $code)));
    }

    /**
     * @param Metar $metar The metar to update
     * @param string $code the code to parse
     */
    public function execute(Metar $metar, string $code): void
    {
        preg_match_all(self::TEMPERATURE_REGEX, $code, $matches);
        $metar->setTemperature(TemperatureConverter::convertTemperature($matches[1][0]));
        $metar->setDewPoint(TemperatureConverter::convertTemperature($matches[2][0]));
    }
}