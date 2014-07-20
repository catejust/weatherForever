<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>ForeverWeather</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<?php



    //API query parameters
    $lat = 43.529220;
    $long = -79.653720;
    $authKey = 'e70b41135b5e8fbcee1e3b4a2500c4f8';
    $units='ca'; // All SI units but wind speeds are in km/h and not m/s



    // accept API URL as input parameter, get JSON forecast response, parse JSON into associate array using json_decode
    function getJsonForecast($url)
    {
        $jForecast = file_get_contents($url);
        return json_decode($jForecast, true);
    }


    // build query URL
    $queryURL = ("https://api.forecast.io/forecast/" . $authKey . "/" . $lat . "," . $long . "?units=" . $units);

    //echo $queryURL . "\n" . "\n";

    // call getJsonForecast function to set $forecast as associate array

    $forecast = getJsonForecast($queryURL);


    //set defeault timezone from timezone in API reply
    date_default_timezone_set($forecast[timezone]);



    // find difference in daylighthours between today and tomorrow, round output, return string difference
    // still need to convert decimal portion of minutes to seconds
    function daylightDiff($forecast)
    {
        $dlSecToday = $forecast[daily][data][0][sunsetTime] - $forecast[daily][data][0][sunriseTime];
        $dlSecTomorrow = $forecast[daily][data][1][sunsetTime] - $forecast[daily][data][1][sunriseTime];
        $diff = $dlSecTomorrow - $dlSecToday;

        return ("Today has " . round(abs($diff/60),1) . ($diff < 0 ? " more minutes" : " fewer minutes") . " of daylight than tomorrow." );
    }




?>






<p>
    <? echo "Current day is " .  date('d F Y', $forecast[currently][time]) ?><br />
    <? echo "Current weather conditions are " . strtolower($forecast[currently][summary]) . " and icon = " . $forecast[currently][icon]  ?><br />
    <? echo "It's " . round($forecast[currently][temperature],1) . " ºC and feels like " . round($forecast[currently][apparentTemperature],1) . " ºC" ?><br />
    <? echo "It will be " . strtolower($forecast[minutely][summary])  ?><br /><br /><br />

    <? echo daylightDiff($forecast) ?>

</p>


</body>
</html>