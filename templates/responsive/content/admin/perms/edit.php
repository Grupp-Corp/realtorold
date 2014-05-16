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
		$Update = $AdminAct->UpdateGroup($_POST['gid'], $_POST);
		if ($Update === true) {
			echo '<p><strong class="red">Group Updated.</strong></p>';
		} else {
			echo '<p><strong class="red">There was a problem with your submission.</strong></p>';
		}
	}
}
$Group = $AdminAct->GetGroupInfo($id);
?>
<form action="#top" method="post" id="ModGroup" name="ModGroup">
<div class="alignCenter"><a href="?act=permissions&amp;gid=<?php echo $id; ?>" title="Set Permissions">Set Group's Permissions</a> | <a href="index.php" title="Group List">Group List</a></div>
<div class="alignCenter">
  <div class="ContentContainer">
	<br />
	<h2>Group Information</h2>
	<div class="SecondColor">
	  <div class="LeftContentColumn"><strong>Title:</strong></div>
	  <div class="RightContentColumn"><div class="alignLeft"><input type="text" id="title" name="title" value="<?php echo $Group['title']; ?>" /> <strong class="red">*</strong></div></div>
	  <br class="clear" />
	</div>
	<div class="FirstColor">
	  <div class="LeftContentColumn"><strong>E-mail:</strong></div>
	  <div class="RightContentColumn"><div class="alignLeft"><input type="text" id="description" name="description" value="<?php echo $Group['description']; ?>" /> <strong class="red">*</strong></div></div>
	  <br class="clear" />
	</div>
  </div>
</div>
<br />
<div class="alignCenter">
	<input type="hidden" id="gid" name="gid" value="<?php echo $Group['id']; ?>" />
	<input type="submit" id="EditGroup" name="EditGroup" value="Edit Group" />
  </form>
</div>