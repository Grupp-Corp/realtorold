<?php
// Includes
// Get permissions
$GetCPPerms = $CheckPerms->GetUserPerms($_SESSION[SESS_PREFIX . 'id']);
// Form Admin Class
require_once('includes/admin/plugins/ccpfg/FormAdmin.php');

// Vars
$html = '';
// Set Permissions
$form_generator = 0;
// Loop through permissions
foreach($GetCPPerms as $PermRow) {
	if ($PermRow['form_generator'] == 1) {
		$form_generator = 1;
	}
}
if ($form_generator == 1) {
	// Get Blog Actions
	$FormAct = new FormAdmin();
	if (isset($_GET['id'])) {
		if (is_numeric($_GET['id'])) {
			if ($_GET['id'] > 0) {
				$id = $_GET['id'];
				$FormRow = $FormAct->GetFormSelect($id); // Get selected blog
			} else {
				$id = 0;
			}
		} else {
			$id = 0;
		}
	} else {
		$id = 0;
	}
	// Submit POST
	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		$err_mess = '';
		$error = 0;
		if (!isset($_POST['id'])) {
			$error = 1;
			$err_mess .= '<strong class="red">The ID is not entered.</strong>';
		} elseif ($_POST['id'] == 0) {
			$error = 1;
			$err_mess .= '<strong class="red">Invalid ID.</strong>';
		} elseif (!is_numeric($_POST['id'])) {
			$error = 1;
			$err_mess .= '<strong class="red">Invalid ID.</strong>';
		}
		// Error Check
		if ($error == 0) {
			// Attempt to add blog
			if ($FormAct->DeleteForm($id) === true) {
				header('Location: index.php?form_deleted=1');
			} else {
				echo '<strong class="red">There was an error with the database.</strong><br /><br />';
			}
		} else {
			echo $err_mess . '<br /><br />';
		}
	} else {
	?>
		<form action="" method="post" id="DeleteForm" name="DeleteForm">
		  <div class="alignCenter">
			<strong class="red">Are you sure you want to delete the Form &quot;<?php echo $FormRow['FormName']; ?>&quot;</strong>
		  </div>
		  <input type="hidden" id="id" name="id" value="<?php echo $FormRow['fid']; ?>" />
		  <div class="alignCenter"><input type="submit" id="submit" name="submit" value="Delete Form" /></div>
		  </div>
		</form>
	<?php
	}
} else {
	echo 'Access Denied.';
}
?>