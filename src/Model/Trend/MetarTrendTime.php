<?php


namespace PHPMetarTafParser\Model\Trend;


class MetarTrendTime
{
    /**
     * @var string The type of trend AT, FM, TL.
     */
    private $type;
    /**
     * @var array time of the trend.
     */
    private $time;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public function getType() : string
    {
        return $this->type;
    }

    /**
     * @param int $hour
     * @param int $minute
     * @return MetarTrendTime
     */
    public function setTime(int $hour, int $minute) : MetarTrendTime
    {
        $this->time = array("hour" => $hour, "minute" => $minute);
        return $this;
    }

    /**
     * @return array the time of the trend keys: hour and minute
     */
    public function getTime() : array
    {
        return $this->time;
    }
}