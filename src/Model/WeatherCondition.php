<?php


namespace PHPMetarTafParser\Model;


final class WeatherCondition
{
    /**
     * @var string|null
     */
    private $intensity;
    /**
     * @var string|null
     */
    private $descriptor;
    /**
     * @var array
     */
    private $phenomenons;

    public function __construct()
    {
        $this->phenomenons = array();
    }

    /**
     * @param $phenomenon string the phenomenon to add
     */
    public function addPhenomenon(string $phenomenon)
    {
        $this->phenomenons[] = $phenomenon;
    }

    /**
     * @return array The phenomenons
     */
    public function getPhenomenons() : array
    {
        return $this->phenomenons;
    }

    /**
     * @return string|null
     */
    public function getIntensity()
    {
        return $this->intensity;
    }

    /**
     * @return string|null
     */
    public function getDescriptor()
    {
        return $this->descriptor;
    }

    /**
     * @param string $intensity The intensity to set
     * @return WeatherCondition
     */
    public function setIntensity(string $intensity): WeatherCondition
    {
        $this->intensity = $intensity;
        return $this;
    }

    public function setDescriptor(string $descriptive) : WeatherCondition
    {
        $this->descriptor = $descriptive;
        return $this;
    }

}