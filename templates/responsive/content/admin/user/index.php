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
if ($admin_access == 1) {
	if (isset($_GET['act']) && $_GET['act'] == "edit") {
		include('edit.php');
	} else if (isset($_GET['act']) && $_GET['act'] == "permissions") {
		include('perms.php');
	} else {
		// Fetching User Data with pagination
		$FetchUserTable = new UserAdmin();
		// Config
		$limit = 25; // Record Return Limit
		$ajax = 0;
		$ajaxFunctionName = 'LoadUserPage';
		// Checking Variables and setting Defaults
		// Is an ID present?
		if (isset($_GET['id'])) {
			if (is_numeric($_GET['id'])) {
				$id = $_GET['id'];
			} else {
				$id = 0;
			}
		} else {
			$id = 0;
		}
		// Check to see if we have a page variable
		if (isset($_GET['page'])) {
			if (is_numeric($_GET['page'])) {
				$page = $_GET['page'];
			} else {
				$page = 0;
			}
		} else {
			$page = 0;
		}
		// Getting User Data Array with Pagination
		$UserList = $FetchUserTable->UserList($id, $page, $limit, $ajax, $ajaxFunctionName);
		?>
		<div class="alignCenter"><a href="../index.php" title="Administration Index">Administration Index</a></div>
		<br />
		<p class="alignCenter">To edit User's permissions view the <a href="../perms/index.php" title="Groups List"><span class="underline">Groups List</span></a></p>
		<br />
		<div id="ContentAjax">
		<div class="alignRight">Pages:&nbsp;
		<?php echo $UserList['paginator']; ?>
		</div>
		<br /><br />
		<div class="alignCenter">
		<div class="MainContainer">
		<h2>User List</h2>
		<?php
		$i = 1;
		foreach($UserList['users'] as $userData) {
			if ($i == 1) {
				echo '<div class="FirstColor">'; // Alternating row colour
			} else {
				echo '<div class="SecondColor">'; // Alternating row colour
			}
			// Left Column
			echo '<div class="LeftContentColumn';
			echo '"><a href="?act=edit&id=' . $userData['id'] . '">' . $userData['username'] . '</div>'; // Left Column
			// Right Column
			echo '<div class="RightContentColumn';
			echo '"><a href="?act=permissions&amp;id=' . $userData['id'] . '" title="Permissions">Permissions</a>';
			if ($edit_user == 1) {
				echo ' | <a href="?act=edit&id=' . $userData['id'] . '">Edit</a>';
			}
			if ($delete_user == 1) {
				echo ' | <a href="?act=delete&id=' . $userData['id'] . '">Delete</a>';
			}
			echo '</div>'; // Right COlumn
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
		<br /><br />
		<div class="alignRight">Pages:&nbsp;
		<?php echo $UserList['paginator']; ?>
		</div>
		</div>
<?php
	}
} else {
	echo 'Access Denied.';
}
?>