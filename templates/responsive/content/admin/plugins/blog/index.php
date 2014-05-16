<?php
$CheckPerms = new PermsPub();
// Get permissions
$GetCPPerms = $CheckPerms->GetUserPerms($_SESSION[$this->config['session_prefix'] . 'id']);
// Loop through permissions
foreach($GetCPPerms as $Array) {
	foreach ($Array as $key=>$val) {
		extract(array($key=>$val));
	}
}
if (($add_edit_blog == 1) or ($delete_blog == 1)) {
	// Menu
	require_once('menu.php');
	// Actions
	if ((isset($_GET['act'])) && ($_GET['act'] == 'add')) { // Add
		require_once('addblog.php');
	} elseif ((isset($_GET['act'])) && ($_GET['act'] == 'edit')) { // Edit
		if (isset($_GET['id'])) {
			require_once('editblog.php');
		} else {
			echo 'No ID entered.';
		}
	} elseif ((isset($_GET['act'])) && ($_GET['act'] == 'delete')) { // Delete
		if (isset($_GET['id'])) {
			require_once('deleteblog.php');
		} else {
			echo 'No ID entered.';
		}
	} else { // List
		require_once('listblogs.php');
	}
} else {
	echo 'Access Denied.';
}
?>