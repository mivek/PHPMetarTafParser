<?php
/**
 * @author Jean-Kevin KPADEY
 */

namespace PHPMetarTafParser\Command\Metar;


use PHPMetarTafParser\Model\Metar;

final class AltimeterCommand implements Command
{
    /**
     * Regex for the altimeter in Pascal.
     */
    private const ALTIMETER_REGEX = "/^Q(\d{4})$/";
    /**
     * @param string $code
     * @return bool true if the command can parse the code.
     */
    public function canParse(string $code): bool
    {
        return !empty(preg_grep(self::ALTIMETER_REGEX, explode('\n', $code)));
    }

    /**
     * @param Metar $metar
     * @param string $code
     */
    public function execute(Metar $metar, string $code): void
    {
        preg_match_all(self::ALTIMETER_REGEX, $code, $matches);
        $metar->setAltimeter($matches[1][0], 'hPa');
    }
}