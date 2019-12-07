<?php


namespace PHPMetarTafParser\Model;

use PHPMetarTafParser\Model\Trend\ProbTafTrend;
use PHPMetarTafParser\Model\Trend\TafTrend;

/**
 * Class TAF
 * @package PHPMetarTafParser\Model
 * @author Jean-Kevin KPADEY
 */
class TAF extends AbstractWeatherCode
{
    /**
     * @var Validity The validity of the TAF.
     */
    private $validity;
    /**
     * @var DatedTemperature
     */
    private $minTemperature;
    /**
     * @var DatedTemperature
     */
    private $maxTemperature;
    /**
     * @var TafTrend[]
     */
    private $trends;
    /**
     * @var ProbTafTrend[] of trends containing probability.
     */
    private $probTrends;
    /**
     * @var boolean Indicates if the TAF is an amendment.
     */
    private $amendment;

    /**
     * Sets the validity of a TAF object.
     * @param Validity The validity to set.
     * @return $this
     */
    public function setValidity(Validity $validity) : TAF
    {
        $this->validity = $validity;
        return $this;
    }

    /**
     * @return Validity The validity
     */
    public function getValidity() : Validity
    {
        return $this->validity;
    }

    /**
     * @param DatedTemperature
     * @return $this
     */
    public function setMinTemperature(DatedTemperature $temperature) : TAF
    {
        $this->minTemperature = $temperature;
        return $this;
    }

    /**
     * @param DatedTemperature
     * @return $this
     */
    public function setMaxTemperature(DatedTemperature $temperature) : TAF
    {
        $this->maxTemperature = $temperature;
        return $this;
    }

    /**
     * @return DatedTemperature The minimum temperature
     */
    public function getMinTemperature()
    {
        return $this->minTemperature;
    }

    /**
     * @return DatedTemperature The maximum temperature
     */
    public function getMaxTemperature()
    {
        return $this->maxTemperature;
    }

    /**
     * @param TafTrend $trend The trend to add
     * @return TAF
     */
    public function addTrend(TafTrend $trend)
    {
        $this->trends[] = $trend;
        return $this;
    }

    /**
     * @param ProbTafTrend $trend The taf trend with probability to add
     * @return TAF
     */
    public function addProbTrend(ProbTafTrend $trend)
    {
        $this->probTrends[] = $trend;
        return $this;
    }

    /**
     * @return TafTrend[]
     */
    public function getTrends()
    {
        return $this->trends;
    }

    /**
     * @return ProbTafTrend[]
     */
    public function getProbTrends()
    {
        return $this->probTrends;
    }

    /**
     * @return bool
     */
    public function isAmendment(): bool
    {
        return $this->amendment;
    }

    /**
     * @param bool $amendment
     * @return TAF
     */
    public function setAmendment(bool $amendment): TAF
    {
        $this->amendment = $amendment;
        return $this;
    }


    /**
     * TAF constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->probTrends = array();
        $this->trends = array();
    }
}