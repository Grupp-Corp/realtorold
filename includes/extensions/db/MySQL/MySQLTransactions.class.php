<?php
class Transaction {
	// Construct
	function __construct() {
		// Initialize the database
		$this->db_conn = DBMySQL::obtain(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_NAME);
		// connect to the server 
		$this->db_conn->connect();
	}
	// Select
	public function SelectQuery($table, $where = '', $columns = '', $orderby = '') {
		// Secure
		if (isset($table)) {
			if ($table > '') {
				$table = stripslashes(trim(strip_tags(mysql_real_escape_string($table))));
				if ((!isset($table)) or ($table == "")) {
					return false;
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
		if (isset($where)) {
			if ($where > '') {
				$where = stripslashes(trim(strip_tags(mysql_real_escape_string($where))));
				if ((!isset($where)) or ($where == "")) {
					$where = '';
				}
			} else {
				$where = '';
			}
		} else {
			$where = '';
		}
		if (isset($orderby)) {
			if ($orderby > '') {
				$orderby = stripslashes(trim(strip_tags(mysql_real_escape_string($orderby))));
				if ((!isset($orderby)) or ($orderby == "")) {
					$orderby = '';
				}
			} else {
				$orderby = '';
			}
		} else {
			$orderby = '';
		}
		if (isset($columns)) {
			if ($columns > '') {
				$columns = stripslashes(trim(strip_tags(mysql_real_escape_string($columns))));
				if ((!isset($columns)) or ($columns == "")) {
					return false;
				}
			} else {
				$columns = '*';
			}
		} else {
			$columns = '*';
		}
		// DB Obtain
		$query_string = "SELECT " . $columns . " FROM " . TABLE_PREFIX . "" . $table . "";
		if ($where > '') {
			 $query_string = " WHERE " . $where . "";
		}
		if ($orderby > '') {
			 $query_string = " ORDER BY " . $orderby . "";
		}
		$query = $this->db_conn->query($query_string);
		$row = $this->db_conn->fetch($query);
		return $row;
	}
	// Add
	public function InsertQuery($table, $columns, $values) {
		// Secure Columns
		$columns = explode(",", $columns);
		$columns_count = count($columns);
		$column_clean = "";
		for ($i = 0; $i < $columns_count; $i++) {
			$stringc_ind = stripslashes(trim(strip_tags(mysql_real_escape_string($columns[$i]))));
			if ((!isset($stringc_ind)) or ($stringc_ind == "")) {
				return false;
			}
			$column_clean .= $stringc_ind;
			$k = $i + 1;
			if ($k != $columns_count) {
				$column_clean .= ", ";
			}
		}
		// Secure Values
		$values = explode(",", $values);
		$values_count = count($values);
		$values_clean = "";
		for ($j = 0; $j < $values_count; $j++) {
			$stringv_ind = "'" . stripslashes(trim(strip_tags(mysql_real_escape_string($values[$j])))) . "'";
			if ((!isset($stringv_ind)) or ($stringv_ind == "")) {
				return false;
			}
			$values_clean .= $stringv_ind;
			$m = $j + 1;
			if ($m != $values_count) {
				$values_clean .= ", ";
			}
		}
		// Make sure we have equal columns to values
		$k = $k; // column count
		$m = $m; // value count
		if ($k != $m) {
			return false;
		} else {
			$this->db_conn->query("SET NAMES 'latin1'");
			$query_string = "INSERT INTO " . TABLE_PREFIX . "" . $table . " (" . $column_clean . ") VALUES (" . $values_clean . ")";
			$query = $this->db_conn->query($query_string);
			if (!$query) {
				return false;
			} else {
				return true;
			}
		}
	}
	//Edit
	public function EditQuery($table, $columns, $values, $where_col, $where_val) {
		// Secure Columns
		$columns = explode(",", $columns);
		$columns_count = count($columns);
		$column_clean = "";
		for ($i = 0; $i < $columns_count; $i++) {
			$stringc_ind = stripslashes(trim(strip_tags(mysql_real_escape_string($columns[$i]))));
			if ((!isset($stringc_ind)) or ($stringc_ind == "")) {
				return false;
			}
			$column_clean .= $stringc_ind;
			$k = $i + 1;
			if ($k != $columns_count) {
				$column_clean .= ",";
			}
		}
		// Secure Values
		$values = explode(",", $values);
		$values_count = count($values);
		$values_clean = "";
		for ($j = 0; $j < $values_count; $j++) {
			$stringv_ind = "" . stripslashes(trim(strip_tags(mysql_real_escape_string($values[$j])))) . "";
			if ((!isset($stringv_ind)) or ($stringv_ind == "")) {
				return false;
			}
			$values_clean .= $stringv_ind;
			$m = $j + 1;
			if ($m != $values_count) {
				$values_clean .= ",";
			}
		}
		// Secure Columns
		$where_col_clean = "";
		$stringw_ind = "" . stripslashes(trim(strip_tags(mysql_real_escape_string($where_col)))) . "";
		if ((!isset($stringw_ind)) or ($stringw_ind == "")) {
			return false;
		}
		$where_col_clean .= $stringw_ind;
		// Secure Values
		$where_val_clean = "";
		$stringwv_ind = "" . stripslashes(trim(strip_tags(mysql_real_escape_string($where_val)))) . "";
		if ((!isset($stringwv_ind)) or ($stringwv_ind == "")) {
			return false;
		}
		$where_val_clean .= $stringwv_ind;
		// Make sure we have equal columns to values
		$k = $k; // column count
		$m = $m; // value count
		if ($k != $m) {
			return false;
		} else {
			$column_clean = explode(",", $column_clean);
			$values_clean = explode(",", $values_clean);
			$set_string = "UPDATE " . TABLE_PREFIX . "" . $table . " SET ";
			for ($p = 0; $p < $values_count; $p++) {
				$set_string .= $column_clean[$p] . " = '" . $values_clean[$p] . "'";
				$b = $p + 1;
				if ($b != $values_count) {
					$set_string .= ", ";
				}
			}
			$updatesql = $set_string . " WHERE " . $where_col_clean . " = " . $where_val_clean . "";
			$this->db_conn->query("SET NAMES 'latin1'");
			$query = $this->db_conn->query($updatesql);
			if (!$query) {
				return false;
			} else {
				return true;
			}
		}
	}
	//Delete
	public function DeleteQuery($table, $columns, $values) {
		// Secure Columns
		$columns = explode(",", $columns);
		$columns_count = count($columns);
		$column_clean = "";
		for ($i = 0; $i < $columns_count; $i++) {
			$stringc_ind = stripslashes(trim(strip_tags(mysql_real_escape_string($columns[$i]))));
			if ((!isset($stringc_ind)) or ($stringc_ind == "")) {
				return false;
			}
			$column_clean .= $stringc_ind;
			$k = $i + 1;
			if ($k != $columns_count) {
				$column_clean .= ",";	
			}
		}
		// Secure Values
		$values = explode(",", $values);
		$values_count = count($values);
		$values_clean = "";
		for ($j = 0; $j < $values_count; $j++) {
			$stringv_ind = "'" . stripslashes(trim(strip_tags(mysql_real_escape_string($values[$j])))) . "'";
			if ((!isset($stringv_ind)) or ($stringv_ind == "")) {
				return false;
			}
			$values_clean .= $stringv_ind;
			$m = $j + 1;
			if ($m != $values_count) {
				$values_clean .= ",";
			}
		}
		// Make sure we have equal columns to values
		$k = $k; // column count
		$m = $m; // value count
		if ($k != $m) {
			return false;
		} else {
			$sql_all_ok = 0;
			$column_clean = explode(",", $column_clean);
			$values_clean = explode(",", $values_clean);
			for ($p = 0; $p < $values_count; $p++) {
				$editsql = "DELETE FROM " . TABLE_PREFIX . "" . $table . " WHERE " . $column_clean[$p] . " = " . $values_clean[$p] . "";
				$this->db_conn->query("SET NAMES 'latin1'");
				$query = $this->db_conn->query($editsql);
				if ($query) {
					$sql_all_ok = 1;
				}
			}
			if ($sql_all_ok == 0) {
				return false;
			} else {
				return true;
			}
		}
	}
}
?>