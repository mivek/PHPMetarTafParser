<?php


namespace PHPMetarTafParser\Model;


abstract class AbstractWeatherContainer
{
    /**
     * @var Wind
     */
    private $wind;
    /**
     * @var Visibility
     */
    private $visibility;
    /**
     * @var array of clouds
     */
    private $clouds;
    /**
     * @var array of weather conditions
     */
    private $weatherConditions;
    /**
     * @var WindShear|null
     */
    private $windShear;
    /**
     * @var bool
     */
    private $cavok;
    /**
     * @var string|null
     */
    private $remark;
    /**
     * @var int
     */
    private $verticalVisibility;

    public function __construct()
    {
        $this->weatherConditions = array();
        $this->clouds = array();
    }

    /**
     * @return Wind|null
     */
    public function getWind() : ?Wind
    {
        return $this->wind;
    }

    /**
     * @param Wind $wind
     * @return $this
     */
    public function setWind(Wind $wind)
    {
        $this->wind = $wind;
        return $this;
    }

    /**
     * @return Visibility
     */
    public function getVisibility(): ?Visibility
    {
        return $this->visibility;
    }

    /**
     * @param Visibility $visibility
     * @return AbstractWeatherContainer
     */
    public function setVisibility(Visibility $visibility): AbstractWeatherContainer
    {
        $this->visibility = $visibility;
        return $this;
    }

    /**
     * @return Cloud[]
     */
    public function getClouds(): array
    {
        return $this->clouds;
    }

    /**
     * Adds a cloud.
     * @param Cloud $cloud to add.
     * @return AbstractWeatherContainer
     */
    public function addCloud(Cloud $cloud): AbstractWeatherContainer
    {
        $this->clouds[] = $cloud;
        return $this;
    }

    /**
     * @return WeatherCondition[]
     */
    public function getWeatherConditions(): array
    {
        return $this->weatherConditions;
    }

    /**
     * @param WeatherCondition $wc
     * @return AbstractWeatherContainer
     */
    public function addWeatherCondition(WeatherCondition $wc) : AbstractWeatherContainer
    {
        $this->weatherConditions[] = $wc;
        return $this;
    }

    /**
     * @return WindShear
     */
    public function getWindShear(): ?WindShear
    {
        return $this->windShear;
    }

    /**
     * @param WindShear $windShear
     * @return AbstractWeatherContainer
     */
    public function setWindShear(WindShear $windShear): AbstractWeatherContainer
    {
        $this->windShear = $windShear;
        return $this;
    }

    /**
     * @return bool
     */
    public function isCavok(): bool
    {
        return $this->cavok;
    }

    /**
     * @param bool $cavok
     * @return AbstractWeatherContainer
     */
    public function setCavok(bool $cavok): AbstractWeatherContainer
    {
        $this->cavok = $cavok;
        return $this;
    }

    /**
     * @return string
     */
    public function getRemark(): ?string
    {
        return $this->remark;
    }

    /**
     * @param string $remark
     * @return AbstractWeatherContainer
     */
    public function setRemark(string $remark): AbstractWeatherContainer
    {
        $this->remark = $remark;
        return $this;
    }

    /**
     * @return int
     */
    public function getVerticalVisibility(): int
    {
        return $this->verticalVisibility;
    }

    /**
     * @param int $verticalVisibility
     * @return AbstractWeatherContainer
     */
    public function setVerticalVisibility(int $verticalVisibility): AbstractWeatherContainer
    {
        $this->verticalVisibility = $verticalVisibility;
        return $this;
    }


}