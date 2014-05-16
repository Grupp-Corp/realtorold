<?php
// Vars
$html = '';
// Grab Form Adinistration Object
$AdminForm = new FormAdmin();
// Check if we want a field or form options
if (!isset($_GET['fid'])) { // Form options
	$FormRow = $AdminForm->GetFormSelect($_GET['id']);
	$FormFieldRows = $AdminForm->GetFormFieldList($_GET['id']);
	// Other messages from other admin areas
	if (isset($_GET['field_added']) && $_GET['field_added'] == 1) {
		echo '<strong class="red">Form Field added.</strong><br /><br />';
	} else if (isset($_GET['field_deleted']) && $_GET['field_deleted'] == 1) {
		echo '<strong class="red">Form Field deleted.</strong><br /><br />';
	}
	// Check post method
	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		$error = 0;
		$err_mess = '';
		// Error Check
		if (!isset($_POST['FormName'])) {
			$error = 1;
			$err_mess .= '<strong class="red">Please choose a Form Name.</strong><br />';
		} elseif ($_POST['FormName'] == '') {
			$error = 1;
			$err_mess .= '<strong class="red">Please choose a Form Name.</strong><br />';
		}
		if (!isset($_POST['FormID'])) {
			$error = 1;
			$err_mess .= '<strong class="red">Please enter a Form ID.</strong><br />';
		} elseif ($_POST['FormID'] == '') {
			$error = 1;
			$err_mess .= '<strong class="red">Please enter a Form ID.</strong><br />';
		}
		if ((isset($_POST['SendMail'])) && ($_POST['SendMail'] == 1)) {
			if (!isset($_POST['Subject'])) {
				$error = 1;
				$err_mess .= '<strong class="red">Please enter the Email Subject.</strong><br />';
			} elseif ($_POST['Subject'] == '') {
				$error = 1;
				$err_mess .= '<strong class="red">Please enter the Email Subject.</strong><br />';
			}
			if (!isset($_POST['ToField'])) {
				$error = 1;
				$err_mess .= '<strong class="red">Please enter the &quot;To&quot; Email.</strong><br />';
			} elseif ($_POST['ToField'] == '') {
				$error = 1;
				$err_mess .= '<strong class="red">Please enter the &quot;To&quot; Email.</strong><br />';
			}
		}
		// Check for errors
		if ($error == 0) {
			// Attempt query to update form
			if ($AdminForm->UpdateForm($_POST['FormName'], $_POST['FormID'], $_POST['RedirectPage'], $_POST['HTML5'], $_POST['Captcha'], $_POST['EmailAddressRequired'], $_POST['SendMail'], $_POST['Subject'], $_POST['Message'], $_POST['ToField'], $_POST['ToName'], $_POST['DomainFrom'], $_POST['HTML'], $_POST['fid']) === true) {
				echo '<strong class="red">Form Edited.</strong><br /><br />' . PHP_EOL;
			} else {
				echo '<strong class="red">There was an error with the database.</strong><br /><br />' . PHP_EOL;
			}
		} else {
			echo $err_mess . '<br /><br />';
		}
	}
	// Start fields html list
	$Fields_HTML = '<table class="adminTable">' . PHP_EOL;
	// Loop through fields
	foreach($FormFieldRows as $frow) {
		// Check if set and is array?
		if ((isset($frow)) && (is_array($frow))) {
			// Loop through data
			foreach($frow as $row) {
				$Fields_HTML .= '<tr>' . PHP_EOL;
				$Fields_HTML .= '<td><a href="?act=edit&amp;id=' . $row['fid'] . '&amp;fid=' . $row['field_id'] . '">' . $row['LabelName'] . '</a> (' . ucfirst($row['Type']) . '&nbsp;Field)</td>' . PHP_EOL;
				$Fields_HTML .= '<td><a href="?act=edit&amp;id=' . $row['fid'] . '&amp;fid=' . $row['field_id'] . '">Edit</a> | <a href="?act=delete&amp;id=' . $row['fid'] . '&amp;fid=' . $row['field_id'] . '">Delete</a></td>' . PHP_EOL;
				$Fields_HTML .= '</tr>' . PHP_EOL;
			}
		}
	}
	// Close the list
	$Fields_HTML .= '</table>' . PHP_EOL;
	?>
	<?php if (!isset($_GET['do'])) { ?>
		<div class="right" id="FieldBox">
		  <?php include('dyna_menu/fieldlist.php'); ?>
		</div>
	<?php } ?>
	<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" id="EditForm" name="EditForm">
	  <div class="FormContainer">
		<div class="FormLeftColumn"><label title="Form Name"><strong>Form Name:</strong></label></div>
		<div class="FormRightColumn"><input type="text" size="30" id="FormName" name="FormName" value="<?php if (isset($_POST['FormName'])) { echo $_POST['FormName']; } else { echo $FormRow['FormName']; } ?>" /></div>
		<br class="clearfix" />
		<div class="paddingBottom10"></div>
		<div class="FormLeftColumn"><label title="Form ID"><strong>Form ID:</strong></label></div>
		<div class="FormRightColumn"><input type="text" size="35" id="FormID" name="FormID" value="<?php if (isset($_POST['FormID'])) { echo $_POST['FormID']; } else { echo $FormRow['FormID']; } ?>" /></div>
		<br class="clearfix" />
		<div class="paddingBottom10"></div>
		<div class="FormLeftColumn"><label title="Redirect Page"><strong>Redirect Page:</strong></label></div>
		<div class="FormRightColumn"><input type="text" size="35" id="RedirectPage" name="RedirectPage" value="<?php if (isset($_POST['RedirectPage'])) { echo $_POST['RedirectPage']; } else { echo $FormRow['RedirectPage']; } ?>" /></div>
		<br class="clearfix" />
		<div class="paddingBottom10"></div>
		<div class="FormLeftColumn"><label title="HTML5 Attributes"><strong>HTML5 Attributes:</strong></label></div>
		<div class="FormRightColumn">
		  <select name="HTML5" id="HTML5">
			<option value="1"<?php if (((isset($_POST['HTML5'])) && ($_POST['HTML5'] == 1)) or ($FormRow['HTML5'] == 1)) { echo' selected="selected"'; } ?>>Yes</option>
			<option value="0"<?php if (((isset($_POST['HTML5'])) && ($_POST['HTML5'] == 0)) or ($FormRow['HTML5'] == 0)) { echo' selected="selected"'; } ?>>No</option>
		  </select>
		</div>
		<br class="clearfix" />
		<div class="paddingBottom10"></div>
		<div class="FormLeftColumn"><label title="Captcha"><strong>Captcha:</strong></label></div>
		<div class="FormRightColumn">
		  <select name="Captcha" id="Captcha">
			<option value="1"<?php if (((isset($_POST['Captcha'])) && ($_POST['Captcha'] == 1)) or ($FormRow['Captcha'] == 1)) { echo' selected="selected"'; } ?>>Yes</option>
			<option value="0"<?php if (((isset($_POST['Captcha'])) && ($_POST['Captcha'] == 0)) or ($FormRow['Captcha'] == 0)) { echo' selected="selected"'; } ?>>No</option>
		  </select>
		</div>
		<br class="clearfix" />
		<div class="paddingBottom10"></div>
		<div class="FormLeftColumn"><label title="Email Address Required"><strong>Email Address Required:</strong></label></div>
		<div class="FormRightColumn">
		  <select name="EmailAddressRequired" id="EmailAddressRequired">
			<option value="1"<?php if (((isset($_POST['EmailAddressRequired'])) && ($_POST['EmailAddressRequired'] == 1)) or ($FormRow['EmailAddressRequired'] == 1)) { echo' selected="selected"'; } ?>>Yes</option>
			<option value="0"<?php if (((isset($_POST['EmailAddressRequired'])) && ($_POST['EmailAddressRequired'] == 0)) or ($FormRow['EmailAddressRequired'] == 0)) { echo' selected="selected"'; } ?>>No</option>
		  </select>
		</div>
		<br class="clearfix" />
		<div class="paddingBottom10"></div>
		<div class="FormLeftColumn"><label title="Send Email"><strong>Send Email:</strong></label></div>
		<div class="FormRightColumn">
		  <select name="SendMail" id="SendMail" onchange="ShowHideArea(this.value, 'EmailArea');">
			<option value="1"<?php if (((isset($_POST['SendMail'])) && ($_POST['SendMail'] == 1)) or ($FormRow['SendMail'] == 1)) { echo' selected="selected"'; } ?>>Yes</option>
			<option value="0"<?php if (((isset($_POST['SendMail'])) && ($_POST['SendMail'] == 0)) or ($FormRow['SendMail'] == 0)) { echo' selected="selected"'; } ?>>No</option>
		  </select>
		</div>
		<br class="clearfix" />
		<div class="paddingBottom10"></div>
		<div class="hide" id="EmailArea">
		  <div class="FormLeftColumn"><label title="Email Subject"><strong>Email Subject:</strong></label></div>
		  <div class="FormRightColumn"><input type="text" size="30" id="Subject" name="Subject" value="<?php if (isset($_POST['Subject'])) { echo $_POST['Subject']; } else { echo $FormRow['Subject']; } ?>" /></div>
		  <br class="clearfix" />
		  <div class="paddingBottom10"></div>
		  <div class="FormLeftColumn alignTop"><label title="Message"><strong>Message:</strong></label></div>
		  <div class="FormRightColumn"><textarea rows="8" cols="40" id="Message" name="Message"><?php if (isset($_POST['Message'])) { echo $_POST['Message']; } else { echo $FormRow['Message']; } ?></textarea></div>
		  <br class="clearfix" />
		  <div class="paddingBottom10"></div>
		  <br class="clearfix" />
		  <div class="paddingBottom10"></div>
		  <div class="FormLeftColumn"><label title="&quot;To&quot; Email"><strong>&quot;To&quot; Email:</strong></label></div>
		  <div class="FormRightColumn"><input type="text" size="35" id="ToField" name="ToField" value="<?php if (isset($_POST['ToField'])) { echo $_POST['ToField']; } else { echo $FormRow['ToField']; } ?>" /></div>
		  <br class="clearfix" />
		  <div class="paddingBottom10"></div>
		  <div class="FormLeftColumn"><label title="&quot;To&quot; Name"><strong>&quot;To&quot; Name:</strong></label></div>
		  <div class="FormRightColumn"><input type="text" size="35" id="ToName" name="ToName" value="<?php if (isset($_POST['ToName'])) { echo $_POST['ToName']; } else { echo $FormRow['ToName']; } ?>" /></div>
		  <br class="clearfix" />
		  <div class="paddingBottom10"></div>
		  <div class="FormLeftColumn"><label title="Domain &quot;From&quot;"><strong>Domain &quot;From&quot;:</strong></label></div>
		  <div class="FormRightColumn"><input type="text" size="35" id="DomainFrom" name="DomainFrom" value="<?php if (isset($_POST['DomainFrom'])) { echo $_POST['DomainFrom']; } else { echo $FormRow['DomainFrom']; } ?>" /></div>
		  <br class="clearfix" />
		  <div class="paddingBottom10"></div>
		  <div class="FormLeftColumn"><label title="HTML Email"><strong>HTML Email:</strong></label></div>
		  <div class="FormRightColumn">
			<select name="HTML" id="HTML">
			  <option value="1"<?php if (((isset($_POST['HTML'])) && ($_POST['HTML'] == 1)) or ($FormRow['HTML'] == 1)) { echo' selected="selected"'; } ?>>Yes</option>
			  <option value="0"<?php if (((isset($_POST['HTML'])) && ($_POST['HTML'] == 0)) or ($FormRow['HTML'] == 0)) { echo' selected="selected"'; } ?>>No</option>
			</select>
		  </div>
		  <br class="clearfix" />
		  <div class="paddingBottom10"></div>
		</div>
		<input type="hidden" id="fid" name="fid" value="<?php echo $FormRow['fid']; ?>" />
		<div class="alignLeft"><input type="submit" id="submit" name="submit" value="Edit Form" /></div>
		<script type="text/javascript">
		/* <![CDATA[ */
		<?php if (((isset($_POST['SendMail'])) && ($_POST['SendMail'] == 1)) or ($FormRow['SendMail'] == 1)) { ?>
			document.getElementById('EmailArea').className = 'show';
		<?php } else { ?>
			document.getElementById('EmailArea').className = 'hide';
		<?php } ?>
		/* ]]> */
		</script>
	  </div>
	</form>
<?php 
} else {
	
	// Get form field data
	$FormRow = $AdminForm->GetFormFieldSelect($_GET['fid']);
	$FormFieldRows = $AdminForm->GetFormFieldList($_GET['id']);
	// Check post method
	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		$error = 0;
		$err_mess = '';
		// Error Check
		if (!isset($_POST['LabelName'])) {
			$error = 1;
			$err_mess .= '<strong class="red">Please choose a Label Name.</strong><br />';
		} elseif ($_POST['LabelName'] == '') {
			$error = 1;
			$err_mess .= '<strong class="red">Please choose a Label Name.</strong><br />';
		}
		if (!isset($_POST['Type'])) {
			$error = 1;
			$err_mess .= '<strong class="red">Please select a Type.</strong><br />';
		} elseif ($_POST['Type'] == '') {
			$error = 1;
			$err_mess .= '<strong class="red">Please select a Type.</strong><br />';
		} elseif ($_POST['Type'] == 'textarea') {
			if (!isset($_POST['rows'])) {
				$error = 1;
				$err_mess .= '<strong class="red">Please enter the Text Area Rows.</strong><br />';
			} elseif ($_POST['rows'] == '') {
				$error = 1;
				$err_mess .= '<strong class="red">Please enter the Text Area Rows.</strong><br />';
			} elseif ($_POST['rows'] < 1) {
				$error = 1;
				$err_mess .= '<strong class="red">Please enter the Text Area Rows amount greater then 0.</strong><br />';
				$_POST['rows'] = 0;
			}
			if (!isset($_POST['cols'])) {
				$error = 1;
				$err_mess .= '<strong class="red">Please enter the Text Area Columns.</strong><br />';
				$_POST['cols'] = 0;
			} elseif ($_POST['cols'] == '') {
				$error = 1;
				$err_mess .= '<strong class="red">Please enter the Text Area Columns.</strong><br />';
				$_POST['cols'] = 0;
			} elseif ($_POST['cols'] < 1) {
				$error = 1;
				$err_mess .= '<strong class="red">Please enter the Text Area Columns amount greater then 0.</strong><br />';
				$_POST['cols'] = 0;
			}
		} elseif (($_POST['Type'] == 'text') or ($_POST['Type'] == 'email')) {
			if (!isset($_POST['Size'])) {
				$error = 1;
				$err_mess .= '<strong class="red">Please enter the Text Field Size.</strong><br />';
				$_POST['Size'] = 0;
			} elseif ($_POST['Size'] == '') {
				$error = 1;
				$err_mess .= '<strong class="red">Please enter the Text Field Size.</strong><br />';
				$_POST['Size'] = 0;
			} elseif ($_POST['Size'] < 1) {
				$error = 1;
				$err_mess .= '<strong class="red">Please enter the Text Field Size greater then 0.</strong><br />';
				$_POST['Size'] = 0;
			}
			if (!isset($_POST['Maxlength'])) {
				$error = 1;
				$err_mess .= '<strong class="red">Please enter the Text Field Maximum Length.</strong><br />';
				$_POST['Maxlength'] = 0;
			} elseif ($_POST['Maxlength'] == '') {
				$error = 1;
				$err_mess .= '<strong class="red">Please enter the Text Field Maximum Length.</strong><br />';
				$_POST['Maxlength'] = 0;
			} elseif ($_POST['Maxlength'] < 1) {
				$error = 1;
				$err_mess .= '<strong class="red">Please enter the Text Field Maximum Length greater then 0.</strong><br />';
				$_POST['Maxlength'] = 0;
			}
		}
		if (!isset($_POST['Required'])) {
			$error = 1;
			$err_mess .= '<strong class="red">Please select if this field is Required.</strong><br />';
		} elseif ($_POST['Required'] == '') {
			$error = 1;
			$err_mess .= '<strong class="red">Please select if this field is Required.</strong><br />';
		} elseif ($_POST['Required'] == 1) {
			if (!isset($_POST['ErrorMessage'])) {
				$error = 1;
				$err_mess .= '<strong class="red">You must enter an error message for required fields.</strong><br />';
			} elseif ($_POST['ErrorMessage'] == '') {
				$error = 1;
				$err_mess .= '<strong class="red">You must enter an error message for required fields.</strong><br />';
			}
		}
		// Check for errors
		if ($error == 0) {
			// Attempt query to update blog
			$result = $AdminForm->UpdateFormField(str_replace(' ', '_', $_POST['LabelName']), str_replace(' ', '_', $_POST['FieldID']), $_POST['Type'], $_POST['rows'], $_POST['cols'], $_POST['Value'], $_POST['Required'], $_POST['Size'], $_POST['Maxlength'], $_POST['ErrorMessage'], $_POST['ForEmail'], $_POST['Class'], $_POST['Optional'], $_POST['field_id']);
			if ($result === true) {
				header('Location: index.php?act=edit&id=' . $_GET['id'] . '&fid=' . $_GET['fid'] . '&success=1');
			} else {
				header('Location: index.php?act=edit&id=' . $_GET['id'] . '&fid=' . $_GET['fid'] . '&success=0');
			}
		} else {
			echo $err_mess . '<br /><br />';
		}
	}
	if ((isset($_GET['success'])) && ($_GET['success'] == 1)) {
		echo '<strong class="red">Form Field Edited.</strong><br /><br />' . PHP_EOL;
	} else if ((isset($_GET['success'])) && ($_GET['success'] == 0)) {
		echo '<strong class="red">There was an error with the database.</strong><br /><br />' . PHP_EOL;
	}
	// Start fields html list
	$Fields_HTML = '<ol>' . PHP_EOL;
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
	$Fields_HTML .= '</ol>' . PHP_EOL;
	?>
	<?php if (!isset($_GET['do'])) { ?>
		<div class="right" id="FieldBox">
		  <?php include('dyna_menu/fieldlist.php'); ?>
		</div>
	<?php } ?>
	<p><a href="?act=edit&amp;id=<?php echo $_GET['id']; ?>" title="Back to Form Edit"><span class="underline">Back to Form Edit</span></a></p>
	<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" id="EditFormField" name="EditFormField">
	  <input type="hidden" id="field_id" name="field_id" value="<?php echo $FormRow['field_id']; ?>" />
	  <div class="FormLeftColumn"><label title="Field Name &amp; Label"><strong>Field Name &amp; Label:</strong></label></div>
	  <div class="FormRightColumn"><input type="text" size="20" id="LabelName" name="LabelName" value="<?php if (isset($_POST['LabelName'])) { echo str_replace('_', ' ', $_POST['LabelName']); } else { echo str_replace('_', ' ', $FormRow['LabelName']); } ?>" /></div>
	  <br class="clearfix" />
	  <div class="paddingBottom10"></div>
	  <div class="FormLeftColumn"><label title="Field ID"><strong>Field ID:</strong></label></div>
	  <div class="FormRightColumn"><input type="text" size="20" id="FieldID" name="FieldID" value="<?php if (isset($_POST['FieldID'])) { echo str_replace('_', ' ', $_POST['FieldID']); } else { echo str_replace('_', ' ', $FormRow['FieldID']); } ?>" /></div>
	  <br class="clearfix" />
	  <div class="paddingBottom10"></div>
	  <div class="FormLeftColumn"><label title="Field Type"><strong>Field Type:</strong></label></div>
	  <div class="FormRightColumn">
		<select name="Type" id="Type" onchange="TypeSwitch(this.value);">
		  <option value="text"<?php if (((isset($_POST['Type'])) && ($_POST['Type'] == 'text')) or ($FormRow['Type'] == 'text')) { echo' selected="selected"'; } ?>>Text</option>
		  <option value="email"<?php if (((isset($_POST['Type'])) && ($_POST['Type'] == 'email')) or ($FormRow['Type'] == 'email')) { echo' selected="selected"'; } ?>>Email</option>
		  <option value="textarea"<?php if (((isset($_POST['Type'])) && ($_POST['Type'] == 'textarea')) or ($FormRow['Type'] == 'textarea')) { echo' selected="selected"'; } ?>>Text Area</option>
		  <option value="select"<?php if (((isset($_POST['Type'])) && ($_POST['Type'] == 'select')) or ($FormRow['Type'] == 'select')) { echo' selected="selected"'; } ?>>Pull Down Options</option>
		  <option value="checkbox"<?php if (((isset($_POST['Type'])) && ($_POST['Type'] == 'checkbox')) or ($FormRow['Type'] == 'checkbox')) { echo' selected="selected"'; } ?>>Checkboxes</option>
		  <option value="radio"<?php if (((isset($_POST['Type'])) && ($_POST['Type'] == 'radio')) or ($FormRow['Type'] == 'radio')) { echo' selected="selected"'; } ?>>Radio Buttons</option>
		  <option value="hidden"<?php if (((isset($_POST['Type'])) && ($_POST['Type'] == 'hidden')) or ($FormRow['Type'] == 'hidden')) { echo' selected="selected"'; } ?>>Hidden Field</option>
		</select>
		<span id="SelectOptionsAdditional" class="show">
		  <div class="paddingBottom10"></div>
		  <?php
		  $FormOptionsRows = $AdminForm->GetFormFieldOptionsSelect($FormRow['field_id']);
		  if ($FormOptionsRows['TotalRows'] > 0) {
			  $OptionHTML = '<label title="Field Options"><p><strong>Current Field Options</strong> (<a href="?act=edit&amp;id=' . $_GET['id'] . '&amp;fid=' . $_GET['fid'] . '&amp;addfield=1" title="Add Field Option"><span class="underline">Add</span></a> | <a href="?act=edit&amp;id=' . $_GET['id'] . '&amp;fid=' . $_GET['fid'] . '&amp;editoptions=1" title="Edit Field Options"><span class="underline">Edit</span></a>):</p></label>' . PHP_EOL;
			  $OptionHTML .= '<select name="set_options">' . PHP_EOL;
			  foreach ($FormOptionsRows['Rows'] as $OptionRow) {
				  $OptionHTML .= '<option value="' . $OptionRow['value'] . '">' . $OptionRow['name'] . '</option>' . PHP_EOL;
			  }
			  $OptionHTML .= '</select>' . PHP_EOL;
			  echo $OptionHTML;
		  } else if ($FormOptionsRows['TotalRows'] == 0) {
		     echo '<label title="Field Options"><strong>Add Field Options:</strong> <a href="?act=edit&amp;id=' . $_GET['id'] . '&amp;fid=' . $_GET['fid'] . '&amp;addfield=1" title="Add Field Option"><span class="underline">Add</span></a>'; 
	      }
		  ?>
		</span>
		<div id="SelectCheckboxesAdditional" class="show">
		  <div class="paddingBottom10"></div>
		  <?php
		  $FormOptionsRows = $AdminForm->GetFormFieldOptionsSelect($FormRow['field_id']);
		  if ($FormOptionsRows['TotalRows'] > 0) {
			  $OptionHTML = '<label title="Field Options"><p><strong>Current Field Options</strong> (<a href="?act=edit&amp;id=' . $_GET['id'] . '&amp;fid=' . $_GET['fid'] . '&amp;addfield=1" title="Add Field Option"><span class="underline">Add</span></a> | <a href="?act=edit&amp;id=' . $_GET['id'] . '&amp;fid=' . $_GET['fid'] . '&amp;editoptions=1" title="Edit Field Options"><span class="underline">Edit</span></a>):</p></label>' . PHP_EOL;
			  foreach ($FormOptionsRows['Rows'] as $OptionRow) {
				  $OptionHTML .= '<input type="checkbox" name="CheckBoxOption" id="CheckBoxOption" value="' . $OptionRow['value'] . '" disabled="disabled" />&nbsp;' . str_replace(' ', '&nbsp;', $OptionRow['name']) . '&nbsp;&nbsp;' . PHP_EOL;
			  }
			  echo $OptionHTML . PHP_EOL;
		  } else if ($FormOptionsRows['TotalRows'] == 0) {
		     echo '<label title="Field Options"><strong>Add Field Options:</strong> <a href="?act=edit&amp;id=' . $_GET['id'] . '&amp;fid=' . $_GET['fid'] . '&amp;addfield=1" title="Add Field Option"><span class="underline">Add</span></a>'; 
	      }
		  ?>
		</div>
		<div id="SelectRadioAdditional" class="show">
		  <div class="paddingBottom10"></div>
		  <?php
		  $FormOptionsRows = $AdminForm->GetFormFieldOptionsSelect($FormRow['field_id']);
		  if ($FormOptionsRows['TotalRows'] > 0) {
			  $OptionHTML = '<label title="Field Options"><p><strong>Current Field Options</strong> (<a href="?act=edit&amp;id=' . $_GET['id'] . '&amp;fid=' . $_GET['fid'] . '&amp;addfield=1" title="Add Field Option"><span class="underline">Add</span></a> | <a href="?act=edit&amp;id=' . $_GET['id'] . '&amp;fid=' . $_GET['fid'] . '&amp;editoptions=1" title="Edit Field Options"><span class="underline">Edit</span></a>):</p></label>' . PHP_EOL;
			  foreach ($FormOptionsRows['Rows'] as $OptionRow) {
				  $OptionHTML .= '<input type="radio" name="RadioOption" id="RadioOption" value="' . $OptionRow['value'] . '" disabled="disabled" />&nbsp;' . str_replace(' ', '&nbsp;', $OptionRow['name']) . '&nbsp;&nbsp;' . PHP_EOL;
			  }
			  echo $OptionHTML . PHP_EOL;
		  } else if ($FormOptionsRows['TotalRows'] == 0) {
		     echo '<label title="Field Options"><strong>Add Field Options:</strong> <a href="?act=edit&amp;id=' . $_GET['id'] . '&amp;fid=' . $_GET['fid'] . '&amp;addfield=1" title="Add Field Option"><span class="underline">Add</span></a>'; 
	      }
		  ?>
		</div>
		<br class="clearfix" />
		<div class="paddingBottom10"></div>
	  </div>
	  <div id="TextAdditional" class="show">
		<div class="FormLeftColumn"><label title="Field Size"><strong>Field Size:</strong></label></div>
		<div class="FormRightColumn"><input type="text" size="10" id="Size" name="Size" value="<?php if (isset($_POST['Size'])) { echo $_POST['Size']; } else { echo $FormRow['Size']; } ?>" /></div>
		<br class="clearfix" />
		<div class="paddingBottom10"></div>
		<div class="FormLeftColumn"><label title="Field Maximum Length"><strong>Field Maximum Length:</strong></label></div>
		<div class="FormRightColumn"><input type="text" size="10" id="Maxlength" name="Maxlength" value="<?php if (isset($_POST['Maxlength'])) { echo $_POST['Maxlength']; } else { echo $FormRow['Maxlength']; } ?>" /></div>
		<br class="clearfix" />
		<div class="paddingBottom10"></div>
	  </div>
	  <div id="TextAreaAdditional" class="show">
		<div class="FormLeftColumn"><label title="Rows"><strong>Rows:</strong></label></div>
		<div class="FormRightColumn"><input type="text" size="20" id="rows" name="rows" value="<?php if (isset($_POST['rows'])) { echo $_POST['rows']; } else { echo $FormRow['rows']; } ?>" /></div>
		<br class="clearfix" />
		<div class="paddingBottom10"></div>
		<div class="FormLeftColumn"><label title="Columns"><strong>Columns:</strong></label></div>
		<div class="FormRightColumn"><input type="text" size="20" id="cols" name="cols" value="<?php if (isset($_POST['cols'])) { echo $_POST['cols']; } else { echo $FormRow['cols']; } ?>" /></div>
		<br class="clearfix" />
		<div class="paddingBottom10"></div>
	  </div>
	  <div class="FormLeftColumn"><label title="Field Default Value"><strong>Field Default Value:</strong></label></div>
	  <div class="FormRightColumn"><input type="text" size="20" id="Value" name="Value" value="<?php if (isset($_POST['Value'])) { echo $_POST['Value']; } else { echo $FormRow['Value']; } ?>" /></div>
	  <br class="clearfix" />
	  <div class="paddingBottom10"></div>
	  <div class="FormLeftColumn"><label title="Required Field"><strong>Required Field:</strong></label></div>
	  <div class="FormRightColumn">
		<select name="Required" id="Required" onchange="ShowHideArea(this.value, 'RequiredAdditional');">
		  <option value="1"<?php if (((isset($_POST['Required'])) && ($_POST['Required'] == 1)) or ($FormRow['Required'] == 1)) { echo' selected="selected"'; } ?>>Yes</option>
		  <option value="0"<?php if (((isset($_POST['Required'])) && ($_POST['Required'] == 0)) or ($FormRow['Required'] == 0)) { echo' selected="selected"'; } ?>>No</option>
		</select>
	  </div>
	  <br class="clearfix" />
	  <div class="paddingBottom10"></div>
	  <div id="RequiredAdditional" class="show">
		<div class="FormLeftColumn"><label title="Error Message (if required)"><strong>Error Message (if required):</strong></label></div>
		<div class="FormRightColumn"><input type="text" size="35" id="ErrorMessage" name="ErrorMessage" value="<?php if (isset($_POST['ErrorMessage'])) { echo $_POST['ErrorMessage']; } else { echo $FormRow['ErrorMessage']; } ?>" /></div>
		<br class="clearfix" />
		<div class="paddingBottom10"></div>
		<div class="FormLeftColumn"><label title="Email Label Type"><strong>Email Label Type:</strong></label></div>
		<div class="FormRightColumn">
		  <select name="ForEmail" id="ForEmail">
			<option value=""<?php if (((isset($_POST['ForEmail'])) && ($_POST['ForEmail'] == '')) or ($FormRow['ForEmail'] == '')) { echo' selected="selected"'; } ?>>None</option>
			<option value="email"<?php if (((isset($_POST['ForEmail'])) && ($_POST['ForEmail'] == 'email')) or ($FormRow['ForEmail'] == 'email')) { echo' selected="selected"'; } ?>>Email</option>
			<option value="name"<?php if (((isset($_POST['ForEmail'])) && ($_POST['ForEmail'] == 'name')) or ($FormRow['ForEmail'] == 'name')) { echo' selected="selected"'; } ?>>Name</option>
		  </select>
		</div>
		<br class="clearfix" />
		<div class="paddingBottom10"></div>
	  </div>
	  <div class="FormLeftColumn"><label title="Field Class"><strong>Field Class:</strong></label></div>
	  <div class="FormRightColumn"><input type="text" size="15" id="Class" name="Class" value="<?php if (isset($_POST['Class'])) { echo $_POST['Class']; } else { echo $FormRow['Class']; } ?>" /></div>
	  <br class="clearfix" />
	  <div class="paddingBottom10"></div>
	  <div class="FormLeftColumn"><label title="Optional Field Attributes"><strong>Optional Field Attributes:</strong></label></div>
	  <div class="FormRightColumn"><input type="text" size="20" id="Optional" name="Optional" value="<?php if (isset($_POST['Optional'])) { echo $_POST['Optional']; } else { echo $FormRow['Optional']; } ?>" /></div>
	  <br class="clearfix" />
	  <div class="paddingBottom10"></div>
	  <div class="alignLeft">
		<input type="submit" id="EditField" name="EditField" value="Edit Field" />
	  </div>
	</form>
	<script type="text/javascript">
	/* <![CDATA[ */
	<?php if (((isset($_POST['Required'])) && ($_POST['Required'] == 1)) or ($FormRow['Required'] == 1)) { ?>
		document.getElementById('RequiredAdditional').className = 'show';
	<?php } else { ?>
		document.getElementById('RequiredAdditional').className = 'hide';
	<?php } ?>
	<?php if (((isset($_POST['Type'])) && ($_POST['Type'] == 'text')) or ($FormRow['Type'] == 'text')) { ?>
		document.getElementById('TextAdditional').className = 'show';
	<?php } else { ?>
		document.getElementById('TextAdditional').className = 'hide';
	<?php } ?>
	<?php if (((isset($_POST['Type'])) && ($_POST['Type'] == 'textarea')) or ($FormRow['Type'] == 'textarea')) { ?>
		document.getElementById('TextAreaAdditional').className = 'show';
	<?php } else { ?>
		document.getElementById('TextAreaAdditional').className = 'hide';
	<?php } ?>
	<?php if (((isset($_POST['Type'])) && ($_POST['Type'] == 'select')) or ($FormRow['Type'] == 'select')) { ?>
		document.getElementById('SelectOptionsAdditional').className = 'show';
	<?php } else { ?>
		document.getElementById('SelectOptionsAdditional').className = 'hide';
	<?php } ?>
	<?php if (((isset($_POST['Type'])) && ($_POST['Type'] == 'checkbox')) or ($FormRow['Type'] == 'checkbox')) { ?>
		document.getElementById('SelectCheckboxesAdditional').className = 'show';
	<?php } else { ?>
		document.getElementById('SelectCheckboxesAdditional').className = 'hide';
	<?php } ?>
	<?php if (((isset($_POST['Type'])) && ($_POST['Type'] == 'radio')) or ($FormRow['Type'] == 'radio')) { ?>
		document.getElementById('SelectRadioAdditional').className = 'show';
	<?php } else { ?>
		document.getElementById('SelectRadioAdditional').className = 'hide';
	<?php } ?>
	/* ]]> */
	</script>
<?php } ?>