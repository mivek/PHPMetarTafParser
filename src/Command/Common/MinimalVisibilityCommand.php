<?php


namespace PHPMetarTafParser\Command\Common;


use PHPMetarTafParser\Model\AbstractWeatherContainer;
use PHPMetarTafParser\Model\Visibility;

class MinimalVisibilityCommand implements Command
{

    private const MIN_VISIBILITY_REGEX = "/^(\d{4})([a-z])$/";
    /**
     * @param AbstractWeatherContainer $container The container to update
     * @param string $code The string to parse
     */
    public function execute(AbstractWeatherContainer $container, string $code)
    {
        preg_match_all(self::MIN_VISIBILITY_REGEX, $code, $matches);
        if ($container->getVisibility() == null) {
            $container->setVisibility(new Visibility());
        }
        $container->getVisibility()->setMinVisibility($matches[1][0], $matches[2][0]);
    }

    /**
     * Indicates if a command can parse the $code
     * @param string $code The code to parse
     * @return bool
     */
    function canParse(string $code): bool
    {
        return !empty(preg_grep(self::MIN_VISIBILITY_REGEX, explode('\n', $code)));
    }
}