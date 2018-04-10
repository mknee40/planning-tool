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


/*
    Pixel Data
*/

$csv = trim(file_get_contents($PATH . "day.csv"));
$csv = explode("\n", $csv); // using this instead of file("file.csv");
$output = array();
$req_pixel = explode(",", trim(urldecode($curtabpixel->pixel)));


$sortedArray = array();

foreach($csv as $line) {
    list($pixel, $device, $platform, $date, $uniques, $firing, $day) = str_getcsv($line);
   if(array_search($pixel, $req_pixel) !== false){
        $output[$platform] += (int)$uniques;
    }
}

?>

<script>

dataObjDevice = {
        height:550,
		title:{
			text: "",
			fontFamily: "arial black"
		},
                animationEnabled: false,
		legend: {
			verticalAlign: "center",
			horizontalAlign: "left",
			fontSize: 20,
			fontFamily: "Helvetica"
		},
		theme: "theme1",
           data: [
                {        
                    type: "pie",
                    indexLabelFontFamily: "Garamond",       
                    indexLabelFontSize: 20,
                    indexLabelFontWeight: "bold",
                    startAngle:0,
                    indexLabelFontColor: "MistyRose",       
                    indexLabelLineColor: "darkgrey", 
                    indexLabelPlacement: "inside", 
                    toolTipContent: " {y} Users",
                    showInLegend: true,
                    indexLabel: "#percent%", 
                    dataPoints: [
                        <?php foreach($output as $deviceName=>$deviceValue): ?>
                        {  y: <?php echo round($deviceValue); ?>, name: "<?php echo $deviceName; ?>", legendMarkerType: "circle"},
                        <?php endforeach; ?>
                    ]
                }
		    ]
        };
        $("#main-content").append("<div id='chartContainerDevice' style='width:90%;margin:4% auto;height:350px;'></div>");
        var chartDevice = new CanvasJS.Chart("chartContainerDevice", dataObjDevice);
        chartDevice.render();
</script>