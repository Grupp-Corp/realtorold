<?php
$CatAct = new CategoryActions();
$BlogRow = $CatAct->GetCategory($_GET['id']);
if ($_SERVER['REQUEST_METHOD'] == "POST") {
	$error = 0;
	$err_mess = '';
	// Error Check
	if (!isset($_POST['catname'])) {
		$error = 1;
		$err_mess .= '<strong class="red">Please enter a Category Title.</strong><br />';
	} elseif ($_POST['catname'] == '') {
		$error = 1;
		$err_mess .= '<strong class="red">Please enter a Category title.</strong><br />';
	}
	if (!isset($_POST['catdescription'])) {
		$error = 1;
		$err_mess .= '<strong class="red">Please enter the Category description.</strong><br />';
	} elseif ($_POST['catdescription'] == '') {
		$error = 1;
		$err_mess .= '<strong class="red">Please enter the Category description.</strong><br />';
	}
	if ($error == 0) {
		// Attempt query to update blog
		if ($CatAct->UpdateCategory($_POST['catname'], $_POST['catdescription'], $_POST['id']) === true) {
			echo '<strong class="red">Category Edited.</strong><br /><br />';
		} else {
			echo '<strong class="red">There was an error with the database.</strong><br /><br />';
		}
	} else {
		echo $err_mess . '<br /><br />';
	}
}
?>
<form action="" method="post" id="EditCat" name="EditCat">
  <div class="FormContainer">
    <div class="FormLeftColumn"><label title="Category Title"><strong>Category Title:</strong></label></div>
    <div class="FormRightColumn"><input type="text" size="30" id="catname" name="catname" value="<?php if (isset($_POST['catname'])) { echo $_POST['catname']; } else { echo $BlogRow['name']; } ?>" /></div>
    <br class="clearfix" />
    <div class="paddingBottom10"></div>
    <div class="FormLeftColumn"><label title="Description"><strong>Description:</strong></label></div>
    <div class="FormRightColumn"><input type="text" size="35" id="catdescription" name="catdescription" value="<?php if (isset($_POST['catdescription'])) { echo $_POST['catdescription']; } else { echo $BlogRow['description']; } ?>" /></div>
    <br class="clearfix" />
    <div class="paddingBottom10"></div>
    <input type="hidden" id="id" name="id" value="<?php echo $BlogRow['id']; ?>" />
    <div class="alignCenter"><input type="submit" id="submit" name="submit" value="Edit Category" /></div>
  </div>
</form>