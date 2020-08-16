<?php 
use src\Core\Route;

$arr[] = ["get", "/@MainController@main"]; 
$arr[] = ["get", "/par/calendar@ViewController@calendar"];
$arr[] = ["get", "/par/viewFestival@ViewController@viewFestival"]; 

foreach (src\Core\DB::fetchAll("SELECT sn, len FROM item ORDER BY sn DESC") as $v) {
	$arr[] = ["get", "/par/views/".$v->sn."@ViewController@views"];
	$arr[] = ["get", "/par/modify/".$v->sn."@ViewController@modify"];
	$arr[] = ["get", "/download/tar/".$v->sn."@ViewController@tar"];
	$arr[] = ["get", "/download/zip/".$v->sn."@ViewController@zip"];
	$arr[] = ["get", "/img/".$v->sn."/0@ViewController@viewImage"];
	
	for($i = 0; $i < $v->len; $i++) {
		$arr[] = ["get", "/img/$v->sn/$i@ViewController@viewImage"];
	}
}

if(ss()) { // 로그인 중 일떄
	$arr[] = ["get", "/user/logout@ActionController@logout"];
	$arr[] = ["get", "/par/append_list@ViewController@append_list"];
	$arr[] = ["get", "/par/calendar@viewController@calendar"]; 

	$arr[] = ["post", "/par/modify_action@ActionController@modify_action"];
}else {
	$arr[] = ["get", "/user/login@ViewController@login"];
	$arr[] = ["get", "/user/join@ViewController@join"];

	$arr[] = ["post", "/user/login_action@ActionController@login_action"];
	$arr[] = ["post", "/user/join_action@ActionController@join_action"];
}

Route::reg($arr);