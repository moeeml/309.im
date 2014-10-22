jQuery(document).ready(function(){

$('#contactform').submit(function(){

var action = $(this).attr('action');

$("#message").slideUp(750,function() {
$('#message').hide();

 	$('#submit')
.after('<img src="images/ajax-loader.gif" class="loader" />')
.attr('disabled','disabled');

$.post(action, {
name: $('#name').val(),
email: $('#email').val(),
comments: $('#comments').val()
},
function(data){
document.getElementById('message').innerHTML = data;
$('#message').slideDown('slow');
$('#contactform img.loader').fadeOut('slow',function(){$(this).remove()});
$('#submit').removeAttr('disabled');
//if(data.match('success') != null) $('#contactform').slideUp('slow');
jQuery('#message').has('.error_message').mousemove(function() {
jQuery(this).hide();
});
jQuery('#message').has('#success_page').hover(function() {
jQuery(this).show();
});
jQuery('#message').has('#success_page').mousemove(function() {
jQuery(this).show();
});

}
);

});

return false;

});

});