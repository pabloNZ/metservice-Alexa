<?php
//Script by pabloNZ

   header('Content-Type: application/json');

   $data = array();

   //format the update time as UTC like amazon expects
   $current_time = date("U");
   $utc_time = $current_time - date("Z");
   $update_time = date("Y-m-d", $utc_time) . "T" . date("H:i:s", $utc_time) . ".0Z";

   $data["uid"] = "urn:uuid:1335c695-cfb8-4ebb-abbd-80da344efa6b";
   $data["updateDate"] = $update_time;
   $data["titleText"] = "Metservice Weather";
   $data["mainText"] = get_forecast("lyall-bay");
   $data["redirectionUrl"] = "http://www.metservice.com";

   echo json_encode($data);

   function get_forecast($region)
   {
       //URL of targeted sites
       $forecastUrl = file_get_contents('http://www.metservice.com/publicData/localForecast' . $region);
       $obsUrl = file_get_contents('http://www.metservice.com/publicData/localObs_' . $region);

       $directions = array('N' => 'northerly', 'S' => 'southerly', 'E' => 'easterly', 'W' => 'westerly', 'NE' => 'north-easterly', 'NW' => 'north-westerly', 'SE' => 'south-easterly', 'SW' => 'south-westerly');

       $forecastRaw = json_decode($forecastUrl);
       $obsRaw = json_decode($obsUrl);

       $forecastData = $forecastRaw->{'days'}[0]->{'forecast'};
       $maxData = $forecastRaw->{'days'}[0]->{'max'};
       $minData = $forecastRaw->{'days'}[0]->{'min'};

       $tempNow = $obsRaw->{'threeHour'}->{'temp'};
       $windDir = isset($directions[$obsRaw->{'threeHour'}->{'windDirection'}])? $directions[$obsRaw->{'threeHour'}->{'windDirection'}] : $obsRaw->{'threeHour'}->{'windDirection'};
       $windNow = $obsRaw->{'threeHour'}->{'windSpeed'};
       $tempFeels = $obsRaw->{'threeHour'}->{'windChill'};

       $result = ('The weather today will be ' .$forecastData. ' with a high of ' .$maxData. ' degrees and low of ' .$minData. ' degrees. Right now it is ' .$tempNow. ' with ' .$windDir. ' winds of ' .$windNow. ' kilometers per hour which makes it feel like ' .$tempFeels. ' degrees');

       return $result;
   }
?>
