<?php
class UserAdmin extends CCTemplate
{
	// Getting some help...
	public function __construct() {
		parent::__construct();
		// Load Paginator with AJAX
		$this->Pages = new Pagination();
		// Load String Checkers
		$this->StringCheck = new StringCheckers();
		// Load XSS Class
		$this->XSSCheck = new InputFilter(1, 1);
	}
	// User Listing with ID pull
	public function UserList($id = 0, $page = 1, $limit = 2, $ajax = 1, $ajaxFunctionName) {
		// vars
		$html = '';
		$showAll = 0;
		//Checks
		if ($this->StringCheck->CheckInteger($id) === false) { // Check ID
			$showAll = 1;
		}
		if ($id == 0) {
			$showAll = 1;
		}
		if ($this->StringCheck->CheckInteger($page) === false) { // Check Page
			$page = 1;
		}
		if ($this->StringCheck->CheckInteger($limit) === false) { // Check Limit
			$limit = 1;
		}
		// Output
		if ($showAll == 1) { // Show all blog entries
			$page = $page - 1;
			$limitStartRecord = $page * $limit;
			$query_string = "SELECT * FROM " . $this->config['table_prefix'] . "users ORDER BY username DESC";
			$query_string_limit = " LIMIT " . $limitStartRecord . ", " . $limit . "";
			$query = $this->db_conn->query($query_string . $query_string_limit); // This returns true if successful
			$allRows = $this->db_conn->affected_rows; // Row Checker
			// Make sure we have data
			if ($allRows > 0) {
				$pages = $this->Pages->PagesBuilder($query_string, $limit, $page, $ajax, $ajaxFunctionName); // Pagination
				$repeat_rows = DBMySQLi::fetch_assoc($query);
				return array('paginator' => $pages, 'users' => $repeat_rows, 'querystring' => $query_string . $query_string_limit);
			} else {
				return NULL;
			}
		} else {
			$query_string = "SELECT * FROM " . $this->config['table_prefix'] . "users WHERE id = " . $id . "";
			$query = $this->db_conn->query($query_string); // This returns true if successful
			$allRows = $this->db_conn->affected_rows; // Row Checker
			// Make sure we have data
			if ($allRows > 0) {
				$pages = $this->Pages->PagesBuilder($query_string, $limit, $page, $ajax, $ajaxFunctionName); // Pagination
				$row = DBMySQLi::fetch_single($query);
				return array('paginator' => $pages, 'user' => $row);
			} else {
				return NULL;
			}
		}
	}
}
?>