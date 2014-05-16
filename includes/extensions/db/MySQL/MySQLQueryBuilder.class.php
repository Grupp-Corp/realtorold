<?php
class MySQLQueryBuilder extends DBMySQL {
	public function update($table, $data, $where = '1'){
		$q="UPDATE `$table` SET ";
	
		foreach($data as $key => $val) {
			if (strtolower($val) == 'null') {
				$q .= "`$key` = NULL, ";
			} elseif(strtolower($val) == 'now()') {
				$q .= "`$key` = NOW(), ";
			} elseif(preg_match("/^increment\((\-?\d+)\)$/i", $val, $m)) {
				$q .= "`$key` = `$key` + $m[1], "; 
			} else {
				$q .= "`$key`='" . $this->escape($val) . "', ";
			}
		}
		$q = rtrim($q, ', ') . ' WHERE ' . $where . ';';
		return $this->query($q);
	}
	public function insert($table, $data){
		$q = "INSERT INTO `$table` ";
		$v = ''; 
		$n = '';
		foreach($data as $key => $val) {
			$n .= "`$key`, ";
			if (strtolower($val) == 'null') {
				$v .= "NULL, ";
			} elseif(strtolower($val) == 'now()') {
				$v .= "NOW(), ";
			} else {
				$v .= "'" . $this->escape($val) . "', ";
			}
		}
		$q .= "(" . rtrim($n, ', ') . ") VALUES (" . rtrim($v, ', ') . ");";
		if ($this->query($q)) {
			return mysql_insert_id($this->link_id);
		} else {
			return false;
		}	
	}
}
?>