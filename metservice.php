<?php
//URL of targeted sites
$forecastUrl = file_get_contents('http://www.metservice.com/publicData/localForecastlyall-bay');
$obsUrl = file_get_contents('http://www.metservice.com/publicData/localObs_lyall-bay');

$forecastRaw = json_decode($forecastUrl);
$obsRaw = json_decode($obsUrl);

$timestamp = "20150611183741.941Z";
$date = DateTime::createFromFormat('YmdHis.ue', $timestamp);

$forecastData = $forecastRaw->{'days'}[0]->{'forecast'};
$maxData = $forecastRaw->{'days'}[0]->{'max'};
$minData = $forecastRaw->{'days'}[0]->{'min'};

$tempNow = $obsRaw->{'threeHour'}->{'temp'};
$windDir = $obsRaw->{'threeHour'}->{'windDirection'};
$windNow = $obsRaw->{'threeHour'}->{'windSpeed'};
$tempFeels = $obsRaw->{'threeHour'}->{'windChill'};
$updateTime = $obsRaw->{'threeHour'}->{'dateTime'};

$mainText = (' ' .$forecastData. ' with a high of ' .$maxData. ' degrees and low of ' .$minData. ' degrees. Right now it is ' .$tempNow. ' with ' .$windDir. ' winds of ' .$windNow. ' kilometers per hour which makes it feel like ' .$tempFeels. ' degrees');

$uid = 'urn:uuid:1335c695-cfb8-4ebb-abbd-80da344efa6b';
$updateDate = $date->format('Y-m-d H:i:s');
$titleText = 'Metservice Weather';
$redirectionUrl = 'http://www.metservice.co.nz';

$arr = array('uid'=>$uid, 'updateDate'=>$date, 'titleText'=>$titleText, 'mainText'=>$mainText, 'redirectionUrl'=>$redirectionUrl);

$postResult = json_encode($arr);

echo ($postResult);
?>
