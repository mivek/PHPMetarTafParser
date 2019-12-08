<?php
/**
 * @author Jean-Kevin KPADEY
 */

namespace PHPMetarTafParser\Parser;


use PHPMetarTafParser\Command\Common\CommandSupplier;
use PHPMetarTafParser\Model\AbstractWeatherCode;
use PHPMetarTafParser\Model\AbstractWeatherContainer;
use PHPMetarTafParser\Model\Descriptor;
use PHPMetarTafParser\Model\Metar;
use PHPMetarTafParser\Model\Phenomenon;
use PHPMetarTafParser\Model\TAF;
use PHPMetarTafParser\Model\Time;
use PHPMetarTafParser\Model\Visibility;
use PHPMetarTafParser\Model\WeatherCondition;

abstract class AbstractParser
{
    protected const FM = 'FM';
    protected const BECMG = 'BECMG';
    protected const RMK = 'RMK';
    protected const TEMPO = 'TEMPO';

    private const TOKENIZE_REGEX = '#\s((?=\d/\dSM)(?<!\s\d\s)|(?!\d/\dSM))|=\z#';
    private const INTENSITY_REGEX = '#^(-|\+|VC)#';
    private const CAVOK = 'CAVOK';

    /**
     * @var CommandSupplier
     */
    private $commandSupplier;

    /**
     * Parse the string into a weather condition
     * @param string $weatherPart
     * @return WeatherCondition|null
     */
    public function parseWeatherCondition(string $weatherPart): ?WeatherCondition
    {
        if(self::TEMPO == $weatherPart){
            return null;
        }

        preg_match(self::INTENSITY_REGEX, $weatherPart, $matches);
        $wc = new WeatherCondition();
        if(!empty($matches)) {
            $wc->setIntensity($matches[1]);
        }

        foreach (Descriptor::VALUES as $descriptor) {
            if(!empty(preg_grep('#('.$descriptor.')#', explode('\n', $weatherPart)))) {
                $wc->setDescriptor($descriptor);
            }
        }

        foreach (Phenomenon::PHENOMENONS as $phenomenon) {
            if(!empty(preg_grep('#('.$phenomenon.')#', explode('\n', $weatherPart)))) {
                $wc->addPhenomenon($phenomenon);
            }
        }

        if(!empty($wc->getPhenomenons()) || $wc->getDescriptor() !=null) {
            return $wc;
        }
        return null;
    }

    /**
     * @param AbstractWeatherCode $abstractWeatherCode
     * @param string $str_time
     */
    public function parseDeliveryTime(AbstractWeatherCode $abstractWeatherCode, string $str_time)
    {
        $abstractWeatherCode->setDay(substr($str_time, 0,2));
        $time = new Time();
        $time->setHours(substr($str_time,2, 2));
        $time->setMinutes(substr($str_time,4, 2));
        $abstractWeatherCode->setTime($time);
    }

    /**
     * @param string $code
     * @return string[]
     */
    public function tokenize(string $code)
    {
        return preg_split(self::TOKENIZE_REGEX, $code);
    }

    /**
     * Parses method common to AbstractWeatherContainers
     * @param AbstractWeatherContainer $container
     * @param string $code
     * @return bool true if this method manages to parse the code.
     */
    public function commonParse(AbstractWeatherContainer $container, string $code) : bool
    {
        $isParsed=true;
        $command = $this->commandSupplier->get($code);
        if (self::CAVOK == $code) {
            $container->setCavok(true);
            if($container->getVisibility() == null) {
                $container->setVisibility(new Visibility());
            }
            $container->getVisibility()->setMainVisibility(9999,'m');
        } elseif($command) {
            $command->execute($container, $code);
        } else {
            $wc = $this->parseWeatherCondition($code);
            if($wc == null) {
                $isParsed = false;
            } else {
                $container->addWeatherCondition($wc);
            }
        }
        return $isParsed;

    }
    /**
     * Parses a code a returns a TAF or a METAR
     * @param string $code
     * @return TAF | Metar
     */
    public abstract function parse(string $code) : AbstractWeatherCode;

    /**
     * AbstractParser constructor.
     * @param CommandSupplier $commandSupplier
     */
    public function __construct(CommandSupplier $commandSupplier)
    {
        $this->commandSupplier = $commandSupplier;
    }

    /**
     * Builds a string of the remark.
     * @param array $rmkArray
     * @param int $index index of the RMK token
     * @return string
     */
    protected function parseRemark(array $rmkArray, int $index) : string
    {
        return implode(' ', array_slice($rmkArray, $index+1));
    }
}