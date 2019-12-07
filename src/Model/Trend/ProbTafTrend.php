<?php


namespace PHPMetarTafParser\Model\Trend;

/**
 *
 * Class ProbTafTrend a TafTrend with probability
 * @package PHPMetarTafParser\Model\Trend
 * @author Jean-Kevin KPADEY
 */
class ProbTafTrend extends TafTrend
{
    /**
     * @var int.
     */
    private $probability;

    /**
     * @return int
     */
    public function getProbability(): int
    {
        return $this->probability;
    }

    /**
     * @param int $probability
     * @return ProbTafTrend
     */
    public function setProbability(int $probability): ProbTafTrend
    {
        $this->probability = $probability;
        return $this;
    }

}