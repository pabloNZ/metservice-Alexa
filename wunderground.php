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
   $data["mainText"] = get_forecast;
   $data["redirectionUrl"] = "http://www.metservice.com";

   echo json_encode($data);

   function get_forecast
   {
       //URL of targeted sites

       $json_string = file_get_contents('http://api.wunderground.com/api/b6bab063a074da1c/geolookup/forecast10day/q/NZ/wellington.json');

       $parsed_json = json_decode($json_string);

       $titleNight = $parsed_json->{'forecast'}->{'txt_forecast'}->{'forecastday'}[1]->{'title'};
       $text = $parsed_json->{'forecast'}->{'txt_forecast'}->{'forecastday'}[0]->{'fcttext_metric'};
       $textTonight = $parsed_json->{'forecast'}->{'txt_forecast'}->{'forecastday'}[1]->{'fcttext_metric'};
       $pop = $parsed_json->{'forecast'}->{'txt_forecast'}->{'forecastday'}[0]->{'pop'};

       $result = ('Today will be ' .${text}. ' with a '.${pop}. ' percent chance of rain.' .${titleNight}. ' will be ' .${textTonight}. ' ');

       return $result;
   }
?>
