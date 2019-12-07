<?php


namespace PHPMetarTafParser\Model\Trend;


use PHPMetarTafParser\Model\AbstractWeatherContainer;

class AbstractTrend extends AbstractWeatherContainer
{
    /**
     * @var string
     */
    private $type;

    /**
     * AbstractTrend constructor.
     * @param string $type
     */
    public function __construct(string $type)
    {
        parent::__construct();
        $this->type = $type;
    }

    /**
     * @return string the weather change type.
     */
    public function getType() : string
    {
        return $this->type;
    }
}