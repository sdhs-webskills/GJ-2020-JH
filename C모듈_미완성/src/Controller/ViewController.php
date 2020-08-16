<?php 
namespace src\Controller;
use src\Core\DB;
class ViewController
{
	public function login() {
		view("user/login");
	}

	public function join() {
		view("user/join");
	}

	public function getList($sn = 1) {
		return DB::fetch("SELECT * FROM item WHERE sn = ?", [$sn]);
	} 

	public function viewFestival() {
		$list = DB::fetchAll("SELECT * FROM item ORDER BY sn DESC");

		view("par/viewFestival", ["list" => $list]);
	}

	public function views() {
		$list = self::getList((int) explode("/", $_GET['url'])[2]);

		view("/par/view", ["list" => $list]);
	}

	public function modify() {
		$list = self::getList((int) explode("/", $_GET['url'])[2]);
		$arr = (object) array("sn" => $list->sn, "arr" => explode(",", $list->image));
		$len = $list->len;

		view("/par/modify", ["list" => $list, "arr" => $arr, "len" => $len ]);
	}

	public function getImage($list) {
		$imgs = explode(",", $list->image);
		// $imgs = array_slice($imgs, 0, (count($imgs) - 1));
		$txt = (mb_strlen($list->sn) == 1 ? "00".$list->sn : "0".$list->sn)."_".$list->no;
		$src = "../../festivalImages/$txt/";

		return [$imgs, $src];
	}

	public function tar() {
		$list = self::getList((int) explode("/", $_GET['url'])[2]);
		if($list->image == "") back("이미지가 존재하지 않습니댜.");

		$images = self::getImage($list);


		fileDown($list, $images[0], "tar", $images[1]);
	}

	public function zip() {
		$list = self::getList((int) explode("/", $_GET['url'])[2]);
		if($list->image == "") back("이미지가 존재하지 않습니댜.");

		$images = self::getImage($list);


		fileDown($list, $images[0], "zip", $images[1]);
	}

	public function viewImage() {
		[, $sn, $num] = explode("/", $_GET['url']);
		$list = self::getList($sn);
		$image = self::getImage($list);
		
		viewImage($image, $num);
	}

	public function append_list() {
		view("par/append_list");
	} 

	public function calendar($p = '') {
		$arr = [];
		[$year, $month] = isset($_GET['date']) ? explode("-", $_GET['date']) : [date("Y"), date("n")];

		if($month > 12) back("잘못된 값");

		$last = date("t", strtotime("{$year}-{$month}-01"));
		$startDay = date("w", strtotime("{$year}-{$month}-01"));
		$lastDay = date("w", strtotime("{$year}-{$month}-{$last}"));
		$lastWeek = ceil(($startDay + $last) / 7);


		$nextYear = $year;
		if ($month == 12) $nextYear += 1;
		$prevYear = $year;
		if ($month == 1) $prevYear -= 1;
		$nextMonth = $month + 1;
		if ($nextMonth > 12) $nextMonth = 1;
		$prevMonth = $month - 1;
		if ($month <= 1) $prevMonth = 12;

		$prevButton = "<a class='btn btn-primary' href=\"/par/calendar?date={$prevYear}-{$prevMonth}\">이전달</a>";
		$nextButton = "<a class='btn btn-primary' href=\"/par/calendar?date={$nextYear}-{$nextMonth}\">다음달</a>";

		$day = 1;
		$zeroMonth = sprintf('%02d', $month);
		$calendarTag  = "<tbody>";
		$test = "asda-sdasd";
		for ($i = 0; $i < $lastWeek; $i++) {
			$calendarTag .= "<tr>";
			for ($j = 0; $j < 7; $j++) {
				$calendarTag .= "<td>";
				if ($i === 0 && $j < $startDay) continue;
				if ($day > $last) continue;
				$calendarTag .= $day;
				$DBstartDay = DB::fetchAll("SELECT * FROM item WHERE dt like '{$year}.{$zeroMonth}%' or dt like '{$year}-{$zeroMonth}%' ");
				if(isset($DBstartDay)) {
					foreach ($DBstartDay as $v) {
						[$DbBeforeDay, $DbAfterDay] = $DBstartDay != "" ? explode("~", trim($v->dt)) : [".-.-", ".-.-"];
						[$DbBeforeDay, $DbAfterDay] = 
						[(explode(".", $DbBeforeDay) ?? explode("-", $DbBeforeDay)),
						(explode(".", $DbAfterDay) ?? explode("-", $DbAfterDay))];
						$DbAfterDay[0] = ltrim($DbAfterDay[0], '0');
						if($day == $DbBeforeDay[2]) $calendarTag .= "<br><span class='ContentHead'>{$v->nm}</span>";
						else if($day > (int) $DbBeforeDay[2] && $day <= (int) $DbAfterDay[1] ) $calendarTag .= "<br><span class='ContentBody'></span>";
					}
				}

				// print_r($DbAfterDay[0]);

				$day += 1;
				$calendarTag .= "</td>";
			}
			$calendarTag .= "</tr>";
		}
		$calendarTag .= "</tbody>";

		view("par/calendar", ["tag" => $calendarTag, "nextButton"=>$nextButton, "prevButton"=>$prevButton]);
	}
}