<?php


namespace PHPMetarTafParser\Model;

use PHPMetarTafParser\Model\Trend\MetarTrend;

/**
 * Class Metar
 * @package PHPMetarTafParser\Model
 * @author Jean-Kevin KPADEY
 */
class Metar extends AbstractWeatherCode
{
    /**
     * @var int
     */
    private $temperature;
    /**
     * @var int
     */
    private $dewPoint;
    /**
     * @var array
     */
    private $altimeter;
    /**
     * @var bool
     */
    private $nosig;
    /**
     * @var bool
     */
    private $auto;
    /**
     * @var RunwayInfo[]
     */
    private $runwaysInfo;
    /**
     * @var MetarTrend[]
     */
    private $trends;

    /**
     * @return int
     */
    public function getTemperature(): int
    {
        return $this->temperature;
    }

    /**
     * @param int $temperature
     * @return Metar
     */
    public function setTemperature(int $temperature): Metar
    {
        $this->temperature = $temperature;
        return $this;
    }

    /**
     * @return int
     */
    public function getDewPoint(): int
    {
        return $this->dewPoint;
    }

    /**
     * @param int $dewPoint
     * @return Metar
     */
    public function setDewPoint(int $dewPoint): Metar
    {
        $this->dewPoint = $dewPoint;
        return $this;
    }

    /**
     * @return array
     */
    public function getAltimeter(): array
    {
        return $this->altimeter;
    }

    /**
     * @param float $value the value of the altimeter
     * @param string $unit The unit
     * @return Metar
     */
    public function setAltimeter(float $value, string $unit): Metar
    {
        $this->altimeter = array('value'=> $value, 'unit' => $unit);
        return $this;
    }

    /**
     * @return bool
     */
    public function isNosig(): bool
    {
        return $this->nosig;
    }

    /**
     * @param bool $nosig
     * @return Metar
     */
    public function setNosig(bool $nosig): Metar
    {
        $this->nosig = $nosig;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAuto(): bool
    {
        return $this->auto;
    }

    /**
     * @param bool $auto
     * @return Metar
     */
    public function setAuto(bool $auto): Metar
    {
        $this->auto = $auto;
        return $this;
    }

    /**
     * @return RunwayInfo[]
     */
    public function getRunwaysInfo(): array
    {
        return $this->runwaysInfo;
    }

    /**
     * @param RunwayInfo $runwayInfo
     * @return Metar
     */
    public function addRunwayInfo(RunwayInfo $runwayInfo) : Metar
    {
        $this->runwaysInfo[] = $runwayInfo;
        return $this;
    }

    /**
     * @param MetarTrend $trend
     * @return Metar
     */
    public function addTrend(MetarTrend $trend) : Metar
    {
        $this->trends[] = $trend;
        return $this;
    }

    /**
     * @return MetarTrend[]
     */
    public function getTrends() : array
    {
        return $this->trends;
    }

    /**
     * Metar constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->runwaysInfo = array();
        $this->trends = array();
    }


}