<?php 
$csv = trim(file_get_contents("../data/Po.stWidget_Device_Day.csv"));
$csv = explode("\n", $csv); 
$output = array();

$x=0;
foreach($csv as $line) {
    list($publisher, $key, $device, $date, $view, $share, $clickback, $day) = str_getcsv($line);
    $output[trim($publisher)] = $x;
    $x++;
}

$output = array_flip($output);
echo json_encode($output);

?>
