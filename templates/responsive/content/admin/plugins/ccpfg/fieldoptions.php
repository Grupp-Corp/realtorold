<?php
// Vars
$html = '';
// Set Permissions
if (!isset($_GET['foid'])) {
	// Grab Form Adinistration Object
	$AdminForm = new FormAdmin();
	$FormRow = $AdminForm->GetFormFieldOptionsSelect($_GET['fid']);
	$FormFieldRows = $AdminForm->GetFormFieldList($_GET['id']);
	if ((isset($_GET['f_option_deleted'])) && ($_GET['f_option_deleted'] == 1)) {
		echo '<strong class="red">Form Field Option Deleted.</strong><br /><br />' . PHP_EOL;
	} else if ((isset($_GET['f_option_added'])) && ($_GET['f_option_added'] == 1)) {
		echo '<strong class="red">Form Field Option Added.</strong><br /><br />' . PHP_EOL;
	}
	// HTML
	$i = 1;
	echo '<p><a href="?act=edit&amp;id=' . $_GET['id'] . '&amp;fid=' . $_GET['fid'] . '&amp;addfield=1"><span class="underline">Add Field Option</span></a>&nbsp;|&nbsp;<a href="?act=edit&amp;id=' . $_GET['id'] . '&amp;fid=' . $_GET['fid'] . '"><span class="underline">Back to field</span></a></p>' . PHP_EOL;
	echo '<table id="adminTable">' . PHP_EOL;
	echo '<tr>' . PHP_EOL;
	echo '<th class="centerCol">#</th>' . PHP_EOL;
	echo '<th class="centerCol">Name</th>' . PHP_EOL;
	echo '<th class="centerCol">Value</th>' . PHP_EOL;
	echo '<th class="centerCol">Selected</th>' . PHP_EOL;
	echo '<th class="centerCol">Admin</th>' . PHP_EOL;
	echo '</tr>' . PHP_EOL;
	if ($FormRow['TotalRows'] > 0) {
		foreach ($FormRow['Rows'] as $key=>$val) {
			$optionSelected = 'No';
			if ($val['option_selected'] == 1) {
				$optionSelected = 'Yes';
			}
			if($i&1) {
				$classTr = 'bg1';
			} else {
				$classTr = 'bg2';
			}
			echo '<tr class="' . $classTr . '">' . PHP_EOL;
			echo '<td class="centerCol">' . $i . '</td>' . PHP_EOL;
			echo '<td class="centerCol">' . $val['name'] . '</td>' . PHP_EOL;
			echo '<td class="centerCol">' . $val['value'] . '</td>' . PHP_EOL;
			echo '<td class="centerCol">' . $optionSelected . '</td>' . PHP_EOL;
			echo '<td class="centerCol"><a href="?act=edit&amp;id=' . $_GET['id'] . '&amp;fid=' . $_GET['fid'] . '&amp;editoptions=1&amp;foid=' . $val['fid'] . '&amp;do=edit"><span class="underline">Edit<span class="underline"></a>' . PHP_EOL;
			echo '&nbsp;&nbsp;<a href="?act=edit&amp;id=' . $_GET['id'] . '&amp;fid=' . $_GET['fid'] . '&amp;editoptions=1&amp;foid=' . $val['fid'] . '&amp;do=delete"><span class="underline">Delete</span></a></td>' . PHP_EOL;
			echo '</tr>' . PHP_EOL;
			$i++;
		}
	} else {
		echo '<tr>';
		echo '<td class="centerCol" colspan="5"><p><strong class="red">No Form Field Options Available.</strong></p></td>';
		echo '</tr>';
	}
	echo '</table>' . PHP_EOL;
} else {
	// Grab Form Adinistration Object
	$AdminForm = new FormAdmin();
	$FormRow = $AdminForm->GetFormSelect($_GET['id']);
	$FormOptionRow = $AdminForm->GetFormFieldOptionById($_GET['foid']);
	$FormFieldRows = $AdminForm->GetFormFieldList($_GET['id']);
	// Check post method
	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		$error = 0;
		$err_mess = '';
		// Error Check
		if (!isset($_POST['fid'])) {
			$error = 1;
			$err_mess .= '<strong class="red">Invalid ID.</strong><br />' . PHP_EOL;
		}
		if ((isset($_GET['do'])) && ($_GET['do'] == "delete")) {
			// Check for errors
			if ($error == 0) {
				// Attempt query to update blog
				if ($AdminForm->DeleteFieldOption($_POST['fid']) === true) {
					header('Location: ?act=edit&id=' . $_GET['id'] . '&fid=' . $_GET['fid'] . '&editoptions=1&f_option_deleted=1');
				} else {
					echo '<strong class="red">There was an error with the database.</strong><br /><br />' . PHP_EOL;
				}
			} else {
				echo $err_mess . '<br /><br />' . PHP_EOL;
			}
		} else if ((isset($_GET['do'])) && ($_GET['do'] == "edit")) {
			// Check for errors
			if ($error == 0) {
				// Attempt query to update blog
				if ($AdminForm->UpdateFieldOption($_POST['fid'], $_POST['Value'], $_POST['Name'], $_POST['Selected']) === true) {
					echo '<strong class="red">Form Field Option Edited.</strong><br /><br />' . PHP_EOL;
				} else {
					echo '<strong class="red">There was an error with the database.</strong><br /><br />' . PHP_EOL;
				}
			} else {
				echo $err_mess . '<br /><br />' . PHP_EOL;
			}
		}
	}
	?>
	<?php if (!isset($_GET['do'])) { ?>
		<div class="right" id="FieldBox">
		  <?php include(ADMIN_TEMPLATE_DIR . 'plugins/ccpfg/dyna_menu/fieldlist.php'); ?>
		</div>
	<?php } ?>
	<?php
	if ((isset($_GET['do'])) && ($_GET['do'] == "edit")) {
	?>
		<p><a href="?act=edit&amp;id=<?php echo $_GET['id']; ?>&amp;fid=<?php echo $_GET['fid']; ?>&editoptions=1"><span class="underline">Back to Options</span></a></p>
		<br />
		<form action="<?php echo $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']; ?>" method="post" id="EditFormOption" name="EditFormOption">
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
			<div class="FormRightColumn"><input type="text" size="35" id="Name" name="Name" value="<?php if (isset($_POST['Name'])) { echo $_POST['Name']; } else { echo $FormOptionRow['name']; } ?>" /></div>
			<br class="clearfix" />
			<div class="paddingBottom10"></div>
			<div class="FormLeftColumn"><label title="Value"><strong>Value:</strong></label></div>
			<div class="FormRightColumn"><input type="text" size="35" id="Value" name="Value" value="<?php if (isset($_POST['Value'])) { echo $_POST['Value']; } else { echo $FormOptionRow['value']; } ?>" /></div>
			<br class="clearfix" />
			<div class="paddingBottom10"></div>
			<div class="FormLeftColumn"><label title="Selected"><strong>Selected:</strong></label></div>
			<div class="FormRightColumn">
			  <select name="Selected" id="Selected">
				<option value="1"<?php if (((isset($_POST['Selected'])) && ($_POST['Selected'] == 1)) or ($FormOptionRow['option_selected'] == 1)) { echo' selected="selected"'; } ?>>Yes</option>
				<option value="0"<?php if (((isset($_POST['Selected'])) && ($_POST['Selected'] == 0)) or ($FormOptionRow['option_selected'] == 0)) { echo' selected="selected"'; } ?>>No</option>
			  </select>
			</div>
			<br class="clearfix" />
			<div class="paddingBottom10"></div>
			<input type="hidden" id="fid" name="fid" value="<?php echo $FormOptionRow['fid']; ?>" />
			<div class="alignLeft"><input type="submit" id="submit" name="submit" value="Edit Form Option" /></div>
		  </div>
		</form>
	<?php
	} elseif ((isset($_GET['do'])) && ($_GET['do'] == "delete")) {
		echo '<p><a href="?act=edit&amp;id=' . $_GET['id'] . '&amp;fid=' . $_GET['fid'] . '&editoptions=1"><span class="underline">Back to Options</span></a></p>';
		echo '<p class="red">Are you sure you want to delete the form option &quot;<strong>' . $FormOptionRow['name'] . '</strong>&quot; for the form &quot;<strong>' . $FormRow['FormID'] . '</strong>&quot;?</p>' . PHP_EOL;
		echo '<form action="' . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'] . '" method="POST" id="DeleteOption" name="DeleteOption">' . PHP_EOL;
		echo '<input type="hidden" name="fid" id="fid" value="' . $FormOptionRow['fid'] . '" />' . PHP_EOL;
		echo '<input type="submit" name="DeleteOption" id="DeleteOption" value="Confirm">' . PHP_EOL;
		echo '</form>' . PHP_EOL;
	} else {
		echo '<p><strong class="red">Invalid selection.</strong></p>' . PHP_EOL;
	}
	?>
<?php
}
?>