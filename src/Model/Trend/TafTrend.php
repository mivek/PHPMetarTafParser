<?php


namespace PHPMetarTafParser\Model\Trend;

use PHPMetarTafParser\Model\Validity;

/**
 * Class TafTrend
 * @package PHPMetarTafParser\Model\Trend
 */
class TafTrend extends AbstractTrend
{
    /**
     * @var Validity
     */
    private $validity;

    /**
     * @param Validity $validity
     * @return TafTrend
     */
    public function setValidity(Validity $validity) : TafTrend
    {
        $this->validity = $validity;
        return $this;
    }

    /**
     * @return Validity
     */
    public function getValidity() : Validity
    {
        return $this->validity;
    }


}