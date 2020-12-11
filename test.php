<?php
error_reporting(0);
header("Content-Type: text/plain");


$url = 'https://90p.live';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
curl_setopt($ch, CURLOPT_BINARYTRANSFER, true); 
curl_setopt($ch, CURLOPT_REFERER, 'https://90p.live');
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.172 Safari/537.22");
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: 90p.live'));
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
$kq = curl_exec($ch);
curl_close($ch); 


$pageURL = 'http';
if ($_SERVER["HTTPS"] == "on")
{
$pageURL .= "s";
}
$pageURL .= "://";
if ($_SERVER["SERVER_PORT"] != "80")
{
$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
}
else
{
$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
}



preg_match_all('/\<a href\="https:\/\/xem.binhluanvidamme.online(.*?)\<\/script\>/s',$kq,$list);


$list['0'] = preg_replace('|\<a href(.*?)\<div class\="list\-channel|is','',$list['0']);

foreach($list['0'] as $row)
{
preg_match('/\<div class\="title"\>(.*?)\<\/div\>/s',$row,$title);
preg_match('/data\-time\="(.*?)"/',$row,$time);
preg_match('/href\="(.*?)"/',$row,$id);
$data[]=array(
'id'=>$id['1'],
'name'=>date("H:i-d/m ",
strtotime($time['1'])).remove_line($title['1']),
);
}


echo "{\"name\": \"Kênh tuyển chọn Kodi\", \"author\": \"Kendo Kodi\", \"url\": \"$pageURL\", \"image\": \"https://lh3.googleusercontent.com/fp0Y8La8Pn1cjOj7_i_bFaly7MBrj89uBN8B7Sy-plI8EERNcQqD94jgoIYypD4-tw=w800\", \"contact\": \"nguyenducmanh609@gmail.com\", \"info\": \"Kênh Kendo Kodi\", \"stations\": [\r\n";
foreach($data as $m3u)
{
$name=$m3u['name'];
$link=$m3u['id'];


echo "{\"name\": \"$name\", \"imageScale\": \"centerCrop\", \"url\": \"$link\", \"referer\" : \"".$m3u['id']."\", \"userAgent\": \"Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2227.0 Safari/537.36\", \"isHost\": true},";
}
function remove_line($str){$str=str_replace("\n","",$str);
   $str=str_replace("\r","",$str);return trim($str);
}


echo "]}";
exit();
?>
