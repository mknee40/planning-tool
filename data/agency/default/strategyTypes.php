<?php
    $csv = trim(file_get_contents("sType.csv"));
    $csv = explode("\n", $csv);

    $type_array = array();
    $x = 0;

    foreach($csv as $type){
        if($x > 0){
            list($raw, $type, $funnel, $description) = str_getcsv($type);
            if(!isset($type_array[$funnel])){
                $type_array[$funnel] = array();
            }
            array_push(
                $type_array[$funnel], 
                array(
                    "type"=>$type, 
                    "raw"=>$raw,
                    "desc"=>$description
                )
            );

        }
        $x ++;
    }
?>