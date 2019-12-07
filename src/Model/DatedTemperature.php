<?php
/**
 * @author Jean-Kevin KPADEY
 */

namespace PHPMetarTafParser\Model;

/**
 * Class DatedTemperature
 * @package PHPMetarTafParser\Model
 * Temperature with a date
 */
class DatedTemperature
{
    /**
     * @var int The value of the temperature in Celsius
     */
    private $temperature;
    /**
     * @var int The day
     */
    private $day;
    /**
     * @var int The hour.
     */
    private $hour;

    /**
     * @return int
     */
    public function getTemperature(): int
    {
        return $this->temperature;
    }

    /**
     * @param int $temperature
     * @return DatedTemperature
     */
    public function setTemperature(int $temperature): DatedTemperature
    {
        $this->temperature = $temperature;
        return $this;
    }

    /**
     * @return int
     */
    public function getDay(): int
    {
        return $this->day;
    }

    /**
     * @param int $day
     * @return DatedTemperature
     */
    public function setDay(int $day): DatedTemperature
    {
        $this->day = $day;
        return $this;
    }

    /**
     * @return int
     */
    public function getHour(): int
    {
        return $this->hour;
    }

    /**
     * @param int $hour
     * @return DatedTemperature
     */
    public function setHour(int $hour): DatedTemperature
    {
        $this->hour = $hour;
        return $this;
    }

}