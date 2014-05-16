<?php
// Includes
$AdminAct = new AdminActions();
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
if ($_SERVER['REQUEST_METHOD'] == "POST") {
	if ((isset($_POST['gid'])) && (is_numeric($_POST['gid']))) {
		if ($Update = $AdminAct->DeleteGroup($_POST['gid'])) {
			CCTemplate::redirect('index.php');
		} else {
			echo '<p><strong class="red">There was a problem with your submission.</strong></p>';
		}
	}
}
$Group = $AdminAct->GetGroupInfo($id);
?>
<p class="alignCenter"><strong class="red">Are you sure you want to delete this group?</strong></p>
<form action="#top" method="post" id="DelGroup" name="DelGroup">
<div class="alignCenter">
  <div class="ContentContainer">
	<br />
	<h2>Group Information</h2>
	<div class="SecondColor">
	  <div class="LeftContentColumn"><strong>Title:</strong></div>
	  <div class="RightContentColumn"><div class="alignLeft"><?php echo $Group['title']; ?></div></div>
	  <br class="clear" />
	</div>
	<div class="FirstColor">
	  <div class="LeftContentColumn"><strong>E-mail:</strong></div>
	  <div class="RightContentColumn"><div class="alignLeft"><?php echo $Group['description']; ?></div></div>
	  <br class="clear" />
	</div>
  </div>
</div>
<br />
<div class="alignCenter">
	<input type="hidden" id="gid" name="gid" value="<?php echo $Group['id']; ?>" />
	<input type="submit" id="DeleteGroup" name="DeleteGroup" value="Delete Group" />
  </form>
</div>