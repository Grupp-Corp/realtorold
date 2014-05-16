<?php
class CCVideo extends CCTemplate
{
	//////////
	// Vars //
	//////////
	protected $GetDB;
	protected $Pages;
	protected $StringCheck;
	protected $XSSCheck;
	protected $UserProfile;
	///////////////
	// Construct //
	///////////////
	public function __construct() {
		parent::__construct();
		// Set DB Vars
		$this->GetDB = $this->db_conn;
		// Load Paginator with AJAX
		$this->Pages = new Pagination();
		// Load String Checkers
		$this->StringCheck = new StringCheckers();
		// Load XSS Class
		$this->XSSCheck = new InputFilter(1, 1);
		// Load Uploader class
		$this->UserProfile = new UserProfile();
	}
	///////////////////
	// Video Listing //
	///////////////////
	public function GetVideos($song = '', $template_name = 'canuckcoder', $page = 1, $limit = 2, $ajax = 1, $ajaxFunctionName = '', $sort = '', $col = '', $SEOUrl = '/') {
		// Vars
		$qs_add = '';
		$query_string_limit = '';
		$query_string_orderby = ' ORDER BY v.vid_title ASC';
		// Page
		if ($page > 0) {
			$page = $page - 1;
		} else {
			$page = 0;
		}
		// Check for ID Pull
		$limitStartRecord = $page * $limit;
		$query_string_limit = " LIMIT " . $limitStartRecord . ", " . $limit . " ";
		// Check template name
		if ($template_name > '') {
			$qs = 'SELECT id FROM ' . $this->config['table_prefix'] . 'websites WHERE url = "' . $template_name . '"';
			$query = $this->GetDB->query($qs); // This returns true if successful
			$TotalRows = $this->GetDB->affected_rows; // Row Checker
			// Check Rows
			if ($TotalRows > 0) { // Rows exist
				$row = DBMySQLi::fetch_single($query);
				$site_id = $row['id'];
			} else {
				$site_id = 0;
			}
		} else {
			$site_id = 0;
		}
		// Order By...
		if (($sort > '') && ($col > '')) {
			// Column whitelist
			$goodCols = array('title', 'time', 'user', 'published', 'actions');
			if ((isset($col)) && (in_array($col, $goodCols))) {
				switch ($col) {
					case 'title':
						$col = 'v.vid_title';
						break;
					case 'time':
						$col = 'v.vid_time';
						break;
					case 'user':
						$col = 'u.username';
						break;
					case 'published':
						$col = 'v.active';
						break;
				}
				if ($this->StringCheck->SafeSQLTableCol($col) == 0) {
					$col = '';
				}
			} else {
				$col = '';
			}
			// Sort Check
			if ($sort == "ASC") {
				$sort = $sort;
			} else if ($sort == "DESC") {
				$sort = $sort;
			} else {
				$sort = '';
			}
			if ($this->StringCheck->SafeSQLTableCol($col) == 1) {
				$query_string_orderby = ' ORDER BY ' . $col. ' ' . $sort . '';
			}
		}
		// Get site_id
		$qs_add .= ' WHERE  v.site_id = ' . $site_id . '';
		if ($song > '') {
			$qs_add_title = ' AND v.vid_title = "' . $this->XSSCheck->escapeString($song) . '" AND v.show_in_list = 1 AND v.active = 1 ';
		} else {
			$qs_add_title = ' AND v.show_in_list = 1 AND v.active = 1 ';
		}
		// Querystring
		$qs = 'SELECT v.id, v.vid_title, v.vid_desc, v.active, v.vid_time, v.site_id, v.vid_file, v.date_modified, v.vid_download, v.vid_download_title, v.vid_download_desc, v.show_in_list, u.username, v.user_id FROM ' . $this->config['table_prefix'] . 'videos v ';
		$qs .= ' LEFT JOIN ' . $this->config['table_prefix'] . 'users u ON u.id=v.user_id ';
		$qs .= '' . $qs_add . '';
		// Query
		$query = $this->GetDB->query($qs . $qs_add_title . $query_string_orderby . $query_string_limit); // This returns true if successful
		$TotalRows = $this->GetDB->affected_rows; // Row Checker
		// Check Rows
		if ($TotalRows > 0) { // Rows exist
			$pages = $this->Pages->PagesBuilder($qs, $limit, $page, $ajax, $ajaxFunctionName, $SEOUrl); // Pagination
            $queryAll = $this->GetDB->query($qs);
			$repeat_rows = DBMySQLi::fetch_assoc($queryAll);
			if ($qs_add_title > '') {
				$rowQuery = $this->GetDB->query($qs . $qs_add_title . $query_string_orderby . $query_string_limit); // MySQL Fetch Array for row iteration
				$row = DBMySQLi::fetch_assoc($rowQuery);
			} else {
				$row = false;
			}
			//echo $qs . $query_string_limit;
			// Return array
			return array('paginator' => $pages, 'RowArray' => $repeat_rows, 'Row' => $row, 'TotalRows' => $TotalRows, 'querystring' => $qs);
		} else { // Rows do not exist
			// Return array
			return array('paginator' => 1, 'RowArray' => array(), 'Row' => false, 'TotalRows' => 0, 'querystring' => ''); // Defaulting
		}
	}
	////////////////////////////
	// FEATURED Video Listing //
	////////////////////////////
	public function FeaturedVideos() {
		$qs = 'SELECT v.id, v.vid_title, v.vid_desc, v.active, v.vid_time, v.site_id, v.vid_file, v.date_modified, v.vid_download, v.vid_download_title, v.vid_download_desc, v.show_in_list, u.username, v.user_id, u.username, v.user_id FROM ' . $this->config['table_prefix'] . 'videos v ';
		$qs .= ' LEFT JOIN ' . $this->config['table_prefix'] . 'users u ON u.id=v.user_id ';
		$qs .= ' WHERE v.is_featured = 1 AND v.show_in_list = 1 AND v.active = 1 ORDER BY v.date_modified DESC LIMIT 0, 5';
		$query = $this->GetDB->query($qs); // This returns true if successful
		$TotalRows = $this->GetDB->affected_rows; // Row Checker
		// Check Rows
		if ($TotalRows > 0) { // Rows exist
			$repeat_rows = DBMySQLi::fetch_assoc($query);
			// Return array
			return array('RowArray' => $repeat_rows, 'TotalRows' => $TotalRows);
		} else {
			// Return array
			return array('RowArray' => array(), 'TotalRows' => 0);
		}
	}
	//////////////////////////
	// Get User Information //
	//////////////////////////
	public function GetUserInfo($id) {
		if ($this->StringCheck->CheckInteger($id)) {
			return $this->UserProfile->GetProfile($id);
		} else {
			return NULL;
		}
	}
}
?>