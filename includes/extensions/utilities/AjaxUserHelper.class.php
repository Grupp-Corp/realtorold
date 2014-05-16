<?php
class AjaxUserHelper extends UserActions {
	// Construct
	public function __construct() {
		parent::__construct();
	}
	// Login User with JQuery AJAX
	public function AjaxLoginUser() {
		// Variables
		$user_error = 0;
		$pass_error = 0;
		$buttonval = 'Sign in';
		$formok = 0;
		$error = '';
		// Username
		if (isset($_REQUEST['username'])) {
			$username = $this->XSSCheck->process($_REQUEST['username']);
		} else {
			if (isset($username)) {
				$username = $username;
			} else {
				$username = '';
			}
		}
		// Password
		if (isset($_REQUEST['password'])) {
			$password = $_REQUEST['password'];
		} else {
			if (isset($password)) {
				$password = $password;
			} else {
				$password = '';
			}
		}
		// Submitted material required processed below
		// Was form submitted?
		if ((isset($_REQUEST['submit'])) &&  ($_REQUEST['submit'] == $buttonval)) {
			// Username
			if (!isset($username)) {
				$user_error = 1;
			}
			if ($username == '') {
				$user_error = 1;
			}
			// Password
			if (!isset($password)) {
				$pass_error = 1;
			}
			if ($password == '') {
				$pass_error = 1;
			}
			// User Error Report
			if ($user_error == 1) {
				$error .= 'Please Enter your username.<br />';
				$formok = 0;
			}
			// Password Error Report
			if ($pass_error == 1) {
				$error .= 'Please Enter your password.<br />';
				$formok = 0;
			}
			// Errors present?
			if ($error > '') {
				$form .= '' . $error . '';
				$formok = 0;
			} else {
				$formok = 1;
			}
		}
		$logged = $this->PasswordMatch($username, $password); // Check password
		if ($logged === true) { // See if login succeeded
			if ($this->UpdateActivity($username) == true) { // update user db
				$UserInfoArray = $this->GetUserInformation($username); // Get user info
				// Set Sessions
				$_SESSION[$this->config['session_prefix'] . 'id'] = $UserInfoArray['id'];
				$_SESSION[$this->config['session_prefix'] . 'username'] = $UserInfoArray['username'];
				$_SESSION[$this->config['session_prefix'] . 'groupid'] = $UserInfoArray['groupid'];
				$_SESSION[$this->config['session_prefix'] . 'siteid'] = $UserInfoArray['site_id'];
				$_SESSION[$this->config['session_prefix'] . 'grouplevel'] = $UserInfoArray['level'];
				$_SESSION[$this->config['session_prefix'] . 'lastlogin'] = $UserInfoArray['lastactivity'];
				$_SESSION[$this->config['session_prefix'] . 'passok'] = 1;
				$_SESSION[$this->config['session_prefix'] . 'adminok'] = 0;
				return json_encode($UserInfoArray);
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
}
?>