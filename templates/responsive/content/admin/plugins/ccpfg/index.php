<?php
$CheckPerms = new PermsPub();
// Get permissions
$GetCPPerms = $CheckPerms->GetUserPerms($_SESSION[$this->config['session_prefix'] . 'id']);
// Grab Form Adinistration Object
$AdminForm = new FormAdmin();
// Get Form List
$FormList = $AdminForm->GetFormList();
// Vars
$html = '';
// Loop through permissions
foreach($GetCPPerms as $Array) {
	foreach ($Array as $key=>$val) {
		extract(array($key=>$val));
	}
}
if ($admin_access == 1) {
	if (isset($_GET['editoptions']) && $_GET['editoptions'] == 1) {
		include('fieldoptions.php');
	} else if (isset($_GET['addfield']) && $_GET['addfield'] == 1) {
		include('addfieldoption.php');
	} else if (isset($_GET['act']) && $_GET['act'] == "edit") {
		include('edit.php');
	} else if (isset($_GET['act']) && $_GET['act'] == "delete") {
		include('delete.php');
	} else if (isset($_GET['act']) && $_GET['act'] == "add") {
		include('add.php');
	} else {
		if (isset($_GET['form_added']) && $_GET['form_added'] == 1) {
			echo '<div class="alignCenter"><strong class="red">Form Added.</strong></div><br /><br />';
		} else if (isset($_GET['form_deleted']) && $_GET['form_deleted'] == 1) {
			echo '<div class="alignCenter"><strong class="red">Form Deleted.</strong></div><br /><br />';
		}
		// Check our rows
		if ($FormList['TotalRows'] > 0) {
			$html .= '<div class="alignCenter">';
			$html .= '<a href="?act=add" title="Add Form">Add Form</a>';
			$html .= '</div>';
			$html .= '<br />';
			$html .= '<div class="smallContainer">';
			$html .= '<h2>Form(s) List</h2>';
			$i = 1; // Incrementer
			foreach($FormList['RowArray'] as $row) {
				if ($i == 1) {
					$html .= '<div class="FirstColor">';
				} else {
					$html .= '<div class="SecondColor">';
				}
				// Left Column
				$html .= '<div class="LCol';
				$html .= '"><a href="?act=edit&amp;id=' . $row['fid'] . '" title="' . $row['FormName'] . '"><span class="underline">' . $row['FormName'] . '</span></a>';
				$html .= '</div>';
				// Right Column
				$html .= '<div class="RCol';
				$html .= '">';
				if ($form_generator == 1) {
					$html .= '<a href="?act=edit&amp;id=' . $row['fid'] . '" title="Edit"><span class="underline">Edit</span></a>';
					$html .= ' | <a href="?act=delete&amp;id=' . $row['fid'] . '" title="Delete"><span class="underline">Delete</span></a>';
				}
				$html .= '</div>';
				$html .= '<br class="clear" />';
				// Change alternating row
				if ($i == 1) {
					$i = 0;
				} else {
					$i = 1;
				}
				$html .= '</div>';
			}
			$html .= '</div>';
		} else {
			$html .= '<p><strong class="red">No Forms exist. <a href="?act=add" title="Add Form"><span class="underline red">Add a form here</span></a>.</strong></p>';
		}
		// Return
		echo $html;
	}
} else {
	echo 'Access Denied.';
}
?>