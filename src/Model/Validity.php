<?php
/**
 * @author Jean-Kevin KPADEY
 */

namespace PHPMetarTafParser\Model;

/**
 * Class Validity
 * @package PHPMetarTafParser\Model
 * Class representing the validity of a TAF.
 */
class Validity
{
    /**
     * @var int The start day of the validity
     */
    private $startDay;
    /**
     * @var int The start hour of the validity.
     */
    private $startHour;
    /**
     * @var int The start minute of the validity.
     */
    private $startMinute;
    /**
     * @var int The end day of the validity.
     */
    private $endDay;
    /**
     * @var int The end hour of the validity.
     */
    private $endHour;

    /**
     * @return int
     */
    public function getStartDay(): int
    {
        return $this->startDay;
    }

    /**
     * @param int $startDay
     * @return Validity
     */
    public function setStartDay(int $startDay): Validity
    {
        $this->startDay = $startDay;
        return $this;
    }

    /**
     * @return int
     */
    public function getStartHour(): int
    {
        return $this->startHour;
    }

    /**
     * @param int $startHour
     * @return Validity
     */
    public function setStartHour(int $startHour): Validity
    {
        $this->startHour = $startHour;
        return $this;
    }

    /**
     * @return int
     */
    public function getEndDay(): ?int
    {
        return $this->endDay;
    }

    /**
     * @param int $endDay
     * @return Validity
     */
    public function setEndDay(int $endDay): Validity
    {
        $this->endDay = $endDay;
        return $this;
    }

    /**
     * @return int
     */
    public function getEndHour(): ?int
    {
        return $this->endHour;
    }

    /**
     * @param int $endHour
     * @return Validity
     */
    public function setEndHour(int $endHour): Validity
    {
        $this->endHour = $endHour;
        return $this;
    }

    /**
     * @return int
     */
    public function getStartMinute(): ?int
    {
        return $this->startMinute;
    }

    /**
     * @param int $startMinute
     * @return Validity
     */
    public function setStartMinute(int $startMinute): Validity
    {
        $this->startMinute = $startMinute;
        return $this;
    }

}