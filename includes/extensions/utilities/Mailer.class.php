<?php
class Mailer extends CCTemplate
{
	// Simple Mailing function with HTML/TEXT Support
	public function sendSimpleMail($to, $from, $subject, $message, $html = 0, $optional = '') {
		// From Header
		$headers = '';
		// Is this an HTML Email?
		if ($html == 1) {
			// Additional Headers below is for sending HTML Emails...
			$headers .= 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . PHP_EOL;
			$headers .= 'Content-Transfer-encoding: 8bit' . PHP_EOL;
		}
		// Required headers
		if ($optional == '') {
			$headers .= 'From: ' . $from . '' . PHP_EOL;
		}
		$headers .= 'Reply-To: ' . $from . '' . PHP_EOL .
    	'X-Mailer: PHP/' . phpversion();
		// What is the message we are sending?
		$message = $message;
		// Email, set name, message and header information
		if ($optional == '') {
			if (mail($to, $subject, stripslashes(nl2br($message)), $headers)) {
				return true; // If mail succeeded...
			} else {
				return false; // If mail failed...
			}
		} else {
			$optional = '-f' . $optional;
			if (@mail($to, $subject, stripslashes(nl2br($message)), $headers, $optional)) {
				return true; // If mail succeeded...
			} else {
				return false; // If mail failed...
			}
		}
	}
	// Mailer based on $_POST Data Array
	public function advanced_mailer($to, $from = "Anonymous@canuckcoder.com", $subject, $message, $postdata = '', $cc_email = '', $bcc_email = '') {
		// Fields to ignore
		$field = array("recipient", "redirect", "recipient", "subject", "Submit", "submit", "To", "to", "referrer");
		// Check if the message variable has a value.
		if (isset($message)) {
			if (isset($to)) { // Check if the 'to' has a value.
				$message = $message; // What is the message we are sending?
				$message .= "<br /><br />"; // Add a line break to our message...
				// Check if any postdata exists, if not don't bother processing this code block
				if (isset($postdata)) {
					// Get all the post data and begin building the message...
					$es = $postdata;
					// Loop through Keys and values of the post data and concatenate them together in a nice way...
					while (list($key, $val) = each($es)) {
						//Lets make sure there is a value first...
						if (isset($val)) {
							//Lets filter out a few fields that don't need to be in the message...
							$process_key = 1;
							foreach ($field as $fval) {
								if ($key == $fval) {
									$process_key = 0;
								}
							}
							// Value exists and key is ok
							if (($val > "") && ($process_key == 1)) {
								//Building our string for each key and value...
								$key = str_replace("_", " ", $key);
								$key = str_replace("--", "'", $key);
								$key = ucfirst($key);
								$message .= "<strong>".$key." : </strong> ".nl2br(stripslashes($val))."<br />";
							}
						}
					}
				}
				// Building headers for emails...
				$headers  = 'From: '.$from.'' . PHP_EOL;
				if (isset($cc_email)) {
					if ($cc_email > '') {
						$headers .= 'Cc: ' . $cc_email . '' . PHP_EOL;
					}
				}
				if (isset($bcc_email)) {
					if ($bcc_email > "") {
						$headers .= 'Bcc: '.$bcc_email.'' . PHP_EOL;
					}
				}
				// Additional Headers below is for sending HTML Emails...
				$headers .= 'MIME-Version: 1.0' . "\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . PHP_EOL;
				$headers .= 'Content-Transfer-encoding: 8bit' . PHP_EOL;
				// Required headers
				$headers .= 'Organization: ' . $this->config['WebSiteTitle'] . PHP_EOL;
				$headers .= 'X-Mailer:'.phpversion().'' . PHP_EOL;
				// Email, set name, message and header information
				if (mail($to, $subject, $message, $headers)) {
					// If mail succeeded...
					return true;
				} else {
					// If mail failed...
					return false;
				}
			//'to' was empty
			} else {
				return false;	
			}
		} else { // Message was empty
			return false;	
		}
	}
}
?>