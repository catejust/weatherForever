<?php 
date_default_timezone_set('America/Toronto');
// $timestamp=1333699439;
// echo date("D", $timestamp);

// $weather = array(

// 	'clear-day' => 'images\\'

// 	);


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

//	// $currentTempIcon = "images/clear-night-1.png";
    $currentTempIcon = strtolower($forecast[currently][icon]); //gives images/partly-cloudy-day
        


        // adding in the "images/" part was unconditionally adding 7 characters to every icon, forcing it to always use the 'else' condition :P
    	if (strlen($currentTempIcon) <= 6) {

            // logic was reversed here -- if current time is less than sunrise time or current time is greater than sunset time
    		if ($forecast[currently][time] > $forecast[daily][data][0][sunriseTime] && $forecast[currently][time] < $forecast[daily][data][0][sunsetTime]) {

    			$currentTempIcon = "images/" . $currentTempIcon . "-day" . "_1".'.png'; //adds -day-1.png

    		} else {
    			$currentTempIcon = "images/" . $currentTempIcon . "-night" . "_1".'.png'; //adds -night-1.png
    		}


    	} else {

    		$currentTempIcon = "images/" . $currentTempIcon . "_1". '.png'; //only adds -1.png
    	}
//


    
//
//
		// echo $currentTempIcon;
//		echo $weekdayForecasts[1][icon];


//    	echo count($forecast[daily][data]);
//
//    	for ($i=0; $i<=4; $i++) {
//    		$iconImage = "images/" . $forecast[daily][data][$i][icon]. ".png";
//    	}


    $weekdayForecasts = array();
/*	$weekdayForecasts[0] = array(
	    "day" => "Tmrw",
	    "icon" => "images/" . $forecast[daily][data][0][icon]. "-1" . ".png",
	    "temperatureMax" => $forecast[daily][data][0][temperatureMax],
	    "temperatureMin" => $forecast[daily][data][0][temperatureMin],
	    "summary" => $forecast[daily][data][0][summary]

	);
	$weekdayForecasts[1] = array(
	    "day" => date('D',time()+172800),
	    "icon" => "images/" . $forecast[daily][data][1][icon]. "-1" . ".png",
	    "temperatureMax" => $forecast[daily][data][1][temperatureMax],
	    "temperatureMin" => $forecast[daily][data][1][temperatureMin],
	    "summary" => $forecast[daily][data][1][summary] 
	);
	$weekdayForecasts[2] = array(
	    "day" => date('D',time()+259200),
	    "icon" => "images/" . $forecast[daily][data][2][icon]. "-1" . ".png",
	    "temperatureMax" => $forecast[daily][data][2][temperatureMax],
	    "temperatureMin" => $forecast[daily][data][2][temperatureMin],
	    "summary" => $forecast[daily][data][0][summary] 
	);
	$weekdayForecasts[3] = array(
	    "day" => date('D',time()+345600),
	    "icon" => "images/" . $forecast[daily][data][3][icon]. "-1" . ".png",
	    "temperatureMax" => $forecast[daily][data][3][temperatureMax],
	    "temperatureMin" => $forecast[daily][data][3][temperatureMin],
	    "summary" => $forecast[daily][data][0][summary] 
	);
	$weekdayForecasts[4] = array(
	    "day" => date('D',time()+432000),
	    "icon" => "images/" . $forecast[daily][data][4][icon]. "-1" . ".png",    
	    "temperatureMax" => $forecast[daily][data][4][temperatureMax],
	    "temperatureMin" => $forecast[daily][data][4][temperatureMin],
	    "summary" => $forecast[daily][data][0][summary] 
	);*/

    for ($i=1; $i<6; $i++)

    {

    	$weeklyTempIcon = strtolower( $forecast[daily][data][$i][icon]); //gives images/partly-cloudy-day
        


        // adding in the "images/" part was unconditionally adding 7 characters to every icon, forcing it to always use the 'else' condition :P
    	if (strlen($weeklyTempIcon) <= 6) {

            // logic was reversed here -- if current time is less than sunrise time or current time is greater than sunset time
    		if ($forecast[daily][data][$i][sunriseTime] > $forecast[currently][time] && $forecast[currently][time] < $forecast[daily][data][$i][sunsetTime]) {

    			$weeklyTempIcon = "images/" . $weeklyTempIcon . "-day" . "_1".'.png'; //adds -day-1.png

    		} else {
    			$weeklyTempIcon = "images/" . $weeklyTempIcon . "-night" . "_1".'.png'; //adds -night-1.png
    		}


    	} else {

    		$weeklyTempIcon = "images/" . $weeklyTempIcon . "_1". '.png'; //only adds -1.png
    	}


        $weekdayForecasts[$i] = array(
            "day" => ($i == 1 ? "tmrw" : date('D',$forecast[daily][data][$i][temperatureMaxTime]) ),
            "id" => "day" . $i . "Icon",
            "icon" => $weeklyTempIcon ,
            "temperatureMax" => $forecast[daily][data][$i][temperatureMax],
            "temperatureMin" => $forecast[daily][data][$i][temperatureMin],
            "summary" => $forecast[daily][data][$i][summary]
        );
    }

  



?>



<!DOCTYPE html>
<html>
<head>
	<title>Weather Forever</title>
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link href='http://fonts.googleapis.com/css?family=PT+Sans+Narrow:300,600|Roboto+Condensed:400,300|Roboto:200' rel='stylesheet' type='text/css'>

	

</head>
<body>


<nav>
	<div class="container">

		<form method="get" action="/search" id="search">
		  <input name="q" type="text" size="40" placeholder="location.." />
		</form>


		<div class="onoffswitch">
		    <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch" checked>
		    <label class="onoffswitch-label" for="myonoffswitch">
		        <span class="onoffswitch-inner"></span>
		        <span class="onoffswitch-switch"></span>
		    </label>
		</div>

		<aside id="todays-date">
			<p>MISSISSAUGA, ON</p>
			<h3><?php echo strtoupper(date('d F Y', $forecast[currently][time]));  ?></h3>
		</aside>
	</div>
</nav>


<section id="daily-forecast">
	<div class="container">
		 <!-- echo "images\/" . $currentTempIcon; -->
		
		<h2 id="weather-condition"><?php echo strtolower($forecast[currently][summary]); ?></h2>
		<img id="current" class="main-weather-icon">


		<h1 id="daily-temp"><?php echo round($forecast[currently][temperature],1); ?><sup class="sup-celcius">&deg;C</sup></h1>
		<div class="summary-container">
			<p id="right-now">RIGHT NOW</p>
			<article id="daily-summary"><?php echo "It will be " . strtolower($forecast[minutely][summary]); ?></article>
		</div>
</section>

<section id="current-temp-time">
	<div class-"container">
		<h3>Feels like <?php echo round($forecast[currently][apparentTemperature],1) . "ºC"; ?></h3>
		<p><?php echo daylightDiff($forecast); ?></p>
	</div>
</section>

<section id="weekly-forecast">
	<div class="container">


		<ul id="week-temps">
				<?php foreach($weekdayForecasts as $weekdayForecast){ 
						echo '<li>';
							echo '<h3 class="weekdays">'. strtoupper($weekdayForecast["day"]) .'</h3>';
							echo '<img  id="'. $weekdayForecast["id"]  .'" class="small-weather-icon">';
							echo '<h2>'. round($weekdayForecast["temperatureMax"],0);
								echo '<sup class="p-style">' . "&deg;" . '</sup>';
							echo '</h2>';
							echo '<div class="low-high">';
								echo '<p class="high-temp">' . round($weekdayForecast["temperatureMax"],0) . '</p>';
								echo '<p class="low-temp">' . round($weekdayForecast["temperatureMin"],0) .'</p>';
							echo '</div>';
							echo '<p class="summary">' . $weekdayForecast["summary"]  .'</p>';
						echo '</li>';
					}
				?>
		</ul>	




	</div>
</section>

	<script type="text/javascript" src="js/animatedpng.js"></script>
	
	<script type="text/javascript">

		var currentTemp = "<?php echo $currentTempIcon; ?>"; //takes $currentTempIcon value which is filename-1.png
		var clearNight = new AnimatedPNG('current', currentTemp, 59, 50); //insert currentTemp as source argument, insert 'tmrw' as id argument
		clearNight.draw(false);
		

		var tmrwTemp = "<?php echo $weekdayForecasts[1][icon]; ?>"; 
			//This works, when used without quotations. Also only recognizes index # using the array index weekdayForecasts[i], and not weekdayForecast[i]
		var tmrwIcon = new AnimatedPNG('day1Icon', tmrwTemp, 59, 50);
		tmrwIcon.draw(false);


		var dayTwoTemp = "<?php echo $weekdayForecasts[2][icon]; ?>";
		var dayTwoIcon = new AnimatedPNG('day2Icon', dayTwoTemp, 59, 50);
		dayTwoIcon.draw(false);

		var dayThreeTemp = "<?php echo $weekdayForecasts[3][icon]; ?>";
		var dayThreeIcon = new AnimatedPNG('day3Icon', dayThreeTemp, 59, 50);
		dayThreeIcon.draw(false);

		var dayFourTemp = "<?php echo $weekdayForecasts[4][icon]; ?>";
		var dayFourIcon = new AnimatedPNG('day4Icon', dayFourTemp, 59, 50);
		dayFourIcon.draw(false);

		var dayFiveTemp = "<?php echo $weekdayForecasts[5][icon]; ?>";
		var dayFiveIcon = new AnimatedPNG('day5Icon', dayFiveTemp, 59, 50);
		dayFiveIcon.draw(false);

	</script>

</body>

</html>