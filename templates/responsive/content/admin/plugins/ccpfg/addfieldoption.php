<?php
// Vars
$html = '';
// Grab Form Adinistration Object
$AdminForm = new FormAdmin();
$FormRow = $AdminForm->GetFormSelect($_GET['id']);
$FieldRow = $AdminForm->GetFormFieldSelect($_GET['fid']);
$FormFieldRows = $AdminForm->GetFormFieldList($_GET['id']);
if ($_SERVER['REQUEST_METHOD'] == "POST") {
	$error = 0;
	$err_mess = '';
	// Error Check
	if (!isset($_POST['fid'])) {
		$error = 1;
		$err_mess .= '<strong class="red">Invalid ID.</strong><br />' . PHP_EOL;
	}
	// Check for errors
	if ($error == 0) {
		// Attempt query to update blog
		if ($AdminForm->AddFieldOption($_POST['fid'], $_POST['Value'], $_POST['Name'], $_POST['Selected']) === true) {
			header('Location: ?act=edit&id=' . $_GET['id'] . '&fid=' . $_POST['fid'] . '&editoptions=1&f_option_added=1');
		} else {
			echo '<strong class="red">There was an error with the database.</strong><br /><br />' . PHP_EOL;
		}
	} else {
		echo $err_mess . '<br /><br />' . PHP_EOL;
	}
}
// Start fields html list
$Fields_HTML = '<ol>';
// Loop through fields
foreach($FormFieldRows as $frow) {
	// Check if set and is array?
	if ((isset($frow)) && (is_array($frow))) {
		// Loop through data
		foreach($frow as $row) {
			$Fields_HTML .= '<li><a href="?act=edit&amp;id=' . $row['fid'] . '&amp;fid=' . $row['field_id'] . '">' . $row['LabelName'] . '</a> (' . ucfirst($row['Type']) . '&nbsp;Field)</li>' . PHP_EOL;
		}
	}
}
// Close the list
$Fields_HTML .= '</ol>';
?>
<div class="right" id="FieldBox">
  <?php include('dyna_menu/fieldlist.php'); ?>
</div>
<form action="<?php echo $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']; ?>" method="post" id="AddFormOption" name="AddFormOption">
  <div class="FormContainer">
	<div class="FormLeftColumn"><label title="Form Name"><strong>Form Name:</strong></label></div>
	<div class="FormRightColumn"><?php if (isset($FormRow['FormName'])) { echo '<a href="?act=edit&amp;id=' . $_GET['id'] . '"><span class="underline">' . $FormRow['FormName'] . '</span></a>' . PHP_EOL; } ?></div>
	<br class="clearfix" />
	<div class="paddingBottom10"></div>
	<div class="FormLeftColumn"><label title="Form ID"><strong>Form ID:</strong></label></div>
	<div class="FormRightColumn"><?php if (isset($FormRow['FormID'])) { echo $FormRow['FormID'] . PHP_EOL; } ?></div>
	<br class="clearfix" />
	<div class="paddingBottom10"></div>
	<div class="FormLeftColumn"><label title="Name"><strong>Name:</strong></label></div>
	<div class="FormRightColumn"><input type="text" size="35" id="Name" name="Name" value="<?php if (isset($_POST['Name'])) { echo $_POST['Name']; } ?>" /></div>
	<br class="clearfix" />
	<div class="paddingBottom10"></div>
	<div class="FormLeftColumn"><label title="Value"><strong>Value:</strong></label></div>
	<div class="FormRightColumn"><input type="text" size="35" id="Value" name="Value" value="<?php if (isset($_POST['Value'])) { echo $_POST['Value']; }?>" /></div>
	<br class="clearfix" />
	<div class="paddingBottom10"></div>
	<div class="FormLeftColumn"><label title="Selected"><strong>Selected:</strong></label></div>
	<div class="FormRightColumn">
	  <select name="Selected" id="Selected">
		<option value="1"<?php if ((isset($_POST['Selected'])) && ($_POST['Selected'] == 1)) { echo' selected="selected"'; } ?>>Yes</option>
		<option value="0"<?php if ((isset($_POST['Selected'])) && ($_POST['Selected'] == 0)) { echo' selected="selected"'; } ?>>No</option>
	  </select>
	</div>
	<br class="clearfix" />
	<div class="paddingBottom10"></div>
	<input type="hidden" id="fid" name="fid" value="<?php echo $FieldRow['field_id']; ?>" />
	<div class="alignLeft"><input type="submit" id="submit" name="submit" value="Add Form Option" /></div>
  </div>
</form>