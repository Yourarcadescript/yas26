"use strict";
function changeur1() {
	if(seoState) {
		if(seoState == 'yes') {
			location.href =  siteurl + 'search/'+ $("#q").val() + '/page1/';
			return false;
		}
	}
	return false;
}
function addsmilie(code) {
	document.getElementById("comment_message").value += code;
}
function deleteFavorite(gameid,title,pageno) {
	$.get(siteurl + 'includes/deletefavorite.inc.php', { gid: gameid } );
	$.get(siteurl + 'templates/' + theme + '/ajax/loadfavorites.inc.php', { page: pageno }, function(data) {
		$('.cat').html(data);
	});
	showNotification({
		message: title + " successfully deleted from your favorites!",
		type: "success",
		autoClose: true,
		duration: 3
	});
}
function addFavorite(title,gameid) {
	$.get(siteurl + 'includes/addfavorite.inc.php', { gid: gameid } );
	showNotification({
		message: title + " successfully added to your favorites!",
		type: "success",
		autoClose: true,
		duration: 3
	});
}
function download_link(link_id) {
    var iframe;
	iframe = document.getElementById("hiddenDownloader");
	if (iframe === null) {
		iframe = document.createElement('iframe');  
		iframe.id = "hiddenDownloader";
		iframe.style.visibility = 'hidden';
		iframe.style.display = 'none';
		document.body.appendChild(iframe);
	}
	iframe.src = siteurl + 'includes/link_download.php?id=' + link_id;
	return false;
}
function addHit(linkid) {
	new Image().src = siteurl + 'outlink.php?id='+ linkid;
	return true;
}
function preloadimages(arrayOfImages){
    $(arrayOfImages).each(function(){
        $('<img/>')[0].src = this;
    });
    return true;
}
function deleteAvatar(avatarfile) {
	$.get(siteurl + 'includes/deleteavatar.inc.php', { af: avatarfile }, function(data) {
		$('#avatarimage').attr("src", siteurl + 'avatars/' + data);
	});
	$.get(siteurl + 'templates/' + theme + '/ajax/loadavatars.inc.php', { ajax:true }, function(data) {
		$('.avatarBox').html(data);
	});
	showNotification({
		message: "Avatar successfully deleted!",
		type: "success",
		autoClose: true,
		duration: 3
	});
}
function switchAvatar(avatarFile) {
	$('#avatarimage').attr("src", siteurl + 'avatars/' + avatarFile);
	$.get(siteurl + 'includes/updateavatar.inc.php', { addavatar: avatarFile } );
	showNotification({
		message: "Avatar successfully updated!",
		type: "success",
		autoClose: true,
		duration: 3
	});
}
function loadAvatars() {
	$.get(siteurl + 'templates/' + theme + '/ajax/loadavatars.inc.php', { ajax: true }, function(data) {
		$('.avatarBox').html(data);
	});
	showNotification({message: "Avatar successfully uploaded!", type: "success", autoClose: true, duration: 3});
}
function validate_fpass() { 
	var email = $("#useremail").val();
	var name = $("#username").val();
	var emailFormat = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
	if (!name || !email) { 
		$("#preview").slideDown('slow').html("<br/><h3>All fields are required!<h3>"); 
		return false; 
	}
	else if (email.search(emailFormat) == -1) {
		$("#preview").fadeIn(200).html("<br/><h3>Invalid Email Address!<h3>");
		return false;
	}
	return true;
}
function validate_contact() { 
	var email = $("#email").val();
	var name = $("#name").val();
	var	message = $("#message").val();			
	var code = $("#security").val();	
	var codeAlt = $("#code").val();	
	var emailFormat = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
	if (!name || !email || !message) { 
		$("#preview").slideDown('slow').html("<h3>All fields are required!<h3>"); 
		return false; 
	}
	else if (email.search(emailFormat) == -1) {
		$("#preview").fadeIn(200).html("<h3>Invalid Email Address!<h3>");
			return false;
	}
	if (!code) { 
		if (codeAlt) { return true; }
		$("#preview").html("<br/><h3>Missing Security code!<h3>"); 
		return false; 
	}
	else if (!codeAlt) {
		if (code) { return true; }
		$("#preview").html("<br/><h3>Missing Security code!<h3>"); 
		return false; 
	}
	return true;
}
function validate_tfriend() { 
	var email = $("#email").val();
	var name = $("#name").val();
	var recipientname = $("#recipientfname").val();
	var recipientemail = $("#recipientemail").val();		
	var code = $("#code").val();
	var emailFormat = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
	if (!name || !email|| !recipientname || !recipientemail || !code ) { 
		$("#preview").slideDown('slow').html("<h3>All fields are required!<h3>"); 
		return false; 
	}
	else if (email.length > 40 || email.length < 5 ) {
		$("#preview").html("<br/><h3>Email must be less than 40 characters!<h3>");
		return false;
	}
	else if (name.length > 26 || name.length < 3 ) {
		$("#preview").html("<br/><h3>Username must be between 3 and 26 characters!<h3>");
		return false;
	}		
	else if (recipientemail.length > 40 || recipientemail.length < 5 ) {
		$("#preview").html("<br/><h3>Email must be less than 40 characters!<h3>");
		return false;
	}
	else if (recipientname.length > 26 || recipientname.length < 3 ) {
		$("#preview").html("<br/><h3>Username must be between 3 and 26 characters!<h3>");
		return false;
	}		
	else if (email.search(emailFormat) == -1) {
			$("#preview").fadeIn(200).html("<h3>Invalid Email Address!<h3>");
			return false;
	}
	else if (recipientemail.search(emailFormat) == -1) {
		$("#preview").fadeIn(200).html("<h3>Invalid Email Address!<h3>");
		return false;
	}
	return true;
}
function validate_rcheck() { 
	var email = $("#email").val();
	var name = $("#username2").val();
	var	password = $("#password").val();
	var code = $("#security").val();	
	var codeAlt = $("#code").val();
	var emailFormat = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
	if (!name || !email || !password) { 
		$("#preview").slideDown('slow').html("<h3>Username, email and password are required!<h3>"); 
		return false; 
	}
	else if (password.length > 26 || password.length < 4) {
		$("#preview").html("<br/><h3>Password must be between 4 and 26 characters!<h3>");
		return false;
	}
	else if (email.search(emailFormat) == -1) {
		$("#preview").html("<br/><h3>Invalid Email Address!<h3>");
		return false;
	}
	if (!code) { 
		if (codeAlt) { return true; }
		$("#preview").html("<br/><h3>Missing Security code!<h3>"); 
		return false; 
	}
	else if (!codeAlt) {
		if (code) { return true; }
		$("#preview").html("<br/><h3>Missing Security code!<h3>"); 
		return false; 
	}
	return true;
}
$('document').ready(function() {
	$.get(siteurl + 'includes/jobs2.php', { sent: 'hello' }, function(data) {});
	$('#addlink').ajaxForm( {
		url: siteurl + 'templates/' + theme + '/ajax/process_linkrequest.php',
		type: 'post',
		target: '#preview',
		clearForm: false, 
		success: function() {
			var msg = $("#preview").html();
			var searchString = /approve/;
			if (msg.search(searchString) != -1) {
				$('#contactBox').slideUp('slow');
			}
			else {
				document.getElementById('security').value = '';
			}
		}
	});
	$('#profile').ajaxForm( {
		url: siteurl + 'includes/profile_update.php',
		type: 'post',
		target: '#preview',
		clearForm: false, 
		success: function() {
			var msg = $("#preview").html();
			var searchString = /updated/;
			if (msg.search(searchString) != -1) {
				$('#profileBox').slideUp('slow');
			}
		}		
	});
	$('#addcomment').ajaxForm( {
		url: siteurl + 'includes/add_comment.php',
		type: 'post',
		target: '#preview',
		clearForm: false, 
		success: function() {
			var gameid = $("#gameid").val();
			var userid = $("#userid").val();
			var newsid = $("#newsid").val();
			if (userid) {
				$.get(siteurl + 'templates/' + theme + '/ajax/comment_messages.php', {userid: userid}, function(data) {
					$('#messages').html(data);
				});
			}
			else if (newsid) {
				$.get(siteurl + 'templates/' + theme + '/ajax/comment_messages.php', {newsid: newsid}, function(data) {
					$('#messages').html(data);
				});
			}
			else {
				$.get(siteurl + 'templates/' + theme + '/ajax/comment_messages.php', {gameid: gameid}, function(data) {
					$('#messages').html(data);
				});
			}
			var msg = $("#preview").html();
			var searchString = /added/;
			if (msg.search(searchString) != -1) {
				$('#commentBox').slideUp('slow');
			}			
		}		
	});
	$('#contactform').ajaxForm( {
		url: siteurl + 'includes/submit.php',
		type: 'post',
		beforeSubmit: function() {
			return validate_contact();
		},
		target: '#preview',
		success: function() {
			var msg = $("#preview").html();
			var searchString = /sent/;
			if (msg.search(searchString) != -1) {
				$('#contactBox').slideUp('slow');
			}
			else {
				document.getElementById('code').value = '';
			}
		}
	});
	$('#formForgot').ajaxForm( {
		url: siteurl + 'includes/forgotpass.inc.php',
		type: 'post',
		beforeSubmit: function() {
			return validate_fpass();
		},
		target: '#preview',
		success: function() {
			var msg = $("#preview").html();
			var searchString = /sent/;
			if (msg.search(searchString) != -1) {
				$('#contactBox').slideUp('fast');
			}
		}
	});
	$('#arcadelogin').ajaxForm( {
		url: siteurl + 'includes/login_check.php',
		target: '#loginmessage',
		clearForm: true,
		success: function() {
			var msg = $("#loginmessage").html();
			var searchString = /Logging/;
			if (msg.search(searchString) != -1) {
				// send to url
				window.location.replace(siteurl);
				return true;
			}
			else {
				return false;
			}
		}
	});
	$('#form').ajaxForm( {
		url: siteurl + 'includes/register_check.php',
		beforeSubmit: function() {
			return validate_rcheck();
		},
		target: '#preview',
		clearForm: false,
		success: function() {
			var msg = $("#preview").html();
			var searchString = /Registered/;
			if (msg.search(searchString) != -1) {
				$('#contactBox').slideUp('slow');
			}
			else {
				if (document.getElementById('security')) {
					document.getElementById('security').value = '';
				}
				else if (document.getElementById('code')) {
					document.getElementById('code').value = '';
				}
			}
		}
	});
	$('#searchForm').ajaxForm( {		
		type: 'post',
		beforeSubmit: function() {
			return changeur1();
		}
	});
	$('#form_tfriend').ajaxForm( {
		url: siteurl + 'includes/submitafriend.php',
		type: 'post',
		beforeSubmit: function() {
			return validate_tfriend();
		},
		target: '#preview',
		success: function() {
			var msg = $("#preview").html();
			var searchString = /Message sent/;
			if (msg.search(searchString) != -1) {
				$('#contactBox').slideUp('slow');
			}
			else {
				document.getElementById('code').value = '';
			}
		}
	});
});