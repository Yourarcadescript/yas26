
<?php
/*
Page:           _drawrating.php
Created:        Aug 2006
Last Mod:       Mar 18 2007
The function that draws the rating bar.
--------------------------------------------------------- 
ryan masuga, masugadesign.com
ryan@masugadesign.com 
Licensed under a Creative Commons Attribution 3.0 License.
http://creativecommons.org/licenses/by/3.0/
See readme.txt for full credit details.
--------------------------------------------------------- */
function rating_bar($id,$units='',$static='') { 
//set some variables
global $mysqli;
global $setting;
$rating_tableName = 'ratingsbar';
$rating_unitwidth = 30;
$ip = $_SERVER['REMOTE_ADDR'];
if (!$units) {$units = 10;}
if (!$static) {$static = FALSE;}

// get votes, values, ips for the current rating bar
$query=yasDB_select("SELECT total_votes, total_value, used_ips FROM ratingsbar WHERE id='$id' ")or die(" Error");

// insert the id in the DB if it doesn't exist already
// see: http://www.masugadesign.com/the-lab/scripts/unobtrusive-ajax-star-rating-bar/#comment-121

if ($query->num_rows == 0) {
$sql = "INSERT INTO ratingsbar (`id`,`total_votes`, `total_value`, `used_ips`) VALUES ('$id', '0', '0', '')";

$result = yasDB_insert($sql);
}

$numbers = $query->fetch_array(MYSQLI_ASSOC);

if ($numbers['total_votes'] < 1) {
	$count = 0;
} else {
	$count=$numbers['total_votes']; //how many votes total
}
$current_rating=$numbers['total_value']; //total number of rating added together and stored
$tense=($count==1) ? "vote" : "votes"; //plural form votes/vote

// determine whether the user has voted, so we know how to draw the ul/li
$result = yasDB_select("SELECT used_ips FROM ratingsbar WHERE used_ips LIKE '%".$ip."%' AND id='".$id."' ");
$voted = $result->num_rows;
// now draw the rating bar
$rating_width = @number_format($current_rating/$count,2)*$rating_unitwidth;
$rating1 = @number_format($current_rating/$count,1);
$rating2 = @number_format($current_rating/$count,2);


if ($static == 'static') {

	/*	$static_rater = array();
		$static_rater[] .= "\n".'<div class="ratingblock">';
		$static_rater[] .= '<div id="unit_long'.$id.'">';
		$static_rater[] .= '<ul id="unit_ul'.$id.'" class="unit-rating" style="width:'.$rating_unitwidth*$units.'px;">';
		$static_rater[] .= '<li class="current-rating" style="width:'.$rating_width.'px;">Currently '.$rating2.'/'.$units.'</li>';
		$static_rater[] .= '</ul>';
		$static_rater[] .= '<p class="static">'.$id.'. Rating: <strong> '.$rating1.'</strong>/'.$units.' ('.$count.' '.$tense.' cast) <em>This is \'static\'.</em></p>';
		$static_rater[] .= '</div>';
		$static_rater[] .= '</div>'."\n\n";

		return join("\n", $static_rater); */
		$rater ='';
		$rater.='<div class="ratingblock">';
        $rater.='<div id="unit_long'.$id.'">';
		$rater.='  <ul id="unit_ul'.$id.'" class="unit-rating" style="width:'.$rating_unitwidth*$units.'px;">';
		$rater.='     <li class="current-rating" style="width:'.$rating_width.'px;"></li>';
		$rater.='  </ul>';
        $rater.='  <p class="voted" >';
		$rater.='  </p>';
        $rater.='</div>';
        $rater.='</div>';
        return $rater;
} else {

      $rater ='';
      $rater.='<div class="ratingblock">';

      $rater.='<div id="unit_long'.$id.'">';
      $rater.='  <ul id="unit_ul'.$id.'" class="unit-rating" style="width:'.$rating_unitwidth*$units.'px;">';
      $rater.='     <li class="current-rating" style="width:'.$rating_width.'px;"></li>';

      for ($ncount = 1; $ncount <= $units; $ncount++) { // loop from 1 to the number of units
           if(!$voted) { // if the user hasn't yet voted, draw the voting stars
              // YAS arcade url for rating bar db.php
			  $rater.='<li><a href="'.$setting['siteurl'].'includes/ratingbar/db.php?j='.$ncount.'&amp;q='.$id.'&amp;t='.$ip.'&amp;c='.$units.'" title="'.$ncount.' out of '.$units.'" class="r'.$ncount.'-unit rater" rel="nofollow"></a></li>';
		   }
      }
      $ncount=0; // resets the count

      $rater.='  </ul>';
      $rater.='  <p';
      if($voted){ $rater.=' class="voted"'; }
      //$rater.='>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong> '.$rating1.'</strong>/'.$units.' ('.$count.' '.$tense.' cast)';
      $rater.='  </p>';
      $rater.='</div>';
      $rater.='</div>';
      return $rater;
 }
}
?>