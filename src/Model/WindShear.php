<?php


namespace PHPMetarTafParser\Model;


class WindShear extends Wind
{
    /**
     * @var int The height in feet
     */
    private $height;

    /**
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * @param int $height
     * @return WindShear
     */
    public function setHeight(int $height): WindShear
    {
        $this->height = $height;
        return $this;
    }

}