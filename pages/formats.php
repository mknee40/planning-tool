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

$kpi = trim($_GET['kpi']);

/*
    Post URL
*/

$csv = trim(file_get_contents("../data/global/creative.csv"));
$csv = explode("\n", $csv);

$output = array();

$x=0;
foreach($csv as $line) {
    if($x == 0) {
        $x++; continue;
    } 
    list($metric, $platform, $group, $rate, $format) = str_getcsv($line);
    if(strtolower($metric) == $kpi){
        $output[$platform][$group] = $rate; 
    }
}

/*
echo "<pre>";
print_r($output);
echo "</pre>";
exit;
*/

?>

<?php $id=10; foreach($output as $platform=>$arr): ?>
    <div class="format-select">
    <h3><?php echo $platform; ?></h3>
    <?php foreach($arr as $g=>$r): $id ++; ?>        
        <div>
        <input type="checkbox" id="<?php echo $id; ?>" class="<?php echo $id; ?> checkbox-format-select" data-platform="<?php echo $platform; ?>" data-format-values="<?php echo $platform . ' - '. $g . '|' . $r; ?>" value="">
        <div style="display:inline;font-weight:bold;"><?php echo $g; ?></div>
        <div style="display:inline">&pound;<?php echo number_format($r, 2); ?> CPM</div>
        </div>
    <?php endforeach; ?>
    </div>

<?php endforeach; ?>

<h3>Selected Formats</h3>
<div style="padding:0 1em;border:1px solid #dcdcdc;margin-bottom:1em;">
    <h4 id="show-budget" style="margin:0;display:inline-block;border:0;"></h4>    
    <span id="total-ecpm" style="float:right;position:relative;top:4px;font-weight:bold"></span>
</div>
<div id="selected-formats-list"></div>

  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 style="border:0;" class="modal-title">Error!</h4>
        </div>
        <div class="modal-body">
          <p></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>

        /*
        *   Formats methods
        *   @array: curTabPixel.formats
        */        
        //var budget = parseInt(curTabPixel.budget);

        $("#show-budget").html(
                "Budget: £" + $("#budget-selected").text() + "&nbsp;&nbsp;|&nbsp;&nbsp;"
                +"<span>Total Planned &pound;</span><span id='booked-budget'>0</span>&nbsp;&nbsp;|&nbsp;&nbsp;"
                +"<span style='color:green;font-weight:bold' id='total-remaining'>100</span>"
                +"<span id='impressions'>0</span>"
        );
        
        
        /*
            If some formats already exist in the Obj
        */
        if(typeof curTabPixel.formats != "undefined" && curTabPixel.formats.length >= 0){
            for(var f in curTabPixel.formats){
                var curObj = curTabPixel.formats[f];
                $("#" + curObj.id).prop('checked', true);
                addNewFormatItem({
                    id: curObj.id,
                    budget:curObj.budget,
                    impressions: curObj.impressions,
                    percentage: curObj.percentage,
                    type: curObj.type,
                    rate: curObj.rate
                });
            }
            globalBudgetUpdate("return");
        }

        /*
            Add event to checkboxes on new formats
        */
        $('.checkbox-format-select').change(function() {

            var selected = $(this).data("format-values");
            selected = selected.split("|");
            var id = $(this).attr("id");       


            if($(this).is(":checked")) {

                var impressions = ( (parseFloat(curTabPixel.budget)*0.01) / parseFloat(selected[1]) * 1000);
                var formatbudget = (parseFloat(curTabPixel.budget)*0.01);

                // clear everything if nothing selected yet
                if($("#selected-formats-list").html().indexOf("Nothing") > -1) $("#selected-formats-list").html("");

                // append a new selected format group
                
                addNewFormatItem({
                    id: id,
                    budget:formatbudget,
                    impressions: Math.round(impressions),
                    percentage: percentage(1),
                    type: selected[0],
                    rate: selected[1]
                });
                // add new format to global format obj
                curTabPixel.formats.push({
                    "id": id,
                    "type": selected[0],
                    "impressions": Math.round(impressions),
                    "budget":formatbudget,
                    "percentage": 1,
                    "rate": parseFloat(selected[1])
                });

                setCookie("formats", JSON.stringify(curTabPixel.formats), 1);
                
                console.log("New format item added: " + id);
            }
            else
            { 
                // otherwise remove the format
                deleteItem(id);
                setCookie("formats", JSON.stringify(curTabPixel.formats), 1);


                $("#selected-formats-list ." + id).remove(); 
                if($("#selected-formats-list").html() == "") $("#selected-formats-list").html("Nothing selected");             
                console.log("Format deleted: " + id);
            }            
            globalBudgetUpdate();
        });

    function addNewFormatItem(newFormatObj){
        var newFormatHTML = ''
        + '<div class="'+newFormatObj.id+' selected-formats-list">' + newFormatObj.type
            + '<div data-rate="'+newFormatObj.rate+'" data-id="'+newFormatObj.id+'" class="percentage" style="display:inline">'
                + percentage(newFormatObj.percentage)
                + '<span class="budget-booked budget-booked-'+newFormatObj.id+'" style="float:right;margin-right:0.5em;">'
                        + newFormatObj.budget.format().toString()
                + '</span>&nbsp;'
                + '<span style="margin-left:1em;" class="impressions-planned impressions-planned-'+newFormatObj.id+'">'
                    + newFormatObj.impressions.format().toString() + ' impressions'
                + '</span>'
                + '<br style="clear:right;height:1px;">'
            + '</div>'
        + '</div>';

        $("#selected-formats-list").append(newFormatHTML);
    }

    function updateItem(obj){
        for (var i in curTabPixel.formats) 
        {
            if(curTabPixel.formats[i].id == obj.id)
            {                
                for(var x in obj) {
                    curTabPixel.formats[i][x] = obj[x];
                }
                console.log("Format updated " + obj.id);               
                setCookie("formats", JSON.stringify(curTabPixel.formats), 1);
                globalBudgetUpdate(obj);
            }
        }  
    }

    function deleteItem(id){
        curTabPixel.formats = $.grep(curTabPixel.formats, function(a,e) {
            return a.id != id;
        });
        console.log(id  + " has been removed");
        globalBudgetUpdate();
    }

    function ChangePlannedBudget(obj){
        
        // current format / item details
        var id = $(obj).parent().data("id")                
        var rate = parseFloat($(obj).parent().data("rate"))
        var percent = parseInt($(obj).val());

        // New assigned budget / impressions to item
        var newBudget = (parseFloat(curTabPixel.budget) * (percent/100) );
        var Imps = parseFloat((newBudget / rate * 1000)); 
        
        // Current budget / total
        var currentTotalBooked = parseInt($("#booked-budget").text().replace(/,/g, ""));        
        var ItemCurrentBudget = parseInt($(".budget-booked-" + id).text().replace(/,/g, ""));
        
        var IncrementBudget = newBudget - ItemCurrentBudget;

        //alert(rate + " - " + percent + " - " + newBudget);
        
        // Check new budget does not exceed total budget available
        /*if(checkBudgetRemaining((currentTotalBooked + IncrementBudget)) === false){  
            $(obj).val(1); ChangePlannedBudget(obj);
            $(".modal-body").text("You can't book more than the budget.")
            $("#myModal").modal();
            return;
        }*/
      
        $(obj).next().text(newBudget.format());
        $(".impressions-planned-" + id).text(Imps.format() + " impressions");

        updateItem({
            id: id,
            budget: newBudget,
            percentage: percent,
            impressions: Imps
        }); 

        console.log("Change planned budget completed " + id);
        setCookie("formats", JSON.stringify(curTabPixel.formats), 1);
        globalBudgetUpdate(obj);
    };

    function globalBudgetUpdate(obj){

        var planned =0; var impressions = 0;
        var id = $(obj).parent().data("id");

        $(".budget-booked").each(function(){
            planned += parseInt($(this).text().replace(/,/g, ""));
        });

        $(".impressions-planned").each(function(){
            impressions += parseInt($(this).text().replace(/,/g, ""));      
        });

        var ecpm = (planned == 0) ? 0 : planned / (impressions/1000);
        
        /*if(checkBudgetRemaining(planned) === false){           
            $(".modal-body").text("You can't book more than the budget.")
            $("#myModal").modal();
            return;
        }*/

        var newTotalRemaining = parseInt(((curTabPixel.budget - planned) / curTabPixel.budget) * 100);

        //update global status
        $("#booked-budget").text(planned.format());
        $("#total-remaining").text(newTotalRemaining);
        $("#impressions").text(impressions.format());
        $("#total-ecpm").html("&pound;" + ecpm.format(2) + " eCPM");

        if(newTotalRemaining < 0){
            $("#total-remaining").css("color", "red");
        } else {
            $("#total-remaining").css("color", "green");
        }

        console.log("Global update completed");
    }

    function checkBudgetRemaining(check){
        if(check > parseFloat(curTabPixel.budget)){
            return false;
        }
        return true;
    }

    function percentage(percent){
        var str = '<select onchange="javascript:ChangePlannedBudget(this)" class="change-planned-budget" style="float:right;border-radius:20px;">';
        
        for(i = 1; i < 101; i++) {
            str += '<option value="' + i + '"';
            if(i == percent) str += ' selected';
            str += '>' + i + '%</option>';
        }

        str += '</select>';

        return str;
    }    

</script>
