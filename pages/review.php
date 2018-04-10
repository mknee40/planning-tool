<?php 
if(isset($_GET['curtabpixel']) AND $_GET['curtabpixel'] != "")
{
    $curtabpixel = json_decode(base64_decode($_GET['curtabpixel']));
}
?>
<div id="summary"></div>
<script>

$(".tab-content").each(function(){
    $("#summary").append("<div id='"+$(this).attr("data-load").+"-summary'></div>")
});

function loadSummary(){

}

</script>

