<?php 
$csv = trim(file_get_contents("PlanningTool_Pixel_Data.csv"));
$csv = explode("\n", $csv); // using this instead of file("file.csv");
$output = array();
foreach($csv as $line) {
    list($pixel, $platform, $date, $uniques, $day) = str_getcsv($line);
    $pixel = str_replace("#","_", trim($pixel));
    $date  = trim($date);
    $uniques = trim($uniques);
    $day = trim($day);
    $output[$pixel][$day] += (int)$uniques;
}
echo json_encode($output);

?>