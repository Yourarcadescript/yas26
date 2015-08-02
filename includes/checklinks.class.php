<?php
/*   checklinks.class.php ver.1.1
**   Author: Jason Henry www.yourarcadescript.com 
**   Please keep authorship info intact
**   Nov. 26, 2011
**
**   Class is used to validate that a link exists on a given web page
**   Usage example:
**   include("checklinks.class.php");
**   $checklink = new checkLink; 
**   $response = $checklink->validateLink("www.website.com/links.html", "www.mywebsite.com");
**   $response will be: LINKDATAERROR(means unable to get data from the website) or LINKFOUND or LINKNOTFOUND or LINKFOUNDNOFOLLOW

** Updates in ver. 1.1
   Added the port and path to the link choices to check allowing for a deep link search. Was returning a false negative.
*/
class checkLink {
	const LINKFOUND = 1;
	const LINKNOTFOUND = 0;
	const LINKFOUNDNOFOLLOW = 2;
	const LINKDATAERROR = 3;
	
	public function get_data($url) {
	  $ch = curl_init();
	  $timeout = 5;
	  $userAgent = 'Googlebot/2.1 (http://www.googlebot.com/bot.html)';
	  curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
	  curl_setopt($ch,CURLOPT_URL,$url);
	  curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	  curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
	  $data = curl_exec($ch);
	  curl_close($ch);
	  return $data;
	}

	public function linkChoices($link) {
		$array = parse_url($link);
		$link1 = $array['host'];
		if (isset($array['port'])) {
			$link1 .= ":" . $array['port'];
		}
		if (isset($array['path'])) {
			$link1 .= $array['path'];
		}
		$links[0] = rtrim($link1,"/");
		$links[1] = str_replace("www.", "", $links[0]);
		$links[2] = "http://".trim($links[1]);
		$links[3] = "http://www.".$links[1];
		return $links;
	}

	public function validateLink($url, $url_tofind) {
		$page = $this->get_data($url);
		if (!$page) return LINKDATAERROR;
		$urlchoices = $this->linkChoices($url_tofind);
		$dom = new DOMDocument();
		@$dom->loadHTML($page);
		$x = new DOMXPath($dom);    
		foreach($x->query("//a") as $node) {
			$link = rtrim($node->getAttribute("href"), "/");
			$rel = $node->getAttribute("rel");
			if (in_array($link, $urlchoices)) {
				if(strtolower($rel) == "nofollow") {
					return LINKFOUNDNOFOLLOW;
				} else {
					return LINKFOUND;
				}
			}
		}
		return LINKNOTFOUND;
	}
}
?>