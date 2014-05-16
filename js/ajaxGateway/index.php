<?php
/**
 * This is the main AJAX index response page
 * @name index.php
 *
 * @author Steven Scharf
 * @copyright (c) 2013, Steven Scharf
 */
if (isset($_REQUEST['cb']) && $_REQUEST['cb'] != '') {
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
	$cb_function = $_REQUEST['cb'];
	if(!isset($_SESSION)) {
		ob_start();
		session_start();
	}
	$AjaxHelper = new AjaxUserHelper();
	if ($AjaxHelper->$cb_function()) {
		echo $AjaxHelper->$cb_function();
	}
	session_write_close();
	ob_end_flush();
} else {
	return '';
}
?>