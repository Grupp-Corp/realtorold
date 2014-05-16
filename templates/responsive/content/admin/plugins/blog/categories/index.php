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
if (($add_edit_blog_cats == 1) or ($add_edit_blog_cats == 1)) {
	// Menu
	require_once('menu.php');
	// Actions
	if ((isset($_GET['act'])) && ($_GET['act'] == 'add')) { // Add
		require_once('addcat.php');
	} elseif ((isset($_GET['act'])) && ($_GET['act'] == 'edit')) { // Edit
		if (isset($_GET['id'])) {
			require_once('editcat.php');
		} else {
			echo 'No ID entered.';
		}
	} elseif ((isset($_GET['act'])) && ($_GET['act'] == 'delete')) { // Delete
		if (isset($_GET['id'])) {
			require_once('deletecat.php');
		} else {
			echo 'No ID entered.';
		}
	} else { // List
		require_once('listcats.php');
	}
} else {
	echo 'Access Denied.';
}
?>