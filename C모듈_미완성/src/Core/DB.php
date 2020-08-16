<?php 
namespace src\Core;

class DB
{
	static $db;

	static function getDB() {
		if(is_null(self::$db))
			self::$db = new \PDO("mysql:host=localhost; dbname=junbook; charset=utf8mb4;", "root", "", 
				[\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION, \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ]);
		return self::$db;
	}

	static function query($sql, $d = []) {
		$row = self::getDB()->prepare($sql);
		try {
			$row->execute($d);
		}catch(\Exception $e) {
			echo $e->getMessage();
		}
	}

	static function fetch($sql, $d = []) {
		$row = self::getDB()->prepare($sql);
		$row->execute($d);

		return $row->fetch();
	}

	static function fetchAll($sql, $d = []) {
		$row = self::getDB()->prepare($sql);
		$row->execute($d);

		return $row->fetchAll();
	}
}