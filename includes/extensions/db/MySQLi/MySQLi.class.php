<?php
class DBMySQLi
{
	private static $connection;
	
	public static function SQL($host = '', $user = '', $pass = '', $db = '') {
		if(empty(self::$connection)) {
			@self::$connection = new mysqli($host, $user, $pass, $db);
			if (mysqli_connect_errno() != 0) {
				self::$connection = false;
				return self::$connection;
			} else {
				return self::$connection;
			}
		}
		return self::$connection;
	}
	public static function fetch_single($result) {
        $rows_reset = array();
		while ($rows = $result->fetch_array(MYSQLI_ASSOC)) {
			$rows_reset[] = $rows;
		}
		if (isset($rows_reset[0])) {
			return $rows_reset[0];
		} else {
			return false;
		}
	}
	public function fetch_assoc($result) {
        $rows_reset = array();
		while ($rows = $result->fetch_array(MYSQLI_ASSOC)) {
			$rows_reset[] = $rows;
		}
		return $rows_reset;
	}
}
?>