<?php
class CategoryActions extends BlogActions
{
	public function __construct() {
		parent::__construct();
	}
	// Category List
	public function CategoryList($sortcol = '', $sortby = '') {
		// Sort Check
		if ($sortcol == '') {
			$sortcol = 'name';
		}
		if ($sortby == '') {
			$sortby = 'ASC';
		}
		$query_string = "SELECT * FROM " . $this->config['table_prefix'] . "blog_categories ORDER BY " . $sortcol . " " . $sortby . "";
		$query = $this->db_conn->query($query_string); // This returns true if successful
		$allRows = $this->db_conn->affected_rows; // Row Checker
		// Content build
		if ($allRows > 0) { // check if there is a category to return
			$repeat_rows = DBMySQLi::fetch_assoc($query);
			return array('TotalRows' => $allRows, 'RowArray' => $repeat_rows);
		} else {
			return array('TotalRows' => false, 'RowArray' => false);
		}
	}
	// Add Category
	public function AddCategory($name, $description) {
		// Insert query
		$query_string = "INSERT INTO " . $this->config['table_prefix'] . "blog_categories (name, description) VALUES ('" . $name . "', '" . $description . "')";
		$query = $this->db_conn->query($query_string);
		$insertdID = $this->db_conn->insert_id; // The current inserted ID
		// Was query successful
		if ($query) {
			return true;
		} else {
			return false;
		}
	}
	// Update Category
	public function UpdateCategory($catname, $description, $id) {
		// Update Query
		$query_string = "UPDATE " . $this->config['table_prefix'] . "blog_categories SET name='" . $catname . "', description='" . $description . "' WHERE id=" . $id . "";
		$query = $this->db_conn->query($query_string);
		// Was query successful
		if ($query) {
			return true;
		} else {
			return false;
		}
	}
	// Delete Category
	public function DeleteCategory($id) {
		// ID Check
		if (isset($id)) {
			if (!is_numeric($id)) {
				$skip = 1;	
			} else {
				$skip = 0;
			}
		} else {
			$skip = 1;
		}
		if ($skip == 0) {
			$query_string = "DELETE FROM " . $this->config['table_prefix'] . "blog_categories WHERE id=" . $id . "";
			$query = $this->db_conn->query($query_string);
			$query_string = "DELETE FROM " . $this->config['table_prefix'] . "catblog_link WHERE catid=" . $id . "";
			$query = $this->db_conn->query($query_string);
			return true;
		} else {
			return false;
		}
	}
	// Get Category
	public function GetCategory($id) {
		// ID Check
		if (isset($id)) {
			if (!is_numeric($id)) {
				$skip = 1;	
			} else {
				if ($id > 0) {
					$skip = 0;
				} else {
					$skip = 1;	
				}
			}
		} else {
			$skip = 1;
		}
		if ($skip == 0) {
			$row = $this->db_conn->query("SELECT * FROM " . $this->config['table_prefix'] . "blog_categories WHERE id='" . $id . "'"); // Get user db data
			$repeat_rows = DBMySQLi::fetch_single($row);
			return $repeat_rows;
		} else {
			return false;
		}
	}
}
?>