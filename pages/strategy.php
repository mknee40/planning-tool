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
        category, goal, kpi, budget
    */
    $cat = isset($_GET['category']) ? $_GET['category'] : exit("Category not selected.");
    $cat = trim(strtolower($cat));

    $kpi = isset($_GET['kpi']) ? $_GET['kpi'] : exit("kpi not selected.");
    $kpi = trim(strtolower($kpi)); 

    $goal = isset($_GET['goal']) ? $_GET['goal'] : exit("goal not selected.");
    $goal = trim(strtolower($goal)); 

    $budget = isset($_GET['budget']) ? $_GET['budget'] : exit("budget not selected.");
    $budget = trim(strtolower($budget)); 

    /*
        check if post url or post widget has been selected
    */
    $isAvailableShortner = false;
    $isAvailableWidget = false;
    if(isset($curtabpixel->shortner)){
        $isAvailableShortner = true;
    }
    if(isset($curtabpixel->widget)){
        $isAvailableWidget = true;
    }


    $csv_file_toLoad = "../data/global/".$kpi.".csv";
    $csv = trim(file_get_contents($csv_file_toLoad));
    $csv = explode("\n", $csv);
    $type_array = array();
    $x = 0;
    $total_share = 0;
    $funnel_order = array(
            "names"=> array("Upper Funnel", "Mid Funnel", "Low Funnel"),
            "heading"=> array("Event-based", "Pattern-based", "Attribution-based")
    );

    $funnel_order_bar = array("Low Funnel", "Mid Funnel", "Upper Funnel");
 
    $funnel_sort = array();
    $funnel_current_total = 0;

?>

    <!-- Three boxes with upper, middle and lower funnel -->
<div id="funnel-box-container">


<?php foreach($csv as $type){
        if($x > 0){
            list($Category,$StrategyType,$ImpPerc,$ctr,$Conversions,$StrategyName,$Funnel,$Description) = str_getcsv($type);      
           
            if($cat == trim(strtolower($Category)))
            {
                
                /*
                    check post url and post widget exists
                */
                if($StrategyName == "PO.ST URL" AND $isAvailableShortner === false){
                    continue;
                }
                if($StrategyName == "PO.ST WIDGET" AND $isAvailableWidget === false){
                    continue;
                }
                
                
                $exist = false;
                $countExist = 0;

                if(isset($type_array[$Funnel])){
                    foreach($type_array[$Funnel] as $checkExist){
                        if($checkExist['name'] == $StrategyName){
                            $exist = true;
                            break;
                        }
                        $countExist ++;
                    }
                }
                
                if($exist === true){
                    $type_array[$Funnel][$countExist][$StrategyName] += (int) $ImpPerc;
                }else{
                    $type_array[$Funnel][] = array(
                            "name"=>$StrategyName, 
                            "ctr"=>$ctr,
                            "Imp"=>$ImpPerc,
                            "Conv"=>$Conversions,
                            "Desc"=>$Description
                    );
                }                
                $type_array[$Funnel]["total"] += $ImpPerc;
                $funnel_current_total += $ImpPerc;
            }
        }
        $x ++;
    }

    if(count($type_array) < 1) : 
        echo $cat . " does not generate any strategies. Check this category exists.";
    else :

        for($r=0;$r<count($funnel_order["names"]);$r++)
        {
            if(array_key_exists($funnel_order["names"][$r],$type_array))
            {
                $funnel_sort[$funnel_order["names"][$r]]=$type_array[$funnel_order["names"][$r]]; 
                
            ?>
                    <div class="funnel-box" id="upper">
                        <h3><?php echo $funnel_order["names"][$r]; ?></h3>
                        <table border="0" style="table-layout:fixed;border-collapse: separate; border-spacing: 5px; width:100%;">
                            <tr>
                                <td>
                                    <div class="funnel-box-heading" style="background:#ffffff;padding:1em;font-weight:bold;">
                                        <?php echo $funnel_order["heading"][$r]; ?> segmentation
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div  style="background:#ffffff;border-radius:15px;overflow:hidden;height:100%;max-height:800px;min-height:370px;margin-top:0.5em;width:100%;box-shadow: 0px 10px 5px #888888;">
                                    <ul>
                                    <?php foreach($funnel_sort[$funnel_order["names"][$r]] as $strat): ?>
                                        <li class="funnel-box-list">
                                            <?php if($strat['Desc'] != ""): ?>
                                                <span style="font-weight:bold"><?php echo $strat['name']; ?></span>
                                                <p style="margin:0;padding:0;line-height:1em;"><?php echo $strat['Desc']; ?></p>
                                            <?php endif; ?>
                                        </li>
                                    <?php endforeach; ?>
                                    </ul>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>

            <?php 

            }
        }   
    ?>

    <br class="clear-left">
    </div>

    <script type="text/javascript">
            $("#main-content").append("<div id='chartContainer' style='width:100%;auto;height:200px;margin:1em auto;border: 1px solid #cccccc'></div>")
            var chart = new CanvasJS.Chart("chartContainer", {
                theme: "theme",
                title:{
                    text:""			

                },
                animationEnabled: true,
                axisX:{
                    interval: 1,
                    gridThickness: 0,
                    labelFontSize: 16,
                    labelFontStyle: "normal",
                    labelFontWeight: "normal",
                    labelFontFamily: "Lucida Sans Unicode"

                },
                axisY2:{

                },
                data: [
                    {
                        type: "bar",
                        name: "",
                        toolTipContent: "{y}%",
                        axisYType: "secondary",
                        color: "#014D65",
                        dataPoints: [
            
                            {y: <?php echo number_format(($funnel_sort["Low Funnel"]["total"]/$funnel_current_total)*100); ?>, 
                            label: "Lower Funnel <?php echo number_format(($funnel_sort["Low Funnel"]["total"]/$funnel_current_total)*100); ?>%"},
                            
                            {y: <?php echo number_format(($funnel_sort["Mid Funnel"]["total"]/$funnel_current_total)*100); ?>, 
                            label: "Middle Funnel <?php echo number_format(($funnel_sort["Mid Funnel"]["total"]/$funnel_current_total)*100); ?>%"},

                            {y: <?php echo number_format(($funnel_sort["Upper Funnel"]["total"]/$funnel_current_total)*100); ?>, 
                            label: "Upper Funnel <?php echo number_format(($funnel_sort["Upper Funnel"]["total"]/$funnel_current_total)*100); ?>%"},

                        ]
                    }
                ]
            });

    chart.render();
    $('[data-toggle="tooltip"]').tooltip(); 
        </script>

<?php endif; ?>


