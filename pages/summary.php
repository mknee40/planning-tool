<style>
    h4.summary{
        border:0 !important;
        font-weight:bold;
        font-size: 18px;
        background: #cccccc;
        padding: 0.3em 0.7em;
        border-radius: 10px 10px 0 0;
    }
    #budget-pie{
       height:400px;
    }
    #budget-title{
        text-align:center;
        font-weight:bold;
        font-size: 18px;
    }
    #total-row{
        font-weight: bold;
        background: beige;
    }
</style>

<div style="overflow-y: scroll; overflow-x: hidden; max-height:535px;">
    <h4 class="summary">Budget</h4>
    <div id="budget-title"></div>
    <div id="budget-pie"></div>

    <h4 class="summary">Formats</h4>
    <table id="formats-append" class="table">
        <tr>
            <th style="text-align:left">Format</th>
            <th>Rate</th>
            <th>Budget</th>
            <th>Impressions</th>
        </tr>
    </table>
    <div id="formats-summary"></div>

    <h4 class="summary">Category</h4>
    <span id="category-summary"></span>

    <h4 class="summary">Business Outcome</h4>
    <span id="outcome-summary"></span>


    <h4 class="summary">Audience</h4>
    <div id="audience-summary"></div>
</div>

<script>

    var platforms = {};

    $("#category-summary").html(curTabPixel.cat);
    $("#outome-summary").html("Goal: " + curTabPixel.goal + " Outcome: " + curTabPixel.kpi);
    

    /*
        check that there are formats selected before
        printing to the table.
    */
    if(curTabPixel.formats.length > 0)
    {
        $(curTabPixel.formats).each(function(ind, val){

            var platform = curTabPixel.formats[ind].type.split("-");

            if(typeof platforms[platform[0]] !== "undefined"){
                platforms[platform[0]] += parseFloat(curTabPixel.formats[ind].budget);
            }
            else {
                platforms[platform[0]] = parseFloat(curTabPixel.formats[ind].budget);
            }


            $("#formats-append  tr:last").after(
                '<tr><td style="text-align:left">'+curTabPixel.formats[ind].type+'</td>'
                + '<td>&pound;'+curTabPixel.formats[ind].rate.format(2)+'</td>'
                + '<td class="format-budget-count">&pound;<span>'+curTabPixel.formats[ind].budget.format(2)+'</span></td>'
                + '<td class="format-impressions-count">'+curTabPixel.formats[ind].impressions.format()+'</td></tr>'
            );

        });
        $("#formats-append  tr:last").after(
            '<tr id="total-row"><td style="text-align:left">Total</td><td id="ecpm"></td><td id="total-budget"></td><td id="total-impressions"></td></tr>'
        );
    }
    else 
    {
       $("#formats-append  tr:last").after(
            '<tr><td style="text-align:center" colspan="4">Select formats to assign budget.</td></td></tr>'
        );
    }




    $("#audience-summary").html("Gender: " + curTabPixel.gender + "<br>" + "Age: " + curTabPixel.age);

    if(curTabPixel.kpi == 'ctr'){
        var outcome_output = curTabPixel.goal + '%' + ' ' + curTabPixel.kpi;
    } else {
        var outcome_output = '&pound;' + curTabPixel.goal + ' ' + curTabPixel.kpi;
    }
    $("#outcome-summary").html(outcome_output);

    var totalBookedBudget = 0;
    var totalImpressions = 0;

    $(".format-budget-count span").each(function(){
        totalBookedBudget += parseInt($(this).text().replace(/,/g, ""));
    });

    $(".format-impressions-count").each(function(){
        totalImpressions += parseInt($(this).text().replace(/,/g, ""));      
    });

    var ecpm = totalBookedBudget / (totalImpressions/1000);

    $("#total-budget").html("&pound;" + totalBookedBudget.format(2));
    $("#total-impressions").text(totalImpressions.format());
    $("#ecpm").html("&pound;" + ecpm.format(2));            

    /*
        Create pie chart showing distribution of budget            
    
    */
    dataObjDevice = {
        height:400,
        title:{
            fontFamily: "arial black",
            fontSize: 18
        },
        animationEnabled: false,
        legend: {
            verticalAlign: "center",
            horizontalAlign: "bottom",
            fontSize: 14,
            fontFamily: "Helvetica"
        },
        theme: "theme1",
        data: [
                {        
                    type: "pie",
                    indexLabelFontFamily: "arial",       
                    indexLabelFontSize: 20,
                    indexLabelFontWeight: "bold",
                    startAngle:0,
                    indexLabelFontColor: "MistyRose",       
                    indexLabelLineColor: "darkgrey", 
                    indexLabelPlacement: "inside", 
                    toolTipContent: "{name}: #percent%",
                    showInLegend: true,
                    indexLabel: "£{y}", 
                    dataPoints: []
                }
            ]
        };

    if(totalBookedBudget < 1){
        $("#budget-pie").html("Select formats to assign budget.");
        $("#budget-pie").css({"height":"auto","text-align":"center"});
    }
    else 
    {                
        for(var d in platforms){
            dataObjDevice.data[0].dataPoints.push({
                y: platforms[d],
                name: d,
                legendMarkerType: "circle"
            });
            console.log(d + " - " + platforms[d]);
        }
        var chartDevice = new CanvasJS.Chart("budget-pie", dataObjDevice);
        $("#budget-title").html("Budget Available: £" + parseFloat(curTabPixel.budget).format(2) + "<br>Total Booked: £" + totalBookedBudget.format(2));

        chartDevice.render();
    }


</script>
