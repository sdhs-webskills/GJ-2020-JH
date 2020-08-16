<?php 
namespace src\Controller;
use src\Core\DB;
class MainController
{
	public function main($asd = "") {
		echo $asd;
		view("main");
	}
}