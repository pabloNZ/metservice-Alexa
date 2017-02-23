 <?php
//Script by Disrespective and MickeyD @ geekzone

/*
    Usage:
        Set the feed url to http://alexa.vagabond.net.nz/metservice_weather.php?region=xxxxxx
        Where region is as defined on the metservice website
*/

    header('Content-Type: application/json');

    $data = array();

    //did they specify a location?
    $forecast_region = "lyall-bay";  //if not, default to Lyall Bay in honour of Disrespective :-)

    if(isset($_GET["region"]))
    {
        //is it valid? First, let's sanitise the variable
        $matches = array();
        if(preg_match("/([a-zA-Z\-]+)/", $_GET["region"], $matches))
        {
            $forecast_region = strtolower($matches[1]);
        }
    }

    //format the update time as UTC like amazon expects
    $current_time = date("U");
    $utc_time = $current_time - date("Z");
    $update_time = date("Y-m-d", $utc_time) . "T" . date("H:i:s", $utc_time) . ".0Z";

    $data["uid"] = "urn:uuid:1335c695-cfb8-4ebb-abbd-80da344efa6b";
    $data["updateDate"] = $update_time;
    $data["titleText"] = "Metservice Weather for " . $forecast_region;
    $data["mainText"] = get_forecast($forecast_region);
    $data["redirectionUrl"] = "http://www.metservice.com";

    echo json_encode($data);

    function get_forecast($region)
    {
        $directions = array('N' => 'northerly', 'S' => 'southerly', 'E' => 'easterly', 'W' => 'westerly', 'NE' => 'north-easterly', 'NW' => 'north-westerly', 'SE' => 'south-easterly', 'SW' => 'south-westerly');
        $cache_url = "http://newscache.vagabond.net.nz/metservice_cache.php?region=";

        //URL of targeted sites
        $forecastUrl = 'http://www.metservice.com/publicData/localForecast' . $region;

        if(!UR_exists($forecastUrl))
        {
            $result = "I was unable to find a forecast for " . $region;
        } else {
            //load from the cache (if not cached, we'll get it and cache it in the future)
            $rawJsonData = file_get_contents($cache_url . $region);
            $forecast = json_decode($rawJsonData);
            if(isset($forecast->{'error'}))
            {
                return $forecast->{'error'};
            }

            $forecastData = $forecast->{'forecastFile'}->{'days'}[0]->{'forecast'};
            $maxData = $forecast->{'forecastFile'}->{'days'}[0]->{'max'};
            $minData = $forecast->{'forecastFile'}->{'days'}[0]->{'min'};

            $tempNow = $forecast->{'obsFile'}->{'threeHour'}->{'temp'};
            $windDir = isset($directions[$forecast->{'obsFile'}->{'threeHour'}->{'windDirection'}])? $directions[$forecast->{'obsFile'}->{'threeHour'}->{'windDirection'}] : $forecast->{'obsFile'}->{'threeHour'}->{'windDirection'};
            $windNow = $forecast->{'obsFile'}->{'threeHour'}->{'windSpeed'};
            $tempFeels = $forecast->{'obsFile'}->{'threeHour'}->{'windChill'};

            $result = ('The weather today will be ' .$forecastData. ' with a high of ' .$maxData. ' degrees and low of ' .$minData. ' degrees. Right now it is ' .$tempNow. ' with ' .$windDir. ' winds of ' .$windNow. ' kilometers per hour which makes it feel like ' .$tempFeels. ' degrees');

        }

        return $result;
    }

    function UR_exists($url){
        //function copied from Patrick at StackOverflow
        //http://stackoverflow.com/questions/7684771/how-check-if-file-exists-from-the-url
       $headers=get_headers($url);
       return stripos($headers[0],"200 OK")? true:false;
    }
?>

