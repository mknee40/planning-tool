<?php 
$csv = trim(file_get_contents("../data/day.csv"));
$csv = explode("\n", $csv); 
$output = array();

$x=0;
foreach($csv as $line) {
    if($x > 0){
        list($pixel, $platform, $date, $uniques, $firing, $day) = str_getcsv($line);
        //$pixel = str_replace("#","_", trim($pixel));
        $output[$pixel] = $x;
    }
    $x++;
}

$output = array_flip($output);

echo json_encode($output);

?>
