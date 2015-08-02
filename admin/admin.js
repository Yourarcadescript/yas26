function doQue(gameid, theAction, source, title, thumb) {
	$.post("gameque.php", { type: source, queid: gameid, action: theAction, title: title, thumb: thumb }, function(data) {
		if (theAction == "que") {
			$('#dummy' + gameid).html('<input type="button" id="qbutton" class="button_remove" name="que" value="UN-queue" style="font-size:11px;" onclick="return doQue(' + gameid + ', \'remove\', \'' + source + '\', \'' + title + '\', \'' + thumb + '\');"/>');
		} else if (theAction == "remove") {
			$('#dummy' + gameid).html('<input type="button" id="qbutton" class="button" name="que" value="Queue it" onclick="return doQue(' + gameid + ', \'que\', \'' + source + '\', \'' + title + '\', \'' + thumb + '\');"/>');
		}
		showNotification({
			message: data,
			type: "information",
			autoClose: true,
			duration: 3
		});
	});		
	return false;
}
$(document).ready(function() {
	$("ul.nav li").hover(function() {
		$(this).css('background-color', '#D9D9D9');
		$(this).css('color', '#000');
	}, function() {
		$(this).css('background-color', 'transparent');
		$(this).css('color', '#3E3E3E');
	})
});