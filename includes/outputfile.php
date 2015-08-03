<?php
/*********************************************
            Example of use
**********************************************/
 
/*
Make sure script execution doesn't time out.
Set maximum execution time in seconds (0 means no limit).

set_time_limit(0); 
$file_path='that_one_file.txt';
output_file($file_path, 'some file.txt', 'text/plain');
*/
function output_file($file, $name, $mime_type='') {
 /*
 This function takes a path to a file to output ($file),
 the filename that the browser will see ($name) and
 the MIME type of the file ($mime_type, optional).
 
 If you want to do something on download abort/finish,
 register_shutdown_function('function_name');
 */
 if(!is_readable($file)) die('File not found or inaccessible!');
  
 $size = filesize($file);
 $name = rawurldecode($name);
  
 /* Figure out the MIME type (if not specified) */
 $known_mime_types=array(
    "pdf" => "application/pdf",
    "txt" => "text/plain",
    "html" => "text/html",
    "htm" => "text/html",
    "exe" => "application/octet-stream",
    "zip" => "application/zip",
    "rar" => "application/x-rar-compressed",
	"doc" => "application/msword",
    "xls" => "application/vnd.ms-excel",
    "ppt" => "application/vnd.ms-powerpoint",
    "gif" => "image/gif",
    "png" => "image/png",
    "jpeg"=> "image/jpg",
    "jpg" =>  "image/jpg",
    "php" => "text/plain",
	"swf" => "application/x-shockwave-flash"
 );
       
 if($mime_type==''){
     $file_extension = strtolower(substr(strrchr($file,"."),1));
     if(array_key_exists($file_extension, $known_mime_types)){
        $mime_type=$known_mime_types[$file_extension];
     } else {
        $mime_type="application/force-download";
     };
 };
  
 @ob_end_clean(); //turn off output buffering to decrease cpu usage
  
 // required for IE, otherwise Content-Disposition may be ignored
 if(ini_get('zlib.output_compression'))
  ini_set('zlib.output_compression', 'Off');
   
 header('Content-Type: ' . $mime_type);
 header('Content-Disposition: attachment; filename="'.$name.'"');
 header("Content-Transfer-Encoding: binary");
 header('Accept-Ranges: bytes');
  
 /* The three lines below basically make the
    download non-cacheable */
 header("Cache-control: private");
 header('Pragma: private');
 header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
 
 // multipart-download and download resuming support
 if(isset($_SERVER['HTTP_RANGE']))
 {
    list($a, $range) = explode("=",$_SERVER['HTTP_RANGE'],2);
    list($range) = explode(",",$range,2);
    list($range, $range_end) = explode("-", $range);
    $range=intval($range);
    if(!$range_end) {
        $range_end=$size-1;
    } else {
        $range_end=intval($range_end);
    }
 
    $new_length = $range_end-$range+1;
    header("HTTP/1.1 206 Partial Content");
    header("Content-Length: $new_length");
    header("Content-Range: bytes $range-$range_end/$size");
 } else {
    $new_length=$size;
    header("Content-Length: ".$size);
 }
 
 /* output the file itself */
 $chunksize = 1*(1024*1024); //you may want to change this
 $bytes_send = 0;
 if ($file = fopen($file, 'r')) {
    if(isset($_SERVER['HTTP_RANGE'])) {
		fseek($file, $range);
    } 
    while(!feof($file) && (!connection_aborted()) && ($bytes_send<$new_length)) {
        $buffer = fread($file, $chunksize);
        print($buffer); //echo($buffer); // is also possible
        flush();
        $bytes_send += strlen($buffer);
    }
	fclose($file);
 } else echo 'could not open file';
exit;
}

function isRarOrZip($file) {
    // get the first 7 bytes
    $bytes = file_get_contents($file, FALSE, NULL, 0, 7);
    $ext = strtolower(substr($file, - 4));

    // RAR magic number: Rar!\x1A\x07\x00
    // http://en.wikipedia.org/wiki/RAR
    if ($ext == '.rar' and bin2hex($bytes) == '526172211a0700') {
        return TRUE;
    }

    // ZIP magic number: none, though PK\003\004, PK\005\006 (empty archive), 
    // or PK\007\008 (spanned archive) are common.
    // http://en.wikipedia.org/wiki/ZIP_(file_format)
    if ($ext == '.zip' and substr($bytes, 0, 2) == 'PK') {
        return TRUE;
    }

    return FALSE;
}
/*************** Example ***********************
if (isRarOrZip($argv[1])) {
    echo 'It is probably a RAR or ZIP file.';
} else {
    echo 'It is probably not a RAR or ZIP file.';
}
*/
?>