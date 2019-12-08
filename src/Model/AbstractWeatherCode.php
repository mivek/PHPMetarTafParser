<?php


namespace PHPMetarTafParser\Model;


abstract class AbstractWeatherCode extends AbstractWeatherContainer
{
    /**
     * @var string The icao of the airport
     */
    private $icao;
    /**
     * @var int The delivery day
     */
    private $day;
    /**
     * @var Time The delivery time
     */
    private $time;
    /**
     * @var string the raw message
     */
    private $message;

    /**
     * @return string
     */
    public function getIcao(): string
    {
        return $this->icao;
    }

    /**
     * @param string $icao
     * @return AbstractWeatherCode
     */
    public function setIcao(string $icao): AbstractWeatherCode
    {
        $this->icao = $icao;
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
     * @return AbstractWeatherCode
     */
    public function setDay(int $day): AbstractWeatherCode
    {
        $this->day = $day;
        return $this;
    }

    /**
     * @return Time
     */
    public function getTime(): Time
    {
        return $this->time;
    }

    /**
     * @param Time $time
     * @return AbstractWeatherCode
     */
    public function setTime(Time $time): AbstractWeatherCode
    {
        $this->time = $time;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return AbstractWeatherCode
     */
    public function setMessage(string $message): AbstractWeatherCode
    {
        $this->message = $message;
        return $this;
    }
}