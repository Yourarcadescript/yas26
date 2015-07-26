<?php
function createConfigFile() {
$data = '';
$name = array();
$value = array();
$type = array();
$settings = array();
$result = yasDB_select("SELECT * FROM settings WHERE id = 1");
while ($meta = $result->fetch_field()) { 
	$name[] = $meta->name;
	$type[] = $meta->type;
}
$i = 0;
while ($row = $result->fetch_row()) { 
	$count = count($row);
	$y = 0;
	while ($y < $count) {
		$value[] = current($row);
		next($row);
		$y++;
	}
	$i++;
}
$result->free_result();
for ($i=0;$i<count($name);$i++) {
	$settings[$name[$i]][0] = $value[$i];
	$settings[$name[$i]][1] = $type[$i];
}
ksort($settings);
$keys = array_keys($settings);
$values = array_values($settings);
$data = "<?php"."\n";
$data .= "#--------------------------------
# http://www.yourarcadescript.com
#       config.inc.php
#		VERSION 2.5
#		CC BY-ND 3.0 Licensed (http://creativecommons.org/licenses/by-nd/3.0/)
#       
#       Do not manually edit
#       Use Admin Cpanel
#--------------------------------"."\n";
for ($i=0;$i<count($settings);$i++) {
	if ($keys[$i] != 'id' && $keys[$i] != 'gapassword') { 
		if ($values[$i][1] == 3) {
			$data .= "\$setting['".$keys[$i]."'] = ".$values[$i][0].";" . "\n";
		} else {
			$data .= "\$setting['".$keys[$i]."'] = '".addslashes($values[$i][0])."';" . "\n";
		}
	}
}
$data .=  "?>";
unset($name);
unset($settings);
unset($value);
global $setting;
$file = $setting['sitepath'].'/includes/config.inc.php';
$h = fopen($file, 'w');
fwrite($h,$data);
fclose($h);
}
?>