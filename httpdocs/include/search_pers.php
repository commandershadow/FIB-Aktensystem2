<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script type="text/javascript">
$(function(){
$(".search").keyup(function() 
{ 
var searchid = $(this).val();
var dataString = 'search='+ searchid;
if(searchid!='')
{
$.ajax({
type: "POST",
url: "result.php",
data: dataString,
cache: false,
success: function(html)
{
$("#result").html(html).show();
}
});
}return false; 
});
jQuery("#result").live("click",function(e){ 
var $clicked = $(e.target);
var $name = $clicked.find('.name').html();
var decoded = $("<div/>").html($name).text();
$('#searchid').val(decoded);
});
jQuery(document).live("click", function(e) { 
var $clicked = $(e.target);
if (! $clicked.hasClass("search")){
jQuery("#result").fadeOut(); 
}
});
$('#searchid').click(function(){
jQuery("#result").fadeIn();
});
});
</script>

<style type="text/css">
    .content{
        width:900px;
        margin:0 auto;
    }
    #searchid
    {
        width:500px;
        border:solid 1px #000;
        padding:10px;
        font-size:14px;
    }
    #result
    {
        width:500px;
        padding:10px;
		display: none;
        margin-top:-1px;
        border-top:0px;
        overflow:hidden;
        border:1px #CCC solid;
        background-color: #222222;
    }
    .show
    {
        padding:10px; 
        border-bottom:1px #999 dashed;
        font-size:15px; 
        height:130px;
    }
    .show:hover
    {
        background:#4c66a4;
        color:#FFF;
        cursor:pointer;
    }
</style>

<h3>Datenbank durchsuchen</h3><br/>
       		Du kannst nach Vor- / Nachname, Telefonnummer oder Kennzeichen suchen.
<div class="content">
<input type="text" class="search" id="searchid" placeholder="323, Max, Mustermann" /><br /> 
<div id="result"></div>
</div>








