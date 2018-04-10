<?php require("../functions.php");

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


//This is the template array.. Changing this alters the output
$dow=array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');





/*

    Post URL

*/

$csv_posturl = trim(file_get_contents($PATH . "Po.stURL_Device_Day.csv"));
$csv_posturl = explode("\n", $csv_posturl); 
$output_posturl = array();

$sortedArray_posturl = array();
$isAvailshortner = false;



if(isset($curtabpixel->shortner) AND $curtabpixel->shortner != "")
{
    $req_posturl = trim($curtabpixel->shortner);

    foreach($csv_posturl as $line_posturl)
    {
        list($name, $id, $device, $date_post, $users, $clicks, $day_post) = str_getcsv($line_posturl);

        if($name == $req_posturl)
        {
            $output_posturl['users'][$day_post] += (int) $users;
            $output_posturl['clicks'][$day_post] +=(int) $clicks;
        }
    }
    $isAvailshortner = true;
}


/*
    Pixel Data
*/

$csv = trim(file_get_contents($PATH . "day.csv"));
$csv = explode("\n", $csv); 
$output = array();
$ReqPixel = explode(",", trim(urldecode($_GET['pixel'])));

$sortedArray = array();

foreach($csv as $line) 
{

    list($pixel, $device, $platform, $date, $uniques, $firing, $day) = str_getcsv($line);
   if(array_search($pixel, $ReqPixel) !== false)
   {

        $pixel = str_replace("#","_", trim($pixel));
        $date  = trim($date);
        $uniques = trim($uniques);
        $day = trim($day);
        $output['uniques'][$day] += (int)$uniques;
        $output['firing'][$day] += (int)$firing;
    }
}



foreach($dow as $day)
{
    /*
        Pixel Firings
    */
    if(array_key_exists($day,$output['uniques']))
    {
        $sortedArray['uniques'][$day]=$output['uniques'][$day]; 
    }

    if(array_key_exists($day,$output['firing']))
    {
        $sortedArray['firing'][$day]=$output['firing'][$day]; 
    }



    //echo ($isAvailshortner === false) ? "False" : "True"; exit;

    if($isAvailshortner === true)
    {
        /*
            Shortner uniques / clicks
        */

        if(array_key_exists($day,$output_posturl['users']))
        {
            $sortedArray_posturl['users'][$day]=$output_posturl['users'][$day]; 
        }

        if(array_key_exists($day,$output_posturl['clicks']))
        {
            $sortedArray_posturl['clicks'][$day]=$output_posturl['clicks'][$day]; 
        }  
    } 
}


?>
	<script type="text/javascript">
    dataObjUniques = {

            title: {

                title: "Pixels - Unique Users"

            },

            axisY: {

                title: "Unique Users"

            },

            axisX: {

                title: "Day of the Week"

            },

            data: [

                {

                    type: "column",

                    toolTipContent: "<a href = {name}> {label}</a><hr/>Unique Users: {y}",

                    dataPoints: [

                    <?php foreach($sortedArray['uniques'] as $out=>$value): ?>

                    {y: <?php echo $value; ?>, label: "<?php echo $out; ?>"},

                    <?php endforeach; ?>

                    ]

                }

            ]

        };

        $("#main-content").append("<div id='chartContainerUnique' style='width:90%;margin:4% auto;height:350px;'></div>");

        var chartUnique = new CanvasJS.Chart("chartContainerUnique", dataObjUniques);

        chartUnique.render();



<?php if($isAvailshortner === true) : ?>



    dataObjShortner = {

            title:{

                text: "Post URL - Users vs. Clicks"

            },

            axisY: {

                title: "Users vs. Clicks"

            },

            axisX: {

                title: "Day of the Week"

            },

            data: [

                {

                    type: "column",

                    toolTipContent: "<a href = {name}> {label}</a><hr/>Unique Users: {y}",

                    dataPoints: [

                    <?php foreach($sortedArray_posturl['users'] as $out_post=>$value_post): ?>

                    {y: <?php echo $value_post; ?>, label: "<?php echo $out_post; ?>"},

                    <?php endforeach; ?>

                    ]

                },                

                {        

                    type: "line",

                    toolTipContent: "<a href = {name}> {label}</a><hr/>Clicks: {y}",

                    dataPoints: [

                    <?php foreach($sortedArray_posturl['clicks'] as $out_post_click=>$value_post_click): ?>

                    {y: <?php echo $value_post_click; ?>, label: "<?php echo $out_post_click; ?>"},

                    <?php endforeach; ?>

                    ]

                }

            ]

        };

        $("#main-content").append("<div id='chartContainerShortner' style='width:90%;margin:4% auto;height:350px;'></div>");

        var chartShortner = new CanvasJS.Chart("chartContainerShortner", dataObjShortner);

        chartShortner.render();



    <?php endif; ?>



	</script>

    

