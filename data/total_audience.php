<?php header('Content-Type: application/json');

require("../functions.php");
if(!pageCheck())
{ 
    echo "Please login to access this page";
    exit();
}

$PATH = "../data/agency/" . $_SESSION["agency"] . "/";

/*
    curTabPixel settings / metrics / settings
*/
$curtabpixel = base64_decode(($_GET['curtabpixel']));
$curtabpixel = json_decode($curtabpixel);

$reqpixel = explode(",", $curtabpixel->pixel);

$csv = trim(file_get_contents($PATH . "day.csv"));
$csv = explode("\n", $csv); 
$total_audience = 0;


foreach($csv as $line) {
    list($pixel,$device, $date, $users,$firing,$day) = str_getcsv($line);
     if(array_search($pixel, $reqpixel) !== false){
        $total_audience += (int) $users;
     }  
}



echo json_encode(array("total"=>$total_audience));

?>