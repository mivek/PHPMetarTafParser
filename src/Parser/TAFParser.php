<?php
/**
 * @author Jean-Kevin KPADEY
 */

namespace PHPMetarTafParser\Parser;


use PHPMetarTafParser\Command\Common\CommandSupplier;
use PHPMetarTafParser\Exception\ErrorCodes;
use PHPMetarTafParser\Exception\ParseException;
use PHPMetarTafParser\Model\AbstractWeatherCode;
use PHPMetarTafParser\Model\DatedTemperature;
use PHPMetarTafParser\Model\Metar;
use PHPMetarTafParser\Model\TAF;
use PHPMetarTafParser\Model\Trend\ProbTafTrend;
use PHPMetarTafParser\Model\Trend\TafTrend;
use PHPMetarTafParser\Model\Validity;
use PHPMetarTafParser\Utils\TemperatureConverter;

class TAFParser extends AbstractParser
{
    private const TAF = 'TAF';
    private const PROB = 'PROB';
    private const TX = 'TX';
    private const TN = 'TN';
    private const REGEX_VALIDITY = '#^\d{4}/\d{4}$#';

    /**
     * Parses a code a returns a TAF or a METAR
     * @param string $code
     * @return TAF | Metar
     * @throws ParseException if the code is not valid
     */
    public function parse(string $code): AbstractWeatherCode
    {
        $lines = $this->extractLineTokens($code);
        if(self::TAF != $lines[0][0]){
            throw new ParseException(ErrorCodes::INVALID_CODE);
        }
        $taf = new TAF();

        // First line
        $line1 = $lines[0];
        $idx = 1;
        if(self::TAF == $line1[1]){
            $idx++;
        }
        if("AMD" == $line1[$idx]){
            $taf->setAmendment(true);
            $idx++;
        }

        $taf->setIcao($line1[$idx]);
        $idx++;
        $taf->setMessage($code);

        $this->parseDeliveryTime($taf, $line1[$idx]);
        $idx++;
        $taf->setValidity($this->parseValidity($line1[$idx]));

        $line1size = count($line1);
        for ($j=$idx; $j < $line1size; $j++) {
            $token = $line1[$j];
            if(self::RMK == $token) {
                $taf->setRemark($this->parseRemark($line1,$j));
            } elseif(substr_compare($token,self::TX,0,2)==0) {
                $taf->setMaxTemperature($this->parseTemperatureDated($token));
            } elseif (substr_compare($token, self::TN, 0,2)==0) {
                $taf->setMinTemperature($this->parseTemperatureDated($token));
            } else {
                $this->commonParse($taf, $token);
            }
        }

        // Other lines
        $linesSize = count($lines);
        for ($i = 1; $i < $linesSize; $i++) {
            $this->parseLines($taf, $lines[$i]);
        }
        return $taf;
    }

    /**
     * @param string $code
     * @return string[][]
     */
    public function extractLineTokens(string $code) : array
    {
        $withoutLinebreaks = preg_replace('#\n#'," ", $code);
        $cleaned = preg_replace('#\s{2,}#'," ", $withoutLinebreaks);
        $line = preg_replace('#\s(PROB\d{2}\sTEMPO|TEMPO|BECMG|FM|PROB)#', "\n$1", $cleaned);
        $lines = explode("\n", $line);

        $linesToken = array_map(array($this,'tokenize'), $lines);

        if (count($linesToken) > 1 ) {
            $last = $linesToken[count($linesToken)-1];
            $temperatures = array_filter($last, function ($code){
                return substr_compare($code, 'TX',0,2) == 0 ||substr_compare($code, 'TN',0,2) == 0;
            });
            if (!empty($temperatures)) {
                $linesToken[0] = array_merge($linesToken[0], $temperatures);
                $linesToken[count($linesToken)-1] = array_filter($last, function ($code){
                   return substr_compare($code, 'TX',0,2) != 0 && substr_compare($code, 'TN', 0,2) != 0;
                });
            }
        }
        return $linesToken;

    }

    /**
     * Parses the validity of a TAF
     * @param string $code the string to parse
     * @return Validity
     */
    private function parseValidity(string $code) : Validity
    {
        $validity = new Validity();
        $validityArray = preg_split('#/#', $code);
        $validity
            ->setStartDay(substr($validityArray[0],0,2))
            ->setStartHour(substr($validityArray[0], 2))
            ->setEndDay(substr($validityArray[1],0,2))
            ->setEndHour(substr($validityArray[1],2));
        return $validity;
    }

    private function parseTemperatureDated(string $code) : DatedTemperature
    {
        $temperatureArray = preg_split('#/#', $code);
        $temperature =new DatedTemperature();
        $temperature->setTemperature(TemperatureConverter::convertTemperature(substr($temperatureArray[0],2)))
            ->setDay(substr($temperatureArray[1], 0,2))
            ->setHour(substr($temperatureArray[1], 2,2));
        return $temperature;
    }

    private function parseLines(TAF $taf, array $line)
    {
        $sizeLine = count($line);
        if ($line[0] == self::BECMG || $line[0] == self::TEMPO) {
           $trend = new TafTrend($line[0]);
           $this->iterateOverTrend(1, $line, $trend);
           $taf->addTrend($trend);
        } elseif(substr_compare($line[0],self::FM,0,2)==0) {
            $trend = new TafTrend('FM');
            $trend->setValidity($this->parseFromValidity($line[0]));
            foreach ($line as $item) {
                $this->commonParse($trend, $item);
            }
            $taf->addTrend($trend);
        } elseif(substr_compare($line[0], self::PROB,0,4)==0) {
            $probability = substr($line[0],4);
            $idx = 1;
            $trend = null;
            if ($sizeLine > 1 && $line[1] == self::TEMPO){
                $trend = new ProbTafTrend(self::TEMPO);
                $idx = 2;
            } else {
                $trend = new ProbTafTrend(self::PROB);
            }
            $trend->setProbability($probability);
            $this->iterateOverTrend($idx, $line, $trend);
            $taf->addProbTrend($trend);
        }
    }

    /**
     * Iterates over an array and updates the trend
     * @param int $idx the starting index of the array
     * @param array $parts the array of string
     * @param TafTrend $trend the trend to update
     */
    private function iterateOverTrend(int $idx, array $parts, TafTrend $trend)
    {
        $size = count($parts);
        for ($i = $idx; $i < $size; $i++){
            if (self::RMK == $parts[$i]){
                $trend->setRemark($this->parseRemark($parts,$i));
            } else {
                $this->parseTrend($trend, $parts[$i]);
            }
        }
    }

    /**
     * Parses a TAF trend
     * @param TafTrend $trend
     * @param string $code
     */
    private function parseTrend(TafTrend $trend, string $code)
    {
        if (preg_match(self::REGEX_VALIDITY, $code)==1) {
            $trend->setValidity($this->parseValidity($code));
        } else {
            $this->commonParse($trend, $code);
        }
    }

    private function parseFromValidity(string $code)
    {
        $validity = new Validity();
        $validity
            ->setStartDay(substr($code,2,2))
            ->setStartHour(substr($code,4,2))
            ->setStartMinute(substr($code,6,2));
        return $validity;
    }

    /**
     * TAFParser constructor.
     */
    public function __construct()
    {
        parent::__construct(new CommandSupplier());
    }
}