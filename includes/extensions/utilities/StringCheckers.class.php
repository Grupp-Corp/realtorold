<?php
class StringCheckers extends CCTemplate
{
	// Construct
	public function __construct() {
		// Initialize Email Validator Class
		$this->ValidateEmail = new EmailAddressValidator;
	}
	// Example for below function:
	// $this->CheckIfEmpty('test'); // Returns True
	// $this->CheckIfEmpty(''); // Returns False
	public function CheckIfEmpty($string) {
		if (isset($string)) {
			if ($string > '') {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}	
	}
	// Example for below function:
	// $this->CheckInteger(5); // Returns True
	// $this->CheckInteger('test'); // Returns False
	// $this->CheckInteger('9.1'); // Returns True
	// $this->CheckInteger('1e4'); // Returns True
	public function CheckInteger($int) {
		if (isset($int)) {
			if ($int > '') {
				if (is_numeric($int)) {
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
	}
	// Example for below function:
	// $this->CheckIntegerStrict(5); // Returns True
	// $this->CheckIntegerStrict('5'); // Returns False
	// $this->CheckIntegerStrict('9.1'); // Returns False
	// $this->CheckIntegerStrict('1e4'); // Returns False
	public function CheckIntegerStrict($int) {
		if (isset($int)) {
			if ($int > '') {
				if (is_int($int)) {
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
	}
	// Example for below function:
	// $this->CheckEmailAddress('someone@hc-sc.gc.ca', 'gc.ca'); // Ensures email address is part of the gc.ca domain (returns true or false)
	// $this->CheckEmailAddress('someone@hc-sc.gc.ca'); // Just checks for a valid email address (returns true or false)
	public function CheckEmailAddress($email) {
        if ($this->ValidateEmail->check_email_address($email) === true) {
            // Email address is technically valid
			return true;
        } else {
			return false;
		}
	}
	// Example for below function:
	// $this->CheckPhoneArea('613'); // Returns True
	// $this->CheckPhoneArea('6135'); // Returns False
	// $this->CheckPhoneArea('Step'); // Returns False
	public function CheckPhoneArea($area) { 
		$text = (string)$area;
		$textlen = strlen($text);
		//Not 0
		if ($textlen==0) {
			return false;
		}
		//Is equal to 4
		if ($textlen<>3) {
			return false;
		}
		for ($i=0;$i < $textlen;$i++) { 
			$ch = ord($text{$i});
		  	if (($ch<48) || ($ch>57)) {
				return false;
			}
		}
		return true;
	}
	// Example for below function:
	// $this->CheckPhonePrefix('555'); // Returns True
	// $this->CheckPhonePrefix('5555'); // Returns False
	// $this->CheckPhonePrefix('Step'); // Returns False
	public function CheckPhonePrefix($preffix) { 
		$text = (string)$preffix;
		$textlen = strlen($text);
		//Not 0
		if ($textlen==0) {
			return false;
		}
		//Is equal to 4
		if ($textlen<>3) {
			return false;
		}
		for ($i=0;$i < $textlen;$i++) { 
			$ch = ord($text{$i});
			if (($ch<48) || ($ch>57)) {
				return false;
			}
		}
		return true;
	}
	// Example for below function:
	// $this->CheckPhonePrefix('5555'); // Returns True
	// $this->CheckPhonePrefix('555'); // Returns False
	// $this->CheckPhonePrefix('555555'); // Returns False
	// $this->CheckPhonePrefix('Step'); // Returns False
	public function CheckPhoneSuffix($suffix) { 
		$text = (string)$suffix;
		$textlen = strlen($text);
		//Not 0
		if ($textlen==0) {
			return false;
		}
		//Is equal to 4
		if ($textlen<>4) {
			return false;
		}
		for ($i=0;$i < $textlen;$i++) { 
			$ch = ord($text{$i});
			if (($ch<48) || ($ch>57)) {
				return false;
			}
		}
		return true;
	}
	// Example for below function:
	// $this->StringLimiter('Lorem ipsum dolor sit amet.', 6); // Returns 'Lorem...'
	// $this->StringLimiter('Lorem ipsum dolor sit amet.'); // Returns 'Lorem ipsum dolor sit amet.'
	public function StringLimiter($content, $maxLength = '') {
		//Set Some Variables...
		$NoMaxLength = 0; // Max Length is on
		// Is max length staying on?
		if (!isset($maxLength)) { // not set
			$NoMaxLength = 1;
		} elseif ($maxLength == '') { // empty
			$NoMaxLength = 1;
		} elseif (!is_numeric($maxLength)) { // not numeric
			$NoMaxLength = 1;
		} else { // max length is set and numeric
			$strLength = strlen($content); // check content length
		}
		// Max lenth checker
		if ($NoMaxLength == 0) {// Max length is on
			//See if the length exceeded our amount, if not just show the title normally.
			if ($strLength > $maxLength) { // Remove extra characters
				if (false !== ($breakpoint = strpos($content, '.', $maxLength))) {
					$content = substr($content, 0, $breakpoint);
					return $content . '...';
				}
			} else { // Nothing to do...
				return $content;
			}
		} else { // Nothing to do...
			return $content;
		}
	}
	// Example for below function:
	// $this->SafeSQLTableCol('Lorem ipsum dolor sit amet.', 6); // Returns string
	// $this->SafeSQLTableCol('&^%$$#'); // Returns false
	public function SafeSQLTableCol($col) {
		if (preg_match("/^[a-zA-Z0-9_`.]+$/", $col) == 1) {
			return 1;
		} else {
			return 0;
		}
	}
	// Example for below function:
	// $this->SafeGet('Lorem ipsum dolor sit amet_', 6); // Returns string
	// $this->SafeGet('&^%$$#'); // Returns false
	public function SafeGetChars($TheGet) {
		if (preg_match("/^[a-zA-Z_ ]+$/", $TheGet) == 1) {
			return 1;
		} else {
			return 0;
		}
	}
}
?>