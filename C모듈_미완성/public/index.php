<?php 
session_start();

require "../lib.php";
require "../web.php";


if(src\Core\DB::fetch("SELECT COUNT(sn) as cnt FROM item")->cnt == 0) { // DB에 xml 값 넣기
	$url = "./xml/festivalList.xml";
	$xml = simplexml_load_file($url);
	$item = $xml->items->item;
	$imageArr = "";
	foreach ($item as $v) {
		$imageArr = "";
		if($v->sn <= 30) {
			for ($i=0; $i < count($v->images->image); $i++) { 
				$imageArr .= $v->images->image[$i].",";
			}
		}

		src\Core\DB::query("INSERT INTO item SET sn = ?, no = ?, nm = ?, area = ?, location = ?, dt = ?, cn = ?, image = ?, len = ?", 
			[$v->sn, $v->no, $v->nm, $v->area, ($v->sn == 34 ? $v->dt : $v->location), ($v->sn == 34 ? $v->location : $v->dt), $v->cn, $imageArr, ((int) $v->sn > 30 ? 0 : count($v->images->image))]);
	}
}

src\Core\Route::init();