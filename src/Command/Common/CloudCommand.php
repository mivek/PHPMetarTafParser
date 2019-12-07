<?php


namespace PHPMetarTafParser\Command\Common;

use PHPMetarTafParser\Model\AbstractWeatherContainer;
use PHPMetarTafParser\Model\Cloud;

/**
 * Class CloudCommand
 * @package PHPMetarTafParser\Command
 * @author Jean-Kevin KPADEY
 */
final class CloudCommand implements Command
{
    /**
     * String of cloud quantity.
     */
    const CLOUD_QUANTITY = "SKC|FEW|BKN|SCT|OVC|NSC";

    const CLOUD_TYPE = "CB|TCU|CI|CC|CS|AC|ST|CU|AS|NS|SC";
    const CLOUD_REGEX = "/^(".self::CLOUD_QUANTITY.")(\d{3})?(".self::CLOUD_TYPE.")?$/";

    /**
     * @param AbstractWeatherContainer $container The container to update
     * @param string $code The string to parse
     */
    public function execute(AbstractWeatherContainer $container, string $code)
    {
        $container->addCloud($this->parseCloud($code));
    }

    private function parseCloud(string $code) : Cloud
    {
        $cloud = new Cloud();
        preg_match_all(self::CLOUD_REGEX, $code, $matches);
        $cloud->setQuantity($matches[1][0])->setHeight(100*intval($matches[2][0]))->setType($matches[3][0]);
        return $cloud;
    }

    /**
     * Indicates if a command can parse the $code
     * @param string $code The code to parse
     * @return bool
     */
    function canParse(string $code): bool
    {
        return !empty(preg_grep(self::CLOUD_REGEX, explode('\n', $code)));
    }
}