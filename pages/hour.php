<?php require("../functions.php");
if(!pageCheck())
{ 
    echo "Please login to access this page";
    exit();
}

$PATH = "../data/agency/" . $_SESSION["agency"] . "/";


$csv = trim(file_get_contents($PATH . "hour.csv"));
$csv = explode("\n", $csv); // using this instead of file("file.csv");
$output = array();

$ReqPixel = explode(",", trim(urldecode($_GET['pixel'])));

//This is the template array.. Changing this alters the output
$hour=array(0,1,2,3,4,5,6,7,8,9,10,11);

$sortedArray = array();

foreach($csv as $line) {
    list($pixel, $platform, $hour, $uniques) = str_getcsv($line);
   if(array_search($pixel, $ReqPixel) !== false){
        $pixel = str_replace("#","_", trim($pixel));
        $hour  = trim($hour);
        $uniques = trim($uniques);
        $output['uniques'][$hour] += (int)$uniques;
    }
}

?>
	<script type="text/javascript">
    dataObjUniques = {
            axisY: {
                title: "Unique Users"
            },
            axisX: {
                title: "Hour of Day"
            },
            data: [
                {
                    type: "column",
                    toolTipContent: "<a href = {name}> {label}</a><hr/>Unique Users: {y}",
                    dataPoints: [
                    <?php foreach($output['uniques'] as $out=>$value): ?>
                    {y: <?php echo $value; ?>, label: "<?php echo $out; ?>"},
                    <?php endforeach; ?>
                    ]
                }
            ]
        };
        $("#main-content").append("<div id='chartContainerUnique' style='width:90%;margin:4% auto;height:350px;'></div>")
        var chartUnique = new CanvasJS.Chart("chartContainerUnique", dataObjUniques);
        chartUnique.render();
	</script>
    
