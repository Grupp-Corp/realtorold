<?php
// Includes
$AdminAct = new AdminActions();
if ($_SERVER['REQUEST_METHOD'] == "POST") {
	if ((isset($_POST['gid'])) && (is_numeric($_POST['gid']))) {
		$Update = $AdminAct->AddGroup($_POST);
		if ($Update['error'] === true) {
			echo '<p><strong class="red">There was a problem with your submission.</strong></p>';
		} else {
			header('Location: index.php?act=permissions&gid=' . $Update['id'] . '');
		}
	}
}
?>
<div class="alignCenter"><a href="index.php" title="Group List">Group List</a> | <a href="../index.php" title="Administration Index">Administration Index</a></div>
<form action="#top" method="post" id="AddGroup" name="Group">
<div class="alignCenter">
  <div class="ContentContainer">
	<br />
	<h2>Group Information</h2>
	<div class="SecondColor">
	  <div class="LeftContentColumn"><strong>Title:</strong></div>
	  <div class="RightContentColumn"><div class="alignLeft"><input type="text" id="title" name="title" value="" /> <strong class="red">*</strong></div></div>
	  <br class="clear" />
	</div>
	<div class="FirstColor">
	  <div class="LeftContentColumn"><strong>Description:</strong></div>
	  <div class="RightContentColumn"><div class="alignLeft"><input type="text" id="description" name="description" value="" /> <strong class="red">*</strong></div></div>
	  <br class="clear" />
	</div>
  </div>
</div>
<br />
<div class="alignCenter">
	<input type="hidden" id="gid" name="gid" value="1" />
	<input type="submit" id="InsertGroup" name="InsertGroup" value="Add Group" />
  </form>
</div>