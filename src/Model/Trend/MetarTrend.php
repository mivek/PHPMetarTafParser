<?php


namespace PHPMetarTafParser\Model\Trend;

/**
 * Class MetarTrend
 * @package PHPMetarTafParser\Model\Trend
 */
class MetarTrend extends AbstractTrend
{
    /**
     * @var MetarTrendTime[]
     */
    private $times;

    /**
     * @return MetarTrendTime[]
     */
    public function getTimes(): array
    {
        return $this->times;
    }

    /**
     * @param MetarTrendTime $time to add
     * @return MetarTrend this
     */
    public function addTime(MetarTrendTime $time) : MetarTrend
    {
        $this->times[] = $time;
        return $this;
    }
    public function __construct(string $type)
    {
        parent::__construct($type);
        $this->times = array();
    }

}