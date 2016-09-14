<?php

    $weather = "";
    $error = "";
    $city = "";

    if($_GET && $_GET['city']){
        
        $openWeatherMapAppID = file_get_contents("openweathermapkey.txt");
        $urlContents = file_get_contents("http://api.openweathermap.org/data/2.5/weather?q=". urlencode($_GET['city']) . "&appid=" . $openWeatherMapAppID);
        $weatherArray = json_decode($urlContents,true);
        
        if($weatherArray['cod'] == 200){
            
            // Get first description
            $weather0Description = $weatherArray['weather'][0]['description'];
            $weather1Description = "";
            // Get second description if one exists
            if(array_key_exists(1, $weatherArray['weather'])){
                $weather1Description = $weatherArray['weather'][1]['description'];    
            }
            
            // Convert temperature from Kevlin -> Farenheit
            $tempInFarenheit = intval((($weatherArray['main']['temp'] - 273) * 1.8) + 32);
            $minTempInFarenheit = intval((($weatherArray['main']['temp_min'] - 273) * 1.8) + 32);
            $maxTempInFarenheit = intval((($weatherArray['main']['temp_max'] - 273) * 1.8) + 32);
            
            // Get wind speed
            $windSpeed = $weatherArray['wind']['speed'];
            
            // Get humidity
            $humidity = $weatherArray['main']['humidity'];
            
            $weather = "The weather in " . $_GET['city'] . " is currently '" . $weather0Description . " " . $weather1Description . "' ";
            $weather .= " with a current temperature of " . $tempInFarenheit . "&deg;f. Low of: " . $minTempInFarenheit . "&deg;f  and High of: " . $maxTempInFarenheit;
            $weather .= ", wind speed of " . $windSpeed . "m/s, and " . $humidity . "% humidity";    
        }
        else {
            $error = "Could not find city: " . $_GET['city'] . "- please try again.";
        }
        
        
        // Crappy web scraping method
//        $exists = false;
//        
//        $city = str_replace(' ', '', $_GET['city']);
//        
//        // check if url exists
//        //$forecastPage = file_get_contents("http://www.weather-forecast.com/locations/" .$city. "/forecasts/latest");
//        $forecastPage = 'http://www.weather-forecast.com/locations/' . $city . '/forecasts/latest';
//        $file_headers = @get_headers($forecastPage);
//        if($file_headers[0] == 'HTTP/1.1 404 Not Found'){
//            $exists = false;
//        }
//        else {
//            $exists = true;
//        }
//            
//        
//        if($exists == true){
//
//           $forecastPage = file_get_contents("http://www.weather-forecast.com/locations/" .$city. "/forecasts/latest");
//            
//            $pageArray = explode ('3 Day Weather Forecast Summary:</b><span class="read-more-small"><span class="read-more-content"> <span class="phrase">', $forecastPage);
//            
//            if(sizeof($pageArray) > 1){
//                $secondPageArray = explode('</span></span></span></p><div class="forecast-cont"><div class="units-cont"><a class="units metric active">', $pageArray[1]);
//                
//                if(sizeof($secondPageArray) > 1){
//                    $weather = $secondPageArray[0];            
//                }
//                else {
//                    $error = "Could not find weather for: " . $city;        
//                }
//            }
//            else {
//                $error = "Could not find weather for: " . $city;    
//            }
//        } 
//        else {
//            $error = "Could not find weather for: " . $city;
//        }
//        
    }

?>


    <!DOCTYPE html>
    <html lang="en">

    <head>
        <!-- Required meta tags always come first -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">

        <title>Simple Weather</title>

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/css/bootstrap.min.css" integrity="sha384-y3tfxAZXuh4HwSYylfB+J125MxIs6mR5FOHamPBG064zB+AFeWH94NdvaCBm8qnd" crossorigin="anonymous">


        <style type="text/css">
            html {
                background: url(background.jpg) no-repeat center center fixed;
                -webkit-background-size: cover;
                -moz-background-size: cover;
                -o-background-size: cover;
                background-size: cover;
            }
            
            body {
                background: none;
            }
            
            .container {
                text-align: center;
                margin-top: 200px;
                color: white;
            }
            
            #weather {
                margin-top: 20px;
            }
        </style>

    </head>

    <body>
        <!-- jQuery first, then Bootstrap JS. -->
        <div class="container">
            <h1>Get your weather!</h1>
            <form method="get">
                <fieldset class="form-group">
                    <label for="city">Enter the name of a city</label>
                    <input type="text" class="form-control" name="city" id="city" placeholder="Eg. London, Tokyo, etc..">
                </fieldset>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>

            <div id="weather">
                <?php
            
            if($weather){
               echo  '<div class="alert alert-success" role="alert">' . $weather . '</div>';
            }
            if($error){
                echo  '<div class="alert alert-danger" role="alert">' . $error . '</div>';
            }
        
            
            ?>

            </div>
        </div>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/js/bootstrap.min.js" integrity="sha384-vZ2WRJMwsjRMW/8U7i6PWi6AlO1L79snBrmgiDpgIWJ82z8eA5lenwvxbMV1PAh7" crossorigin="anonymous"></script>
    </body>

    </html>