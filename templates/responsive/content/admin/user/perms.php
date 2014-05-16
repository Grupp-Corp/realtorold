<?php
// Class Calls
$GetPerms = new Perms(); // Permissions Class
$CheckPerms = new PermsPub(); // Permissions Class
$FetchUserTable = new UserAdmin(); // User Information Class
$GetProfile = new UserProfile(); // User Profile Information Class
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
// Is an GID present?
if (isset($_GET['gid'])) {
	if (is_numeric($_GET['gid'])) {
		$gid = 0;
	} else {
		$gid = 0;
	}
} else {
	$gid = 0;
}
// Submission Processing
if ($_SERVER['REQUEST_METHOD'] == "POST") {
	if ((isset($_POST['restoreperms'])) && ($_POST['restoreperms'] == 2)) {
		if ((isset($_POST['gid'])) && (is_numeric($_POST['gid']))) {
			$QueryUpdate = $GetPerms->UpdateGroupPerms($_POST['gid'], $_POST);
			if ($QueryUpdate === true) {
				echo '<p class="alignCenter"><strong class="red">Group Permissions Updated.</strong></p>';
			} else {
				echo '<p class="alignCenter"><strong class="red">There was a problem with your submission.</strong></p>';
			}
		} else {
			echo '<p class="alignCenter"><strong class="red">There was a problem with your submission.</strong></p>';
		}
	} elseif ((isset($_POST['restoreperms'])) && ($_POST['restoreperms'] == 1)) {
		if ((isset($_POST['uid'])) && (is_numeric($_POST['uid']))) {
			$QueryUpdate = $GetPerms->RestoreUserGroupPerms($_POST['uid']);
			if ($QueryUpdate === true) {
				echo '<p class="alignCenter"><strong class="red">Group Permissions Restored.</strong></p>';
			} else {
				echo '<p class="alignCenter"><strong class="red">There was a problem with your submission.</strong></p>';
			}
		} else {
			echo '<p class="alignCenter"><strong class="red">There was a problem with your submission.</strong></p>';
		}
	} elseif ((isset($_POST['restoreperms'])) && ($_POST['restoreperms'] == 0)) {
		if ((isset($_POST['uid'])) && (is_numeric($_POST['uid']))) {
			$QueryUpdate = $GetPerms->EditUserSpecificPerms($_POST['uid'], $_POST);
			if ($QueryUpdate === true) {
				echo '<p class="alignCenter"><strong class="red">User Specific Permissions Updated.</strong></p>';
			} else {
				echo '<p class="alignCenter"><strong class="red">There was a problem with your submission.</strong></p>';
			}
		} else {
			echo '<p class="alignCenter"><strong class="red">There was a problem with your submission.</strong></p>';
		}
	}
}
// Getting User Data Array
if (($id > 0) or ($gid > 0)) {
	// ID Checker Final (GID overrides ID)
	if ($id == 0) {
		$id = $gid;
		$gid = $gid;
		$UserData = $GetProfile->GetSingleGroup($id); // Get user groups from ID
		$User = $UserData; // User Row
		$User['username'] = $UserData['title'];
		$UserGroups = $UserData; // Get user groups from ID
	} else {
		$id = $id;
		$gid = 0;
		// User info
		$UserData = $FetchUserTable->UserList($id, 0, 1, 0, 'NULL'); // Pull user data from ID
		$User = $UserData['user']; // User Row
		$UserGroups = $GetProfile->GetUsersGroups($id); // Get user groups from ID
	}
	// GID Check for User Groups
	// Permissions Data
	// Getting user perms from database
	$DataArray = $CheckPerms->GetUserPerms($id, $gid); // Data Array from Permissions Table
	// Field/Column Array
	$PermsFieldArray = array(
						 'allow_blog_comments', 
						 'add_edit_blog', 
						 'delete_blog', 
						 'add_edit_blog_cats', 
						 'delete_blog_cats', 
						 'form_generator', 
						 'edit_user', 
						 'delete_user', 
						 'add_to_group', 
						 'remove_from_group', 
						 'disable_users', 
						 'lock_out_users', 
						 'send_password', 
						 'user_center_access', 
						 'admin_access'
						 );
	// Loop through data array
	foreach ($DataArray as $PermsEntry) {
		if (isset($PermsEntry[$PermsFieldArray[0]])) {
			$IsChecked_AllowBlogComments[] = $PermsEntry[$PermsFieldArray[0]];
		}
		if (isset($PermsEntry[$PermsFieldArray[1]])) {
			$IsChecked_AddEditBlog[] = $PermsEntry[$PermsFieldArray[1]];
		}
		if (isset($PermsEntry[$PermsFieldArray[2]])) {
			$IsChecked_DeleteBlog[] = $PermsEntry[$PermsFieldArray[2]];
		}
		if (isset($PermsEntry[$PermsFieldArray[3]])) {
			$IsChecked_AddEditBlogCats[] = $PermsEntry[$PermsFieldArray[3]];
		}
		if (isset($PermsEntry[$PermsFieldArray[4]])) {
			$IsChecked_DeleteBlogCats[] = $PermsEntry[$PermsFieldArray[4]];
		}
		if (isset($PermsEntry[$PermsFieldArray[5]])) {
			$IsChecked_FormGenerator[] = $PermsEntry[$PermsFieldArray[5]];
		}
		if (isset($PermsEntry[$PermsFieldArray[6]])) {
			$IsChecked_EditUser[] = $PermsEntry[$PermsFieldArray[6]];
		}
		if (isset($PermsEntry[$PermsFieldArray[7]])) {
			$IsChecked_DeleteUser[] = $PermsEntry[$PermsFieldArray[7]];
		}
		if (isset($PermsEntry[$PermsFieldArray[8]])) {
			$IsChecked_AddtoGroup[] = $PermsEntry[$PermsFieldArray[8]];
		}
		if (isset($PermsEntry[$PermsFieldArray[9]])) {
			$IsChecked_RemovefromGroup[] = $PermsEntry[$PermsFieldArray[9]];
		}
		if (isset($PermsEntry[$PermsFieldArray[10]])) {
			$IsChecked_DisableUsers[] = $PermsEntry[$PermsFieldArray[10]];
		}
		if (isset($PermsEntry[$PermsFieldArray[11]])) {
			$IsChecked_LockOutUsers[] = $PermsEntry[$PermsFieldArray[11]];
		}
		if (isset($PermsEntry[$PermsFieldArray[12]])) {
			$IsChecked_SendPassword[] = $PermsEntry[$PermsFieldArray[12]];
		}
		if (isset($PermsEntry[$PermsFieldArray[13]])) {
			$IsChecked_UserCenterAccess[] = $PermsEntry[$PermsFieldArray[13]];
		}
		if (isset($PermsEntry[$PermsFieldArray[14]])) {
			$IsChecked_AdminAccess[] = $PermsEntry[$PermsFieldArray[14]];
		}
	}
	// Var Start
	$AllowBlogComments = '';
	$AddEditBlog = '';
	$DeleteBlog = '';
	$AddEditBlogCats = '';
	$DeleteBlogCats = '';
	$FormGenerator = '';
	$EditUser = '';
	$DeleteUser = '';
	$AddtoGroup = '';
	$RemovefromGroup = '';
	$DisableUsers = '';
	$LockOutUsers = '';
	$SendPassword = '';
	$UserCenterAccess = '';
	$AdminAccess = '';
	//Allow Blog Comments
	foreach ($IsChecked_AllowBlogComments as $PermRow) {
		if (isset($PermRow) && $PermRow == 1) {
			$AllowBlogComments = ' checked="checked" ';
		}
	}
	// Check Add/Edit Blog Perms
	foreach ($IsChecked_AddEditBlog as $PermRow) {
		if (isset($PermRow) && $PermRow == 1) {
			$AddEditBlog = ' checked="checked" ';
		}
	}
	// Check Delete Blog Perms
	foreach ($IsChecked_DeleteBlog as $PermRow) {
		if (isset($PermRow) && $PermRow == 1) {
			$DeleteBlog = ' checked="checked" ';
		}
	}
	// Check Add/Edit Blog Cats Perms
	foreach ($IsChecked_AddEditBlogCats as $PermRow) {
		if (isset($PermRow) && $PermRow == 1) {
			$AddEditBlogCats = ' checked="checked" ';
		}
	}
	// Check Delete Blog Cats Perms
	foreach ($IsChecked_DeleteBlogCats as $PermRow) {
		if (isset($PermRow) && $PermRow == 1) {
			$DeleteBlogCats = ' checked="checked" ';
		}
	}
	// Form Generator
	foreach ($IsChecked_FormGenerator as $PermRow) {
		if (isset($PermRow) && $PermRow == 1) {
			$FormGenerator = ' checked="checked" ';
		}
	}
	// Check Edit User Perms
	foreach ($IsChecked_EditUser as $PermRow) {
		if (isset($PermRow) && $PermRow == 1) {
			$EditUser = ' checked="checked" ';
		}
	}
	// Check Delete Users Perms
	foreach ($IsChecked_DeleteUser as $PermRow) {
		if (isset($PermRow) && $PermRow == 1) {
			$DeleteUser = ' checked="checked" ';
		}
	}
	// Check Add to Group Perms
	foreach ($IsChecked_AddtoGroup as $PermRow) {
		if (isset($PermRow) && $PermRow == 1) {
			$AddtoGroup = ' checked="checked" ';
		}
	}
	// Check Remove from Group Perms
	foreach ($IsChecked_RemovefromGroup as $PermRow) {
		if (isset($PermRow) && $PermRow == 1) {
			$RemovefromGroup = ' checked="checked" ';
		}
	}
	// Check Disable Users Perms
	foreach ($IsChecked_DisableUsers as $PermRow) {
		if (isset($PermRow) && $PermRow == 1) {
			$DisableUsers = ' checked="checked" ';
		}
	}
	// Check Lock Out Users Perms
	foreach ($IsChecked_LockOutUsers as $PermRow) {
		if (isset($PermRow) && $PermRow == 1) {
			$LockOutUsers = ' checked="checked" ';
		}
	}
	// Check Send Passwords Perms
	foreach ($IsChecked_SendPassword as $PermRow) {
		if (isset($PermRow) && $PermRow == 1) {
			$SendPassword = ' checked="checked" ';
		}
	}
	// User Center Access
	foreach ($IsChecked_UserCenterAccess as $PermRow) {
		if (isset($PermRow) && $PermRow == 1) {
			$UserCenterAccess = ' checked="checked" ';
		}
	}
	// Admin Access
	foreach ($IsChecked_AdminAccess as $PermRow) {
		if (isset($PermRow) && $PermRow == 1) {
			$AdminAccess = ' checked="checked" ';
		}
	}
	?>
	<p class="alignCenter"><a href="index.php?act=edit&amp;id=<?php echo $User['id']; ?>" title="Edit <?php echo $User['username']; ?>">Edit <?php echo $User['username']; ?></a> | <a href="index.php" title="User List">User List</a></p>
	<br />
	<p class="alignCenter"><strong class="red">Notes:</strong></p>
	<ul class="alignCenter">
	  <li>Adding/Editing these permissions will override all Group based permissions.</li>
	  <li>If the <?php echo $User['username']; ?> is part of many Groups, <?php echo $User['username']; ?> will inherit all permissions those Groups possess.</li>
	</ul>
	<br />
	<div class="smallContainer">
	  <h2>Editing Permissions for <?php echo $User['username']; ?></h2>
	  <div class="padding5">
		<?php if ($gid == 0) { ?>
			<div class="LCol">
		<?php 
		} else { 
			echo '<div class="LeftColumn">';
		}
		?>
		  <div class="alignCenter">
			<?php
			if ($gid > 0) { // Empty User groups for a GID entry
				$UserSpecific = -1;
			} else {
				$UserSpecific = $GetPerms->CheckIfUserSpecific($id);
				echo '<p><strong>Current Permission Set: </strong>';
			}
			if ($UserSpecific === true) {
				echo 'User Specific</p>';
			} elseif ($UserSpecific === false) {
				echo 'Group Specific</p>';
			}
			?>
			<form action="#top" method="POST" id="PermissionForm" name="PermissionForm">
			  <div class="smallContainer">
				<div class="SecondColor padding5">
				  <div class="LCol">
					<?php if ($edit_user == 1) { ?>
						<input type="checkbox" name="EditUser" id="EditUser" <?php echo $EditUser; ?>/> <label title="Edit Users"><strong>Edit Users</strong></label>
					<?php } else { ?>
						<input disabled="disabled" type="checkbox" name="EditUser" id="EditUser" <?php echo $EditUser; ?>/> <label title="Edit Users"><strong>Edit Users</strong></label>
					<?php } ?> 
				  </div>
				  <div class="LCol">
					<?php if ($delete_user == 1) { ?>
						<input type="checkbox" name="DeleteUser" id="DeleteUser" <?php echo $DeleteUser; ?>/> <label title="Delete Users"><strong>Delete Users</strong></label>
					<?php } else { ?>
						<input disabled="disabled" type="checkbox" name="DeleteUser" id="DeleteUser" <?php echo $DeleteUser; ?>/> <label title="Delete Users"><strong>Delete Users</strong></label>
					<?php } ?> 
				  </div>
				  <br class="clear" />
				</div>
				<div class="FirstColor padding5">
				  <div class="LCol">
					<?php if ($add_to_group == 1) { ?>
						<input type="checkbox" name="AddtoGroup" id="AddtoGroup" <?php echo $AddtoGroup; ?>/> <label title="Add to Group"><strong>Add to Group</strong></label>
					<?php } else { ?>
						<input disabled="disabled" type="checkbox" name="AddtoGroup" id="AddtoGroup" <?php echo $AddtoGroup; ?>/> <label title="Add to Group"><strong>Add to Group</strong></label>
					<?php } ?>
				  </div>
				  
				  <div class="LCol">
					<?php if ($remove_from_group == 1) { ?>
						<input type="checkbox" name="RemovefromGroup" id="RemovefromGroup" <?php echo $RemovefromGroup; ?>/> <label title="Remove from Group"><strong>Remove from Group</strong></label>
					<?php } else { ?>
						<input disabled="disabled" type="checkbox" name="RemovefromGroup" id="RemovefromGroup" <?php echo $RemovefromGroup; ?>/> <label title="Remove from Group"><strong>Remove from Group</strong></label>
					<?php } ?>
				  </div>
				  <br class="clear" />
				</div>
				<div class="SecondColor padding5">
				  <div class="LCol">
					<?php if ($disable_users == 1) { ?>
						<input type="checkbox" name="DisableUsers" id="DisableUsers" <?php echo $DisableUsers; ?>/> <label title="Disable Users"><strong>Disable Users</strong></label>
					<?php } else { ?>
						<input disabled="disabled" type="checkbox" name="DisableUsers" id="DisableUsers" <?php echo $DisableUsers; ?>/> <label title="Disable Users"><strong>Disable Users</strong></label>
					<?php } ?>
				  </div>
				  <br class="clear" />
				</div>
				<div class="SecondColor padding5">
				  <div class="LCol">
					<?php if ($lock_out_users == 1) { ?>
						<input type="checkbox" name="LockOutUser" id="LockOutUser" <?php echo $LockOutUsers; ?>/> <label title="Lock Out Users"><strong>Lock Out Users</strong></label>
					<?php } else { ?>
						<input disabled="disabled" type="checkbox" name="LockOutUser" id="LockOutUser" <?php echo $LockOutUsers; ?>/> <label title="Lock Out Users"><strong>Lock Out Users</strong></label>
					<?php } ?>
				  </div>
				  <div class="LCol">
					<?php if ($send_password == 1) { ?>
						<input type="checkbox" name="SendUserPass" id="SendUserPass" <?php echo $SendPassword; ?>/> <label title="Send Users Password"><strong>Send Users Password</strong></label>
					<?php } else { ?>
						<input disabled="disabled" type="checkbox" name="SendUserPass" id="SendUserPass" <?php echo $SendPassword; ?>/> <label title="Send Users Password"><strong>Send Users Password</strong></label>
					<?php } ?>
				  </div>
				  <br class="clear" />
				</div>
				<div class="SecondColor padding5">
				  <div class="LCol">
					<?php if ($user_center_access == 1) { ?>
						<input type="checkbox" name="UserCenterAccess" id="UserCenterAccess" <?php echo $UserCenterAccess; ?>/> <label title="User Center Access"><strong>User Center Access</strong></label>
					<?php } else { ?>
						<input disabled="disabled" type="checkbox" name="UserCenterAccess" id="UserCenterAccess" <?php echo $UserCenterAccess; ?>/> <label title="User Center Access"><strong>User Center Access</strong></label>
					<?php } ?>
				  </div>
				  <div class="LCol">
					<?php if ($admin_access == 1) { ?>
						<input type="checkbox" name="AdminAccess" id="AdminAccess" <?php echo $AdminAccess; ?>/> <label title="Administration Access"><strong>Administration Access</strong></label>
					<?php } else { ?>
						<input disabled="disabled" type="checkbox" name="AdminAccess" id="AdminAccess" <?php echo $AdminAccess; ?>/> <label title="Administration Access"><strong>Administration Access</strong></label>
					<?php } ?>
				  </div>
				  <br class="clear" />
				</div>
			  </div>
			  <br />
			  <div class="alignCenter">
				<?php if (($UserSpecific === true) or ($UserSpecific === false)) { ?>
					<input type="hidden" id="restoreperms" name="restoreperms" value="0" />
					<input type="hidden" id="uid" name="uid" value="<?php echo $User['id']; ?>" />
				<?php } else { ?>
					<input type="hidden" id="restoreperms" name="restoreperms" value="2" />
					<input type="hidden" id="gid" name="gid" value="<?php echo $User['id']; ?>" />
				<?php } ?>
				<label title="Edit Permissions for <?php echo $User['username']; ?>">
				  <input type="submit" id="EditPerms" name="EditPerms" value="Edit Permissions for <?php echo $User['username']; ?>" />
				</label>
			  </div>
			</form>
		  </div>
		</div>
		<?php if (($UserSpecific === true) or ($UserSpecific === false)) { ?>
			<div class="RCol">
			  <div class="alignCenter">
				<?php if ($UserSpecific === true) { ?>
					<p><strong>You may restore the Group permissions for
					<br />
					<?php echo $User['username']; ?> at anytime below.</strong>
					<br />
					Groups <strong><?php echo $User['username']; ?></strong> is part of:</p>
				<?php } elseif ($UserSpecific === false) { ?>
					<p><strong>If you give specific permissions for
					<br />
					<?php echo $User['username']; ?> the Groups permissions listed 
					<br />
					below will no longer apply.</strong>
				<?php } else { ?>
					test2
				<?php } ?>
				<?php if ($UserGroups !== false) {
					$i = 1;
					$html = '<ol>';
					foreach ($UserGroups as $GroupRow) {
						$html .= '<li><a href="../perms/index.php?act=permissions&amp;gid=' . $GroupRow['id'] . '"><u>' . $GroupRow['title'] . '</u></a></li>';
						$i++;
					}
					$html .= '</ol>';
					echo $html;
				} elseif ($gid == 0) {
					echo $User['username'] . ' is not part of any groups.';
				}
				?>
				<br />
				<?php if ($UserSpecific === true) { ?>
					<form action="#top" method="POST" id="RestorePermissionForm" name="RestorePermissionForm" class="alignCenter">
					  <input type="hidden" id="restoreperms" name="restoreperms" value="1" />
					  <input type="hidden" id="uid" name="uid" value="<?php echo $User['id']; ?>" />
					  <label title="Restore Group Permissions">
						<input type="submit" id="RestorePerms" name="RestorePerms" value="<?php echo 'Restore Group'; if ($i > 1) { echo 's'; } echo ' Permissions to ' . $User['username']; ?> " />
					  </label>
					</form>
				<?php } ?>
			  </div>
			</div>
		<?php } ?>
		<br class="clear" />
	  </div>
	</div>
	<br /><br />
<?php
// Error (No Id)
} else {
	echo '<p><strong class="red">Invalid or Unknown ID.</strong></p>';
}
?>