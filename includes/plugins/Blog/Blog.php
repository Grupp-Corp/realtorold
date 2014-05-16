<?php
class Blog extends CCTemplate
{
	public function __construct() {
		global $SocialOption; // Social Plugin
		// Parents Construct
		parent::__construct();
		// Load Paginator with AJAX
		$this->Pages = new Pagination();
		// Load String Checkers
		$this->StringCheck = new StringCheckers();
		// Load String Checkers
		$this->CheckUser = new UserChecks();
		// Load XSS Class
		$this->XSSCheck = new InputFilter(1, 1);
		// Load Social Option
		if ($SocialOption == 1) {
			$this->Social = new Socials();
		}
	}
	// Get Blog Categories
	public function GetBlogCategories($name = '', $type = 'inline', $CatSelect = 0) {
		// Check ID
		if (!isset($name)) {
			$name = 0;
		} else if ($name == '') {
			$name = 0;
		} else {
			$name = str_replace('_', ' ', $name);
		}
		// Start HTML
		$html = '';
		// Check ID
		if ($name != 0) {
			if ($type == 'inline') { // inline type
				$catquery_string = "SELECT * FROM " . $this->config['table_prefix'] . "blog_categories WHERE name = '" . $name . "'";
				$catquery = $this->db_conn->query($catquery_string); // This returns true if successful
				$allRows = $this->db_conn->affected_rows; // Row Checker
				if ($allRows > 0) { // check if there is a category to return
					while($row = $catquery->fetch_array(MYSQLI_ASSOC)) {
						$repeat_rows_reset[] = $row;
					}
					$html .= '<strong>Category:</strong> ';
					$i = 1; // Start counter
					// Loop
					foreach ($repeat_rows_reset as $rep_row) {
						// if we have multiple rows let's split it up with a comma
						if ($allRows != $i) {
							$html .= '<a href="/blog/category/' . $rep_row['name'] . '" title="' . $rep_row['name'] . '">' . $rep_row['name'] . '</a>, ';
						} else {
							$html .= '<a href="/blog/category/' . $rep_row['name'] . '" title="' . $rep_row['name'] . '">' . $rep_row['name'] . '</a><br />';
						}
						// Increment counter
						$i++;
					}
				}
			}
		} else {
			if ($type == 'inline') { // inline type
				$catquery_string = "SELECT * FROM " . $this->config['table_prefix'] . "blog_categories WHERE name = '" . $name . "'";
				$catquery = $this->db_conn->query($catquery_string); // This returns true if successful
				$allRows = $this->db_conn->affected_rows; // Row Checker
				if ($allRows > 0) { // check if there is a category to return
					while($row = $catquery->fetch_array(MYSQLI_ASSOC)) {
						$repeat_rows_reset[] = $row;
					}
					$html .= '<strong>Category:</strong> ';
					$i = 1; // Start counter
					// Loop
					foreach ($repeat_rows_reset as $rep_row) {
						// if we have multiple rows let's split it up with a comma
						if ($allRows != $i) {
							$html .= $rep_row['name'] . ', ';
						} else {
							$html .= $rep_row['name'] . '<br />';
						}
						// Increment counter
						$i++; 
					}
				}
			} elseif ($type == 'FormSelects') { // form select options
				$catquery_string = "SELECT DISTINCT " . $this->config['table_prefix'] . "blog_categories.id, " . $this->config['table_prefix'] . "blog_categories.name, " . $this->config['table_prefix'] . "blog_categories.id FROM " . $this->config['table_prefix'] . "catblog_link, " . $this->config['table_prefix'] . "blog_categories WHERE (" . $this->config['table_prefix'] . "catblog_link.catid = " . $this->config['table_prefix'] . "blog_categories.id) ORDER BY " . $this->config['table_prefix'] . "blog_categories.name ASC";
				$catquery = $this->db_conn->query($catquery_string); // This returns true if successful
				$allRows = $this->db_conn->affected_rows; // Row Checker
				if ($allRows > 0) { // check if there is a category to return
					while($row = $catquery->fetch_array(MYSQLI_ASSOC)) {
						$repeat_rows_reset[] = $row;
					}
					$html .= '<form action="/blog/" class="inline" id="CatSearchBlog" name="CatSearchBlog" method="post">';
					$html .= '<input type="hidden" id="redirect" name="redirect" value="1" />';
					$html .= '<select id="category" name="category" class="inline" required="required">';
					$html .= '<option value="">[Select]</option>';
					foreach ($repeat_rows_reset as $row) {
						if ((((isset($CatSelect)) && ($CatSelect > '') && (strlen($CatSelect) > 3) && (str_replace('_', ' ', $CatSelect) == $row['name'])))) {
							$html .= '<option value="' . str_replace(' ', '_', $row['name']) . '" selected="selected">&bull; ' . $row['name'] . '</option>' . PHP_EOL;
						} else {
							$html .= '<option value="' . str_replace(' ', '_', $row['name']) . '">&bull; ' . $row['name'] . '</option>' . PHP_EOL;
						}
					}
					$html .= '</select>';
					$html .= '<input type="submit" class="btn" id="CatSearch" name="CatSearch" value="Go" />';
					$html .= '</form><br /><br />';
				}
			} else if ($type == 'TitlePull') { // inline type
				$newquery_string = "SELECT * FROM " . $this->config['table_prefix'] . "blog_categories WHERE name = '" . str_replace('_', ' ', $CatSelect) . "'";
				$newquery = $this->db_conn->query($newquery_string); // This returns true if successful
				while ($row = $newquery->fetch_array(MYSQLI_ASSOC)) {
					$rowReturn[] = $row;
				}
				$allRows = $this->db_conn->affected_rows; // Row Checker
				// if we have multiple rows let's split it up with a comma
				if ($allRows > 0) {
					return array('name' => $rowReturn[0]['name'], 'description' => $rowReturn[0]['description']);
				}
			} else {
				$html .= NULL;
			}
		}
		if ($type != 'TitlePull') {
			return $html;
		}
	}
	// Show Blog Contents
	public function ShowBlog($id = 0, $title = '', $page = 1, $category = 0, $limit = 2, $ajax = 1) {
		// Globals
		global $BlogContentIndexLimit;
		global $ShowLink_Delicious;
		global $ShowLink_Twitter;
		global $ShowLink_Facebook;
		global $SocialOption;
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
		if ((@isset($category)) && (strlen($category) > 3)) { // Check Page
			$category = str_replace('_', ' ', $category);
		} else {
			$category = '';
		}
		if ($this->StringCheck->CheckInteger($limit) === false) { // Check Limit
			$limit = 1;
		}
		// Output
		if ($showAll == 1) { // Show all blog entries
			// Setting some variables and getting data
			$page = $page - 1;
			$limitStartRecord = $page * $limit;
			// Check for categorical listings...
			if ($category > '') {
				$query_string = "SELECT * 
				FROM " . $this->config['table_prefix'] . "blog 
				LEFT JOIN " . $this->config['table_prefix'] . "catblog_link 
				ON " . $this->config['table_prefix'] . "blog.id = " . $this->config['table_prefix'] . "catblog_link.bid 
				LEFT JOIN " . $this->config['table_prefix'] . "blog_categories 
				ON  " . $this->config['table_prefix'] . "blog_categories.id = " . $this->config['table_prefix'] . "catblog_link.catid 
				WHERE " . $this->config['table_prefix'] . "blog_categories.name = '" . $category . "' 
				ORDER BY " . $this->config['table_prefix'] . "blog.time_stamp DESC";
			} else { // Show all
				$query_string = "SELECT * FROM " . $this->config['table_prefix'] . "blog ORDER BY time_stamp DESC";
			}
			$query_string_limit = " LIMIT " . $limitStartRecord . ", " . $limit . "";
			$query = $this->db_conn->query($query_string . $query_string_limit); // This returns true if successful
			//$row = $query->fetch_array(MYSQLI_ASSOC); // Data array based on column names
			$allRows = $this->db_conn->affected_rows; // Row Checker
			// Make sure we have data
			if ($allRows > 0) {
				// Pagination
				$pages = $this->Pages->PagesBuilder($query_string, $limit, $page, $ajax, '', '/blog'); // Pagination
				while($row = $query->fetch_array(MYSQLI_ASSOC)) {
					$repeat_rows_reset[] = $row;
				}
				$BlogDataSet = array('rowset' => false, 'repeatRows' => $repeat_rows_reset, 'allRows' => $allRows, 'Paginator' => $pages, 'title' => $title);
			} else {
				$BlogDataSet = array('repeatRows' => '', 'allRows' => '', 'Paginator' => '', 'title' => $title);
			}
		} else { // Show Specific Blog Entry
			// Query
			$query_string = "SELECT * FROM " . $this->config['table_prefix'] . "blog WHERE id = " . $id . "";
			$rowQuery = $this->db_conn->query($query_string); // Data array based on column names
			while ($row = $rowQuery->fetch_array(MYSQLI_ASSOC)) {
				$rowReturn[] = $row;
			}
			$allRows = $this->db_conn->affected_rows; // Row Checker
			$BlogDataSet = array('rowset' => $rowReturn[0], 'repeatRows' => false, 'allRows' => $allRows, 'Paginator' => false, 'title' => $title);
		}
		// Return
		return $BlogDataSet;
	}
	// Show Blog comments
	public function ShowBlogComments($id, $ajax = 1) {
		$error = 1;
		$html = '';
		// Check errors
		if (isset($_SESSION[$this->config['session_prefix'] . 'username'])) {
			if ($_SESSION[$this->config['session_prefix'] . 'username'] > '') {
				if ($_SESSION[$this->config['session_prefix'] . 'username'] != 'Anonymous') {
					$error = 0;
				}
			}
		}
		// Check for no errors
		if ($error == 0) {
			$FetchCommentsQuery = "SELECT * FROM (SELECT * FROM " . $this->config['table_prefix'] . "blog_comments WHERE bid = " . $id . "  ORDER BY id DESC) as derivedtable ORDER BY 1";
			$DoQuery = $this->db_conn->query($FetchCommentsQuery); // This returns true if successful
			$allRows = $this->db_conn->affected_rows;
			$html .= '<a id="comment"></a>';
			$html .= '<form action="' . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'] . '#comment" name="commentform" id="commentform" method="post">';
			$html .= '<br /><br />';
			// Do we have rows to return?
			if ($allRows > 0) {
				$html .= '<h3 class="comments">Comment(s)</h3>';
				while($row = $DoQuery->fetch_array(MYSQLI_ASSOC)) {
					$repeat_rows_reset[] = $row;
				}
				// Loop through all rows and display content
				foreach ($repeat_rows_reset as $row) {
					$html .= '<div class="commentAuthor">' . $row['username'] . ' says:';
					$html .= '<div class="commentBox">';								
					$html .= nl2br(stripcslashes($row['comment']));
					$html .= '<br /><br />';
					$html .= '<div class="smalltext">Posted ' . $row['time_stamp'] . '</div>';
					$html .= '</div></div>';
					$html .= '<br />';
				}
			}
			$html .= '<label title="Make a Comment">';
			$html .= '<textarea name="commentarea" rows="10" cols="55" id="commentarea"></textarea>';
			$html .= '</label>';
			$html .= '<br /><br />';
			$html .= '<label title="Submit your Comment">';
			if ($ajax == 1) {
				$html .= '<input type="button" name="buttonset" id="buttonset" value="Submit Comment" onclick="javascript:SubmitBlogComment(' . $id . ', \"' . $_SESSION[SESS_PREFIX . 'username'] . '\", ' . $_SESSION[SESS_PREFIX . 'id'] . ', \"commentarea\");" />';
			} else {
				$html .= '<input type="submit" name="submit" id="submit" value="Submit Comment" />';
			}
			$html .= '</label>';
			$html .= '<br /><br /><br /><br />';
			$html .= '</form>';
			return $html;
		} else {
			return '';
		}
	}
	// Submitted Comment
	public function SubmitBlogComment($bid, $user, $userid, $comment) {
		// Error Check
		$error = 0;
		if (!isset($bid)) {
			$error = 1;
		} elseif (!is_numeric($bid)) {
			$error = 1;
		}
		if (!isset($user)) {
			$error = 1;
		} elseif ($user == '') {
			$error = 1;
		} elseif ($user == 'Anonymous') {
			$error = 1;
		}
		if (!isset($userid)) {
			$error = 1;
		} elseif (!is_numeric($userid)) {
			$error = 1;
		}
		if (!isset($comment)) {
			$error = 1;
		} elseif ($comment == '') {
			$error = 1;
		}
		$comment = $this->XSSCheck->process($comment); // XSS Checker
		$comment = mysql_real_escape_string($comment); // make a safe sql string
		// Check if errors are present
		if ($error == 0) {
			// Querying
			$query_string = "INSERT INTO " . $this->config['table_prefix'] . "blog_comments (bid, uid, username, comment) VALUES ('" . $bid . "', '" . $userid . "', '" . $user . "', '" . $comment . "')"; // insert query
			// Was the insert coment successful?
			if ($this->db_conn->query($query_string) === true) {
				/*$error = 1;
				$html = '';
				// Check errors
				if (isset($_SESSION[SESS_PREFIX . 'username'])) {
					if ($_SESSION[SESS_PREFIX . 'username'] > '') {
						if ($_SESSION[SESS_PREFIX . 'username'] != 'Anonymous') {
							$error = 0;
						}
					}
				}
				// Check for no errors
				if ($error == 0) {
					// Querying
					$FetchCommentsQuery = "SELECT * FROM (SELECT * FROM " . $this->config['table_prefix'] . "blog_comments WHERE bid = " . $bid . "  ORDER BY id DESC) as derivedtable ORDER BY 1";
					$DoQuery = $this->db_conn->query($FetchCommentsQuery); // This returns true if successful
					$allRows = $this->db_conn->affected_rows;
					// Content
					$html .= '<a id="comment"></a>';
					$html .= '<form action="#commentform" name="comment" id="comment" method="post">';
					$html .= '<br /><br />';
					// We have rows?
					if ($allRows > 0) {
						$html .= '<h3 class="comments">Comment(s)</h3>';
						$repeat_rows = $this->db_conn->fetch_array($FetchCommentsQuery);
						$html .= '<p><strong class="red">Comment Submitted.</strong></p>';
						foreach ($repeat_rows as $row) {
							$html .= '<div class="commentAuthor">' . $row['username'] . ' says:';
							$html .= '<div class="commentBox">';								
							$html .= nl2br(stripcslashes($row['comment']));
							$html .= '<br /><br />';
							$html .= '<div class="smalltext">Posted ' . $row['time_stamp'] . '</div>';
							$html .= '</div></div>';
							$html .= '<br />';
						}
					}
					$html .= '<a id="commentform"></a>';
					$html .= '<label title="Make a Comment">';
					$html .= '<textarea name="commentarea" rows="10" cols="55" id="commentarea"></textarea>';
					$html .= '</label>';
					$html .= '<br /><br />';
					$html .= '<label title="Submit your Comment">';
					$html .= '<input type="button" name="submit" id="submit" value="Submit Comment" onclick="javascript:SubmitBlogComment(' . $bid . ', \'' . $_SESSION[SESS_PREFIX . 'username'] . '\', ' . $_SESSION[SESS_PREFIX . 'id'] . ', \'commentarea\');" />';
					$html .= '</label>';
					$html .= '<br /><br /><br /><br />';
					$html .= '</form>';
					return $html;
				} else {
					return "There was a problem with your submission.";
				}*/
				return true;
			} else { // Comment not submitted
				return "There was a problem submitting your comment.";
			}
		} else { // empty comment or logged out...
			return "You can't submit an empty comment.";
		}
	}
	// Get total blog comments by bid
	public function CountBlogComments($id) {
		if ((isset($id)) && (is_numeric($id))) {
			$query_string = "SELECT * FROM " . $this->config['table_prefix'] . "blog_comments WHERE bid = " . $id . "";
			$query = $this->db_conn->query($query_string); // This returns true if successful
			$allRows = $this->db_conn->affected_rows; // Row Checker
			return $allRows;
		} else {
			return 0;
		}
	}
	// Create friendly url (SEO)
    public function friendlyUrl($str = '') {
		$friendlyURL = htmlentities($str, ENT_COMPAT, "UTF-8", false);
        $friendlyURL = str_replace('&amp;', 'and', $friendlyURL);
		$friendlyURL = preg_replace('/&([a-z]{1,2})(?:acute|lig|grave|ring|tilde|uml|cedil|caron);/i', '\1', $friendlyURL);
		$friendlyURL = html_entity_decode($friendlyURL, ENT_COMPAT, "UTF-8"); 
		$friendlyURL = preg_replace('/[^a-z0-9-]+/i', '-', $friendlyURL);
		$friendlyURL = preg_replace('/-+/', '-', $friendlyURL);
		$friendlyURL = trim($friendlyURL, '-');
		$friendlyURL = strtolower($friendlyURL);
		return $friendlyURL;
	}
}
?>