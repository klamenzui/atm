/*
var begins
_-global function
$-global var

*/
function jQajax(gurl,resid){
$.ajax({
	type: "POST",
	url: gurl,
	// data: gdata,
	success: function(msg){
	$('#'+resid).html(msg);
	// alert( "Data Saved:"+ msg );
	}});
}