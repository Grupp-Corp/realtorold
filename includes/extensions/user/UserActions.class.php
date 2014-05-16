<?php
class UserActions extends CCTemplate 
{
	// Construct
	public function __construct() {
		parent::__construct();
		// Initialize String Checkers
		$this->StringCheck = new StringCheckers();
		// Initialize XSS Check
		$this->XSSCheck = new InputFilter(1, 1);
		if (isset($_GET['logout'])) {
			if ($_GET['logout'] == 1) {
				$this->LogOut(); // Logout
				//header('Location: ' . $_SERVER['PHP_SELF'] . '');
			}
		}
		if (isset($_POST['username']) && $_POST['username'] > '') {
			if (isset($_POST['submit']) && $_POST['submit'] == 'Login') {
				$printset = $this->LoginUserResponsive($_POST['username'], $_POST['password']); // Login and return message
				if ($printset == 1) {
					//header('Location: ' . $_SERVER['PHP_SELF'] . '');
				}
			} else if (isset($_POST['password']) && $_POST['password'] > '') {
				$printset = $this->LoginUserResponsive($_POST['username'], $_POST['password']); // Login and return message
				if ($printset == 1) {
					//header('Location: ' . $_SERVER['PHP_SELF'] . '');
				}
			}
		}
	}
	// Generate SALT
	public function GenerateSalt($saltlength = 16) {
		// start with a blank salt
		$salt = "";
		// define possible characters
		$possible = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz"; 
		// set up a counter
		$i = 0; 
		// add random characters to $password until $length is reached
		while ($i < $saltlength) { 
			// pick a random character from the possible ones
			$char = substr($possible, mt_rand(0, strlen($possible) -1), 1);
			// we don't want this character if it's already in the password
			//if (!strstr($guid, $char)) { 
				$salt .= $char;
				$i++;
			//}
		}
		return $salt;
	}
	// Generate Recovery Code
	public function PassRecoveryCode($RecCode = 32) {
		// start with a blank salt
		$salt = "";
		// define possible characters
		$possible = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"; 
		// set up a counter
		$i = 0; 
		// add random characters to $password until $length is reached
		while ($i < $RecCode) { 
			// pick a random character from the possible ones
			$char = substr($possible, mt_rand(0, strlen($possible) -1), 1);
			// we don't want this character if it's already in the password
			//if (!strstr($guid, $char)) { 
				$salt .= $char;
				$i++;
			//}
		}
		return strtoupper($salt);
	}
	// Create Password
	public function PasswordCreate($array) {
		$twisted = "";
		$array_strlen = array();
		foreach ($array as $element){
			$array_strlen[] = strlen($element);
		}
		for ($i = 0; $i < max($array_strlen); $i++){
			foreach ($array as $element){
				if ($i < strlen($element)){
					$twisted = $twisted . $element{$i};
				}
			}
		}
	  	return md5(crypt($twisted, $array[1]));
	}
	// Match Password from input
	public function PasswordMatch($username, $password) {
		// Vars
		$UserError = 0;
		$PassError = 0;
		// Error Checking
		// Username
		if (!isset($username)) { // Check if Username is set
			$UserError = 1;
		} elseif ($username == "") { // Check if Username empty
			$UserError = 1;
		}
		// Password
		if (!isset($password)) { // Check if Password is set
			$PassError = 1;
		} elseif ($password == "") { // Check if Password empty
			$PassError = 1;
		}
		// Processing
		if (($UserError != 1) && ($PassError != 1)) { // No Errors
			$row = $this->db_conn->query("SELECT * FROM " . $this->config['table_prefix'] . "users WHERE username='" . $username . "'"); // Get user db data
			while ($rows = $row->fetch_array(MYSQLI_ASSOC)) {
				$rowSet[] = $rows;
			}
			// Make sure we have a salt to work with
			if (isset($rowSet[0]['sspw']) && $rowSet[0]['sspw'] > '') {
				$salt = $rowSet[0]['sspw']; // salt
				$sp = $rowSet[0]['password']; // password (encrypted)
				$EncryptedPass = $this->PasswordCreate(array($username, $password, $salt)); // Hash/Encrypt user entered password for matching
			} else {
				return 'Invalid Username/Password'; // No Salt was present
			}
			// Does the password match?
			if ($EncryptedPass == $sp) {
				return true; // Password matches
			} else {
				return 'Invalid Username/Password'; // Password does not match
			}
		} else { // Errors exists
			// Check for null entries
			if ($UserError == 1) { // Username is empty
				return 'Username Empty';
			} elseif ($PassError == 1) { // Password is empty
				return 'Password Empty';
			}
		}
	}
	// Question/Answer Match
	public function QuestionAnswerMatch($username, $answer) {
		// Vars
		$UserError = 0;
		$AnswerError = 0;
		// Error Checking
		// Username
		if (!isset($username)) { // Check if Username is set
			$UserError = 1;
		} elseif ($username == "") { // Check if Username empty
			$UserError = 1;
		}
		// Password
		if (!isset($answer)) { // Check if Password is set
			$AnswerError = 1;
		} elseif ($answer == "") { // Check if Password empty
			$AnswerError = 1;
		}
		// Processing
		if (($UserError != 1) && ($AnswerError != 1)) { // No Errors
			$row = $this->db_conn->query("SELECT * FROM " . $this->config['table_prefix'] . "users WHERE username='" . $username . "'"); // Get user db data
			while ($rows = $row->fetch_array(MYSQLI_ASSOC)) {
				$rowSet[] = $rows;
			}
			// Make sure we have a salt to work with
			if ($rowSet[0]['secret_answer'] > '') { // check salt_answer
				$salt_answer = $rowSet[0]['salt_answer']; // salt for answer
				$secret_answer = $rowSet[0]['secret_answer']; // salt for answer
				$EncryptedAnswer = $this->PasswordCreate(array($username, $answer, $salt_answer)); // Hash/Encrypt user entered password for matching
			} else {
				return 'No secret Question/Answer Exists.'; // No Salt was present
			}
			// Does the answer match?
			if ($EncryptedAnswer == $secret_answer) {
				return true; // answer matches
			} else {
				return 'Answer does not match.'; // answer does not match
			}
		} else { // Errors exists
			// Check for null entries
			if ($UserError == 1) { // Username is empty
				return 'Username was Empty';
			} elseif ($AnswerError == 1) { // answer is empty
				return 'The Answer was Empty';
			}
		}
	}
	// Change Password From Question Answer
	public function QA_ChangePassword($uid, $username, $password, $passwordAgain) {
		// Begin Content Build
		$html = '<p>';
		// Error Messages
		$IDErrorString = '<strong class="red">Error.</strong><br />'; // Fatal Error
		$UserErrorString = '<strong class="red">You are not logged in.</strong><br />'; // Fatal Error
		$OldPassErrorString = '<strong class="red">You must enter your old password.</strong><br />';
		$PasswordErrorString = '<strong class="red">You must enter a new password.</strong><br />';
		$PasswordAgainErrorString = '<strong class="red">You must re-enter your new password.</strong><br />';
		$PasswordNoMatchErrorString = '<strong class="red">You must re-enter the same new password.</strong><br />';
		$OldPassNotCorrect = '<strong class="red">Your old password was incorrect.</strong><br />';
		$PassUpdated = '<strong class="red">Password Updated!<br />Next time you login you will need to use the new password.</strong><br />';
		$PassLengthError = '<strong class="red">Your password must be 9 characters or longer.</strong><br />';
		$error = 0;
		// Validation
		// UID Check
		if (!isset($uid)) {
			$error = 1;
			$html .= $IDErrorString;
		} elseif (!is_numeric($uid)) {
			$error = 1;
			$html .= $IDErrorString;
		}
		// Username Check
		if (!isset($username)) {
			$error = 1;
			$html .= $UserErrorString;
		} elseif ($username == '') {
			$error = 1;
			$html .= $UserErrorString;
		}
		//  Pass Check
		if (!isset($password)) {
			$error = 1;
			$html .= $PasswordErrorString;
		} elseif ($password == '') {
			$error = 1;
			$html .= $PasswordErrorString;
		}
		// Re-entered Pass Check
		if (!isset($passwordAgain)) {
			$error = 1;
			$html .= $PasswordAgainErrorString;
		} elseif ($passwordAgain == '') {
			$error = 1;
			$html .= $PasswordAgainErrorString;
		}
		// Check if any errors up to now...
		if ($error == 0) {
			// Matching Passwords
			if ($password != $passwordAgain) {
				$error = 1;
				$html .= $PasswordNoMatchErrorString;
			}
		}
		// No Errors exist
		if ($error == 0) {
			if (strlen($password) >= 9) {
				$salt = $this->GenerateSalt();
				$passwordmix = $this->PasswordCreate(array($username, $password, $salt));
				$this->db_conn->query("UPDATE " . $this->config['table_prefix'] . "users SET password='" . $passwordmix .  "', sspw ='" . $salt . "', pw_recovery='0' WHERE id = " . $uid . "");
				$this->LogOut();
				$html .= $PassUpdated;
			} else {
				$html .= $PassLengthError;
			}
		}
		// Closing tag
		$html .= '</p>';
		return $html; // Return HTML
	}
	// Get User From recover Code
	public function GetUserFromRecCode($rec_code) {
		$err = 0;
		if (!isset($rec_code)) {
			$err = 1;
		} elseif (strlen($rec_code) <> 32) {
			$err = 1;
		}
		if ($err == 0) {
			$ExistingUser = $this->db_conn->query("SELECT * FROM " . $this->config['table_prefix'] . "users WHERE pw_recovery='" . $rec_code . "'");
			$ExistingUserReset = array();
			$i = 0;
			while ($rows = $ExistingUser->fetch_array(MYSQLI_ASSOC)) {
				$ExistingUserReset[] = $rows;
				$i++;
			}
			if ($i > 0) {
				return $ExistingUserReset[0];
			} else {
				return $ExistingUserReset;
			}
		} else {
			return false;
		}
	}
	// Change Password
	public function ChangePassword($uid, $username, $oldpassword, $password, $passwordAgain) {
		// Begin Content Build
		$html = '<p>';
		// Error Messages
		$IDErrorString = '<strong class="red">Error.</strong><br />'; // Fatal Error
		$UserErrorString = '<strong class="red">You are not logged in.</strong><br />'; // Fatal Error
		$OldPassErrorString = '<strong class="red">You must enter your old password.</strong><br />';
		$PasswordErrorString = '<strong class="red">You must enter a new password.</strong><br />';
		$PasswordAgainErrorString = '<strong class="red">You must re-enter your new password.</strong><br />';
		$PasswordNoMatchErrorString = '<strong class="red">You must re-enter the same new password.</strong><br />';
		$OldPassNotCorrect = '<strong class="red">Your old password was incorrect.</strong><br />';
		$PassUpdated = '<strong class="red">Password Updated!<br />Next time you login you will need to use the new password.</strong><br />';
		$PassLengthError = '<strong class="red">Your password must be 9 characters or longer.</strong><br />';
		$error = 0;
		// Validation
		// UID Check
		if (!isset($uid)) {
			$error = 1;
			$html .= $IDErrorString;
		} elseif (!is_numeric($uid)) {
			$error = 1;
			$html .= $IDErrorString;
		}
		// Username Check
		if (!isset($username)) {
			$error = 1;
			$html .= $UserErrorString;
		} elseif ($username == '') {
			$error = 1;
			$html .= $UserErrorString;
		}
		// Old Pass Check
		if (!isset($oldpassword)) {
			$error = 1;
			$html .= $OldPassErrorString;
		} elseif ($oldpassword == '') {
			$error = 1;
			$html .= $OldPassErrorString;
		} else {
			// Checking old password with whats in DB
			if ($this->PasswordMatch($username, $oldpassword) !== true) {
				$error = 1;
				$html .= $OldPassNotCorrect;
			}
		}
		// Old Pass Check
		if (!isset($password)) {
			$error = 1;
			$html .= $PasswordErrorString;
		} elseif ($password == '') {
			$error = 1;
			$html .= $PasswordErrorString;
		}
		// Re-entered Pass Check
		if (!isset($passwordAgain)) {
			$error = 1;
			$html .= $PasswordAgainErrorString;
		} elseif ($passwordAgain == '') {
			$error = 1;
			$html .= $PasswordAgainErrorString;
		}
		// Check if any errors up to now...
		if ($error == 0) {
			// Matching Passwords
			if ($password != $passwordAgain) {
				$error = 1;
				$html .= $PasswordNoMatchErrorString;
			}
		}
		// No Errors exist
		if ($error == 0) {
			if (strlen($password) >= 9) {
				$salt = $this->GenerateSalt();
				$passwordmix = $this->PasswordCreate(array($username, $password, $salt));
				$this->db_conn->query("UPDATE " . $this->config['table_prefix'] . "users SET password='" . $passwordmix .  "', sspw ='" . $salt . "', pw_recovery='0' WHERE id = " . $uid . "");
				$html .= $PassUpdated;
			} else {
				$html .= $PassLengthError;
			}
		}
		// Closing tag
		$html .= '</p>';
		return $html; // Return HTML
	}
	// Check Username from input
	public function CheckUsername($username, $return_email = 0) {
		if (isset($username)) {
			$username = $this->XSSCheck->process($username);
			if ($username == '') {
				$username = 0; // No username entered (empty)
			} else {
				$ExistingUser = $this->db_conn->query("SELECT username, email FROM " . $this->config['table_prefix'] . "users WHERE username='" . $this->db_conn->real_escape_string($username) . "'");
				while ($rows = $ExistingUser->fetch_array(MYSQLI_ASSOC)) {
					$ExistingUserReset[] = $rows;
				}
				if ($return_email == 0) {
					if (isset($ExistingUserReset[0]['username']) && strtoupper($ExistingUserReset[0]['username']) == strtoupper($username)) {
						$username = 2; // User Exists
					} else {
						$username = $username;
					}
				} elseif ($return_email == 1) {
					if (isset($ExistingUserReset[0]['email'])) {
						if ($ExistingUserReset[0]['email'] > '') {
							$username = $ExistingUserReset[0]['email'];
						}
					}
				}
			}
		} else {
			$username = 1; // No username entered
		}
		return $username;
	}
	// Check email from input
	public function CheckEmail($email, $CheckIfExists = 1) {
		if (isset($email)) {
			//$email = $this->XSSCheck->process($email);
			if ($email == '') {
				$email = 0; // No E-mail entered (empty)
			} else {
				if ($CheckIfExists == 1) {
					$ExistingEmail = $this->db_conn->query("SELECT email FROM " . $this->config['table_prefix'] . "users WHERE email='" . $email . "'");
					while ($rows = $ExistingEmail->fetch_array(MYSQLI_ASSOC)) {
						$ExistingEmailReset[] = $rows;
					}
					if (isset($ExistingEmailReset[0]['email']) && strtoupper($ExistingEmailReset[0]['email']) == strtoupper($email)) {
						$email = 2; // E-mail Exists
					} else {
						if ($this->StringCheck->CheckEmailAddress($email) === true) { // Is the E-mail Valid?
							$email = $this->db_conn->real_escape_string($email);
						} else {
							$email = 3; // Email is not valid
						}
					}
				} else {
					if ($this->StringCheck->CheckEmailAddress($email) === true) { // Is the E-mail Valid?
						$email = $this->db_conn->real_escape_string($email);
					} else {
						$email = 3; // Email is not valid
					}
				}
			}
		} else {
			$email = 1; // No E-mail entered
		}
		return $email;
	}
	// Create user registration form and processing (simple)
	public function CreateUser($ajax = 1) {
		global $GoogleCaptchaOption;
		if ($GoogleCaptchaOption == 1) {
			global $publickey;
			global $privatekey;
		}
		// Vars
		$form_incomplete = 0;
		$user_error = 0;
		$pass_error = 0;
		$email_error = 0;
		$form = '';
		$buttonval = 'Register';
		// Check Username
		if (isset($_POST['RegUsername'])) {
			$username = $this->CheckUsername($_POST['RegUsername']);
			if ($username != $_POST['RegUsername']) {
				$form_incomplete = 1;
				$user_error = $username;
			} elseif ($username == '') {
				$form_incomplete = 1;
				$user_error = $username;
			} else {
				$username = $_POST['RegUsername'];
				$user_error = -1;
			}
		} else {
			if (isset($username)) {
				$username = $username;
				$user_error = -1;
			} else {
				$username = '';
				$form_incomplete = 1;
			}
		}
		// Password Check
		if (isset($_POST['RegPassword'])) {
			$password = $_POST['RegPassword'];
			if ($password == '') {
				$form_incomplete = 1;
				$pass_error = 1;
			} else {
				if (strlen($password) <= 7) {
					$form_incomplete = 1;
					$pass_error = 2;
				} else {
					$pass_error = -1;
				}
			}
		} else {
			if (isset($password)) {
				$password = $password;
			} else {
				$password = '';
				$form_incomplete = 1;
			}
		}
		// E-mail Check
		$email = '';
		if (isset($_POST['RegEmail'])) {
			$email_error = $this->CheckEmail($_POST['RegEmail']);
			if ($email_error == 3) {
				$email = '';
				$form_incomplete = 1;
			} elseif ($email_error == 2) {
				$email = '';
				$form_incomplete = 1;
			} elseif ($email_error == 3) {
				$email = '';
				$email_error = 3;
				$form_incomplete = 1;
			} else {
				$email = $_POST['RegEmail'];
			}
		} else {
			if ($email > '') {
				$email_error = $this->CheckEmail($email);
				$email = $email;
				if ($email_error == 3) {
					$email = '';
					$email_error = 3;
					$form_incomplete = 1;
				} elseif ($email_error == 2) {
					$email = '';
					$form_incomplete = 1;
				} elseif ($email_error == 1) {
					$email = '';
					$email_error = 1;
					$form_incomplete = 1;
				} elseif ($email_error == 0) {
					$email = '';
					$email_error = 0;
					$form_incomplete = 1;
				} else {
					$email = $_POST['RegEmail'];
				}
			} else {
				$email = '';
				$email_error = 0;
				$form_incomplete = 1;
			}
		}
		// Check Secret Question
		if (isset($_POST['RegQuestion'])) {
			$secret_question = $_POST['RegQuestion'];
			if ($secret_question == '') {
				$form_incomplete = 1;
				$secret_question_error = 1;
			} else {
				$secret_question_error = 0;
			}
		} else {
			$secret_question_error = 1;
			$form_incomplete = 1;
		}
		// Check Secret Answer
		if (isset($_POST['RegAnswer'])) {
			$secret_answer = $_POST['RegAnswer'];
			if ($secret_answer == '') {
				$form_incomplete = 1;
				$secret_answer_error = 1;
			} else {
				$secret_answer_error = 0;
			}
		} else {
			$secret_answer_error = 1;
			$form_incomplete = 1;
		}
		// Set Error String
		$error = '';
		// Form
		// Was form submitted?
		if ((isset($_POST['RegSubmit'])) && ($_POST['RegSubmit'] == $buttonval)) {
			// User Error Report
			if ($user_error == 2) {
				$error .= 'The username you selected already exists.<br />';
			} elseif (($user_error == 0) or ($user_error == 1)) {
				$error .= 'Please enter your username.<br />';
			}
			// Password Error Report
			if ($pass_error == 2) {
				$error .= 'Your password must be 8 characters or more.<br />';
			} elseif ($pass_error == 1) {
				$error .= 'Please enter your password.<br />';
			}
			// Secret Question Error Report
			if ($secret_question_error == 1) {
				$error .= 'You must enter a Secret Question.<br />';
			}
			// Secret Answer Error Report
			if ($secret_answer_error == 1) {
				$error .= 'You must enter a Secret Answer.<br />';
			}
			// E-mail Error Report
			if (is_int($email_error)) {
				if ($email_error == 2) {
					$error .= 'The e-mail you entered already exists.<br />';
				} elseif ($email_error == 3) {
					$error .= 'The e-mail you entered is not a valid e-mail.<br />';
				} elseif (($email_error == 0) or ($email_error == 1)) {
					$error .= 'Please enter your e-mail.<br />';
				}
			}
			// Check if Google Captcha is on
			if ($GoogleCaptchaOption == 1) {
				$resp = NULL;
				// Was there a reCAPTCHA response?
				if ((isset($_POST["recaptcha_response_field"])) && (isset($_POST["recaptcha_challenge_field"]))) {
					if (($_POST["recaptcha_response_field"] > '') && ($_POST["recaptcha_challenge_field"] > '')) {
						$resp = recaptcha_check_answer($privatekey, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
						if ($resp->is_valid === false) {
							$form_incomplete = 1;
							if ($resp->error == 'incorrect-captcha-sol') {
								$error .= 'Incorrect captcha words.<br />';
							} else {
								$error .= $resp->error . '<br />';
							}
						} else {
							
						}
					} else {
						$form_incomplete = 1;
						$error .= 'Please enter the captcha words.<br />';
					}
				} else {
					$form_incomplete = 1;
					$error .= 'Please enter the captcha words.<br />';
				}
			}
		}
		$form .= '' . $error . '';
		$form .= '<h2>Register</h2>';
		$form .= '<div class="paddingBottom10 clearfix"></div>';
		$form .= '<form action="#top" name="CreateUser" id="CreateUser" method="post" onsubmit="">';
		$form .= '<div class="FormContainer">';
		// Check username print
		if (is_int($username)) {
			if (isset($_POST['RegUsername'])) {
				$username = $this->XSSCheck->process($_POST['RegUsername']);
			} else {
				$username = $username;
			}
		} else {
			if (isset($username)) {
				$username = $username;
			} else {
				$username = '';
			}
		}
		$form .= '<div class="FormLeftColumn"><label title="Username"><strong>Username:</strong></label></div><br />';
		$form .= '<div class="FormRightColumn"><input type="text" size="25" id="RegUsername" name="RegUsername" value="' . $username . '" required="required" /></div>';
		$form .= '<br class="clearfix" />';
		$form .= '<div class="paddingBottom10 clearfix"></div>';
		$form .= '<div class="FormLeftColumn"><label title="Password"><strong>Password:</strong></label></div><br />';
		$form .= '<div class="FormRightColumn"><input type="password" size="25" id="RegPassword" name="RegPassword" value="" required="required" /></div>';
		$form .= '<br />';
		$form .= '<br class="clearfix" />';
		// Check register question print
		if (isset($_POST['RegQuestion'])) {
			$RegQuestion = $this->XSSCheck->process($_POST['RegQuestion']);
		} else {
			$RegQuestion = '';
		}
		$form .= '<div class="FormLeftColumn"><label title="Secret Question"><strong>Secret Question:</strong></label></div><br />';
		$form .= '<div class="FormRightColumn"><input type="text" size="25" id="RegQuestion" name="RegQuestion" value="' . stripcslashes($RegQuestion) . '" required="required" /></div>';
		$form .= '<br />';
		$form .= '<br class="clearfix" />';
		// Check register answer print
		if (isset($_POST['RegAnswer'])) {
			$RegAnswer = $this->XSSCheck->process($_POST['RegAnswer']);
		} else {
			$RegAnswer = '';
		}
		$form .= '<div class="FormLeftColumn"><label title="Secret Answer"><strong>Secret Answer:</strong></label></div><br />';
		$form .= '<div class="FormRightColumn"><input type="password" size="25" id="RegAnswer" name="RegAnswer" value="' . stripcslashes($RegAnswer) . '" required="required" /></div>';
		$form .= '<br class="clearfix" />';
		$form .= '<div class="paddingBottom10 clearfix"></div>';
		$form .= '<div class="FormLeftColumn"><label title="E-mail"><strong>E-mail:</strong></label></div><br />';
		// Check email print
		if (!is_int($email)) {
			if (isset($email)) {
				$email = $email;
			} else {
				$email = '';
			}
		}
		$form .= '<div class="FormRightColumn"><input type="text" size="25" id="RegEmail" name="RegEmail" value="' . $email . '" required="required" /></div>';
		$form .= '<br />';
		$form .= '<div class="paddingBottom10 clearfix"></div>';
		if (isset($publickey)) {
			$form .= '<div class="FormLeftColumn"><label title="Captcha"><strong>Captcha:</strong></label></div><br />';
			$form .= '<div class="FormRightColumn">' . recaptcha_get_html($publickey, $error) . '</div>';
			$form .= '<br />';
			$form .= '<div class="paddingBottom10 clearfix"></div>';
			$form .= '<div class="paddingBottom10 clearfix"></div>';
		}
		$form .= '<label title="' . $buttonval . '"><input type="submit" id="RegSubmit" name="RegSubmit" value="' . $buttonval . '" /></label>';
		$form .= '<br />';
		$form .= '<div class="paddingBottom10 clearfix"></div>';
		$form .= '</div>';
		$form .= "\t\t" . '<div class="FormContainer">' . PHP_EOL;
		// Ajax Checks for Linking
		if ($ajax == 1) {
			$form .= "\t\t" . '<div class="FormRightColumn"><a href="/forgot" title="Forgot Password?">Forgot Password?</a></div>';
		} else {
			$form .= "\t\t" . '<div class="FormRightColumn"><a href="/index.php?forgot=1" title="Forgot Password?">Forgot Password?</a></div>';
		}
		$form .= "\t\t" . '</div>' . PHP_EOL;
		$form .= "\t\t" . '<br class="clearfix" />' . PHP_EOL;
		$form .= '</form>';
		// The form is initialized/incompleted
		if ($form_incomplete == 1) {
			return $form;
		} else {
			$salt = $this->GenerateSalt(); // Salting password
			$password = $this->PasswordCreate(array($username, $password, $salt)); // Create Password
			// Generate Secret Answer Encrypt
			$salt_answer = $this->GenerateSalt(); // Salting answer
			$new_secret_answer = $this->PasswordCreate(array($username, $secret_answer, $salt_answer));
			$this->db_conn->query('INSERT INTO ' . $this->config['table_prefix'] . 'users (username, password, email, sspw, secret_question, secret_answer, salt_answer) VALUES ("' . $username . '", "' . $password . '", "' . $email . '", "' . $salt . '", "' . $secret_question . '", "' . $new_secret_answer . '", "' . $salt_answer . '")');
			$insertID = $this->db_conn->insert_id;
			$this->db_conn->query('INSERT INTO ' . $this->config['table_prefix'] . 'users_groups (userid, groupid) VALUES ("' . $insertID . '", 2)');
			return '<p>Registration Complete.</p>';
		}
	}
	// Get User Information and put into an Array
	public function GetUserInformation($username) {
		$UserInfo = $this->db_conn->query("
		SELECT " . $this->config['table_prefix'] . "users.id, username, groupid, level, lastactivity, site_id  
		FROM " . $this->config['table_prefix'] . "users 
		LEFT JOIN " . $this->config['table_prefix'] . "users_groups ON " . $this->config['table_prefix'] . "users.id = " . $this->config['table_prefix'] . "users_groups.userid 
		LEFT JOIN " . $this->config['table_prefix'] . "groups ON " . $this->config['table_prefix'] . "users_groups.groupid = " . $this->config['table_prefix'] . "groups.id 
		LEFT JOIN " . $this->config['table_prefix'] . "users_websites ON " . $this->config['table_prefix'] . "users.id = " . $this->config['table_prefix'] . "users_websites.user_id 
		WHERE " . $this->config['table_prefix'] . "users.username = '" . $username . "' 
		LIMIT 1");
		while ($rows = $UserInfo->fetch_array(MYSQLI_ASSOC)) {
			$UserInfoReset[] = $rows;
		}
		return $UserInfoReset[0];
	}
	// Login User
	public function LoginUser($ajax = 1, $loc = '') {
		// Vars
		$user_error = 0;
		$pass_error = 0;
		$buttonval = 'Login';
		$formok = 0;
		$error = '';
		$form = '';
		if (!isset($loc)) {
			$loc = '';
		}
		// Error Checking
		// Username
		if (isset($_POST['username'])) {
			$username = $this->XSSCheck->process($_POST['username']);
		} else {
			if (isset($username)) {
				$username = $username;
			} else {
				$username = '';
			}
		}
		// Password
		if (isset($_POST['password'])) {
			$password = $_POST['password'];
		} else {
			if (isset($password)) {
				$password = $password;
			} else {
				$password = '';
			}
		}
		// Submitted material required processed below
		// Was form submitted?
		if ((isset($_POST['submit'])) &&  ($_POST['submit'] == $buttonval)) {
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
		// Form
		$form = '<div class="FormContainer">' . PHP_EOL;
		$form .= "\t\t" . '<div class="FormLeftColumn"><h2>Login</h2></div>';
		$form .= "\t\t" . '</div>' . PHP_EOL;
		$form .= "\t\t" . '<form action="#top" name="LoginUser" id="LoginUser" method="post">' . PHP_EOL;
		$form .= "\t\t" . '<div class="FormContainer">' . PHP_EOL;
		$form .= "\t\t" . '<div class="FormLeftColumn"><label title="Username"><strong>Username:</strong></label></div>' . PHP_EOL;
		$form .= "\t\t" . '<div class="FormRightColumn"><input type="text" id="username" name="username" size="15" value="' . $username . '" required="required" placeholder="Username" /></div>' . PHP_EOL;
		$form .= "\t\t" . '<div class="paddingBottom10 clearfix"></div>' . PHP_EOL;
		$form .= "\t\t" . '<div class="FormLeftColumn"><label title="Password"><strong>Password:</strong></label></div>' . PHP_EOL;
		$form .= "\t\t" . '<div class="FormRightColumn"><input type="password" size="15" id="password" name="password" value="" required="required" placeholder="Password" /></div>' . PHP_EOL;
		$form .= "\t\t" . '<br />' . PHP_EOL;
		$form .= "\t\t" . '<div class="paddingBottom10 clearfix"></div>' . PHP_EOL;
		if ($ajax == 1) {
			$form .= "\t\t" . '<label title="Login"><input type="submit" id="submit" name="submit" value="' . $buttonval . '" /></label>' . PHP_EOL;
		} else {
			$form .= "\t\t" . '<label title="Login"><input type="button" id="ajaxsubmit" onclick="loginUser()" name="ajaxsubmit" value="' . $buttonval . '" /></label>' . PHP_EOL;
		}
		$form .= "\t\t" . '<br />' . PHP_EOL;
		$form .= "\t\t" . '<div class="paddingBottom10 clearfix"></div>' . PHP_EOL;
		$form .= "\t\t" . '</div>' . PHP_EOL;
		$form .= "\t\t" . '<div class="FormContainer">' . PHP_EOL;
		// Ajax Checks for Linking
		if ($ajax == 1) {
			$form .= "\t\t" . '<div class="FormLeftColumn"><a href="' . $loc . '/register" title="Register">Register</a></div>';
		} else {
			$form .= "\t\t" . '<div class="FormLeftColumn"><a href="' . $loc . '/register" title="Register">Register</a></div>';
		}
		if ($ajax == 1) {
			$form .= "\t\t" . '<div class="FormRightColumn"><a href="' . $loc . '/forgot" title="Forgot Password?">Forgot Password?</a></div>';
		} else {
			$form .= "\t\t" . '<div class="FormRightColumn"><a href="' . $loc . '/forgot" title="Forgot Password?">Forgot Password?</a></div>';
		}
		$form .= "\t\t" . '</div>' . PHP_EOL;
		$form .= "\t\t" . '</form>' . PHP_EOL;
		// Form Checker caught an error
		if ($formok == 0) {
			return $form;
		} else {
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
					return true;
				} else {
					return 'Login Failure, Contact Administrator!' . '<br /><br />';
				}
			} else {
				return 'Invalid Username/Password!<br />' . $form . '<br /><br />';
			}
		}
	}
	// Login User (Responsive Template)
	public function LoginUserResponsive($ajax = 1, $loc = '') {
		// Vars
		$user_error = 0;
		$pass_error = 0;
		$buttonval = 'Sign in';
		$formok = 0;
		$error = '';
		$form = '';
		if (!isset($loc)) {
			$loc = '';
		}
		// Error Checking
		// Username
		if (isset($_POST['username'])) {
			$username = $this->XSSCheck->process($_POST['username']);
		} else {
			if (isset($username)) {
				$username = $username;
			} else {
				$username = '';
			}
		}
		// Password
		if (isset($_POST['password'])) {
			$password = $_POST['password'];
		} else {
			if (isset($password)) {
				$password = $password;
			} else {
				$password = '';
			}
		}
		// Submitted material required processed below
		// Was form submitted?
		if ((isset($_POST['submit'])) &&  ($_POST['submit'] == $buttonval) || (isset($_POST['username'])) &&  ($_POST['username'] > '' && isset($_POST['password'])) &&  ($_POST['password'] > '')) {
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
		// Form
		$form .= '<form action="#top" name="LoginUser" id="LoginUser" method="post" class="navbar-form pull-right">' . PHP_EOL;
		$form .= "\t" . '<input type="text" id="username" name="username" size="15" value="' . $username . '" required="required" placeholder="Username" class="span2" />' . PHP_EOL;
		$form .= "\t" . '<input type="password" size="15" id="password" name="password" value="" required="required" placeholder="Password" class="span2" />' . PHP_EOL;
		if ($ajax == 1) {
			$form .= "\t" . '<button type="submit" class="btn" class="btn">' . $buttonval . '</button>' . PHP_EOL;
		} else {
			//$form .= "\t" . '<input type="button" id="ajaxsubmit" onclick="loginUser()" name="ajaxsubmit" value="' . $buttonval . '" class="btn" />' . PHP_EOL;
			$form .= "\t" . '<button type="submit" class="btn">' . $buttonval . '</button>' . PHP_EOL;
		}
		// Ajax Checks for Linking
		/*if ($ajax == 1) {
			$form .= "\t\t" . '<a href="' . $loc . '/register" title="Register">Register</a></div>';
		} else {
			$form .= "\t\t" . '<a href="' . $loc . '/register" title="Register">Register</a></div>';
		}
		if ($ajax == 1) {
			$form .= "\t\t" . '<a href="' . $loc . '/forgot" title="Forgot Password?">Forgot Password?</a>';
		} else {
			$form .= "\t\t" . '<a href="' . $loc . '/forgot" title="Forgot Password?">Forgot Password?</a>';
		}*/
		$form .= "\t\t" . '</form>' . PHP_EOL;
		// Form Checker caught an error
		if ($formok == 0) {
			return $form;
		} else {
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
					return true;
				} else {
					return 'Login Failure, Contact Administrator!' . '<br /><br />';
				}
			} else {
				return 'Invalid Username/Password!<br />' . $form . '<br /><br />';
			}
		}
	}
	// Register User (Responsive Template)
	public function CreateUserResponsive() {
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
		// Default Vars
		$facebook = '';
		$linkedin = '';
		$instagram = '';
		$pinterest = '';
		$youtube = '';
		$vine = '';
		$twitter = '';
		$myspace = '';
		// Check Picture
		if (isset($_REQUEST['Picture'])) {
			$picture = $_REQUEST['Picture'];
			if ($picture == '') {
				$form_incomplete = 1;
				$picture_error = 1;
			}
		} else {
			$form_incomplete = 1;
			$picture_error = 1;
		}
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
		// Check Username
		if (isset($_REQUEST['regusername'])) {
			$username = $this->CheckUsername($_REQUEST['regusername']);
			if ($username != $_REQUEST['regusername']) {
				$form_incomplete = 1;
				$user_error = 1;
			} elseif ($username == '') {
				$form_incomplete = 1;
				$user_error = 2;
			} else {
				$username = $_REQUEST['regusername'];
			}
		} else {
			$form_incomplete = 1;
			$user_error = 1;
		}
		// Password Check
		if (isset($_REQUEST['regpassword'])) {
			$password = $_REQUEST['regpassword'];
			if ($password == '') {
				$form_incomplete = 1;
				$pass_error = 1;
			} else {
				if (strlen($password) <= 7) {
					$form_incomplete = 1;
					$pass_error = 2;
				}
			}
		} else {
			$form_incomplete = 1;
			$pass_error = 1;
		}
		// Password match
		if (isset($_REQUEST['retypePassword'])) {
			$passwordretype = $_REQUEST['retypePassword'];
			if ($passwordretype == '') {
				$form_incomplete = 1;
				$passretype_error = 1;
			}
		} else {
			$form_incomplete = 1;
			$passretype_error = 1;
		}
		// E-mail Check
		if (isset($_REQUEST['regemail'])) {
			$email_error = $this->CheckEmail($_REQUEST['regemail']);
			if ($email_error == 3) {
				$email = '';
				$form_incomplete = 1;
			} elseif ($email_error == 2) {
				$email = '';
				$form_incomplete = 1;
			} elseif ($email_error == 3) {
				$email = '';
				$email_error = 3;
				$form_incomplete = 1;
			} else {
				$email = $_REQUEST['regemail'];
			}
		} else {
			$email_error = 1;
			$form_incomplete = 1;
		}
		// Question Checks
		if (isset($_REQUEST['RegQuestion'])) {
			$secret_question = $_REQUEST['RegQuestion'];
			if ($secret_question == '') {
				$form_incomplete = 1;
				$secret_question_error = 1;
			}
		} else {
			$form_incomplete = 1;
			$secret_question_error = 1;
		}
		// Check Secret Answer
		if (isset($_REQUEST['RegAnswer'])) {
			$secret_answer = $_REQUEST['RegAnswer'];
			if ($secret_answer == '') {
				$form_incomplete = 1;
				$secret_answer_error = 1;
			}
		} else {
			$form_incomplete = 1;
			$secret_answer_error = 1;
		}
		// Check Captcha
		if (isset($_REQUEST['captcha'])) {
			if(strtoupper($_REQUEST['captcha']) != $_SESSION['captcha_id']) {
				$form_incomplete = 1;
				$captcha_error = 1;
			}
			
		} else {
			$form_incomplete = 1;
			$captcha_error = 1;
		}
		// Check Links
		if (isset($_REQUEST['Facebook']) && $_REQUEST['Facebook'] > '') {
			$facebook = $_REQUEST['Facebook'];
			if (filter_var($facebook, FILTER_VALIDATE_URL) === false) {
				$facebook_error = 1;
				$form_incomplete = 1;
			}
		}
		if (isset($_REQUEST['LinkedIn']) && $_REQUEST['LinkedIn'] > '') {
			$linkedin = $_REQUEST['LinkedIn'];
			if (filter_var($linkedin, FILTER_VALIDATE_URL) === false) {
				$linkedin_error = 1;
				$form_incomplete = 1;
			}
		}
		if (isset($_REQUEST['Instagram']) && $_REQUEST['Instagram'] > '') {
			$instagram = $_REQUEST['Instagram'];
			if (filter_var($instagram, FILTER_VALIDATE_URL) === false) {
				$instagram_error = 1;
				$form_incomplete = 1;
			}
		}
		if (isset($_REQUEST['Pinterest']) && $_REQUEST['Pinterest'] > '') {
			$pinterest = $_REQUEST['Pinterest'];
			if (filter_var($pinterest, FILTER_VALIDATE_URL) === false) {
				$pinterest_error = 1;
				$form_incomplete = 1;
			}
		}
		if (isset($_REQUEST['YouTube']) && $_REQUEST['YouTube'] > '') {
			$youtube = $_REQUEST['YouTube'];
			if (filter_var($youtube, FILTER_VALIDATE_URL) === false) {
				$youtube_error = 1;
				$form_incomplete = 1;
			}
		}
		if (isset($_REQUEST['Vine']) && $_REQUEST['Vine'] > '') {
			$vine = $_REQUEST['Vine'];
			if (filter_var($vine, FILTER_VALIDATE_URL) === false) {
				$vine_error = 1;
				$form_incomplete = 1;
			}
		}
		if (isset($_REQUEST['Twitter']) && $_REQUEST['Twitter'] > '') {
			$twitter = $_REQUEST['Twitter'];
			if (filter_var($twitter, FILTER_VALIDATE_URL) === false) {
				$twitter_error = 1;
				$form_incomplete = 1;
			}
		}
		if (isset($_REQUEST['MySpace']) && $_REQUEST['MySpace'] > '') {
			$myspace = $_REQUEST['MySpace'];
			if (filter_var($myspace, FILTER_VALIDATE_URL) === false) {
				$myspace_error = 1;
				$form_incomplete = 1;
			}
		}
		// Check if form is complete
		if ($form_incomplete == 1) {
			// Error String Building
			$error_return_string = '';
			// Specific errors and strings
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
			// Cast request to session
			$_SESSION['formrequestarray'] = $_REQUEST;
			// Send error string to session
			$_SESSION['regerrorstring'] = $error_return_string;
			// return false
			return "false";
		} else {
			// Process
			$salt = $this->GenerateSalt(); // Salting password
			$password = $this->PasswordCreate(array($username, $password, $salt)); // Create Password
			// Generate Secret Answer Encrypt
			$salt_answer = $this->GenerateSalt(); // Salting answer
			$new_secret_answer = $this->PasswordCreate(array($username, $secret_answer, $salt_answer));
			// Insert user to main table
			$this->db_conn->query('INSERT INTO ' . $this->config['table_prefix'] . 'users (username, password, email, sspw, active, secret_question, secret_answer, salt_answer) VALUES ("' . $username . '", "' . $password . '", "' . $email . '", "' . $salt . '", 1, "' . $secret_question . '", "' . $new_secret_answer . '", "' . $salt_answer . '")');
			$insertID = $this->db_conn->insert_id;
			// Check Insert ID
			if (isset($insertID) && $insertID > 0) {
				// Insert User Data to child tables
				$this->db_conn->query('INSERT INTO ' . $this->config['table_prefix'] . 'users_groups (userid, groupid) VALUES ("' . $insertID . '", 2)');
				$this->db_conn->query('
								INSERT INTO ' . $this->config['table_prefix'] . 'users_infomain 
								(id, 
								business_address, 
								business_address_two, 
								business_city, 
								business_state, 
								business_zip, 
								business_phone_number, 
								business_nmls, 
								business_license, 
								facebook_link, 
								linkedin_link, 
								instagram_link, 
								pinterest_link, 
								youtube_link, 
								vine_link, 
								twitter_link, 
								myspace_link) 
								VALUES 
								(' . $insertID . ', 
								"' . $busadd . '", 
								"' . $busaddtwo . '", 
								"' . $city . '", 
								"' . $state . '", 
								"' . $zip . '", 
								"' . $phonenum . '", 
								"' . $nmlsnum . '", 
								"' . $licensenum . '", 
								"' . $facebook . '", 
								"' . $linkedin . '", 
								"' . $instagram . '", 
								"' . $pinterest . '", 
								"' . $youtube . '", 
								"' . $vine . '",
								 "' . $twitter . '", 
								 "' . $myspace . '")');
				// Return true for AJAX
				return "true";
			} else {
				// Return false for AJAX
				return "false";
			}
		}
	}
	// Update user activity
	public function UpdateActivity($username) {
		return $this->db_conn->query("UPDATE " . $this->config['table_prefix'] . "users SET lastlogin='" . date('Y-m-d H:i:s') . "' WHERE username='" . $username . "'"); // Update last login/activity	
	}
	// Forgot user password
	public function ForgotPassword($ajax = 1) {
		global $GoogleCaptchaOption;
		if ($GoogleCaptchaOption == 1) {
			global $publickey;
			global $privatekey;
		}
		// Vars
		$buttonval = 'Send Password';
		$formok = 0;
		$form = '';
		$error = '';
		$UserFound = 0;
		$EmailFound = 0;
		$UserEntered = 0;
		$EmailEntered = 0;
		$UserNotFound = 0;
		$NotValidEmail = 0;
		$EmailNotFound = 0; 
		// Form Submitted
		if ((isset($_POST['ForgotSubmit'])) && ($_POST['ForgotSubmit'] == $buttonval)) {
			// Did we get username or email?
			if (isset($_POST['ForgotUsername'])) {
				if ($_POST['ForgotUsername'] > '') {
					$UserEntered = 1;
				}
			}
			if (isset($_POST['ForgotEmail'])) {
				if ($_POST['ForgotEmail'] > '') {
					$EmailEntered = 1;
				}
			}
			// Username checker
			if ($UserEntered == 0) {
				$formok = 0;
			} else {
				$username = $_POST['ForgotUsername'];
				$CheckUser = $this->CheckUsername($_POST['ForgotUsername']);
				if ($CheckUser == 2) { // Exists
					// Form submission passes all checks
					$formok = 1;
					$UserFound = 1;
				} else {
					$UserNotFound = 1; // User was not found
				}
			}
			// E-mail checker
			if ($formok == 0) {
				if ($EmailEntered == 0) {
					$formok = 0;
				} else {
					$email = $_POST['ForgotEmail'];
					$CheckEmail = $this->CheckEmail($_POST['ForgotEmail']); // Check if email exists
					if ($CheckEmail == 2) { // Exists
						// Form submission passes all checks
						$formok = 1;
						$EmailFound = 1;
					} elseif ($CheckEmail == 3) { // Not valid
						$NotValidEmail = 1;
					} else { // Email was not found
						$EmailNotFound = 1; 
					}
				}
			} else {
				$email = $this->CheckUsername($_POST['ForgotUsername'], 1); // Pulling email via username
			}
			// Are there errors?
			if (($UserEntered == 0) and ($EmailEntered == 0)) {
				$error .= 'Please enter a Username or E-mail.<br />';
			} else {
				// Check which form entry had a value
				if ($UserEntered == 1) {
					if ($UserNotFound == 1) {
						$error .= 'Username not found.<br />';
					}
				} elseif ($EmailEntered == 1) {
					if ($NotValidEmail == 1) {
						$error .= 'Please enter a valid E-mail.<br />';
					} elseif ($EmailNotFound == 1) {
						$error .= 'E-mail not found.<br />';
					}
				}
			}
			// Google Captcha Code
			if ($GoogleCaptchaOption == 1) { // Check if Google Captcha is on
				$resp = NULL;
				// Was there a reCAPTCHA response?
				if ($_POST["recaptcha_response_field"] != '') {
					$resp = recaptcha_check_answer($privatekey, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
					if ($resp->is_valid === false) {
						$form_incomplete = 1;
						if ($resp->error == 'incorrect-captcha-sol') {
							$error .= 'Incorrect captcha words.<br />';
						} else {
							$error .= $resp->error . '<br />';
						}
					}
				} else {
					$form_incomplete = 1;
					$error .= 'Please enter the captcha words.<br />';
				}
			}
			// If there are errors print the to form variable
			if ($error > '') {
				$form .= '' . $error . '';
			}
		}
		// Form
		$form .= '<h2>Forgot Password</h2>';
		$form .= "\t\t" . '<div class="paddingBottom10 clearfix"></div>' . PHP_EOL;
		$form .= "\t\t" . '<div class="FormContainer">' . PHP_EOL;
		$form .= "\t\t" . '<form action="#top" name="ForgotPass" id="ForgotPass" method="post">' . PHP_EOL;
		$form .= "\t\t" . '<div class="FormContainer">' . PHP_EOL;
		$form .= "\t\t" . '<div class="FormLeftColumn"><label title="Username"><strong>Username:</strong></label></div>' . PHP_EOL;
		$form .= "\t\t" . '<div class="FormRightColumn"><input type="text" id="ForgotUsername" name="ForgotUsername" size="25" maxlength="100" value="" required="required" placeholder="Enter your Username" /></div>' . PHP_EOL;
		$form .= "\t\t" . '<br class="clearfix" />' . PHP_EOL;
		$form .= "\t\t" . '<div class="paddingBottom10 clearfix"></div>' . PHP_EOL;
		$form .= "\t\t" . '<div class="FormLeftColumn"><label title="E-mail"><strong>E-mail:</strong></label></div>' . PHP_EOL;
		$form .= "\t\t" . '<div class="FormRightColumn"><input type="text" id="ForgotEmail" name="ForgotEmail" size="25" maxlength="155" value="" required="required" placeholder="Enter your e-mail" /></div>' . PHP_EOL;
		$form .= "\t\t" . '<div class="paddingBottom10 clearfix"></div>' . PHP_EOL;
		$form .= '<div class="paddingBottom10 clearfix"></div>';
		if (isset($publickey)) {
			$form .= '<div class="FormLeftColumn"><label title="Captcha"><strong>Captcha:</strong></label></div><br />';
			$form .= '<div class="FormRightColumn">' . recaptcha_get_html($publickey, $error) . '</div>';
			$form .= "\t\t" . '<div class="paddingBottom10 clearfix"></div>' . PHP_EOL;
		}
		$form .= "\t\t" . '<label title="' . $buttonval . '"><input type="submit" id="ForgotSubmit" name="ForgotSubmit" value="' . $buttonval . '" /></label>' . PHP_EOL;
		$form .= "\t\t" . '</div>' . PHP_EOL;
		$form .= "\t\t" . '<div class="paddingBottom10 clearfix"></div>' . PHP_EOL;
		$form .= "\t\t" . '<div class="FormContainer">' . PHP_EOL;
		// Ajax Checks for Linking
		if ($ajax == 1) {
			$form .= "\t\t" . '<div class="FormLeftColumn"><a href="register" title="Register">Register</a></div>';
		} else {
			$form .= "\t\t" . '<div class="FormLeftColumn"><a href="/register" title="Register">Register</a></div>';
		}
		$form .= "\t\t" . '</div>' . PHP_EOL;
		$form .= "\t\t" . '</form>' . PHP_EOL;
		$form .= "\t\t" . '</div>' . PHP_EOL;
		// Form had problems
		if ($formok == 0) {
			return $form;
		} else { // Form is ok
			include('extensions/utilities/Mailer.class.php');
			$Mail = new Mailer();
			$to = $email;
			$from = 'no-reply@' . $this->config['WebSiteDomain'];
			$subject = $this->config['WebSiteTitle'] . ' - Recover Password';
			$PassRecCode = $this->PassRecoveryCode(); // Generating Password Recovery Code
			$this->db_conn->query("UPDATE " . $this->config['table_prefix'] . "users SET pw_recovery='" . $PassRecCode . "' WHERE email='" . $email . "'"); // Inserting code into table
			$message = '<p>To recover your password click the link below and follow the instructions.</p>';
			$message .= '<p>http://' . $_SERVER['HTTP_HOST'] . '/profile/recover-password.php?rec_code=' . $PassRecCode . '</p>';
			$html = 1; // Set to html email (1)
			$reply_to = 'no-reply@'  . $this->config['WebSiteDomain'];
			if ($Mail->sendSimpleMail($to, $from, $subject, $message, $html, $reply_to) === true) {
				return 'Email Sent.';
			} else {
				return 'Email Failed.';
			}
		}
	}
	// Password Recovery
	public function PasswordRecovery($pw_code) {
		$buttonval = 'Reset Password';
		$form = '<div class="FormContainer">' . PHP_EOL;
		$form .= "\t\t" . '<div class="FormRightColumn"><strong>Reset Password</strong></div>' . PHP_EOL;
		$form .= "\t\t" . '</div>' . PHP_EOL;
		$form .= "\t\t" . '<br class="clearfix" />' . PHP_EOL;
		$form .= "\t\t" . '<p>Enter your new password below. The entered password will be your new password.<br />It must be 9 characters or more.</p>' . PHP_EOL;
		$form .= "\t\t" . '<div class="paddingBottom10 clearfix"></div>' . PHP_EOL;
		$form .= '<form action="" name="CreateUser" id="CreateUser" method="post">' . PHP_EOL;
		$form .= "\t\t" . '<div class="FormContainer">' . PHP_EOL;
		$form .= "\t\t" . '<div class="FormLeftColumn"><label><strong>Password:</strong></lable></div>' . PHP_EOL;
		$form .= "\t\t" . '<div class="FormRightColumn"><input type="password" id="password" name="password" value="" /></div>' . PHP_EOL;
		$form .= "\t\t" . '<br class="clearfix" />' . PHP_EOL;
		$form .= "\t\t" . '<div class="paddingBottom10 clearfix"></div>' . PHP_EOL;
		$form .= "\t\t" . '<input type="submit" id="submit" name="submit" value="' . $buttonval . '" />' . PHP_EOL;
		$form .= "\t\t" . '<br />' . PHP_EOL;
		$form .= "\t\t" . '<div class="paddingBottom10 clearfix"></div>' . PHP_EOL;
		$form .= "\t\t" . '</div>' . PHP_EOL;
		$form .= "\t\t" . '</form>' . PHP_EOL;
		if (isset($pw_code)) {
			if (strlen($pw_code) == 32) {
				$UserInfo = $this->GetConnection->SelectQuery('users', "pw_recovery='" . $pw_code . "'");
				if (strlen($UserInfo['pw_recovery']) == 32) {
					if ((isset($_POST['submit'])) && ($_POST['submit'] == $buttonval)) {
						if (strlen($_POST['password']) >= 9) {
							$salt = $this->GenerateSalt();
							$password = $this->PasswordCreate(array($UserInfo['username'], $_POST['password'], $salt));
							$this->db_conn->query("UPDATE " . $this->config['table_prefix'] . "users SET password='" . $password .  "', sspw ='" . $salt . "', pw_recovery='" . $pw_code . "'");
							$_SESSION[$this->config['session_prefix'] . 'passok'] = 1;
							$_SESSION[$this->config['session_prefix'] . 'adminok'] = 1;
							return '<p>Password Updated!<p><p><a href="/login">Login here</a></p>';
						} else {
							return '<p>Your password must be 9 characters or longer.</p>' . $form;
						}
					} else {
						return $form;
					}
				} else {
					return '<p>Invalid or Outdated Code!</p><p><a href="?forgot=1">Click here to recover your password</a>.</p>';
				}
			} else {
				return '<p>Invalid or Outdated Code!</p><p><a href="?forgot=1">Click here to recover your password</a>.</p>';
			}
		} else {
			return '<p>Invalid or Outdated Code!</p><p><a href="?forgot=1">Click here to recover your password</a>.</p>';
		}
	}
	// Logout
	public function LogOut() {
		// Reset sessions to default
		$_SESSION[$this->config['session_prefix'] . 'id'] = '';
		$_SESSION[$this->config['session_prefix'] . 'username'] = '';
		$_SESSION[$this->config['session_prefix'] . 'groupid'] = '';
		$_SESSION[$this->config['session_prefix'] . 'siteid'] = '';
		$_SESSION[$this->config['session_prefix'] . 'grouplevel'] = '';
		$_SESSION[$this->config['session_prefix'] . 'lastlogin'] = '';
		$_SESSION[$this->config['session_prefix'] . 'passok'] = 0;
		$_SESSION[$this->config['session_prefix'] . 'adminok'] = 0;
		//return header('Location: ' . $_SERVER['PHP_SELF'] . '');
	}
}
?>