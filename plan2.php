<?php session_start();  define("IN_APP", true);
if(!isset($_SESSION["agency"])) 
{
	header("location: index.php");
}
?>


<!DOCTYPE html>
<html lang="en" ng-app>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">    
    <title>Planning Tool</title>

    <!-- custom methods and properties -->
    <script src="js/base.js"></script>

    <!-- jQuery Version 1.11.1 -->
    <script src="js/jquery.js"></script>    

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/tooltip.js"></script>
    
    <!-- Canvasjs for charts -->
    <script src="canvasjs.min.js"></script>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css">

    <link href='https://fonts.googleapis.com/css?family=Roboto:400,700,900' rel='stylesheet' type='text/css'>



    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <link href="css/basestyle.css" rel="stylesheet">
</head>

<body>

<div id="container">	
		
    <div id="left-col">

        <h4 class="heading">MEDIA BUDGET AND CAMPAIGN GOAL</h4>

        <h4 class="font-normal item mandatory" data-type="text" data-metric="kpi">
            Performance Goal
        </h4>	
        <div class='drop-down'><div class='arrow-down'></div>

            <input type="text" value="" id="kpi-input" placeholder="Enter your goal" class="form-field"> 
            <h5>Select Metric</h5>
            <button class="kpi-button" data-kpi="ctr">CTR</button>
            <button class="kpi-button" data-kpi="cpa">CPA</button>           
            <div class="spacer"></div>
        </div> 
        
        <h4 class="font-normal item mandatory" data-type="text" data-metric="budget">
            Budget
        </h4>
        <div class='drop-down'><div class='arrow-down'></div>
            <select id="input-budget" class="form-field">
                <option value="">-- Select Budget --</option>
            </select>
            <script>
            for(var count=1;count<5001;count++){
                var curBudget = count*5000;
                $("#input-budget").append("<option value='"+curBudget+"'>" + curBudget.format() + "</option>");
            }
        </script>
        </div>
        
        <div class="spacer"></div>
        
        <h4 class="heading">PLAN CAMPAIGN STRATEGY</h4>        
        
        <h4 class="font-normal item mandatory" data-type="text" data-metric="pixel">
            Pixel ID
        </h4>	
        <div class='drop-down'><div class='arrow-down'></div>
            <select name="pixel-name" class="form-field" id="pixel-name" multiple="multiple">
                <option value="">--- Select a Pixel ---</option>
            </select>
        </div>    
        		
        <h4 class="font-normal item" data-type="text" data-metric="widget">Po.st Widget Unique Key</h4>
        <div class='drop-down'><div class='arrow-down'></div>            
            <select id="widgetid" class="form-field">
                <option value="">--- Select Key ---</option>
            </select></div>
        
        
        <h4 class="font-normal item" data-type="text" data-metric="shortner">PO.st URL Shortener Unique Key</h4>
        <div class='drop-down'><div class='arrow-down'></div>            
            <select id="shortnerid" class="form-field">
                <option value="">--- Select Key ---</option>
            </select>
        </div>
        
        
        <h4 class="font-normal item" data-type="text" data-metric="connect">Connect Mobile SDK Unique Key</h4>
        <div class='drop-down'><div class='arrow-down'></div>            
            <select id="sdkid" class="form-field">
                <option value="">--- Select Key ---</option>
            </select></div>
        
        <div class="spacer"></div>
        
        <h4 class="heading">CUSTOM AUDIENCE MEDIA PLANNER</h4>
        
        <h4 class="font-normal item" data-type="option" data-option='["all","Men","women"]'>Gender
         </h4>
        <div class='drop-down'><div class='arrow-down'></div>
            <ul class="choose-gender">
            <li>
                <input type="radio" name="amount" id="a25" class="gender" checked="checked" value="both" />
                <label for="a25">Both</label>
            </li>
            <li>
                <input type="radio" id="a50" class="gender" name="amount" value="male" />
                <label for="a50">Male</label>
            </li>
            <li>
                <input type="radio" id="a75" class="gender" name="amount" value="female" />
                <label for="a75">Female</label>
            </li>
            </ul>
        </div>
        
        
        <h4 class="font-normal item" data-type="dropdown" data-metric="age" data-option='["99","99"]'>Age
        </h4>
        <div class='drop-down'><div class='arrow-down'></div>
            <select name="ageFrom" id="age-range" class="age-form" class="form-field">
                <option value="" selected>--- Select Age Range ---</option>
                <option value="55+">55+</option>
                <option value="45-54">45-54</option>
                <option value="35-44">35-44</option>
                <option value="25-34">25-34</option>
                <option value="15-24">15-24</option>
            </select>
        </div>
        
        
        <h4 class="font-normal item mandatory" data-type="dropdown" data-metric="category">
            Category
        </h4>
        <div class='drop-down'><div class='arrow-down'></div>
            <select name="category" id="category" class="form-field">
                <option value="">--- Select Category ---</option>
            </select>
        </div>
        
        
       <!-- <h4 class="font-normal item" data-type="text">Location</h4>
        <div class='drop-down'><div class='arrow-down'></div>This is a drop down</div>
       -->
        <div class="spacer"></div>

        <button class="btn btn-primary" id="update-button">Load</button>
        

    </div>

    <div id="right-col">

        <div style="position:absolute;right:150px;margin-top:20px;font-size:14px;">Wecolcome, <?php echo $_SESSION['agency']; ?></div>
        <a href="logout.php" style="float:right;position:relative;top:10px;right:10px;" class="btn btn-primary">Logout</a> 

        <h4 class="heading" style="font-size:190% !important;">NEW TARGET AUDIENCE:
            <span id="targetaudience">0</span>
        </h4>
        <div id="filters"> 
            <div id="filters-container"><span style="font-weight:bold;font-size:18px;margin-right:1em;">Selected Filters:</span>         
                <span class="chosen-filter-title">Age:</span><span id="age-selected" class="selected-metric">select</span>
                <span class="chosen-filter-title">Category: </span><span id="category-selected" class="selected-metric">select</span>
                <span class="chosen-filter-title">Gender: </span><span id="gender-selected" class="selected-metric">Both</span> 
                <span class="chosen-filter-title">Pixels: </span><span id="pixel-selected" class="selected-metric">select</span>            
                <span class="chosen-filter-title">Outcome: </span><span id="goal-selected" class="selected-metric">select</span>
                <span class="chosen-filter-title">Budget: </span><span id="budget-selected" class="selected-metric">select</span>
                <span class="chosen-filter-title">Po.st Widget:</span> <span id="widget-selected" class="selected-metric">select</span>
                <span class="chosen-filter-title">Po.st URL: </span><span id="shortner-selected" class="selected-metric">select</span>
             </div> 
        </div>
        <ul>
            <li class="tab-content" data-load="strategy">Campaign Strategy</li>
            <li class="tab-content" data-load="day">By Day</li>
            <li class="tab-content" data-load="hour">By Hour</li>
            <li class="tab-content" data-load="device">By Device</li>
            <li class="tab-content" data-load="demographics">Demographics</li>
            <li class="tab-content" data-load="formats">Creative Formats</li>
            <li class="tab-content" data-load="summary">Summary</li>
        </ul>
        
        <div style="margin:1em;display:none;">
            Lifetime Capping: <select id="capping"></select>
        </div>
        <script>
            for(var count=0;count<50;count++){
                $("#capping").append("<option value='"+count+"'>" + count + "</option>");
            }
        </script>
        <div id="main-content"> 
        
        </div>          
        
    </div>        
    
    <br class="clear-both">
	
</div>



    <!-- /.container -->

	
	<script>
		$(document).ready(function(){
            
            $('[data-toggle="tooltip"]').tooltip(); 
            $('body').tooltip({
                selector: '[data-toggle=tooltip]'
            });
            
            $('.drop-down').hide();

            $('.item').click(function(e){
                
                e.preventDefault();
                // hide all span
                var $this = $(this).next();
                $(".item").next().not($this).hide();
               
                // here is what I want to do
                $this.toggle();
                $(this).find(".icon-arrow").toggleClass("icon-arrow-up");
                
                
            });

             $.each(categories, function(i,field){
                 $("#category").append("<option value='" + field + "'>" + field + "</option>");
             }); 
             
             $(".tab-content").click(function(){
                curTabPixel.tab = $(this).attr("data-load");               
                loadPage();
                $(this).css("borderBottomColor", "#000000"); 
             });

             $("#update-button").click(function(){      
                loadPage("update-button");
                $('.tab-content[data-load="'+curTabPixel.tab+'"]').css("borderBottomColor", "#000000"); 
             });             
             

             function loadPage(message = ''){
                 
                 var page = curTabPixel.tab;

                 /*
                    clear formats selected when
                    update button is clicked
                 */
                 if(message == "update-button"){
                    //curTabPixel.formats = []; 
                    //document.cookie = "formats=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
                 }

                $(".tab-content").each(function(){
                    $(this).css("borderBottomColor", "#dcdcdc");
                });
                
                 if(!checkMandatoryInput())
                 {
                      $("#main-content").html("").load("pages/blank.php");
                 }
                 else
                 {
                    if(typeof page === "undefined"){
                        page = "strategy";
                        curTabPixel.tab = "strategy";
                    }
                    var curtabpixelsting = Base64.encode(JSON.stringify(curTabPixel));
                    

                    $("#main-content").html("").load("pages/" + page + ".php?pixel=" + escape(curTabPixel.pixel) + "&category=" + escape(curTabPixel.cat) + "&budget=" + escape(curTabPixel.budget) + "&goal=" + escape(curTabPixel.goal) + "&kpi=" + escape(curTabPixel.kpi) + "&curtabpixel=" +curtabpixelsting);

                    updateTotalAudience();
                 }
                 
             }

             function updateTotalAudience(){
                     var curtabpixelsting = Base64.encode(JSON.stringify(curTabPixel));
                     $.getJSON("data/total_audience.php?curtabpixel=" + curtabpixelsting, function(result){
                        $("#targetaudience").html(result.total.format()); 
                    });
             }

             function checkMandatoryInput(){
                 var check = true;
                 for (i = 0; i < mandatory.length; i++) {
                      if(typeof curTabPixel[mandatory[i]] === 'undefined'){
                         check = false;
                     }
                 }
                return check;
             }
             
             $.getJSON("data/pixels.php", function(result){
                 $.each(result, function(i, field){
                    var fieldName = field.replace("ADV", "").replace("UK","").replace(/_/g, " ").replace(/#/g, " ");
                    var pixel_cat = '';
                    for(var x=0;x<categories.length;x++)
                     {
                         categories[x] = categories[x].replace(/ /g,"");
                         fieldName = fieldName.replace(categories[x], "");
                         if(field.indexOf(categories[x]) > -1) pixel_cat = categories[x];
                     }
                    $("#pixel-name").append("<option value='" + field + "' data-category='"+pixel_cat+"'>" + fieldName + "</option>");                    

                 });
             }); 

            $.getJSON("data/widget.php", function(result){
                 $.each(result, function(i, field){
                     $("#widgetid").append("<option value='"+field+"'>"+field+"</option>");
                 });
             }); 

            $.getJSON("data/shortner.php", function(result){
                 $.each(result, function(i, field){
                     $("#shortnerid").append("<option value='"+field+"'>"+field+"</option>");
                 });
             });   


             $(".clear-metric").on("click", function(){
               
             });

             /*
                Metric changes below
             */
                $(".kpi-button").on("click", function(){
                    if($("#kpi-input").val() == ""){
                        alert("Enter a performance goal first");
                    }else{
                    $(".kpi-button").each(function(){
                        $(this).css("backgroundImage", "linear-gradient(to bottom, #ffffff, #e6e6e6");
                    })
                    $(this).css("backgroundImage", "linear-gradient(to top, #ffffff, #e6e6e6");
                    curTabPixel.kpi = $(this).attr("data-kpi");
                    curTabPixel.goal = $("#kpi-input").val();

                    $("#goal-selected").html(curTabPixel.kpi + " / " + curTabPixel.goal +"<span data-input-filter=\"kpi-input\" data-filter=\"kpi-goal\" class=\"filter-remove glyphicon glyphicon-remove-circle\"></span>");
                    }
                });

                $("#input-budget").on("change", function(){
                    curTabPixel.budget = this.value;
                    $("#budget-selected").html(parseInt(curTabPixel.budget).format() +"<span data-input-filter=\"input-budget\" data-filter=\"budget\" class=\"filter-remove glyphicon glyphicon-remove-circle\"></span>");
                }); 

                $("#pixel-name").on("change", function(){
                    var newPixelSelect = '';
                    $('#pixel-name :selected').each(function(){
                        if($(this).val() == "" && $(this).text() == "--- Select a Pixel ---"){
                            newPixelSelect = '';
                            delete curTabPixel.pixel;
                            $("#pixel-selected").html("");
                        }else{
                            newPixelSelect = newPixelSelect + ((newPixelSelect != '') ? "," : '') + ($(this).val());
                            $("#pixel-selected").html($("#pixel-name :selected").length + " Pixels<span data-input-filter=\"pixel-name\" data-filter=\"pixel\" class=\"filter-remove glyphicon glyphicon-remove-circle\"></span>");
                            curTabPixel.pixel = newPixelSelect; 
                        }
                    });
                }); 

                $("#widgetid").on("change", function(){
                    $("#widget-selected").html($("#widgetid :selected").val() +"<span data-input-filter=\"widgetid\" data-filter=\"widget\" class=\"filter-remove glyphicon glyphicon-remove-circle\"></span>");
                    curTabPixel.widget = $("#widgetid :selected").val();
                });

                $("#shortnerid").on("change", function(){
                    $("#shortner-selected").html($("#shortnerid :selected").val() +"<span data-input-filter=\"shortnerid\" data-filter=\"shortner\" class=\"filter-remove glyphicon glyphicon-remove-circle\"></span>");
                    curTabPixel.shortner = $("#shortnerid :selected").val();
                });

                $(".gender").click(function(){
                    curTabPixel.gender = $(this).val();
                    $("#gender-selected").html(curTabPixel.gender);
                });

                $("#age-range").on("change", function(){
                    curTabPixel.age = $(this).val();
                    $("#age-selected").html(curTabPixel.age +"<span data-input-filter=\"age-range\" data-filter=\"age\" class=\"filter-remove glyphicon glyphicon-remove-circle\"></span>");
                });

                $("#category").on("change", function(){
                    curTabPixel.cat = $(this).val();
                    $("#category-selected").html(curTabPixel.cat +"<span data-input-filter=\"category\" data-filter=\"cat\" class=\"filter-remove glyphicon glyphicon-remove-circle\"></span>");
                });

                /*
                    Remove button for the metric
                */
                $(".selected-metric").on("click", function(){

                    var curtabObj = $(this).find('span.filter-remove').data("filter");  
                    var filter = $(this).find('span.filter-remove').data("input-filter");
                    if(curtabObj.indexOf("-") > -1){
                        var curtabObj = curtabObj.split("-");
                        for(var v=0;v<curtabObj.length;v++){
                            delete curTabPixel[curtabObj[v]];
                        }
                    }else{
                        delete curTabPixel[curtabObj];
                    }
                    $("#" + filter).val("");
                    if(filter == "kpi-input"){
                        $(".kpi-button").css("backgroundImage","linear-gradient(to bottom, #ffffff, #e6e6e6)");
                    }
                    $(this).html("select");                   
                
                });


        });

        var categories = [
            'Auto',
            'Business',
            'Education',
            'Entertainment',
            'Fashion',
            'Finance',
            'Food & Drink',
            'Health & Fitness',
            'Home & Garden',
            'Law',
            'Shopping',
            'Sport',
            'Technology',
            'Travel'
        ];



	</script>


</body>

</html>
