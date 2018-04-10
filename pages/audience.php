<?php

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

$curtabpixel = json_decode(base64_decode(($_GET['curtabpixel'])));
$pixels = explode(",", $curtabpixel->pixel);



/*

    Audience Data

*/

$csv = trim(file_get_contents($PATH . "audience.csv"));
$csv = explode("\n", $csv); 

$audiences = array();
$overlap_uniques_index = array();

$row = 0;
foreach($csv as $line){
    //if($row == 0) continue;
    list($source_name, $source_type, $tier1, $tier2, $tier3, $population_index, $overlap_unique) = str_getcsv($line);
    $name = explode("-", $source_name);
    $pixel_search = trim($name[0]);  

    if(in_array($pixel_search, $pixels) AND $tier1 == "Demographics")
    {

        if(isset($audiences[$tier1][$tier2][$tier3]))
        {
            $audiences[$tier1][$tier2][$tier3] += ((float) str_replace(",", "", $overlap_unique) * (float) str_replace(",", "",$population_index));
            $overlap_uniques_index[$tier1][$tier2][$tier3] += (int) str_replace(",", "", $overlap_unique);
        } 
        else 
        {
             $audiences[$tier1][$tier2][$tier3] = (float) str_replace(",", "", $overlap_unique) * (float) str_replace(",", "", $population_index);
             $overlap_uniques_index[$tier1][$tier2][$tier3] = (float) str_replace(",", "", $overlap_unique);
        }

    }
    $row ++;

}



foreach($audiences as $t1=>$t2)

{
    echo "<h1>" . $t1 . "</h1>";   

    foreach($t2 as $t3k=>$t3)
    {
        $total_overlap = array_sum($overlap_uniques_index[$t1][$t3k]);
        if(count($t3) > 1)
        {
            echo "<h3>" . $t3k . " - " . $total_overlap ."</h3>";
            foreach($t3 as $t4=>$t5)
            {    
                echo "<div>" . $t4 . " : Colour - " . round($t5/$overlap_uniques_index[$t1][$t3k][$t4],2). ", height - " . round($total_overlap/$overlap_uniques_index[$t1][$t3k][$t4],2). "<div>";
            }
        }
        else 
        {
            echo "<div>" . $t3k . " : Colour - " . round($t3[NULL]/$overlap_uniques_index[$t1][$t3k][NULL],2). ", height - ".round($total_overlap/$overlap_uniques_index[$t1][$t3k][NULL],2)."</div>";
        }
    }
}

?>







