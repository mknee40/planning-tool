<?php 
$csv = trim(file_get_contents("../data/Po.stURL_Device_Day.csv"));
$csv = explode("\n", $csv); 
$output = array();

$x=0;
foreach($csv as $line) {
    list($name, $id, $device, $date, $user, $click, $day) = str_getcsv($line);
    $output[trim($name)] = $x;
    $x++;
}

$output = array_flip($output);
echo json_encode($output);

?>