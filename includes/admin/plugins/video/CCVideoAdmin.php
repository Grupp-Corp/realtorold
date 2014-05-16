<?php
class CCVideoAdmin extends CCTemplate {
	//////////
	// Vars //
	//////////
	protected $GetDB;
	protected $Pages;
	protected $StringCheck;
	protected $XSSCheck;
	protected $UploadObject;
	protected $UserProfile;
	///////////////
	// Construct //
	///////////////
	public function __construct() {
		// Connection
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
		$this->UploadObject = new Uploader();
		// Load Uploader class
		$this->UserProfile = new UserProfile();
	}
	///////////////////
	// Video Listing //
	///////////////////
	public function GetVideos($id = 0, $site_id = 1, $page = 1, $limit = 2, $ajax = 1, $ajaxFunctionName = '', $sort = '', $col = '') {
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
		if ($this->StringCheck->CheckInteger($id)) {
			// Querystring Addition for ID Pull
			$qs_add = ' WHERE v.id = ' . $id . ' ';
			// Limit
			$query_string_limit = " LIMIT 1 ";			
		} else { // No ID
			$limitStartRecord = $page * $limit;
			$query_string_limit = " LIMIT " . $limitStartRecord . ", " . $limit . " ";
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
		// Querystring
		$qs = 'SELECT v.id, v.vid_title, v.vid_desc, v.active, v.vid_time, v.site_id, v.vid_file, v.date_modified, v.vid_download, v.vid_download_title, v.vid_download_desc, u.username, v.user_id, v.is_featured, v.show_in_list FROM ' . $this->config['table_prefix'] . 'videos v ';
		$qs .= ' LEFT JOIN ' . $this->config['table_prefix'] . 'users u ON u.id=v.user_id ';
		$qs .= '' . $qs_add . '' . $query_string_orderby . '';
		// Query
		$query = $this->GetDB->query($qs . $query_string_limit); // This returns true if successful
		$TotalRows = $this->GetDB->affected_rows; // Row Checker
		// Check Rows
		if ($TotalRows > 0) { // Rows exist
			$pages = $this->Pages->PagesBuilder($qs, $limit, $page, $ajax, $ajaxFunctionName); // Pagination
			$repeat_rows = DBMySQLi::fetch_assoc($query);
			// Return array
			return array('paginator' => $pages, 'RowArray' => $repeat_rows, 'TotalRows' => $TotalRows, 'querystring' => $qs);
		} else { // Rows do not exist
			// Return array
			return array('paginator' => 1, 'RowArray' => array(), 'TotalRows' => 0, 'querystring' => ''); // Defaulting
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
	/////////////////////
	// Get User's Sites //
	/////////////////////
	public function GetUserSites($userid) {
		if ($this->StringCheck->CheckInteger($userid)) {
			$qs = 'SELECT * FROM ' . $this->config['table_prefix'] . 'users_websites uw ';
			$qs .= 'LEFT JOIN ' . $this->config['table_prefix'] . 'websites w ON uw.site_id=w.id WHERE uw.user_id = ' . $userid;
			$query = $this->GetDB->query($qs); // This returns true if successful
			$TotalRows = $this->GetDB->affected_rows; // Row Checker
			// Check Rows
			if ($TotalRows > 0) { // Rows exist
				$repeat_rows = DBMySQLi::fetch_assoc($query);
				return array('RowArray' => $repeat_rows, 'TotalRows' => $TotalRows);
			} else {
				return array('RowArray' => array(), 'TotalRows' => 0);
			}
		} else {
			return array('RowArray' => array(), 'TotalRows' => 0);
		}
	}
	/////////////////
	// Edit Videos //
	/////////////////
	public function EditVideo($id, $site_id, $title, $vid_desc, $time, $active, $file, $file_name, $_accepted_extensions_, $max_size, $destination_folder, $vid_download = '', $vid_filename = '', $vid_download_title = '', $vid_download_desc = '', $download_destination = '', $show_in_list = 0, $is_featured = 0) {
		if ($this->StringCheck->CheckInteger($id)) {
			if ((isset($file_name)) && ($file_name != '')) {
				// Upload file
				$UploadFileMessage = $this->UploadObject->simple_upload($file, $file_name, $_accepted_extensions_, $max_size, FULL_FOLDER_PATH . $destination_folder);
				if ($UploadFileMessage === true) {
					$error = 0;
				} else {
					$error = 1;
				}
			} else {
				$error = 0;
				$UploadFileMessage = 1;
			}
			if ((isset($vid_download_title)) && ($vid_download_title != '')) {
				// Upload file
				$UploadDLFileMessage = $this->UploadObject->simple_upload($vid_download, $vid_download_title, $_accepted_extensions_, $max_size, FULL_FOLDER_PATH . $download_destination);
				if ($UploadDLFileMessage === true) {
					$error = 0;
				} else {
					$error = 1;
				}
			} else {
				$error = 0;
				$UploadDLFileMessage = 1;
			}
			if ($this->StringCheck->CheckInteger($show_in_list)) {
				$error = 0;
			} else {
				$error = 1;
			}
			if ($this->StringCheck->CheckInteger($is_featured)) {
				$error = 0;
			} else {
				$error = 1;
			}
			if ($error == 0) {
				$addString = '';
				// Querystring
				if ((isset($file_name)) && ($file_name > '')) {
					$addString .= 'vid_file = "' . $file_name . '", ';
				}
				if ((isset($title)) && ($title > '')) {
					$title = $this->StringCheck->StringLimiter($title, 252);
				}
				if ((isset($vid_desc)) && ($vid_desc > '')) {
					$addString .= 'vid_desc = "' . $vid_desc . '", ';
				}
				if ((isset($vid_filename)) && ($vid_filename > '')) {
					$addString .= 'vid_download = "' . $vid_filename . '", ';
				}
				if ((isset($vid_download_title)) && ($vid_download_title > '')) {
					$addString .= 'vid_download_title = "' . $vid_download_title . '", ';
				}
				if ((isset($vid_download_desc)) && ($vid_download_desc > '')) {
					$addString .= 'vid_download_desc = "' . $vid_download_desc . '", ';
				}
				$addString .= 'show_in_list = ' . $show_in_list . ', ';
				$addString .= 'is_featured = ' . $is_featured . ', ';
				if ($this->StringCheck->CheckInteger($site_id)) {
					$site_id = $site_id;
				} else {
					$site_id = 0;
					$active = 0;
				}
				$qs = 'UPDATE ' . $this->config['table_prefix'] . 'videos SET vid_title = "' . $title . '", vid_desc = "' . $vid_desc . '", site_id = ' . $site_id . ', ' . $addString . 'vid_time = "' . $time . '", active = ' . $active . ', date_modified = now() WHERE id = ' . $id . '';
				// Query
				$query = $this->GetDB->query($qs); // This returns true if successful
				if ($query) {
					return array('query' => true, 'UploadMessage' => $UploadFileMessage);
				} else {
					return array('query' => false, 'UploadMessage' => $UploadFileMessage, 'ErrorMessage' => 'The Query has failed.');
				}
			} else {
				return array('query' => false, 'UploadMessage' => $UploadFileMessage, 'ErrorMessage' => 'Upload Failed.');
			}
		} else {
			return array('query' => false, 'UploadMessage' => $UploadFileMessage, 'ErrorMessage' => 'Invalid ID.');
		}
	}
	////////////////
	// Add Videos //
	////////////////
	public function AddVideo($site_id, $title, $time, $active, $user_id, $file, $file_name, $_accepted_extensions_, $max_size, $destination_folder, $vid_download = '', $vid_filename = '', $vid_download_title = '', $vid_download_desc = '', $download_destination = '', $show_in_list = 0, $is_featured = 0) {
		if ($this->StringCheck->CheckInteger($site_id)) {
			if ((isset($file_name)) && ($file_name != '')) {
				// Upload file
				$UploadFileMessage = $this->UploadObject->simple_upload($file, $file_name, $_accepted_extensions_, $max_size, FULL_FOLDER_PATH . $destination_folder);
				if ($UploadFileMessage === true) {
					$error = 0;
				} else {
					$error = 1;
				}
			} else {
				$error = 1;
				$UploadFileMessage = 1;
			}
			if ((isset($vid_download_title)) && ($vid_download_title != '')) {
				// Upload file
				$UploadDLFileMessage = $this->UploadObject->simple_upload($vid_download, $vid_download_title, $_accepted_extensions_, $max_size, FULL_FOLDER_PATH . $download_destination);
				if ($UploadDLFileMessage === true) {
					$error = 0;
				} else {
					$error = 1;
				}
			} else {
				$error = 0;
				$UploadDLFileMessage = 1;
			}
			if ($this->StringCheck->CheckInteger($show_in_list)) {
				$error = 0;
			} else {
				$error = 1;
			}
			if ($this->StringCheck->CheckInteger($is_featured)) {
				$error = 0;
			} else {
				$error = 1;
			}
			if ($error == 0) {
				$addString = '';
				// Querystring
				if ((isset($title)) && ($title > '')) {
					$title = $this->StringCheck->StringLimiter($title, 252);
				}
				if ($this->StringCheck->CheckInteger($site_id)) {
					$site_id = $site_id;
				} else {
					$site_id = 0;
					$active = 0;
				}
				$qs = 'INSERT INTO ' . $this->config['table_prefix'] . 'videos (site_id, vid_file, vid_desc, vid_title, vid_time, active, user_id, date_modified, vid_download, vid_download_title, vid_download_desc, show_in_list, is_featured) VALUES (' . $site_id . ', "' . $file_name . '", "' . $vid_desc . '", "' . $title . '", "' . $time . '", "' . $active . '", ' . $user_id . ', now(), "' . $vid_filename . '", "' . $vid_download_title . '", "' . $vid_download_desc . '", ' . $show_in_list . ', ' . $is_featured . ')';
				// Query
				$query = $this->GetDB->query($qs); // This returns true if successful
				if ($query) {
					return array('query' => true, 'UploadMessage' => $UploadFileMessage);
				} else {
					return array('query' => false, 'UploadMessage' => $UploadFileMessage, 'ErrorMessage' => 'The Query has failed.');
				}
			} else {
				return array('query' => false, 'UploadMessage' => $UploadFileMessage, 'ErrorMessage' => 'Upload Failed.');
			}
		} else {
			return array('query' => false, 'UploadMessage' => 'Invalid Site ID.', 'ErrorMessage' => 'Invalid Site ID.');
		}
	}
	///////////////////
	// Delete Videos //
	///////////////////
	public function DeleteVideo($id) {
		if ($this->StringCheck->CheckInteger($id)) {
			$qs = 'SELECT * FROM ' . $this->config['table_prefix'] . 'videos WHERE id = ' . $id . '';
			// Query
			$query = $this->GetDB->query($qs); // This returns true if successful
			$TotalRows = $this->GetDB->affected_rows; // Row Checker
			if ($query) {
				// Check Rows
				if ($TotalRows > 0) { // Rows exist
					$row = $this->GetDB->query_first($qs); // MySQL Fetch Array for row iteration
					$qs = 'DELETE FROM ' . $this->config['table_prefix'] . 'videos WHERE id = ' . $id . '';
					// Query
					$query = $this->GetDB->query($qs); // This returns true if successful
					if ($query) {
						$siteURL = $this->GetSiteNameFromID($row['site_id']);
						$destination_folder = '/assets/site/' . $siteURL . '/videos/';
						@unlink(FULL_FOLDER_PATH . $destination_folder . $row['vid_file']);
						$destination_folder = '/assets/site/' . $siteURL . '/downloads/';
						@unlink(FULL_FOLDER_PATH . $destination_folder . $row['vid_download']);
						return array('query' => true, 'Error' => '');
					} else {
						return array('query' => false, 'Error' => 'The entry does not exist.');
					}
				} else {
					return array('query' => false, 'Error' => 'The entry does not exist.');
				}
			} else {
				return array('query' => false, 'Error' => 'The entry does not exist.');
			}
		} else {
			return array('query' => false, 'Error' => 'Invalid ID.');
		}
	}
	//////////////////////////
	// Get Site Information //
	//////////////////////////
	public function GetSiteNameFromID($site_id) {
		if ($this->StringCheck->CheckInteger($site_id)) {
			$qs = 'SELECT * FROM ' . $this->config['table_prefix'] . 'websites WHERE id = ' . $site_id;
			// Query
			$query = $this->GetDB->query($qs); // This returns true if successful
			$TotalRows = $this->GetDB->affected_rows; // Row Checker
			// Check Rows
			if ($TotalRows > 0) { // Rows exist
				$row = $this->GetDB->query_first($qs); // MySQL Fetch Array for row iteration
				return $row['url'];
			} else {
				return 'main';
			}
		} else {
			return 'main';
		}
	}
}
?>