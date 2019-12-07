<?php
/**
 * @author Jean-Kevin KPADEY
 */

namespace PHPMetarTafParser\Command\Metar;


use PHPMetarTafParser\Model\Metar;
use PHPMetarTafParser\Model\RunwayInfo;

class RunwayInfoCommand implements Command
{

    /** Pattern to recognize a runway. */
    private const GENERIC_RUNWAY_REGEX = "#^(R\d{2}\w?)#";
    /** Pattern regex for runway with min and max range visibility. */
    private const RUNWAY_MAX_RANGE_REGEX = "#^R(\d{2}\w?)/(\d{4})V(\d{3})(\w{0,2})#";
    /** Pattern regex for runway visibility. */
    private const RUNWAY_REGEX = "#^R(\d{2}\w?)/(\w)?(\d{4})(\w{0,2})$#";
    /**
     * @param string $code
     * @return bool true if the command can parse the code.
     */
    public function canParse(string $code): bool
    {
        return !empty(preg_grep(self::GENERIC_RUNWAY_REGEX, explode('\n', $code)));
    }

    /**
     * @param Metar $metar The metar to update
     * @param string $code the code to parse
     */
    public function execute(Metar $metar, string $code): void
    {
        $ri = new RunwayInfo();
        preg_match_all(self::RUNWAY_REGEX, $code, $matches);
        if (!empty($matches[0])) {
            $ri->setName($matches[1][0]);
            $ri->setMinRange($matches[3][0]);
            $ri->setTrend($matches[4][0]);
            $metar->addRunwayInfo($ri);
            return;
        }
        preg_match_all(self::RUNWAY_MAX_RANGE_REGEX, $code, $matches);
        if (!empty($matches[0])) {
            $ri->setName($matches[1][0]);
            $ri->setMinRange($matches[2][0]);
            $ri->setMaxRange($matches[3][0]);
            $ri->setTrend($matches[4][0]);
            $metar->addRunwayInfo($ri);
        }
    }
}