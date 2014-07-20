


// clearDay = new AnimatedPNG('main-weather-icon', 'images/clearday/Comp 1_00001.png', 59, 50);
// clearDay.draw(false);

var currentTemp = "<?php echo json_encode($currentTempIcon); ?>"; //takes $currentTempIcon value which is filename-1.png
// // alert(animateIcon);
alert(currentTemp);

var clearNight = new AnimatedPNG('tmrw', currentTemp, 50, 50); //insert currentTemp as arg
clearNight.draw(false);


//var compDay = ("Comp 1_00001.png");


//set a variable to the corresponding $phpvariable
//gets filename, accepts id as arg
//loops through, animates the <img> 

// var tmrw = new AnimatedPNG('tmrw', var animateIcon, 59, 50);
// clearNight.draw(false);

// var dayTwo = new AnimatedPNG('tmrw', var animateIcon, 59, 50);
// clearNight.draw(false);

// var dayThree = new AnimatedPNG('tmrw', var animateIcon, 59, 50);
// clearNight.draw(false);

// var dayFour = new AnimatedPNG('tmrw', var animateIcon, 59, 50);
// clearNight.draw(false);

// var dayFive = new AnimatedPNG('tmrw', var animateIcon, 59, 50);
// clearNight.draw(false);

