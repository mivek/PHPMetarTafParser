PHPMetarTafParser

PHPMetarTafParser is a library written in PHP to parse METAR and TAF messages.

The MetarParser and the TAFParser contain a method parse returning a metar object or a TAF object.

#Model

### AbstractWeatherContainer
Abstract class containing the basic elements

- Wind
- Visibility
- Clouds (Cloud[])
- WeatherConditions (WeatherCondition[])
- Remark (string)
- Cavok (bool)
- VerticalVisibility (array)

### AbstractWeatherCode
Subclass of AbstractWeatherContainer. 

- icao (string) ICAO of the airport
- day (int) Delivery day
- time (Time) Delivery time
- message (string) raw message

### Metar
Subclass of AbstractWeatherCode

- temperature (int)
- dewPoint (int)
- altimeter (array) Array with keys `value` and `unit`
- nosig (bool)
- auto (bool)
- runwaysinfo (RunwayInfo[])
- trends (MetarTrend[])

### TAF
Subclass of AbstractWeatherCode

- validity (Validity)
- minTemperature (DatedTemperature)
- maxTemperature (DatedTemperature)
- trends (TAFTrend[])
- probTrends (ProbTafTrend[])
- amendment (bool)

### RunwayInfo
Represents the visibility on a runway

- name (string) Name of the runway
- trend (string)
- minRange (int)
- maxRange (int)

### Cloud

Represents a cloud layer

- Quantity (string)
- Type of cloud (string optional)
- Height of the layer (string optional)

### DatedTemperature

- Temperature in celsius (int)
- Day (int)
- Hour (int)

### Time

Represents the delivery time of a Metar or a TAF

- Hours (int)
- Minutes (int)

### Validity

Represents the validity of a TAF or of a TAFTrend

- startDay (int)
- startHour (int)
- startMinute (int)
- endDay (int)
- endHour (int)

### Visibility

Represents the visibility of a AbstractWeatherContainer

- mainVisibility : array with keys 'visibility' and 'unit'
- minVisibility : array with keys 'visibility' and 'direction'

### Wind

Represents the wind element

- direction (int) The direction of the wind in degrees
- cardinalDirection (string) The cardinal direction or VRB token
- speed (int)
- unit (string) unit of the speed
- gusts (int) speed of the gusts if applicable
- variable_wind (array) array with keys `min` and `max` containing the direction of variable wind if applicable.


### WindShear

Subclass of Wind

- height (int) Height in feet of the windshear.

## Trends

Both METAR and TAF can contain trends

## AbstractTrend
Abstract parent class of trend classes.

- type: string for the type of trend. Either TEMPO, BECMG, FM or PROB

## MetarTrend
Class representing a trend of a metar, composed of

- times: array of MetarTrendTime to represent the times of the trend

## MetarTrendTime

- type (string) AT, FM or TL
- time:  array of `hour` and `minute`

## TafTrend
Subclass of AbstractTrend composed of

- Validity

## ProbTafTrend
Subclass of the TafTrend composed of

- probability (int) probability of the trend

# Examples:

## Parsing a METAR 
```php
$code = "LFBG 081130Z AUTO 23012KT 9999 SCT022 BKN072 BKN090 22/16 Q1011 TEMPO 26015G25KT 3000 TSRA SCT025CB BKN050";
$metarParser = new \PHPMetarTafParser\Parser\MetarParser();
$metar = $metarParser->parse($code);
```
The result metar is:
```
PHPMetarTafParser\Model\Metar Object
(
    [temperature:PHPMetarTafParser\Model\Metar:private] => 22
    [dewPoint:PHPMetarTafParser\Model\Metar:private] => 16
    [altimeter:PHPMetarTafParser\Model\Metar:private] => Array
        (
            [value] => 1011
            [unit] => hPa
        )
    [nosig:PHPMetarTafParser\Model\Metar:private] => 
    [auto:PHPMetarTafParser\Model\Metar:private] => 1
    [runwaysInfo:PHPMetarTafParser\Model\Metar:private] => Array
        (
        )
    [trends:PHPMetarTafParser\Model\Metar:private] => Array
        (
            [0] => PHPMetarTafParser\Model\Trend\MetarTrend Object
                (
                    [times:PHPMetarTafParser\Model\Trend\MetarTrend:private] => Array
                        (
                        )
                    [type:PHPMetarTafParser\Model\Trend\AbstractTrend:private] => TEMPO
                    [wind:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => PHPMetarTafParser\Model\Wind Object
                        (
                            [direction:PHPMetarTafParser\Model\Wind:private] => 260
                            [cardinalDirection:PHPMetarTafParser\Model\Wind:private] => W
                            [speed:PHPMetarTafParser\Model\Wind:private] => 15
                            [unit:PHPMetarTafParser\Model\Wind:private] => KT
                            [gust:PHPMetarTafParser\Model\Wind:private] => 25
                            [variable_wind:PHPMetarTafParser\Model\Wind:private] => 
                        )
                    [visibility:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => PHPMetarTafParser\Model\Visibility Object
                        (
                            [mainVisibility:PHPMetarTafParser\Model\Visibility:private] => Array
                                (
                                    [visibility] => 3000
                                    [unit] => m
                                )
                            [minVisibility:PHPMetarTafParser\Model\Visibility:private] => 
                        )
                    [clouds:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => Array
                        (
                            [0] => PHPMetarTafParser\Model\Cloud Object
                                (
                                    [height:PHPMetarTafParser\Model\Cloud:private] => 2500
                                    [quantity:PHPMetarTafParser\Model\Cloud:private] => SCT
                                    [type:PHPMetarTafParser\Model\Cloud:private] => CB
                                )
                            [1] => PHPMetarTafParser\Model\Cloud Object
                                (
                                    [height:PHPMetarTafParser\Model\Cloud:private] => 5000
                                    [quantity:PHPMetarTafParser\Model\Cloud:private] => BKN
                                    [type:PHPMetarTafParser\Model\Cloud:private] => 
                                )
                        )
                    [weatherConditions:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => Array
                        (
                            [0] => PHPMetarTafParser\Model\WeatherCondition Object
                                (
                                    [intensity:PHPMetarTafParser\Model\WeatherCondition:private] => 
                                    [descriptor:PHPMetarTafParser\Model\WeatherCondition:private] => TS
                                    [phenomenons:PHPMetarTafParser\Model\WeatherCondition:private] => Array
                                        (
                                            [0] => RA
                                        )
                                )
                        )
                    [windShear:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => 
                    [cavok:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => 
                    [remark:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => 
                    [verticalVisibility:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => 
                )
        )
    [icao:PHPMetarTafParser\Model\AbstractWeatherCode:private] => LFBG
    [day:PHPMetarTafParser\Model\AbstractWeatherCode:private] => 8
    [time:PHPMetarTafParser\Model\AbstractWeatherCode:private] => PHPMetarTafParser\Model\Time Object
        (
            [hours:PHPMetarTafParser\Model\Time:private] => 11
            [minutes:PHPMetarTafParser\Model\Time:private] => 30
        )
    [message:PHPMetarTafParser\Model\AbstractWeatherCode:private] => LFBG 081130Z AUTO 23012KT 9999 SCT022 BKN072 BKN090 22/16 Q1011 TEMPO 26015G25KT 3000 TSRA SCT025CB BKN050
    [wind:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => PHPMetarTafParser\Model\Wind Object
        (
            [direction:PHPMetarTafParser\Model\Wind:private] => 230
            [cardinalDirection:PHPMetarTafParser\Model\Wind:private] => SW
            [speed:PHPMetarTafParser\Model\Wind:private] => 12
            [unit:PHPMetarTafParser\Model\Wind:private] => KT
            [gust:PHPMetarTafParser\Model\Wind:private] => 0
            [variable_wind:PHPMetarTafParser\Model\Wind:private] => 
        )
    [visibility:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => PHPMetarTafParser\Model\Visibility Object
        (
            [mainVisibility:PHPMetarTafParser\Model\Visibility:private] => Array
                (
                    [visibility] => 9999
                    [unit] => m
                )
            [minVisibility:PHPMetarTafParser\Model\Visibility:private] => 
        )
    [clouds:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => Array
        (
            [0] => PHPMetarTafParser\Model\Cloud Object
                (
                    [height:PHPMetarTafParser\Model\Cloud:private] => 2200
                    [quantity:PHPMetarTafParser\Model\Cloud:private] => SCT
                    [type:PHPMetarTafParser\Model\Cloud:private] => 
                )
            [1] => PHPMetarTafParser\Model\Cloud Object
                (
                    [height:PHPMetarTafParser\Model\Cloud:private] => 7200
                    [quantity:PHPMetarTafParser\Model\Cloud:private] => BKN
                    [type:PHPMetarTafParser\Model\Cloud:private] => 
                )
            [2] => PHPMetarTafParser\Model\Cloud Object
                (
                    [height:PHPMetarTafParser\Model\Cloud:private] => 9000
                    [quantity:PHPMetarTafParser\Model\Cloud:private] => BKN
                    [type:PHPMetarTafParser\Model\Cloud:private] => 
                )
        )
    [weatherConditions:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => Array
        (
        )
    [windShear:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => 
    [cavok:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => 
    [remark:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => 
    [verticalVisibility:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => 
)
```

## Parsing a TAF
```php
$code = "TAF LSZH 292025Z 2921/3103 VRB03KT 9999 FEW020 BKN080 TX20/3014Z TN06/3003Z " .
        "PROB30 TEMPO 2921/2923 SHRA " .
        "BECMG 3001/3004 4000 MIFG NSC " .
        "PROB40 3003/3007 1500 BCFG SCT004 " .
        "PROB30 3004/3007 0800 FG VV003 " .
        "BECMG 3006/3009 9999 FEW030 " .
        "PROB40 TEMPO 3012/3017 30008KT";

$tafParser = new \PHPMetarTafParser\Parser\TAFParser();
$taf = $tafParser->parse($code);
```
The result object is: 
```
PHPMetarTafParser\Model\TAF Object
(
    [validity:PHPMetarTafParser\Model\TAF:private] => PHPMetarTafParser\Model\Validity Object
        (
            [startDay:PHPMetarTafParser\Model\Validity:private] => 29
            [startHour:PHPMetarTafParser\Model\Validity:private] => 21
            [startMinute:PHPMetarTafParser\Model\Validity:private] => 
            [endDay:PHPMetarTafParser\Model\Validity:private] => 31
            [endHour:PHPMetarTafParser\Model\Validity:private] => 3
        )
    [minTemperature:PHPMetarTafParser\Model\TAF:private] => PHPMetarTafParser\Model\DatedTemperature Object
        (
            [temperature:PHPMetarTafParser\Model\DatedTemperature:private] => 6
            [day:PHPMetarTafParser\Model\DatedTemperature:private] => 30
            [hour:PHPMetarTafParser\Model\DatedTemperature:private] => 3
        )
    [maxTemperature:PHPMetarTafParser\Model\TAF:private] => PHPMetarTafParser\Model\DatedTemperature Object
        (
            [temperature:PHPMetarTafParser\Model\DatedTemperature:private] => 20
            [day:PHPMetarTafParser\Model\DatedTemperature:private] => 30
            [hour:PHPMetarTafParser\Model\DatedTemperature:private] => 14
        )
    [trends:PHPMetarTafParser\Model\TAF:private] => Array
        (
            [0] => PHPMetarTafParser\Model\Trend\TafTrend Object
                (
                    [validity:PHPMetarTafParser\Model\Trend\TafTrend:private] => PHPMetarTafParser\Model\Validity Object
                        (
                            [startDay:PHPMetarTafParser\Model\Validity:private] => 30
                            [startHour:PHPMetarTafParser\Model\Validity:private] => 1
                            [startMinute:PHPMetarTafParser\Model\Validity:private] => 
                            [endDay:PHPMetarTafParser\Model\Validity:private] => 30
                            [endHour:PHPMetarTafParser\Model\Validity:private] => 4
                        )
                    [type:PHPMetarTafParser\Model\Trend\AbstractTrend:private] => BECMG
                    [wind:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => 
                    [visibility:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => PHPMetarTafParser\Model\Visibility Object
                        (
                            [mainVisibility:PHPMetarTafParser\Model\Visibility:private] => Array
                                (
                                    [visibility] => 4000
                                    [unit] => m
                                )
                            [minVisibility:PHPMetarTafParser\Model\Visibility:private] => 
                        )
                    [clouds:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => Array
                        (
                            [0] => PHPMetarTafParser\Model\Cloud Object
                                (
                                    [height:PHPMetarTafParser\Model\Cloud:private] => 0
                                    [quantity:PHPMetarTafParser\Model\Cloud:private] => NSC
                                    [type:PHPMetarTafParser\Model\Cloud:private] => 
                                )
                        )
                    [weatherConditions:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => Array
                        (
                            [0] => PHPMetarTafParser\Model\WeatherCondition Object
                                (
                                    [intensity:PHPMetarTafParser\Model\WeatherCondition:private] => 
                                    [descriptor:PHPMetarTafParser\Model\WeatherCondition:private] => MI
                                    [phenomenons:PHPMetarTafParser\Model\WeatherCondition:private] => Array
                                        (
                                            [0] => FG
                                        )
                                )
                        )
                    [windShear:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => 
                    [cavok:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => 
                    [remark:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => 
                    [verticalVisibility:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => 
                )
            [1] => PHPMetarTafParser\Model\Trend\TafTrend Object
                (
                    [validity:PHPMetarTafParser\Model\Trend\TafTrend:private] => PHPMetarTafParser\Model\Validity Object
                        (
                            [startDay:PHPMetarTafParser\Model\Validity:private] => 30
                            [startHour:PHPMetarTafParser\Model\Validity:private] => 6
                            [startMinute:PHPMetarTafParser\Model\Validity:private] => 
                            [endDay:PHPMetarTafParser\Model\Validity:private] => 30
                            [endHour:PHPMetarTafParser\Model\Validity:private] => 9
                        )
                    [type:PHPMetarTafParser\Model\Trend\AbstractTrend:private] => BECMG
                    [wind:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => 
                    [visibility:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => PHPMetarTafParser\Model\Visibility Object
                        (
                            [mainVisibility:PHPMetarTafParser\Model\Visibility:private] => Array
                                (
                                    [visibility] => 9999
                                    [unit] => m
                                )
                            [minVisibility:PHPMetarTafParser\Model\Visibility:private] => 
                        )
                    [clouds:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => Array
                        (
                            [0] => PHPMetarTafParser\Model\Cloud Object
                                (
                                    [height:PHPMetarTafParser\Model\Cloud:private] => 3000
                                    [quantity:PHPMetarTafParser\Model\Cloud:private] => FEW
                                    [type:PHPMetarTafParser\Model\Cloud:private] => 
                                )
                        )
                    [weatherConditions:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => Array
                        (
                        )
                    [windShear:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => 
                    [cavok:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => 
                    [remark:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => 
                    [verticalVisibility:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => 
                )
        )
    [probTrends:PHPMetarTafParser\Model\TAF:private] => Array
        (
            [0] => PHPMetarTafParser\Model\Trend\ProbTafTrend Object
                (
                    [probability:PHPMetarTafParser\Model\Trend\ProbTafTrend:private] => 30
                    [validity:PHPMetarTafParser\Model\Trend\TafTrend:private] => PHPMetarTafParser\Model\Validity Object
                        (
                            [startDay:PHPMetarTafParser\Model\Validity:private] => 29
                            [startHour:PHPMetarTafParser\Model\Validity:private] => 21
                            [startMinute:PHPMetarTafParser\Model\Validity:private] => 
                            [endDay:PHPMetarTafParser\Model\Validity:private] => 29
                            [endHour:PHPMetarTafParser\Model\Validity:private] => 23
                        )
                    [type:PHPMetarTafParser\Model\Trend\AbstractTrend:private] => TEMPO
                    [wind:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => 
                    [visibility:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => 
                    [clouds:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => Array
                        (
                        )
                    [weatherConditions:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => Array
                        (
                            [0] => PHPMetarTafParser\Model\WeatherCondition Object
                                (
                                    [intensity:PHPMetarTafParser\Model\WeatherCondition:private] => 
                                    [descriptor:PHPMetarTafParser\Model\WeatherCondition:private] => SH
                                    [phenomenons:PHPMetarTafParser\Model\WeatherCondition:private] => Array
                                        (
                                            [0] => RA
                                        )
                                )
                        )
                    [windShear:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => 
                    [cavok:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => 
                    [remark:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => 
                    [verticalVisibility:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => 
                )
            [1] => PHPMetarTafParser\Model\Trend\ProbTafTrend Object
                (
                    [probability:PHPMetarTafParser\Model\Trend\ProbTafTrend:private] => 40
                    [validity:PHPMetarTafParser\Model\Trend\TafTrend:private] => PHPMetarTafParser\Model\Validity Object
                        (
                            [startDay:PHPMetarTafParser\Model\Validity:private] => 30
                            [startHour:PHPMetarTafParser\Model\Validity:private] => 3
                            [startMinute:PHPMetarTafParser\Model\Validity:private] => 
                            [endDay:PHPMetarTafParser\Model\Validity:private] => 30
                            [endHour:PHPMetarTafParser\Model\Validity:private] => 7
                        )
                    [type:PHPMetarTafParser\Model\Trend\AbstractTrend:private] => PROB
                    [wind:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => 
                    [visibility:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => PHPMetarTafParser\Model\Visibility Object
                        (
                            [mainVisibility:PHPMetarTafParser\Model\Visibility:private] => Array
                                (
                                    [visibility] => 1500
                                    [unit] => m
                                )
                            [minVisibility:PHPMetarTafParser\Model\Visibility:private] => 
                        )
                    [clouds:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => Array
                        (
                            [0] => PHPMetarTafParser\Model\Cloud Object
                                (
                                    [height:PHPMetarTafParser\Model\Cloud:private] => 400
                                    [quantity:PHPMetarTafParser\Model\Cloud:private] => SCT
                                    [type:PHPMetarTafParser\Model\Cloud:private] => 
                                )
                        )
                    [weatherConditions:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => Array
                        (
                            [0] => PHPMetarTafParser\Model\WeatherCondition Object
                                (
                                    [intensity:PHPMetarTafParser\Model\WeatherCondition:private] => 
                                    [descriptor:PHPMetarTafParser\Model\WeatherCondition:private] => BC
                                    [phenomenons:PHPMetarTafParser\Model\WeatherCondition:private] => Array
                                        (
                                            [0] => FG
                                        )
                                )
                        )
                    [windShear:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => 
                    [cavok:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => 
                    [remark:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => 
                    [verticalVisibility:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => 
                )
            [2] => PHPMetarTafParser\Model\Trend\ProbTafTrend Object
                (
                    [probability:PHPMetarTafParser\Model\Trend\ProbTafTrend:private] => 30
                    [validity:PHPMetarTafParser\Model\Trend\TafTrend:private] => PHPMetarTafParser\Model\Validity Object
                        (
                            [startDay:PHPMetarTafParser\Model\Validity:private] => 30
                            [startHour:PHPMetarTafParser\Model\Validity:private] => 4
                            [startMinute:PHPMetarTafParser\Model\Validity:private] => 
                            [endDay:PHPMetarTafParser\Model\Validity:private] => 30
                            [endHour:PHPMetarTafParser\Model\Validity:private] => 7
                        )
                    [type:PHPMetarTafParser\Model\Trend\AbstractTrend:private] => PROB
                    [wind:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => 
                    [visibility:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => PHPMetarTafParser\Model\Visibility Object
                        (
                            [mainVisibility:PHPMetarTafParser\Model\Visibility:private] => Array
                                (
                                    [visibility] => 0800
                                    [unit] => m
                                )
                            [minVisibility:PHPMetarTafParser\Model\Visibility:private] => 
                        )
                    [clouds:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => Array
                        (
                        )
                    [weatherConditions:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => Array
                        (
                            [0] => PHPMetarTafParser\Model\WeatherCondition Object
                                (
                                    [intensity:PHPMetarTafParser\Model\WeatherCondition:private] => 
                                    [descriptor:PHPMetarTafParser\Model\WeatherCondition:private] => 
                                    [phenomenons:PHPMetarTafParser\Model\WeatherCondition:private] => Array
                                        (
                                            [0] => FG
                                        )
                                )
                        )
                    [windShear:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => 
                    [cavok:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => 
                    [remark:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => 
                    [verticalVisibility:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => 300
                )
            [3] => PHPMetarTafParser\Model\Trend\ProbTafTrend Object
                (
                    [probability:PHPMetarTafParser\Model\Trend\ProbTafTrend:private] => 40
                    [validity:PHPMetarTafParser\Model\Trend\TafTrend:private] => PHPMetarTafParser\Model\Validity Object
                        (
                            [startDay:PHPMetarTafParser\Model\Validity:private] => 30
                            [startHour:PHPMetarTafParser\Model\Validity:private] => 12
                            [startMinute:PHPMetarTafParser\Model\Validity:private] => 
                            [endDay:PHPMetarTafParser\Model\Validity:private] => 30
                            [endHour:PHPMetarTafParser\Model\Validity:private] => 17
                        )
                    [type:PHPMetarTafParser\Model\Trend\AbstractTrend:private] => TEMPO
                    [wind:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => PHPMetarTafParser\Model\Wind Object
                        (
                            [direction:PHPMetarTafParser\Model\Wind:private] => 300
                            [cardinalDirection:PHPMetarTafParser\Model\Wind:private] => NW
                            [speed:PHPMetarTafParser\Model\Wind:private] => 8
                            [unit:PHPMetarTafParser\Model\Wind:private] => KT
                            [gust:PHPMetarTafParser\Model\Wind:private] => 0
                            [variable_wind:PHPMetarTafParser\Model\Wind:private] => 
                        )
                    [visibility:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => 
                    [clouds:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => Array
                        (
                        )
                    [weatherConditions:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => Array
                        (
                        )
                    [windShear:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => 
                    [cavok:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => 
                    [remark:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => 
                    [verticalVisibility:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => 
                )
        )
    [amendment:PHPMetarTafParser\Model\TAF:private] => 
    [icao:PHPMetarTafParser\Model\AbstractWeatherCode:private] => LSZH
    [day:PHPMetarTafParser\Model\AbstractWeatherCode:private] => 29
    [time:PHPMetarTafParser\Model\AbstractWeatherCode:private] => PHPMetarTafParser\Model\Time Object
        (
            [hours:PHPMetarTafParser\Model\Time:private] => 20
            [minutes:PHPMetarTafParser\Model\Time:private] => 25
        )
    [message:PHPMetarTafParser\Model\AbstractWeatherCode:private] => TAF LSZH 292025Z 2921/3103 VRB03KT 9999 FEW020 BKN080 TX20/3014Z TN06/3003Z PROB30 TEMPO 2921/2923 SHRA BECMG 3001/3004 4000 MIFG NSC PROB40 3003/3007 1500 BCFG SCT004 PROB30 3004/3007 0800 FG VV003 BECMG 3006/3009 9999 FEW030 PROB40 TEMPO 3012/3017 30008KT
    [wind:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => PHPMetarTafParser\Model\Wind Object
        (
            [direction:PHPMetarTafParser\Model\Wind:private] => 
            [cardinalDirection:PHPMetarTafParser\Model\Wind:private] => VRB
            [speed:PHPMetarTafParser\Model\Wind:private] => 3
            [unit:PHPMetarTafParser\Model\Wind:private] => KT
            [gust:PHPMetarTafParser\Model\Wind:private] => 0
            [variable_wind:PHPMetarTafParser\Model\Wind:private] => 
        )
    [visibility:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => PHPMetarTafParser\Model\Visibility Object
        (
            [mainVisibility:PHPMetarTafParser\Model\Visibility:private] => Array
                (
                    [visibility] => 9999
                    [unit] => m
                )
            [minVisibility:PHPMetarTafParser\Model\Visibility:private] => 
        )
    [clouds:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => Array
        (
            [0] => PHPMetarTafParser\Model\Cloud Object
                (
                    [height:PHPMetarTafParser\Model\Cloud:private] => 2000
                    [quantity:PHPMetarTafParser\Model\Cloud:private] => FEW
                    [type:PHPMetarTafParser\Model\Cloud:private] => 
                )
            [1] => PHPMetarTafParser\Model\Cloud Object
                (
                    [height:PHPMetarTafParser\Model\Cloud:private] => 8000
                    [quantity:PHPMetarTafParser\Model\Cloud:private] => BKN
                    [type:PHPMetarTafParser\Model\Cloud:private] => 
                )
        )
    [weatherConditions:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => Array
        (
        )
    [windShear:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => 
    [cavok:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => 
    [remark:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => 
    [verticalVisibility:PHPMetarTafParser\Model\AbstractWeatherContainer:private] => 
)
```