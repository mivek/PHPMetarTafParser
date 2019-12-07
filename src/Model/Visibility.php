<?php


namespace PHPMetarTafParser\Model;


class Visibility
{
    /**
     * @var array Holds the visibility range and the unit.
     */
    private $mainVisibility;

    /**
     * @var array|null Holds the minimum visibility
     */
    private $minVisibility;
    /**
     * @param string $visibility
     * @param string $unit
     * @return Visibility
     */
    public function setMainVisibility(string $visibility, string $unit) : Visibility
    {
        $this->mainVisibility = array("visibility" => $visibility, "unit"=>$unit);
        return $this;
    }

    /**
     * @param int $visibility
     * @param string $direction
     * @return Visibility
     */
    public function setMinVisibility(int $visibility, string $direction) : Visibility
    {
        $this->minVisibility = array("visibility" => intval($visibility), "direction" => $direction);
        return $this;
    }

    /**
     * @return array contining keys visibility and unit
     */
    public function getMainVisibility() : array
    {
        return $this->mainVisibility;
    }

    public function hasMinVisibility() : bool
    {
        return $this->minVisibility != null;
    }

    public function getMinVisibility() : array
    {
        return $this->minVisibility;
    }
}