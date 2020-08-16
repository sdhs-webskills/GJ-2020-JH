<?php 
namespace src\Controller;
use src\Core\DB;

class ActionController
{
	public function logout() {
		unset($_SESSION['user']);
		move("/", "로그아웃이 완료되었습니다.");
	}

	public function login_action() {
		$id = htmlspecialchars(trim($_POST['id']));
		$pw = htmlspecialchars(trim($_POST['pw']));

		if($id == "" || $pw == "") back("공백란이 존재합니다");
		else if($id == "admin" && $pw == "admin") {
			$_SESSION['user'] = (object) array("id" => "admin", "pw" => "admin");
			move("/", "로그인이 완료되었습니다.");
		}else if($id != "admin" || $pw != "admin"){
			back("아이디 또는 비밀번호가 일치하지 않습니다.");
		}
	}

	public function getNum() {
		$num1 = 31;
		$num2 = 10038;
		$endNum = 0;
		// $bool = true;
		while(!false) {
			$zero = mb_strlen($num1) == 1 ? '00' : (mb_strlen($num1) == 2 ? '0' : '');
			$endNum = "{$zero}{$num1}_{$num2}";
			if(!is_dir("../../festivalImages/$endNum")) {
				mkdir("../../festivalImages/$endNum");
				break;
			}else {
				$num1++;
				$num2++;
			}
		}
		return (object) array("sn" => $num1, "no" => $num2, "src" => "../../festivalImages/".$endNum);
	}

	public function modify_action() {
		// echo "asd";
		$arr = ["jpeg", "jpg", "png", "gif"];
		$sn = htmlspecialchars($_POST['sn']);
		$delImgs = htmlspecialchars($_POST['delImgs']);
		$delImgs = explode(",", $delImgs);
		$names = htmlspecialchars($_POST['names']);
		$area = htmlspecialchars($_POST['area']);
		$dt = htmlspecialchars($_POST['dt']);
		$location = htmlspecialchars($_POST['location']);
		$appendImgs = $_FILES["appendImgs"];
		$list = DB::fetch("SELECT * FROM item WHERE sn = ?", [$sn]);
		$chkImages = explode(",", $list->image);
		$zero = mb_strlen($list->sn) == 1 ? '00' : (mb_strlen($list->sn) == 2 ? '0' : '');
		$arr3 = [];
		$txt = "";

		if($names == "" || $area == "" || $dt == "" || $location == "Z") back("공백이 존재합니다.");

		if($delImgs !== "") $chkImages = array_values(array_diff($chkImages, $delImgs));

		if($appendImgs["name"][0] != "") { // 이미지 추가
			foreach ($appendImgs["type"] as $v) {
				if(!in_array(explode("/", $v)[1], $arr)) back("이미지 파일을 선택하여 주세요.");
			}
			foreach ($appendImgs["tmp_name"] as $key => $v) {
				$name = mt_rand(9999, 99999999999) * mt_rand(99, 999).".".explode('/', $appendImgs["type"][$key])[1]."";
				move_uploaded_file($v, "../../festivalImages/{$zero}{$sn}_{$list->no}/".$name);
				array_push($arr3, $name);
			}

			$chkImages = array_merge($chkImages,$arr3);

		}

		DB::query("UPDATE item SET nm = ?, area = ?, location = ?, dt = ?, image = ?, len = ? WHERE sn = ?",
			[$names, $area, $location, $dt, implode(",", $chkImages), count($chkImages), $sn]);
		move("/par/viewFestival","수정 완료");
	}
}