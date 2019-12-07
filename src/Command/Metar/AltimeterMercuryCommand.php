<?php
/**
 * @author Jean-Kevin KPADEY
 */

namespace PHPMetarTafParser\Command\Metar;


use PHPMetarTafParser\Model\Metar;

class AltimeterMercuryCommand implements Command
{
    private const ALTIMETER_MERCURY_REGEX = "/^A(\d{4})$/";
    /**
     * @param string $code
     * @return bool true if the command can parse the code.
     */
    public function canParse(string $code): bool
    {
        return !empty(preg_grep(self::ALTIMETER_MERCURY_REGEX, explode('\n', $code)));
    }

    /**
     * @param Metar $metar The metar to update
     * @param string $code the code to parse
     */
    public function execute(Metar $metar, string $code): void
    {
        preg_match_all(self::ALTIMETER_MERCURY_REGEX, $code, $matches);
        $metar->setAltimeter(floatval($matches[1][0]/100), 'inHg');
    }
}