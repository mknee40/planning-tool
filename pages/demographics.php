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

foreach($csv as $line)
{
    //if($row == 0) continue;
    list($source_name, $source_type, $tier1, $tier2, $tier3, $population_index, $overlap_unique) = str_getcsv($line);
    $name = explode("-", $source_name);
    $pixel_search = trim($name[0]);   

    if(in_array($pixel_search, $pixels) AND $tier1 == "Demographics")
    {
        if(isset($audiences[$tier1][$tier2][$tier3]))
        {
            //$audiences[$tier1][$tier2][$tier3] += ((float) str_replace(",", "", $overlap_unique) * (float) str_replace(",", "",$population_index));
            $audiences[$tier1][$tier2][$tier3] += ((float) str_replace(",", "", $overlap_unique));
            $overlap_uniques_index[$tier1][$tier2][$tier3] += (int) str_replace(",", "", $overlap_unique);
        }
        else 
        {
             //$audiences[$tier1][$tier2][$tier3] = (float) str_replace(",", "", $overlap_unique) * (float) str_replace(",", "", $population_index);
             $audiences[$tier1][$tier2][$tier3] = (float) str_replace(",", "", $overlap_unique);
             $overlap_uniques_index[$tier1][$tier2][$tier3] = (float) str_replace(",", "", $overlap_unique);
        }
    }
    $row ++;
}
?>



<div style="overflow-y:scroll;max-height:500px;position:relative;">

<?php foreach($audiences as $t1=>$t2) : ?>
    <?php $canvas_count = 1; foreach($t2 as $t3k=>$t3) :?>
      <?php $total_overlap = array_sum($overlap_uniques_index[$t1][$t3k]); ?>

      <div id="chart-<?=$canvas_count;?>"></div>


      <?php if(count($t3) > 1) : ?>
        <div style="background:#dcdcdc;padding:0.5em;width:100%">
          <h3 style="text-indent:1em;"><?php echo $t3k; ?></h3>  
          <canvas id="chart-<?=$canvas_count;?>" width="900" height="350"></canvas>
            <script>
            //data = [];
                dataObjShortner = {};
                
                dataObjShortner = {
                  title:{
                      text: "<?php echo $t3k; ?>"
                  },
                  axisY: {
                      title: "Percentage"
                  },

                  data: [
                      {  
                          type: "column",
                          toolTipContent: "{y}%",
                          dataPoints: []
                      }
                  ]
              };
            <?php $left=0; foreach($t3 as $t4=>$t5) : ?> 
                dataObjShortner.data[0].dataPoints.push({
                  label: "<?php echo $t4; ?>",
                  y: <?php echo round($t5/$total_overlap*100); ?>
                });
            <?php $left += 20; endforeach; ?>
            //generateGraph(data,"chart-<?=$canvas_count;?>")
              var chartShortner = new CanvasJS.Chart("chart-<?=$canvas_count;?>", dataObjShortner);
              chartShortner.render();
            </script>
        </div>
      <?php endif; ?> 

    <?php $canvas_count++; endforeach; ?>  
<?php endforeach; ?>

</div>









