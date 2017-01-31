<?php
  $json_string = file_get_contents("http://api.wunderground.com/api/b6bab063a074da1c/geolookup/forecast10day/q/NZ/wellington.json");
  $parsed_json = json_decode($json_string);
  $titleNight = $parsed_json->{'forecast'}->{'txt_forecast'}->{'forecastday'}[1]->{'title'};
  $text = $parsed_json->{'forecast'}->{'txt_forecast'}->{'forecastday'}[0]->{'fcttext_metric'};
  $textTonight = $parsed_json->{'forecast'}->{'txt_forecast'}->{'forecastday'}[1]->{'fcttext_metric'};
  $pop = $parsed_json->{'forecast'}->{'txt_forecast'}->{'forecastday'}[0]->{'pop'};

  echo "Today will be ${text} with a ${pop} percent chance of rain. ${titleNight} will be ${textTonight}";
?>
