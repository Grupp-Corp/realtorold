<?php
class UserProfile extends UserActions
{
	public function __construct() {
		parent::__construct();
	}
	public function GetProfile($id) {
		if (is_numeric($id)) {
			$ExistingUserGet = $this->db_conn->query('SELECT * FROM ' . $this->config['table_prefix'] . 'users LEFT JOIN cms_users_infomain ON ' . $this->config['table_prefix'] . 'users_infomain.id = ' . $this->config['table_prefix'] . 'users.id LEFT JOIN ' . $this->config['table_prefix'] . 'users_groups ON ' . $this->config['table_prefix'] . 'users_groups.userid = ' . $this->config['table_prefix'] . 'users.id LEFT JOIN ' . $this->config['table_prefix'] . 'groups ON ' . $this->config['table_prefix'] . 'users_groups.groupid = ' . $this->config['table_prefix'] . 'groups.id WHERE ' . $this->config['table_prefix'] . 'users_groups.userid = ' . $id . '');
			while ($rows = $ExistingUserGet->fetch_array(MYSQLI_ASSOC)) {
				$ExistingUser[] = $rows;
			}
			return $ExistingUser[0];
		} else {
			return false;
		}
	}

    public function GetProfileByUsername($username) {
        $ExistingUser = array();

        if (trim($username) != '') {
            $ExistingUserGet = $this->db_conn->query('SELECT * FROM ' . $this->config['table_prefix'] . 'users
                LEFT JOIN cms_users_infomain ON ' . $this->config['table_prefix'] . 'users_infomain.id = ' . $this->config['table_prefix'] . 'users.id
                LEFT JOIN ' . $this->config['table_prefix'] . 'users_groups ON ' . $this->config['table_prefix'] . 'users_groups.userid = ' . $this->config['table_prefix'] . 'users.id
                LEFT JOIN ' . $this->config['table_prefix'] . 'groups ON ' . $this->config['table_prefix'] . 'users_groups.groupid = ' . $this->config['table_prefix'] . 'groups.id
                WHERE ' . $this->config['table_prefix'] . 'users.username = "' . $username . '"');

            while ($rows = $ExistingUserGet->fetch_array(MYSQLI_ASSOC)) {
                $ExistingUser[] = $rows;
            }

            return $ExistingUser[0];
        } else {
            return false;
        }
    }

	public function GetUsersGroups($id) {
		if (is_numeric($id)) {
			$queryString = 'SELECT * FROM ' . $this->config['table_prefix'] . 'users_groups LEFT JOIN ' . $this->config['table_prefix'] . 'groups ON ' . $this->config['table_prefix'] . 'users_groups.groupid = ' . $this->config['table_prefix'] . 'groups.id WHERE ' . $this->config['table_prefix'] . 'users_groups.userid = ' . $id . '';
			$query = $this->db_conn->query($queryString);
			$TotalRows = $this->db_conn->affected_rows;
			if ($TotalRows > 0) {
				while ($rows = $query->fetch_array(MYSQLI_ASSOC)) {
					$repeat[] = $rows;
				}
				return $repeat;
			} else {
				return array();
			}
		} else {
			return array();
		}
	}
	public function GetSingleGroup($id) {
		if (is_numeric($id)) {
			$queryString = 'SELECT * FROM ' . $this->config['table_prefix'] . 'groups WHERE id = ' . $id . '';
			$query = $this->db_conn->query($queryString);
			$TotalRows = $this->db_conn->affected_rows;
			if ($TotalRows > 0) {
				while ($rows = $query->fetch_array(MYSQLI_ASSOC)) {
					$repeat[] = $rows;
				}
				return $repeat[0];
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	// Edit Profile
	public function EditUserProfile() {
		// Error Variables
		$form_incomplete = 0;
		$secret_question_error = 0;
		$secret_answer_error = 0;
		$user_error = -1;
		$pass_error = -1;
		$email_error = 0;
		$facebook_error = 0;
		$linkedin_error = 0;
		$instagram_error = 0;
		$pinterest_error = 0;
		$youtube_error = 0;
		$vine_error = 0;
		$twitter_error = 0;
		$myspace_error = 0;
		$picture_error = 0;
		$busadd_error = 0;
		$city_error = 0;
		$state_error = 0;
		$zip_error = 0;
		$phonenum_error = 0;
		$nmlsnum_error = 0;
		$licensenum_error = 0;
		$captcha_error = 0;
		$passretype_error = 0;
		$id_error = 0;
		// Default Vars
		$facebook = '';
		$linkedin = '';
		$instagram = '';
		$pinterest = '';
		$youtube = '';
		$vine = '';
		$twitter = '';
		$myspace = '';
		// Check ID
		if (!isset($_REQUEST['insertID']) || $_REQUEST['insertID'] < 1)  {
			$form_incomplete = 1;
			$id_error = 1;
		} else {
			$insertID = $_REQUEST['insertID'];
		}
		// Check Picture
		/*if (isset($_REQUEST['Picture'])) {
			$picture = $_REQUEST['Picture'];
			if ($picture == '') {
				$form_incomplete = 1;
				$picture_error = 1;
			}
		} else {
			$form_incomplete = 1;
			$picture_error = 1;
		}*/
		// Check Business Address
		if (isset($_REQUEST['Business_Address_One'])) {
			$busadd = $_REQUEST['Business_Address_One'];
			if ($busadd == '') {
				$form_incomplete = 1;
				$busadd_error = 1;
			}
		} else {
			$form_incomplete = 1;
			$busadd_error = 1;
		}
		// Check Business Address 2
		if (isset($_REQUEST['Business_Address_Two'])) {
			$busaddtwo = $_REQUEST['Business_Address_Two'];
		} else {
			$busaddtwo = '';
		}
		// Check City
		if (isset($_REQUEST['City'])) {
			$city = $_REQUEST['City'];
			if ($city == '') {
				$form_incomplete = 1;
				$city_error = 1;
			}
		} else {
			$form_incomplete = 1;
			$city_error = 1;
		}
		// Check State
		if (isset($_REQUEST['State'])) {
			$state = $_REQUEST['State'];
			if ($state == '') {
				$form_incomplete = 1;
				$state_error = 1;
			}
		} else {
			$form_incomplete = 1;
			$state_error = 1;
		}
		// Check ZIP
		if (isset($_REQUEST['ZIP'])) {
			$zip = $_REQUEST['ZIP'];
			if ($zip == '') {
				$form_incomplete = 1;
				$zip_error = 1;
			} else if(!preg_match("/^([0-9]{5})(-[0-9]{4})?$/i",$zip)) {
				$form_incomplete = 1;
				$zip_error = 1;
			}
		} else {
			$form_incomplete = 1;
			$zip_error = 1;
		}
		// Check Phone
		if (isset($_REQUEST['Phone_Number'])) {
			$phonenum = $_REQUEST['Phone_Number'];
			$justNums = preg_replace("/[^0-9]/", '', $phonenum);
			if ($phonenum == '') {
				$form_incomplete = 1;
				$phonenum_error = 1;
			} else if (strlen($justNums) != 10)  {
				$form_incomplete = 1;
				$phonenum_error = 1;
			}
		} else {
			$form_incomplete = 1;
			$phonenum_error = 1;
		}
		// Check NMLS
		if (isset($_REQUEST['NMLS_Number'])) {
			$nmlsnum = $_REQUEST['NMLS_Number'];
			if ($nmlsnum == '') {
				$form_incomplete = 1;
				$nmlsnum_error = 1;
			}
		} else {
			$form_incomplete = 1;
			$nmlsnum_error = 1;
		}
		// Check License
		if (isset($_REQUEST['License_Number'])) {
			$licensenum = $_REQUEST['License_Number'];
			if ($licensenum == '') {
				$form_incomplete = 1;
				$licensenum_error = 1;
			}
		} else {
			$form_incomplete = 1;
			$licensenum_error = 1;
		}
		// Check Links
		if (isset($_REQUEST['Facebook']) && $_REQUEST['Facebook'] > '') {
			$facebook = $_REQUEST['Facebook'];
			if (!preg_match("#((http|https|ftp)://(\S*?\.\S*?))(\s|\;|\)|\]|\[|\{|\}|,|\"|'|:|\<|$|\.\s)#ie", $facebook)) {
				$facebook_error = 1;
				$form_incomplete = 1;
			}
		}
		if (isset($_REQUEST['LinkedIn']) && $_REQUEST['LinkedIn'] > '') {
			$linkedin = $_REQUEST['LinkedIn'];
			if (!preg_match("#((http|https|ftp)://(\S*?\.\S*?))(\s|\;|\)|\]|\[|\{|\}|,|\"|'|:|\<|$|\.\s)#ie", $linkedin)) {
				$linkedin_error = 1;
				$form_incomplete = 1;
			}
		}
		if (isset($_REQUEST['Instagram']) && $_REQUEST['Instagram'] > '') {
			$instagram = $_REQUEST['Instagram'];
			if (!preg_match("#((http|https|ftp)://(\S*?\.\S*?))(\s|\;|\)|\]|\[|\{|\}|,|\"|'|:|\<|$|\.\s)#ie", $instagram)) {
				$instagram_error = 1;
				$form_incomplete = 1;
			}
		}
		if (isset($_REQUEST['Pinterest']) && $_REQUEST['Pinterest'] > '') {
			$pinterest = $_REQUEST['Pinterest'];
			if (!preg_match("#((http|https|ftp)://(\S*?\.\S*?))(\s|\;|\)|\]|\[|\{|\}|,|\"|'|:|\<|$|\.\s)#ie", $pinterest)) {
				$pinterest_error = 1;
				$form_incomplete = 1;
			}
		}
		if (isset($_REQUEST['YouTube']) && $_REQUEST['YouTube'] > '') {
			$youtube = $_REQUEST['YouTube'];
			if (!preg_match("#((http|https|ftp)://(\S*?\.\S*?))(\s|\;|\)|\]|\[|\{|\}|,|\"|'|:|\<|$|\.\s)#ie", $youtube)) {
				$youtube_error = 1;
				$form_incomplete = 1;
			}
		}
		if (isset($_REQUEST['Vine']) && $_REQUEST['Vine'] > '') {
			$vine = $_REQUEST['Vine'];
			if (!preg_match("#((http|https|ftp)://(\S*?\.\S*?))(\s|\;|\)|\]|\[|\{|\}|,|\"|'|:|\<|$|\.\s)#ie", $vine)) {
				$vine_error = 1;
				$form_incomplete = 1;
			}
		}
		if (isset($_REQUEST['Twitter']) && $_REQUEST['Twitter'] > '') {
			$twitter = $_REQUEST['Twitter'];
			if (!preg_match("#((http|https|ftp)://(\S*?\.\S*?))(\s|\;|\)|\]|\[|\{|\}|,|\"|'|:|\<|$|\.\s)#ie", $twitter)) {
				$twitter_error = 1;
				$form_incomplete = 1;
			}
		}
		if (isset($_REQUEST['MySpace']) && $_REQUEST['MySpace'] > '') {
			$myspace = $_REQUEST['MySpace'];
			if (!preg_match("#((http|https|ftp)://(\S*?\.\S*?))(\s|\;|\)|\]|\[|\{|\}|,|\"|'|:|\<|$|\.\s)#ie", $myspace)) {
				$myspace_error = 1;
				$form_incomplete = 1;
			}
		}
		// Check if form is complete
		if ($form_incomplete == 1) {
			// Error String Building
			$error_return_string = '';
			// Specific errors and strings
			if ($id_error == 1) {
				$error_return_string .= 'An unknown error occurred<br />';
			}
			if ($picture_error == 1) {
				$error_return_string .= 'Please add a profile image<br />';
			}
			if ($busadd_error == 1) {
				$error_return_string .= 'Please enter your Business Address<br />';
			}
			if ($city_error == 1) {
				$error_return_string .= 'Please enter your City<br />';
			}
			if ($state_error == 1) {
				$error_return_string .= 'Please enter your State<br />';
			}
			if ($zip_error == 1) {
				$error_return_string .= 'Please enter a valid U.S. ZIP Code<br />';
			}
			if ($phonenum_error == 1) {
				$error_return_string .= 'Please enter a valid Phone Number<br />';
			}
			if ($nmlsnum_error == 1) {
				$error_return_string .= 'Please enter your NMLS Number<br />';
			}
			if ($licensenum_error == 1) {
				$error_return_string .= 'Please enter your License Number<br />';
			}
			if ($facebook_error == 1) {
				$error_return_string .= 'The Facebook link you entered is not valid. Valid links start with &quot;www.&quot; or &quot;http://&quot;<br />';
			}
			if ($linkedin_error == 1) {
				$error_return_string .= 'The LinkedIn link you entered is not valid. Valid links start with &quot;www.&quot; or &quot;http://&quot;<br />';
			}
			if ($instagram_error == 1) {
				$error_return_string .= 'The Instagram link you entered is not valid. Valid links start with &quot;www.&quot; or &quot;http://&quot;<br />';
			}
			if ($pinterest_error == 1) {
				$error_return_string .= 'The Pinterest link you entered is not valid. Valid links start with &quot;www.&quot; or &quot;http://&quot;<br />';
			}
			if ($youtube_error == 1) {
				$error_return_string .= 'The Youtube link you entered is not valid. Valid links start with &quot;www.&quot; or &quot;http://&quot;<br />';
			}
			if ($vine_error == 1) {
				$error_return_string .= 'The Vine link you entered is not valid. Valid links start with &quot;www.&quot; or &quot;http://&quot;<br />';
			}
			if ($twitter_error == 1) {
				$error_return_string .= 'The Twitter link you entered is not valid. Valid links start with &quot;www.&quot; or &quot;http://&quot;<br />';
			}
			if ($myspace_error == 1) {
				$error_return_string .= 'The MySpace link you entered is not valid. Valid links start with &quot;www.&quot; or &quot;http://&quot;<br />';
			}
			if ($user_error > 0) {
				$error_return_string .= 'Please enter a valid username or the username you entered may already be taken<br />';
			}
			if ($email_error > 0) {
				$error_return_string .= 'Please enter a valid email or the email you entered may already be taken<br />';
			}
			if ($secret_question_error == 1) {
				$error_return_string .= 'Please enter your Secret Question<br />';
			}
			if ($secret_answer_error == 1) {
				$error_return_string .= 'Please enter your Secret Answer<br />';
			}
			if ($pass_error > 0) {
				$error_return_string .= 'Please enter your password it must be at least 8 characters long and make sure they match<br />';
			}
			if ($passretype_error == 1) {
				$error_return_string .= 'Please re-enter your password it must be at least 8 characters long and make sure they match<br />';
			}
			if($captcha_error == 1) {
				$error_return_string .= 'Please enter the correct Captcha<br />';				
			}
			// return false
			return $error_return_string;
		} else {
			// Insert User Data to child table
			$this->db_conn->query('
							UPDATE ' . $this->config['table_prefix'] . 'users_infomain 
							SET business_address = "' . $busadd . '",
							business_address_two = "' . $busaddtwo . '", 
							business_city = "' . $city . '",
							business_state = "' . $state . '", 
							business_zip = "' . $zip . '", 
							business_phone_number = "' . $phonenum . '", 
							business_nmls = "' . $nmlsnum . '", 
							business_license = "' . $licensenum . '", 
							facebook_link = "' . $facebook . '", 
							linkedin_link = "' . $linkedin . '", 
							instagram_link = "' . $instagram . '", 
							pinterest_link = "' . $pinterest . '", 
							youtube_link = "' . $youtube . '", 
							vine_link = "' . $vine . '",
							twitter_link = "' . $twitter . '", 
							myspace_link = "' . $myspace . '"
							WHERE  
							id = ' . $insertID . '');
				// Return true for AJAX
			return "true";
		}
	}
}
?>