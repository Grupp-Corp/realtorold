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
if ($form_generator == 1) {
	if ((!isset($_GET['id'])) && (!isset($_GET['act']))) { // List
		// Fetching User Data with pagination
		$FetchGroupTable = new AdminActions();
		// Checking Variables and setting Defaults
		$GroupInfo = $FetchGroupTable->GetAllGroups();
		?>
		<br />
		<div class="alignCenter"><?php if ($add_to_group == 1) { ?><a href="?act=add" title="Add Group">Add Group</a> | <?php } ?><a href="../index.php" title="Administration Index">Administration Index</a></div>
		<br />
		<?php if ($user_center_access == 1) { ?>
			<p class="alignCenter">To edit Groups permissions view the <a href="../user/index.php" title="Registered User's List"><span class="underline">Registered User's List</span></a></p>
		<?php } ?>
		<br />
		<div class="alignCenter">
		<div class="MainContainer">
		<h2>User List</h2>
		<?php
		$i = 1;
		foreach($GroupInfo as $groupData) {
			if ($i == 1) {
				echo '<div class="FirstColor">'; // Alternating row colour
			} else {
				echo '<div class="SecondColor">'; // Alternating row colour
			}
			// Left Column
			echo '<div class="LeftContentColumn';
			echo '"><a href="?act=edit&id=' . $groupData['id'] . '">' . $groupData['title'] . '</a></div>'; // Left Column
			// Right Column
			echo '<div class="RightContentColumn';
			echo '"><a href="?act=permissions&amp;gid=' . $groupData['id'] . '" title="Set Permissions">Permissions</a> | <a href="?act=edit&id=' . $groupData['id'] . '">Edit</a> | <a href="?act=delete&id=' . $groupData['id'] . '">Delete</a></div>'; // Right COlumn
			echo '<br class="clear" />'; // Clear floats
			// Change alternating row
			if ($i == 1) { // Check iteration for row colour
				$i = 0;
			} else {
				$i = 1;
			}
			echo '</div>';// Close row colour
		}
		?>
		</div>
		</div>
<?php
	} else if ((isset($_GET['act'])) && ($_GET['act'] == 'permissions')) { // Add Form
		require('perms.php'); 
	} else if ((isset($_GET['act'])) && ($_GET['act'] == 'add')) { // Add Form
		echo '<h2>Add Form/Fields</h2><br />' . PHP_EOL;
		require('add.php'); // Form List include
	} else if ((isset($_GET['act'])) && ($_GET['act'] == 'edit')) { // Edit Form
		// What are we adding...
		if (((isset($_GET['act'])) && (isset($_GET['addfield'])) && ($_GET['addfield'] == 1))) { // Add Form Field
			echo '<h2>Add Field Option</h2><br />' . PHP_EOL;
			require('addfieldoption.php'); // Form List include
		} else if (((isset($_GET['act'])) && (isset($_GET['editoptions'])) && ($_GET['editoptions'] == 1))) { // Edit Form Options
			echo '<h2>Edit Field Options</h2><br />' . PHP_EOL;
			require('fieldoptions.php'); // Show Field options		
		} else { // Edit form field
			echo '<h2>Edit Form/Fields</h2><br />' . PHP_EOL;
			require('edit.php'); // Form List include
		}
	} else if ((isset($_GET['act'])) && ($_GET['act'] == 'delete')) { // Delete Form
		if (isset($_GET['fid'])) { // Delete Form
			require('field_delete.php'); // Form List include
		} else {
			require('delete.php'); // Form List include
		}
	} else { // Show form information
		
	}
} else {
	echo 'Access Denied.';
}
?>