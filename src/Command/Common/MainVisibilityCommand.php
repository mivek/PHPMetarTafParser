<?php


namespace PHPMetarTafParser\Command\Common;

use PHPMetarTafParser\Model\AbstractWeatherContainer;
use PHPMetarTafParser\Model\Visibility;

/**
 * Class MainVisibilityCommand
 * @package PHPMetarTafParser\Command
 * @author Jean-Kevin KPADEY
 */
class MainVisibilityCommand implements Command
{
    const MAIN_VISIBILITY_PATTERN = '/^(\d{4})(|NDV)$/';


    /**
     * @param AbstractWeatherContainer $container The container to update
     * @param string $code The string to parse
     */
    public function execute(AbstractWeatherContainer $container, string $code)
    {
        preg_match_all(self::MAIN_VISIBILITY_PATTERN, $code, $matches);
        if ($container->getVisibility() == null) {
            $container->setVisibility(new Visibility());
        }
        $container->getVisibility()->setMainVisibility($matches[1][0], 'm');
    }

    /**
     * Indicates if a command can parse the $code
     * @param string $code The code to parse
     * @return bool
     */
    function canParse(string $code): bool
    {
        return !empty(preg_grep(self::MAIN_VISIBILITY_PATTERN, explode('\n',$code)));
    }
}