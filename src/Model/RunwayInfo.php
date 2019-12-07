<?php


namespace PHPMetarTafParser\Model;


class RunwayInfo
{
    /**
     * @var string the name of the runway
     */
    private $name;
    /**
     * @var string
     */
    private $trend;
    /**
     * @var int The minimal visibility range on the runway
     */
    private $minRange;
    /**
     * @var int The maximal visibility range on the runway
     */
    private $maxRange;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return RunwayInfo
     */
    public function setName(string $name): RunwayInfo
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getTrend(): string
    {
        return $this->trend;
    }

    /**
     * @param string $trend
     * @return RunwayInfo
     */
    public function setTrend(string $trend): RunwayInfo
    {
        $this->trend = $trend;
        return $this;
    }

    /**
     * @return int
     */
    public function getMinRange(): int
    {
        return $this->minRange;
    }

    /**
     * @param int $minRange
     * @return RunwayInfo
     */
    public function setMinRange(int $minRange): RunwayInfo
    {
        $this->minRange = $minRange;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxRange(): int
    {
        return $this->maxRange;
    }

    /**
     * @param int $maxRange
     * @return RunwayInfo
     */
    public function setMaxRange(int $maxRange): RunwayInfo
    {
        $this->maxRange = $maxRange;
        return $this;
    }

}