<?php
/**
 * @author Jean-Kevin KPADEY
 */

namespace PHPMetarTafParser\Model;

/**
 * Class Time
 * @package PHPMetarTafParser\Model
 */
class Time
{
    /**
     * @var int The hour
     */
    private $hours;

    /**
     * @var int the minute
     */
    private $minutes;

    /**
     * @return int
     */
    public function getHours(): int
    {
        return $this->hours;
    }

    /**
     * @param int $hours
     * @return Time
     */
    public function setHours(int $hours): Time
    {
        $this->hours = $hours;
        return $this;
    }

    /**
     * @return int
     */
    public function getMinutes(): int
    {
        return $this->minutes;
    }

    /**
     * @param int $minutes
     * @return Time
     */
    public function setMinutes(int $minutes): Time
    {
        $this->minutes = $minutes;
        return $this;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function __toString()
    {
        return $this->hours.':'.$this->minutes;
    }
}