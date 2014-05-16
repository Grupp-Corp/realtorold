<?php
class BlogActions extends CCTemplate
{
	public function __construct() {
		parent::__construct();
	}
	// Get Blog Categories into form checkboxes
	public function ShowCategoriesInForm($bid = 0, $type = 1) {
		// Get complete list of blog categories in database
		$catquery_string = "SELECT * FROM " . $this->config['table_prefix'] . "blog_categories ORDER BY name ASC";
		$catquery = $this->db_conn->query($catquery_string); // This returns true if successful
		$allRows = $this->db_conn->affected_rows; // Row Checker
		// Check Category form output type 1 = Checkboxes
		if ($type = 1) { // Checkboxes
			$html = ''; // Start HTML Variable
			// Do we have blog categories to show?
			if ($allRows > 0) { // check if there is a category to return
				$repeat_rows = DBMySQLi::fetch_assoc($catquery);
				// Loop through blog categories
				foreach ($repeat_rows as $row) {
					$html .= '<input type="checkbox" name="catid[]" id="catid[]" value="' . $row['id'] . '"';
					// Check for submitted catid
					if (isset($_POST['catid'])) {
						// loop through submitted categories
						foreach($_POST['catid'] as $val) {
							if (isset($val) && $val == $row['id']) {
								$html .= ' checked="checked"';
							}
						}
					} else { // Check for category id's already selected via blog id
						// Get categories entered in DB
						$newquery_string = "SELECT * FROM " . $this->config['table_prefix'] . "catblog_link WHERE bid=" . $bid . "";
						$newquery = $this->db_conn->query($newquery_string); // This returns true if successful
						$allnewRows = $this->db_conn->affected_rows; // Row Checker
						// If category selections exist
						if ($allnewRows > 0) { // check if there is a category to return
							while($rowSet = $newquery->fetch_array(MYSQLI_ASSOC)) {
								$repeat_rows_reset[] = $rowSet;
							}
							foreach ($repeat_rows_reset as $dbrow) {
								if ($row['id'] == $dbrow['catid']) {
									$html .= ' checked="checked"';
								}
							}
						}
					}
					$html .= ' /> ' . $row['name'] . '&nbsp;&nbsp;';
				}
			} else {
				$html .= '<input type="checkbox" name="catid[]" id="catid[]" value="0" /> None&nbsp;&nbsp;';
			}
			$html .= '';
		} else { // other types if needed...
			
		}
		return $html;
	}
	// Add Blog
	public function AddBlog($catid, $title, $content, $author, $author_id, $keywords) {
		// Insert query
		$query_string = "INSERT INTO " . $this->config['table_prefix'] . "blog (title, content, author, author_id, keywords) VALUES ('" . $title . "', '" . $content . "', '" . $author . "', " . $author_id . ", '" . $keywords . "')";
		$query = $this->db_conn->query($query_string);
		$insertdID = $this->db_conn->insert_id; // The current inserted ID
		// Was query successful
		if ($query) {
			// is the catid set?
			if (isset($catid)) {
				// is this an array?
				if (is_array($catid)) {
					// Loop through categories
					foreach($catid as $val) {
						$query_string = "INSERT INTO " . $this->config['table_prefix'] . "catblog_link (bid, catid) VALUES (" . $insertdID . ", " . $val . ")";
						$query = $this->db_conn->query($query_string);
					}
					// Was query successful
					if ($query) {
						return true;
					} else {
						return false;
					}
				} else {
					return false;
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	// Update Blog
	public function UpdateBlog($catid, $title, $content, $author, $author_id, $keywords, $id) {
		// Update Query
		$query_string = "UPDATE " . $this->config['table_prefix'] . "blog SET title='" . $title . "', content='" . $content . "', author='" . $author  ."', author_id=" . $author_id . ", keywords='" . $keywords . "' WHERE id=" . $id . "";
		$query = $this->db_conn->query($query_string);
		// Delete Category Entries
		$query_stringdel = "DELETE FROM " . $this->config['table_prefix'] . "catblog_link WHERE bid=" . $id . "";
		$querydel = $this->db_conn->query($query_stringdel);
		// Was query successful
		if ($query) {
			// is the catid set?
			if (isset($catid)) {
				// is this an array?
				if (is_array($catid)) {
					// Loop through categories
					foreach($catid as $val) {
						// Insert Updated Entries
						$query_string = "INSERT INTO " . $this->config['table_prefix'] . "catblog_link (bid, catid) VALUES (" . $id . ", " . $val . ")";
						$query = $this->db_conn->query($query_string);
					}
					// Was query successful?
					if ($query) {
						return true;
					} else {
						return false;
					}
				} else {
					return false;
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	// Delete Blog
	public function DeleteBlog($id) {
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
			$query_string = "DELETE FROM " . $this->config['table_prefix'] . "blog WHERE id=" . $id . "";
			$query = $this->db_conn->query($query_string);
			return true;
		} else {
			return false;
		}
	}
	// Select Blog
	public function GetBlogSelect($id) {
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
			$row = $this->db_conn->query("SELECT * FROM " . $this->config['table_prefix'] . "blog WHERE id='" . $id . "'"); // Get user db data
			$fetch_single = DBMySQLi::fetch_single($row);
			return $fetch_single;
		} else {
			return false;
		}
	}
	// Get all blogs
	public function GetAllBlogs($sortcol = '', $sortby = '') {
		// Sort Check
		if ($sortcol == '') {
			$sortcol = 'id';
		} else if ($sortcol == 'date') {
			$sortcol = 'time_stamp';
		}
		if ($sortby == '') {
			$sortby = 'DESC';
		}
		$query_string = "SELECT * FROM " . $this->config['table_prefix'] . "blog ORDER BY " . $sortcol . " " . $sortby . "";
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
	// Get Blog Comments for edit
	public function ShowBlogComments($id, $ajax = 1) {
		$html = '';
		// Check for no errors
		$FetchCommentsQuery = "SELECT * FROM (SELECT * FROM " . $this->config['table_prefix'] . "blog_comments WHERE bid = " . $id . "  ORDER BY id DESC) as derivedtable ORDER BY 1";
		$DoQuery = $this->db_conn->query($FetchCommentsQuery); // This returns true if successful
		$allRows = $this->db_conn->affected_rows;
		// Do we have rows to return?
		if ($allRows > 0) {
			$repeat_rows = DBMySQLi::fetch_assoc($DoQuery);
			// Loop through all rows and display content
			foreach ($repeat_rows as $row) {
				$html .= '<textarea id="blog_comment_id' . $row['id'] . '" name="blog_comment_id' . $row['id'] . '" rows="10" cols="90">' . nl2br(stripcslashes($row['comment'])) . '</textarea>';
			}
			return $html;
		} else {
			return false;
		}
	}
}
?>