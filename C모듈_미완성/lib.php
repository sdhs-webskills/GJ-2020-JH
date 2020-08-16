<?php 
function auto($f) {
	require "$f.php";
}
spl_autoload_register("auto");

function script($t) {
	echo "<script>$t</script>";
}

function alert($t) {
	script("alert('$t');");
}

function move($m, $t = "") {
	if($t != "") alert($t);
	script("location.replace('$m');");
	exit;
}

function back($t) {
	if($t != "") alert($t);
	script("history.back();");
	exit;
}

function view($f, $d = []) {
	$get = isset($_GET['url']) ? "/".$_GET['url'] : "/";
	extract($d);

	require "../src/View/temp/header.php";
	require "../src/View/$f.php";
	require "../src/View/temp/footer.php";
}

function fileDown($list = [], $imgs = "", $type, $src) {
	$names = "imgs.$type";

	if($list == [] || $imgs == "") return true;
	else if($type == "zip") {
		$zip = new ZipArchive();
		if(file_exists("imgs.zip")) unlink("imgs.zip");
		$zip = new ZipArchive();

		$zip->open($names, ZipArchive::CREATE);

		foreach ($imgs as $v) {
			$zip->addFile($src.$v, $v);
		}
		$zip->close();
	}else if($type == "tar") {
		if(file_exists("imgs.tar")) unlink("imgs.tar");
		$tar = new PharData($names);
		foreach ($imgs as $v) {
			$tar->addFile($src.$v, $v);
		}
	}

	header("Content-type: application/tar");
	header("Content-Disposition: attachment; filename=$names");
	header("Content-length: " . filesize($names));
	header("Pragma: no-cache");
	header("Expires: 0");
	readfile("$names");
	exit;
}

function viewImage($arr, $num) {
	$name = $arr[1].$arr[0][$num];
	header('Content-Type: image/jpeg');
	fpassthru(fopen($name, 'rb'));

	exit;
}

function ss() {
	return isset($_SESSION['user']) ? $_SESSION['user'] : false;
}