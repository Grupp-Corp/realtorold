<?php
/**
 * This is the main AJAX mailer response page
 * @name index.php
 *
 * @author Steven Scharf
 * @copyright (c) 2013, Steven Scharf
 */
if (empty($_GET['id']) && $_GET['id'] != 'osm489po92@') {
   header("HTTP/1.0 400 Bad Request", true, 400);
   exit('400: Bad Request');
} else {
	// Begin Variables
	$error = 0;
	$nameError = 0;
	$emailError = 0;
	$messageError = 0;
	// Checks
	if (!isset($_REQUEST['Name']) || $_REQUEST['Name'] == '') {
		$error = 1;
		$nameError = 1;
	} else if (!isset($_REQUEST['Email']) && $_REQUEST['Email'] == '') {
		$error = 1;
		$emailError = 1;
	} else if (!isset($_REQUEST['Message']) && $_REQUEST['Message'] == '' || strlen($_REQUEST['Message']) < 25) {
		$error = 1;
		$messageError = 1;
	}
	// Check error
	if ($error == 0) {
		ini_set('include_path', '/var/www/vhosts/myrealtorcliq.com/httpdocs/includes/'); // Set Include path (LIVE)
		//ini_set('include_path', 'M:/Development/Canuck Coder CMS/Versions/includes/'); // Set Include path
		set_time_limit(30);
		// Calling classes based on .ini setting of the include path above
		include('Template.class.php');
		include('TemplateDesign.class.php');
		include('extensions/user/UserActions.class.php'); // User Actions Class
		include('extensions/user/UserChecks.class.php'); // User Checks
		include('extensions/utilities/AjaxUserHelper.class.php'); // AJAX Helper Class
		include('extensions/index.php');
		include('plugins/index.php');
		$Mailer = new Mailer();
		$to = 'admin@myrealtorcliq.com';
		$from = strip_tags(htmlspecialchars($_REQUEST['Name'])) . '<' . strip_tags(htmlspecialchars($_REQUEST['Email'])) . '>';
		$subject = 'Canuck Coder Contact Form - Message from ' . strip_tags(htmlspecialchars($_REQUEST['Name']));
		$message = strip_tags(htmlspecialchars($_REQUEST['Message']));
		$message_header = '<strong>Name/Email:</strong><br /><a href="mailto:' . strip_tags(htmlspecialchars($_REQUEST['Email'])) . '" title="Email of ' . strip_tags(htmlspecialchars($_REQUEST['Name'])) . '">' . strip_tags(htmlspecialchars($_REQUEST['Name'])) . '</a><br />' . PHP_EOL;
		$message = $message_header . '<strong>Message:</strong><br />' . nl2br($message);
		$html = 1;
		$optional = 'steve@canuckcoder.com';
		if ($Mailer->sendSimpleMail($to, $from, $subject, $message, $html, $optional)) {
			if ($Mailer->sendSimpleMail(strip_tags(htmlspecialchars($_REQUEST['Email'])), 'MyRealtorCliq <admin@myrealtorcliq.com>', 'Thank you for contacting MyRealtorCliq.com', 'Hi ' . strip_tags(htmlspecialchars($_REQUEST['Name'])) . ',<br /><br />This is a confirmation that we have received your email and will get back to you within 2 business days.<br />If you have any further questions you can contact me by replying to this email.<br /><br />Sincerely,<br />MyRealtorCliq.com<br /><a href="http://www.myrealtorcliq.com" title="MyRealtorCliq.com Web Site">www.MyRealtorCliq.com</a>', $html, $optional)) {
				echo 'success';
			}
		} else {
			echo 'mailer error';
		}
	} else {
		if ($nameError == 1) {
			echo 'name error';
		} else if ($emailError == 1) {
			echo 'email error';
		} else if ($messageError == 1) {
			echo 'message error';
		} else {
			echo 'unknown error';
		}
	}
}
?>