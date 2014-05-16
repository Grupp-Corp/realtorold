<?php
// Includes
// Fetching User Data with pagination
$FetchUserTable = new UserAdmin();
$AdminAct = new AdminActions();
$UserProf = new UserProfile();
// Config
$limit = 10; // Record Return Limit
$ajax = 0;
$ajaxFunctionName = 'LoadUsersPage';
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
$UserData = $FetchUserTable->UserList($id, $page, $limit, $ajax, $ajaxFunctionName);
$User = $UserData['user'];
if ($_SERVER['REQUEST_METHOD'] == "POST") {
	// Checking which form was posted...
	if ((isset($_POST['gid'])) && (is_numeric($_POST['gid']))) {
		// Add/Remove Group
		if ($_POST['mode'] == 'addtogroup') {
			if ($add_to_group == 1) {
				$AdminAct->AddUserToGroup($User['id'], $_POST['gid']);
			}
		} elseif ($_POST['mode'] == 'removefromgroup') {
			if ($remove_from_group == 1) {
				$AdminAct->RemoveUserFromGroup($User['id'], $_POST['gid']);
			}
		}
	} else if ((isset($_POST['enable'])) && ($_POST['enable'] == "Enable Account")) {
		// Check UID
		if ((isset($_POST['uid'])) && (is_numeric($_POST['uid']))) {
			$UseChecks = new UserChecks();
			$id = $_POST['uid'];
			$active = 1;
			$UseChecks->ActiveSelf($id, $active);
			echo '<p class="alignCenter"><strong class="red">User &quot;' . $User['username'] . '&quot; Activated</strong></p>';
			$UserData = $FetchUserTable->UserList($id, $page, $limit, $ajax, $ajaxFunctionName);
			$User = $UserData['user'];
		} else {
			echo '<p class="alignCenter"><strong class="red">Invalid ID</strong></p>';
		}
	} else if ((isset($_POST['disable'])) && ($_POST['disable'] == "Disable Account")) {
		// Check UID
		if ((isset($_POST['uid'])) && (is_numeric($_POST['uid']))) {
			$UseChecks = new UserChecks();
			$id = $_POST['uid'];
			$active = 0;
			$UseChecks->ActiveSelf($id, $active);
			echo '<p class="alignCenter"><strong class="red">User &quot;' . $User['username'] . '&quot; Deactivated</strong></p>';
			$UserData = $FetchUserTable->UserList($id, $page, $limit, $ajax, $ajaxFunctionName);
			$User = $UserData['user'];
		} else {
			echo '<p><strong class="red">Invalid ID</strong></p>';
		}
	} else if ((isset($_POST['send'])) && ($_POST['send'] == "Send Password")) {
		$PassRecCode = $User->PassRecoveryCode(); // Generating Password Recovery Code
		$db_conn->query("UPDATE " . $this->config['table_prefix'] . "users SET pw_recovery='" . $PassRecCode . "' WHERE email='" . $User['email'] . "'"); // Inserting code into table
		// Include for mailer
		include('classes/Mailer.php');
		// Mail Vars
		$subject = 'Canuck Coder Password Retrieval';
		$MailClass = new Mailer();
		$to = '' . $User['username'] . ' <' . $User['email'] . '>';
		$from = 'Canuck Coder (steve.scharf@canuckcoder.com)';
		$html = 1; // Set to html email (1)
		$optional = 'steve.scharf@canuckcoder.com';
		// Message
		$message = 'Hello ' . $User['username'] . ', ';
		$message .= '<p>A new password has been sent to you by the administrator of Cliqable</p>';
		$message .= '<p><a href="http://' . $_SERVER['HTTP_HOST'] . '/profile/index.php?rec_code=' . $PassRecCode . '" title="Click this link or copy and paste the one below.">Click this link or copy and paste the one below.</a></p>';
		// Do Mail...
		if ($MailClass->sendSimpleMail($to, $from, $subject, $message, $html, $optional) === true) {
			echo '<p class="alignCenter"><strong class="red">Email Sent.</strong></p>';
		} else {
			echo '<p class="alignCenter"><strong class="red">Email Failed.</strong></p>';
		}
	}
}
// Group Data Pull
$group_html = '';
$Groups = $UserProf->GetUsersGroups($User['id']);
$k = 1;
// Loop through group array
foreach ($Groups as $grouprow) {
	$group_html .= $grouprow['title'];
	if ($k < count($Groups)) {
		$group_html .= ', ';
	}
	$groupIDs[] = $grouprow['id'];
	$k++;
}
?>
<div class="alignCenter"><a href="?act=permissions&amp;id=<?php echo $id; ?>" title="Set Permissions for <?php echo $User['username']; ?>">Set Permissions for <?php echo $User['username']; ?></a> | <a href="index.php" title="User List">User List</a></div>
<div class="alignCenter">
  <div class="ContentContainer">
	<br />
	<?php if ($add_to_group == 1) { ?>
		<div>
		  <div class="LeftContentColumn"><strong>Add to Group:</strong></div>
		  <div class="RightContentColumn">
			<div class="alignLeft">
			  <form method="POST" id="addtogroup" name="addtogroup" action="#top">
				<input type="hidden" id="mode" name="mode" value="addtogroup" />
				<select name="gid" id="gid" required="required">
				  <option value="">(Select a Group)</option>
				  <?php
				  $GroupsFromDB = $AdminAct->GetAllGroups();
				  foreach($GroupsFromDB as $rows) {
					  $AlreadyInAdd = 0;
					  foreach ($groupIDs as $idsSet) {
						  if ($idsSet == $rows['id']){
							  $AlreadyInAdd = 1;
						  }
					  }
					  if ($AlreadyInAdd == 0) {
						  echo '<option value="' . $rows['id'] . '">' . $rows['title'] . '</option>';
					  }
				  }
				  ?>
				</select>
				<input type="submit" id="SubmitGroupAdd" name="SubmitGroupAdd" value="Add" />
			  </form>
			</div>
		  </div>
		  <br class="clear" />
		</div>
	<?php } ?>
	<?php if ($remove_from_group == 1) { ?>
		<div>
		  <div class="LeftContentColumn"><strong>Remove from Group:</strong></div>
		  <div class="RightContentColumn">
			<div class="alignLeft">
			  <form method="POST" id="addtogroup" name="addtogroup" action="#top">
				<input type="hidden" id="mode" name="mode" value="removefromgroup" />
				<select name="gid" id="gid" required="required">
				  <option value="">(Select a Group)</option>
				  <?php
				   $GroupsFromDB = $AdminAct->GetAllGroups();
				   foreach($GroupsFromDB as $rows) {
					  $AlreadyInRemove = 1;
					  foreach ($groupIDs as $idsSet) {
						  if ($idsSet == $rows['id']){
							  $AlreadyInRemove = 0;
						  }
					  }
					  if ($AlreadyInRemove == 0) {
						  echo '<option value="' . $rows['id'] . '">' . $rows['title'] . '</option>';
					  }
				  }
				  ?>
				</select>
				<input type="submit" id="SubmitGroupAdd" name="SubmitGroupAdd" value="Remove" />
			  </form>
			</div>
		  </div>
		</div>
	<?php } ?>
	<br class="clear" />
	<div class="alignLeft">
	  <ul>
		<li><strong>Send Password:</strong> Sends the user a randomly generated password.</li>
		<li><strong>Enable/Disable Account:</strong> Accounts user's have self verified.</li>
		<li><strong>Lock/Unlock:</strong> Accounts Administrators locked (if unlocked).</li>
	  </ul>
	</div>
	<br />
	<h2>User Information</h2>
	<div class="SecondColor">
	  <div class="LeftContentColumn"><strong>Username:</strong></div>
	  <div class="RightContentColumn"><div class="alignLeft"><?php echo $User['username']; ?></div></div>
	  <br class="clear" />
	</div>
	<div class="FirstColor">
	  <div class="LeftContentColumn"><strong>E-mail:</strong></div>
	  <div class="RightContentColumn"><div class="alignLeft"><?php echo $User['email']; ?></div></div>
	  <br class="clear" />
	</div>
	<div class="SecondColor">
	  <div class="LeftContentColumn"><strong>Group(s):</strong></div>
	  <div class="RightContentColumn"><div class="alignLeft"><?php echo $group_html; ?></div></div>
	  <br class="clear" />
	</div>
	<div class="FirstColor">
	  <div class="LeftContentColumn"><strong>Active:</strong></div>
	  <div class="RightContentColumn"><div class="alignLeft">
	  <?php 
	  if ($User['active'] == 1) {
		   echo 'Active';
	  } else {
		   echo 'Inactive';
	  }
	  ?>
	  </div></div>
	  <br class="clear" />
	</div>
	<div class="SecondColor">
	  <div class="LeftContentColumn"><strong>Locked:</strong></div>
	  <div class="RightContentColumn"><div class="alignLeft">
	  <?php 
	  if ($User['lock'] == 1) {
		   echo 'Locked';
	  } else {
		   echo 'Unlocked';
	  }
	  ?>
	  </div></div>
	  <br class="clear" />
	</div>
	<div class="FirstColor">
	  <div class="LeftContentColumn"><strong>Last Login:</strong></div>
	  <div class="RightContentColumn"><div class="alignLeft"><?php echo $User['lastlogin']; ?></div></div>
	  <br class="clear" />
	</div>
	<div class="SecondColor">
	  <div class="LeftContentColumn"><strong>Last Activity:</strong></div>
	  <div class="RightContentColumn"><div class="alignLeft"><?php echo $User['lastactivity']; ?></div></div>
	  <br class="clear" />
	</div>
  </div>
</div>
<br />
<div class="alignCenter">
  <form action="#top" method="POST" id="ModUser" name="ModUser">
	<input type="hidden" id="uid" name="uid" value="<?php echo $User['id']; ?>" />
	<?php if ($send_password == 1) { ?>
		<input type="hidden" id="uemail" name="uemail" value="<?php echo $User['email']; ?>" />
		<input type="submit" id="send" name="send" value="Send Password" />
	<?php
	}
	if ($disable_users == 1) {
		if ($User['active'] == 0) { 
	?>
			<input type="submit" id="enable" name="enable" value="Enable Account" />
		<?php } else { ?>
			<input type="submit" id="disable" name="disable" value="Disable Account" />
		<?php
		}
	}
	if ($lock_out_users == 1) {
		if ($User['lock'] == 0) {
	?>
			<input type="button" id="lock" name="lock" value="Lock Account" />
		<?php } else { ?>
			<input type="button" id="unlock" name="unlock" value="Unlock Account" />
		<?php
		}
	}
	?>
  </form>
</div>