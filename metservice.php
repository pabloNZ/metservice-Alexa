<?php
//URL of targeted sites
$forecastUrl = file_get_contents('http://www.metservice.com/publicData/localForecastlyall-bay');
$obsUrl = file_get_contents('http://www.metservice.com/publicData/localObs_lyall-bay');

$forecastRaw = json_decode($forecastUrl);
$obsRaw = json_decode($obsUrl);

$forecastData = $forecastRaw->{'days'}[0]->{'forecast'};
$maxData = $forecastRaw->{'days'}[0]->{'max'};
$minData = $forecastRaw->{'days'}[0]->{'min'};

$tempNow = $obsRaw->{'threeHour'}->{'temp'};
$windDir = $obsRaw->{'threeHour'}->{'windDirection'};
$windNow = $obsRaw->{'threeHour'}->{'windSpeed'};
$tempFeels = $obsRaw->{'threeHour'}->{'windChill'};
$updateTime = $obsRaw->{'threeHour'}->{'dateTime'};

$result = (' ' .$forecastData. ' with a high of ' .$maxData. ' degrees and low of ' .$minData. ' degrees. Right now it is ' .$tempNow. ' with ' .$windDir. ' winds of ' .$windNow. ' kilometers per hour which makes it feel like ' .$tempFeels. ' degrees');

$postResult = ('Content-Type: application/json {"uid": "urn:uuid:1335c695-cfb8-4ebb-abbd-80da344efa6b","updateDate": "2016-05-23T00:00:00.0Z","titleText": "Metservice Weather","mainText": "' .$result. '","redirectionUrl": "http://www.metservice.co.nz"}');

echo $postResult;
?>
