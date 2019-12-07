<?php


namespace PHPMetarTafParser\Model;


class Wind
{
    /**
     * @var int The direction in degrees.
     */
    private $direction;
    /**
     * @var string the cardinal direction
     */
    private $cardinalDirection;
    /**
     * @var int The wind speed.
     */
    private $speed;
    /**
     * @var string The unit of the wind speed
     */
    private $unit;
    /**
     * @var int the gusts speed
     */
    private $gust;
    /**
     * @var array The minimum and maximum wind direction variation.
     */
    private $variable_wind;

    /**
     * @return int
     */
    public function getDirection(): ?int
    {
        return $this->direction;
    }

    /**
     * @param int $direction
     * @return Wind
     */
    public function setDirection(int $direction): Wind
    {
        $this->direction = $direction;
        return $this;
    }

    /**
     * @return int
     */
    public function getSpeed(): int
    {
        return $this->speed;
    }

    /**
     * @param int $speed
     * @return Wind
     */
    public function setSpeed(int $speed): Wind
    {
        $this->speed = $speed;
        return $this;
    }

    /**
     * @return string
     */
    public function getUnit(): string
    {
        return $this->unit;
    }

    /**
     * @param string $unit
     * @return Wind
     */
    public function setUnit(string $unit): Wind
    {
        $this->unit = $unit;
        return $this;
    }

    /**
     * @return int
     */
    public function getGust(): int
    {
        return $this->gust;
    }

    /**
     * @param int $gust
     * @return Wind
     */
    public function setGust(int $gust): Wind
    {
        $this->gust = $gust;
        return $this;
    }

    /**
     * @return array
     */
    public function getVariableWind(): array
    {
        return $this->variable_wind;
    }

    /**
     * @param int $minDirectionVariation The minimum direction variation
     * @param int $maxDirectionVariation The maximum direction variation
     * @return Wind
     */
    public function setVariableWind(int $minDirectionVariation, int $maxDirectionVariation): Wind
    {
        $this->variable_wind = array('min' => $minDirectionVariation, 'max' => $maxDirectionVariation);
        return $this;
    }

    /**
     * @return bool true if the wind has gusts
     */
    public function hasGusts() : bool
    {
        return $this->gust != null;
    }

    /**
     * @return bool true if the wind has variation
     */
    public function hasVariableWind() : bool
    {
        return $this->variable_wind != null;
    }

    /**
     * @return string
     */
    public function getCardinalDirection(): string
    {
        return $this->cardinalDirection;
    }

    /**
     * @param string $cardinalDirection
     * @return Wind
     */
    public function setCardinalDirection(string $cardinalDirection): Wind
    {
        $this->cardinalDirection = $cardinalDirection;
        return $this;
    }


}