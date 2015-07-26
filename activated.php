<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<style>
    *
    {
		margin:0px; padding:0px;
    }
body{background-repeat: repeat-x;background-position: top left;background-attachment: fixed;text-align: center;padding: 0 0 0 0;margin: 0 0 0 0;font-weight: normal;font-size: 12px;font-family: Tahoma, Arial, sans-serif;
background-image: linear-gradient(bottom, #FFFFFF 0%, #D9D9D9 51%);background-image: -o-linear-gradient(bottom, #FFFFFF 0%, #D9D9D9 51%);background-image: -moz-linear-gradient(bottom, #FFFFFF 0%, #D9D9D9 51%);background-image: -webkit-linear-gradient(bottom, #FFFFFF 0%, #D9D9D9 51%);
background-image: -ms-linear-gradient(bottom, #FFFFFF 0%, #D9D9D9 51%);background-image: -webkit-gradient(linear,left bottom,left top,color-stop(0, #FFFFFF),color-stop(0.51, #D9D9D9));}
    img
    {
		border: none;
    }
    .clear
    {
		clear:both;
    }
    #body_wrapper
    {
		margin: 0 auto;   width: 951px; text-align: left;
    }
    #top
    {
		background-repeat:no-repeat;
		width: 950px; height: 60px; position: relative; text-align: center;background-position:center;
    }
#menu{position: relative;width: 910px;height:25px;color:#FFF;padding-left:10px;margin-top:2px;margin-bottom:6px;background: #fff url('images/menu.png');text-align: center;line-height: 25px;
-moz-border-radius:5px;-webkit-border-radius:5px;-webkit-border-radius:5px;-khtml-border-radius: 5px;-khtml-border-radius: 5px;border-radius:5px;}
    #wrapper
    {
		width: 934px; color: #fff; background-color: #FFFFFF;
		padding-top: 8px; padding-bottom: 8px; padding-left: 10px; border:solid 2px orange; text-align: center;
		-moz-border-radius:10px;-webkit-border-radius:5px;-webkit-border-radius:5px;-khtml-border-radius: 5px;-khtml-border-radius: 5px;border-radius:5px;
	}
</style>
<style type="text/css">

#marqueecontainer{
position: relative;
width: 910px; /*marquee width */
height: 100px; /*marquee height */
background-color: white;
color:#000;
overflow: hidden;
text-align: left;
border: 2px solid #6699CC;

padding: 2px;
padding-left: 4px;
}

</style>

<script type="text/javascript">

/***********************************************
* Cross browser Marquee II- © Dynamic Drive (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit http://www.dynamicdrive.com/ for this script and 100s more.
***********************************************/

var delayb4scroll=2000 //Specify initial delay before marquee starts to scroll on page (2000=2 seconds)
var marqueespeed=2 //Specify marquee scroll speed (larger is faster 1-10)
var pauseit=1 //Pause marquee onMousever (0=no. 1=yes)?

////NO NEED TO EDIT BELOW THIS LINE////////////

var copyspeed=marqueespeed
var pausespeed=(pauseit==0)? copyspeed: 0
var actualheight=''

function scrollmarquee(){
if (parseInt(cross_marquee.style.top)>(actualheight*(-1)+8))
cross_marquee.style.top=parseInt(cross_marquee.style.top)-copyspeed+"px"
else
cross_marquee.style.top=parseInt(marqueeheight)+8+"px"
}

function initializemarquee(){
cross_marquee=document.getElementById("vmarquee")
cross_marquee.style.top=0
marqueeheight=document.getElementById("marqueecontainer").offsetHeight
actualheight=cross_marquee.offsetHeight
if (window.opera || navigator.userAgent.indexOf("Netscape/7")!=-1){ //if Opera or Netscape 7x, add scrollbars to scroll and exit
cross_marquee.style.height=marqueeheight+"px"
cross_marquee.style.overflow="scroll"
return
}
setTimeout('lefttime=setInterval("scrollmarquee()",30)', delayb4scroll)
}

if (window.addEventListener)
window.addEventListener("load", initializemarquee, false)
else if (window.attachEvent)
window.attachEvent("onload", initializemarquee)
else if (document.getElementById)
window.onload=initializemarquee


</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Account Activated</title>
</head>
<body>
<div id="body_wrapper">
    <div id="wrapper"><div id="menu">Your Account:</div>
	<div id="marqueecontainer" onMouseover="copyspeed=pausespeed" onMouseout="copyspeed=marqueespeed">
<div id="vmarquee" style="position: absolute; width: 98%;">
<?php 
include_once ("includes/config.inc.php");
include_once ("includes/db_functions.inc.php");
$id = intval($_GET['id']);
$code = yasDB_clean($_GET['code']);

if ($id && $code) 
{
 $check = yasDB_select("SELECT id FROM user WHERE id=$id AND randomkey='$code'",false);
 $checknum = $check->num_rows;
 if ($checknum==1) {
 $activated = yasDB_update("UPDATE user SET activated='1' WHERE id='$id'",false);
 echo ('<h4>Your account is now active you can now login!.<br />
 Click here to login.<br />
 <a href="' . $setting['siteurl'] . 'index.php">Login</a>
 </h4>');
 }
 else
   echo ('<h4>Invalid ID or Activation code.Please contact admin using our contact form<br />
   <a href="' . $setting['siteurl'] . 'contactus.html">Contact Us</a>
   </h4>');
}
else 
 echo ('<h4>Date missing!<br /><br /> This means something has went wrong when you tryed to register with us. <br /> Please contact admin from the contactus page <br /> 
 <a href="' . $setting['siteurl'] . 'contactus.html">Contact Us</a></h4>');
?>
</div>
</div>
</div>
</div>
</body>
</html>