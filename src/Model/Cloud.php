<?php


namespace PHPMetarTafParser\Model;


class Cloud
{
    /**
     * @var integer The height of the cloud layer in feet
     */
    private $height;
    /**
     * @var string|null The quantity token
     */
    private $quantity;

    /**
     * @var string|null The type of cloud
     */
    private $type;

    /**
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * @param int $height
     * @return Cloud
     */
    public function setHeight(int $height): Cloud
    {
        $this->height = $height;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getQuantity(): ?string
    {
        return $this->quantity;
    }

    /**
     * @param string|null $quantity
     * @return Cloud
     */
    public function setQuantity(string $quantity): Cloud
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     * @return Cloud
     */
    public function setType(string $type): Cloud
    {
        $this->type = $type;
        return $this;
    }


}