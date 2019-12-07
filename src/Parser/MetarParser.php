<?php
/**
 * @author Jean-Kevin KPADEY
 */

namespace PHPMetarTafParser\Parser;


use PHPMetarTafParser\Command\Common\CommandSupplier;
use PHPMetarTafParser\Command\Metar\MetarCommandSupplier;
use PHPMetarTafParser\Model\AbstractWeatherCode;
use PHPMetarTafParser\Model\Metar;
use PHPMetarTafParser\Model\Trend\MetarTrend;
use PHPMetarTafParser\Model\Trend\MetarTrendTime;

class MetarParser extends AbstractParser
{
    private const TILL = 'TL';
    private const AT = 'AT';
    private const NOSIG = 'NOSIG';
    private const AUTO = 'AUTO';
    /**
     * @var MetarCommandSupplier
     */
    private $supplier;

    /**
     * @param string $code
     * @return Metar
     */
    public function parse(string $code): AbstractWeatherCode
    {
        $metar = new Metar();
        $metarArray = $this->tokenize($code);
        $metar->setIcao($metarArray[0]);
        $metar->setMessage($code);
        $this->parseDeliveryTime($metar, $metarArray[1]);

        $length = count($metarArray);

        for ($i=2; $i < $length; $i++){
            if (!parent::commonParse($metar, $metarArray[$i])){
               if(self::NOSIG == $metarArray[$i]){
                   $metar->setNosig(true);
               } elseif(self::AUTO == $metarArray[$i]){
                   $metar->setAuto(true);
               }elseif(self::RMK == $metarArray[$i]) {
                   $metar->setRemark($this->parseRemark($metarArray,$i));
               } elseif(self::TEMPO==$metarArray[$i] || self::BECMG == $metarArray[$i]){
                    $trend = $this->initTrend($metarArray[$i]);
                    $i = $this->iterateOverTrend($i, $trend, $metarArray);
                    $metar->addTrend($trend);
               } else {
                   $this->executeCommand($metar, $metarArray[$i]);
               }
            }
        }
        return $metar;
    }

    /**
     * MetarParser constructor.
     */
    public function __construct()
    {
        parent::__construct(new CommandSupplier());
        $this->supplier = new MetarCommandSupplier();
    }

    /**
     * Builds the right type of MetarTrend
     * @param string $trendPart
     * @return MetarTrend
     */
    private function initTrend(string $trendPart)
    {
        if($trendPart == self::TEMPO) {
            return new MetarTrend(self::TEMPO);
        }
        return new MetarTrend(self::BECMG);
    }

    /**
     * @param Metar $m The metar to update
     * @param string $input the string to parse
     */
    private function executeCommand(Metar $m, string $input)
    {
        $command = $this->supplier->get($input);
        if ($command != null) {
            $command->execute($m, $input);
        }
    }

    /**
     * Updates the trend with the input
     * @param MetarTrend $trend the trend to update
     * @param string $input the input to parse
     */
    private function updateTrend(MetarTrend $trend, string $input)
    {
        if(substr_compare($input, self::AT,0,2) ==0) {
            $at = new MetarTrendTime(self::AT);
            $at->setTime(substr($input,2,2), substr($input, 4,2));
            $trend->addTime($at);
        }elseif (substr_compare($input, self::FM, 0, 2) == 0) {
            $fm = new MetarTrendTime(self::FM);
            $fm->setTime(substr($input,2,2), substr($input, 4,2));
            $trend->addTime($fm);
        }elseif(substr_compare($input, self::TILL,0,2)==0){
            $tl = new MetarTrendTime(self::TILL);
            $tl->setTime(substr($input,2,2), substr($input, 4,2));
            $trend->addTime($tl);
        } else {
            parent::commonParse($trend, $input);
        }
    }

    /**
     * Method to iterate over a string array and update the trend
     * @param int $index the current index of the metar array being parsed
     * @param MetarTrend $trend the trend to update
     * @param array $metarArray the array of tokens
     * @return int the new index
     */
    private function iterateOverTrend(int $index, MetarTrend $trend, array $metarArray) : int
    {
        $i = $index+1;
        $arraySize = count($metarArray);
        while ($i<$arraySize && self::TEMPO != $metarArray[$i] && self::BECMG != $metarArray[$i]) {
            $this->updateTrend($trend, $metarArray[$i]);
            $i++;
        }
        return $i-1;
    }
}