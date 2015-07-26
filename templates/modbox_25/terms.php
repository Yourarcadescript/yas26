         <div id="center">
<div class="container_box1">
<div class="header">Terms/F.A.Q./Legal Notice</div>             
<?php
echo '<div class="container_box2">
<p><b>Privacy Policy:</b>
<p>During your visit, '.$setting['sitename'].' may collect the following</p>
<ul style="list-style:none;">
  <li>IP Address </li>
  <li>Web Cookies </li>
  <li>Session IDs </li>
  <li>Web browser</li>
</ul>
<br/>
<p>This site contains links to other sites. '.$setting['sitename'].' is not responsible 
for the privacy practices or content of those web sites. '.$setting['sitename'].' recommends 
you refer to other sites privacy policies for more information. </p>
<p>In addition, Some of our advertisers might collect info as well. 
We have no control over 
their collection of data.</p>


<p>Should you have any questions about this privacy statement,  
the practices of this site, or a technical problem about the 
site that you cannot resolve, please contact us. <br />
</p>
<p>
<br/>
<b>Legal Notice:</b>
<p>


'.$setting['sitename'].' claims no ownership of any game(s) on our site. 
All rights revert back to the authors of said games and may be taken down at any time 
if desired. All games presented on '.$setting['sitename'].' are for the enjoyment of our visitors, and 
are not in any way for sale, resale, or distribution through '.$setting['sitename'].', unless expressly noted. If you feel that 
your material is in violation of copyright laws, please contact us immediately and we will 
remove your content as soon as possible. Please make sure to reference EXACTLY what it is that 
you feel is in violation; ie, which game, URL, ect. If you do not include such information we 
may not know what material it is and will not 
be able to act on it until you provide such information.


<p>
<br/>
<b>Terms of Service:</b>
<p>
<ul style="list-style:none;">
<li>You may not be abusive to others, attack them, etc. Hate, bigotry, racisim, etc, have no place here.</li>
<li>You may not hold '.$setting['sitename'].' responsible for anything that you see on this site. 
We attempt to moderate it to some degree, but due to the dynamic nature of some content, like member and visitor comments, we can not catch everything all the time. 
We cannot be help liable for the comments or material of others.</li>
<li>If you have any issue with anything that you find on this site, 
please do let us know about it. It may be that it is 
something we also do not like, and will attempt to do something about it. 
Please bear in mind though that we do try to give people some leeway.</li>
<li>We reserve all rights to ourselves, including the rights to delete anybody\'s account for any reason or none, 
change these terms as we see fit, and take control as we see fit.</li>
<li>This site may not be used for anything against the law, In our jurisdiction or your own.</li>
<li>When we make a descision, it is final. There is no route of appeal.</li>
<li>This site is not meant for young children(under the age of 13). 
We can not police everything all the time, things happen in here 
and it might take a while before we can sort things out. Parents, 
please do not allow young children to be unsupervised.</li>

<li>You may not use this site unless you agree to these terms, and any alterations we make to them. Your use of this site 
is taken as aceptence of these terms.</li>
</ul>
<p>
<br/>
<ul style="list-style:none;">
<b>Terms of use for the games listed in the download section.</b><p>
<li>
The games listed here are copyright material of their developers and/or sponsors.<p>
You are free to download and use those games on your website or blog, or any other internet media, as long as you do not modify the game files in any way.<p>
You are not allowed to edit the game file, like removing logos, links or credits from the game or replacing them with your own.<p>
Do not use any method to disable the links within the game in any way.<p>
You are not allowed to charge users for playing these games on your website or any other media.</li></ul>

<p>
<br/>
<b>F.A.Q.</b><p>


<ul style="list-style:none;">
<li>

Why doesn\'t the game play?

<p>
There may be more than one reason
<p>

<ul style="list-style:none;">
<li>you might be having problems with a firewall or security setting on your computer. 
You have to deal with your network admin over this one.</li>
<p>
<li>You might need to install the proper plugin. Here is a link to the flash player.</li><p>

<a href="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash&promoid=BIOW">Flash</a>            
<p>

</ul>
</li>
<li>Where do the games, pics, etc come from?<p>
Various places. Usually they are put out for use by their author for use by the public or arcade sites. 
Some are paid for.<p>

<p>
</li>

<li>Are they for sale??<p>
Not through us. They belong to their originator or authors, contact them about buying them.


<p>
</li>
<li>I entered my website or comment, why doesn\'t it show up?<p>
It might need to be approved, or the site settings might be set up so that you need to play a certain number of games first.<p>

</li>
<li>I want to know about advertising on your site.<p>
Use the contact us form below.<p>
</li>';
if ($setting['seo']=='yes') { 
echo '<li><a href="'.$setting['siteurl'].'contactus.html">Contact-Us</a></li>';
} else {
echo '<li><a href="'.$setting['siteurl'].'index.php?act=contactus">Contact-Us</a></li>';
};     
?>
</div>
<div class="clear"></div>
</div>