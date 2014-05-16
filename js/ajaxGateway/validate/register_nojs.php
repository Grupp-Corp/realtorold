<?php
date_default_timezone_set('America/New_York'); // If the server failed to set PHP date.timezone in php.ini
ini_set('include_path', '/var/www/vhosts/myrealtorcliq.com/httpdocs/includes/'); // Set Include path (LIVE)
//ini_set('include_path', 'M:/Development/DaDaCliq/Development/includes/'); // Set Include path
// Calling classes based on .ini setting of the include path above
include('Template.class.php');
include('TemplateDesign.class.php');
include('extensions/index.php');
include('plugins/index.php');
include('extensions/user/UserActions.class.php');
session_start();
$UserActions = new UserActions();
// Registration page processor
if ($UserActions->CreateUserResponsive() === "true") {
	header('Location: /register?success=1');
	session_write_close();
} else {
	header('Location: /register?success=0');
	session_write_close();
}
?>