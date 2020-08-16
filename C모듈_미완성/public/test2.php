<?php 
// $txt = explode("/", $_GET['url'])[1];
$sn = explode("_", $_GET['num'])[0];
$num = explode("_", $_GET['num'])[1];

$list = src\Core\DB::fetch("SELECT * FROM item WHERE sn = ?", [$sn]);
$imgs = explode(",", $list->image);
$imgs = array_slice($imgs, 0, (count($imgs) - 1));
$src = "../../festivalImages/".(mb_strlen($list->sn) == 1 ? "00".$list->sn : "0".$list->sn)."_".$list->no."/";

$name = $src.$imgs[$num];
header('Content-Type: image/jpeg');
fpassthru(fopen($name, 'rb'));

exit;