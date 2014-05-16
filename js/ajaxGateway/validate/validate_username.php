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
$UserActions = new UserActions();
$CheckUser = $UserActions->CheckUsername($_POST['username']);
if ($CheckUser != $_POST['username']) {
	echo 'false';
} else {
	echo 'true';
}
?>