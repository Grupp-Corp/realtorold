<?php
// Vars
$html = '';
// Grab Form Adinistration Object
$AdminForm = new FormAdmin();
// Add Field
if (!isset($_GET['addfield'])) { // Form options
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
			// Grab Form Adinistration Object
			$AdminForm = new FormAdmin();
			// Attempt query to update blog
			if ($AdminForm->AddForm($_POST['FormName'], $_POST['FormID'], $_POST['RedirectPage'], $_POST['HTML5'], $_POST['Captcha'], $_POST['EmailAddressRequired'], $_POST['SendMail'], $_POST['Subject'], $_POST['Message'], $_POST['ToField'], $_POST['ToName'], $_POST['DomainFrom'], $_POST['HTML']) === true) {
				header('Location: index.php?form_added=1');
			} else {
				echo '<strong class="red">There was an error with the database.</strong><br /><br />';
			}
		} else {
			echo $err_mess . '<br /><br />';
		}
	}
	?>
	<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" id="EditForm" name="EditForm">
	  <div class="FormContainer">
		<div class="FormLeftColumn"><label title="Form Name"><strong>Form Name:</strong></label></div>
		<div class="FormRightColumn"><input type="text" size="30" id="FormName" name="FormName" value="<?php if (isset($_POST['FormName'])) { echo $_POST['FormName']; } ?>" /></div>
		<br class="clearfix" />
		<div class="paddingBottom10"></div>
		<div class="FormLeftColumn"><label title="Form ID"><strong>Form ID:</strong></label></div>
		<div class="FormRightColumn"><input type="text" size="35" id="FormID" name="FormID" value="<?php if (isset($_POST['FormID'])) { echo $_POST['FormID']; } ?>" /></div>
		<br class="clearfix" />
		<div class="paddingBottom10"></div>
		<div class="FormLeftColumn"><label title="Redirect Page"><strong>Redirect Page:</strong></label></div>
		<div class="FormRightColumn"><input type="text" size="35" id="RedirectPage" name="RedirectPage" value="<?php if (isset($_POST['RedirectPage'])) { echo $_POST['RedirectPage']; } ?>" /></div>
		<br class="clearfix" />
		<div class="paddingBottom10"></div>
		<div class="FormLeftColumn"><label title="HTML5 Attributes"><strong>HTML5 Attributes:</strong></label></div>
		<div class="FormRightColumn">
		  <select name="HTML5" id="HTML5">
			<option value="1"<?php if ((isset($_POST['HTML5'])) && ($_POST['HTML5'] == 1)) { echo ' selected="selected"'; } ?>>Yes</option>
			<option value="0"<?php if ((isset($_POST['HTML5'])) && ($_POST['HTML5'] == 0)) { echo ' selected="selected"'; } ?>>No</option>
		  </select>
		</div>
		<br class="clearfix" />
		<div class="paddingBottom10"></div>
		<div class="FormLeftColumn"><label title="Captcha"><strong>Captcha:</strong></label></div>
		<div class="FormRightColumn">
		  <select name="Captcha" id="Captcha">
			<option value="1"<?php if ((isset($_POST['Captcha'])) && ($_POST['Captcha'] == 1)) { echo ' selected="selected"'; } ?>>Yes</option>
			<option value="0"<?php if ((isset($_POST['Captcha'])) && ($_POST['Captcha'] == 0)) { echo ' selected="selected"'; } ?>>No</option>
		  </select>
		</div>
		<br class="clearfix" />
		<div class="paddingBottom10"></div>
		<div class="FormLeftColumn"><label title="Email Address Required"><strong>Email Address Required:</strong></label></div>
		<div class="FormRightColumn">
		  <select name="EmailAddressRequired" id="EmailAddressRequired">
			<option value="1"<?php if ((isset($_POST['EmailAddressRequired'])) && ($_POST['EmailAddressRequired'] == 1)) { echo ' selected="selected"'; } ?>>Yes</option>
			<option value="0"<?php if ((isset($_POST['EmailAddressRequired'])) && ($_POST['EmailAddressRequired'] == 0)) { echo ' selected="selected"'; } ?>>No</option>
		  </select>
		</div>
		<br class="clearfix" />
		<div class="paddingBottom10"></div>
		<div class="FormLeftColumn"><label title="Send Email"><strong>Send Email:</strong></label></div>
		<div class="FormRightColumn">
		  <select name="SendMail" id="SendMail" onchange="ShowHideArea(this.value, 'EmailArea');">
			<option value="1"<?php if ((isset($_POST['SendMail'])) && ($_POST['SendMail'] == 1)) { echo ' selected="selected"'; } ?>>Yes</option>
			<option value="0"<?php if ((isset($_POST['SendMail'])) && ($_POST['SendMail'] == 0)) { echo ' selected="selected"'; } ?>>No</option>
		  </select>
		</div>
		<br class="clearfix" />
		<div class="paddingBottom10"></div>
		<div class="show" id="EmailArea">
		  <div class="FormLeftColumn"><label title="Email Subject"><strong>Email Subject:</strong></label></div>
		  <div class="FormRightColumn"><input type="text" size="30" id="Subject" name="Subject" value="<?php if (isset($_POST['Subject'])) { echo $_POST['Subject']; } ?>" /></div>
		  <br class="clearfix" />
		  <div class="paddingBottom10"></div>
		  <div class="FormLeftColumn alignTop"><label title="Message"><strong>Message:</strong></label></div>
		  <div class="FormRightColumn"><textarea rows="8" cols="55" id="Message" name="Message"><?php if (isset($_POST['Message'])) { echo $_POST['Message']; } ?></textarea></div>
		  <br class="clearfix" />
		  <div class="paddingBottom10"></div>
		
		  <br class="clearfix" />
		  <div class="paddingBottom10"></div>
		  <div class="FormLeftColumn"><label title="&quot;To&quot; Email"><strong>&quot;To&quot; Email:</strong></label></div>
		  <div class="FormRightColumn"><input type="text" size="35" id="ToField" name="ToField" value="<?php if (isset($_POST['ToField'])) { echo $_POST['ToField']; } ?>" /></div>
		  <br class="clearfix" />
		  <div class="paddingBottom10"></div>
		  <div class="FormLeftColumn"><label title="&quot;To&quot; Name"><strong>&quot;To&quot; Name:</strong></label></div>
		  <div class="FormRightColumn"><input type="text" size="35" id="ToName" name="ToName" value="<?php if (isset($_POST['ToName'])) { echo $_POST['ToName']; } ?>" /></div>
		  <br class="clearfix" />
		  <div class="paddingBottom10"></div>
		  <div class="FormLeftColumn"><label title="Domain &quot;From&quot;"><strong>Domain &quot;From&quot;:</strong></label></div>
		  <div class="FormRightColumn"><input type="text" size="35" id="DomainFrom" name="DomainFrom" value="<?php if (isset($_POST['DomainFrom'])) { echo $_POST['DomainFrom']; } ?>" /></div>
		  <br class="clearfix" />
		  <div class="paddingBottom10"></div>
		  <div class="FormLeftColumn"><label title="HTML Email"><strong>HTML Email:</strong></label></div>
		  <div class="FormRightColumn">
			<select name="HTML" id="HTML">
			  <option value="1"<?php if ((isset($_POST['HTML'])) && ($_POST['HTML'] == 1)) { echo ' selected="selected"'; } ?>>Yes</option>
			  <option value="0"<?php if ((isset($_POST['HTML'])) && ($_POST['HTML'] == 0)) { echo ' selected="selected"'; } ?>>No</option>
			</select>
		  </div>
		  <br class="clearfix" />
		  <div class="paddingBottom10"></div>
		</div>
		<div class="alignLeft"><input type="submit" id="submit" name="submit" value="Add Form" /></div>
		<script type="text/javascript">
		/* <![CDATA[ */
		<?php if (((isset($_POST['SendMail'])) && ($_POST['SendMail'] == 0)) or ($FormRow['SendMail'] == 0)) { ?>
			document.getElementById('EmailArea').className = 'hide';
		<?php } else { ?>
			document.getElementById('EmailArea').className = 'show';
		<?php } ?>
		/* ]]> */
		</script>
	  </div>
	</form>
<?php 
} else {
	$FormRow = $AdminForm->GetFormSelect($_GET['id']);
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
			//$LabelName, $FieldID, $Type, $rows, $cols, $Value, $Required, $Size, $Maxlength, $ErrorMessage, $ForEmail, $Class, $Optional, $fid
			if ($AdminForm->AddFormField($_POST['LabelName'], $_POST['FieldID'], $_POST['Type'], $_POST['rows'], $_POST['cols'], $_POST['Value'], $_POST['Required'], $_POST['Size'], $_POST['Maxlength'], $_POST['ErrorMessage'], $_POST['ForEmail'], $_POST['Class'], $_POST['Optional'], 1, $_POST['fid']) === true) {
				header('Location: index.php?act=edit&id=' . $_POST['fid'] . '&field_added=1');
			} else {
				echo '<strong class="red">There was an error with the database.</strong><br /><br />';
			}
		} else {
			echo $err_mess . '<br /><br />';
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
				$Fields_HTML .= '<li><a href="?act=edit&amp;id=' . $row['fid'] . '&amp;fid=' . $row['field_id'] . '">' . $row['LabelName'] . '</a> (' . ucfirst($row['Type']) . '&nbsp;Field)</li>';
			}
		}
	}
	// Close the list
	$Fields_HTML .= '</ol>';
	?>
	<div class="right" id="FieldBox">
	  <?php include(ADMIN_TEMPLATE_DIR . 'plugins/ccpfg/dyna_menu/fieldlist.php'); ?>
	</div>
	<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" id="AddFormField" name="AddFormField">
	  <input type="hidden" id="fid" name="fid" value="<?php if (isset($_GET['id'])) { echo $_GET['id']; } ?>" />
	  <div class="FormLeftColumn"><label title="Field Name &amp; Label"><strong>Field Name &amp; Label:</strong></label></div>
	  <div class="FormRightColumn"><input type="text" size="20" id="LabelName" name="LabelName" value="<?php if (isset($_POST['LabelName'])) { echo str_replace('_', ' ', $_POST['LabelName']); } ?>" /></div>
	  <br class="clearfix" />
	  <div class="paddingBottom10"></div>
	  <div class="FormLeftColumn"><label title="Field ID"><strong>Field ID:</strong></label></div>
	  <div class="FormRightColumn"><input type="text" size="20" id="FieldID" name="FieldID" value="<?php if (isset($_POST['FieldID'])) { echo str_replace('_', ' ', $_POST['FieldID']); } ?>" /></div>
	  <br class="clearfix" />
	  <div class="paddingBottom10"></div>
	  <div class="FormLeftColumn"><label title="Field Type"><strong>Field Type:</strong></label></div>
	  <div class="FormRightColumn">
		<select name="Type" id="Type" onchange="TypeSwitch(this.value);">
		  <option value=""<?php if ((isset($_POST['Type'])) && ($_POST['Type'] == '')) { echo' selected="selected"'; } ?>>(Select Type)</option>
		  <option value="text"<?php if ((isset($_POST['Type'])) && ($_POST['Type'] == 'text')) { echo' selected="selected"'; } ?>>Text</option>
		  <option value="email"<?php if ((isset($_POST['Type'])) && ($_POST['Type'] == 'email')) { echo' selected="selected"'; } ?>>Email</option>
		  <option value="textarea"<?php if ((isset($_POST['Type'])) && ($_POST['Type'] == 'textarea')) { echo' selected="selected"'; } ?>>Text Area</option>
		  <option value="select"<?php if ((isset($_POST['Type'])) && ($_POST['Type'] == 'select')) { echo' selected="selected"'; } ?>>Pull Down Options</option>
		  <option value="checkbox"<?php if ((isset($_POST['Type'])) && ($_POST['Type'] == 'checkbox')) { echo' selected="selected"'; } ?>>Checkboxes</option>
		  <option value="radio"<?php if ((isset($_POST['Type'])) && ($_POST['Type'] == 'radio')) { echo' selected="selected"'; } ?>>Radio Buttons</option>
		  <option value="hidden"<?php if ((isset($_POST['Type'])) && ($_POST['Type'] == 'hidden')) { echo' selected="selected"'; } ?>>Hidden Field</option>
		</select>
		<br class="clearfix" />
		<div class="paddingBottom10"></div>
	  </div>
	  <div id="TextAdditional" class="show">
		<div class="FormLeftColumn"><label title="Field Size"><strong>Field Size:</strong></label></div>
		<div class="FormRightColumn"><input type="text" size="10" id="Size" name="Size" value="<?php if (isset($_POST['Size'])) { echo $_POST['Size']; } ?>" /></div>
		<br class="clearfix" />
		<div class="paddingBottom10"></div>
		<div class="FormLeftColumn"><label title="Field Maximum Length"><strong>Field Maximum Length:</strong></label></div>
		<div class="FormRightColumn"><input type="text" size="10" id="Maxlength" name="Maxlength" value="<?php if (isset($_POST['Maxlength'])) { echo $_POST['Maxlength']; } ?>" /></div>
		<br class="clearfix" />
		<div class="paddingBottom10"></div>
	  </div>
	  <div id="TextAreaAdditional" class="show">
		<div class="FormLeftColumn"><label title="Rows"><strong>Rows:</strong></label></div>
		<div class="FormRightColumn"><input type="text" size="20" id="rows" name="rows" value="<?php if (isset($_POST['rows'])) { echo $_POST['rows']; } ?>" /></div>
		<br class="clearfix" />
		<div class="paddingBottom10"></div>
		<div class="FormLeftColumn"><label title="Columns"><strong>Columns:</strong></label></div>
		<div class="FormRightColumn"><input type="text" size="20" id="cols" name="cols" value="<?php if (isset($_POST['cols'])) { echo $_POST['cols']; } ?>" /></div>
		<br class="clearfix" />
		<div class="paddingBottom10"></div>
		<div class="FormLeftColumn"><label title="Field Default Value"><strong>Field Default Value:</strong></label></div>
		<div class="FormRightColumn"><input type="text" size="20" id="Value" name="Value" value="<?php if (isset($_POST['Value'])) { echo $_POST['Value']; } ?>" /></div>
		<br class="clearfix" />
		<div class="paddingBottom10"></div>
	  </div>
	  <div class="FormLeftColumn"><label title="Field Default Value"><strong>Field Default Value:</strong></label></div>
	  <div class="FormRightColumn"><input type="text" size="20" id="Value" name="Value" value="<?php if (isset($_POST['Value'])) { echo $_POST['Value']; } ?>" /></div>
	  <br class="clearfix" />
	  <div class="paddingBottom10"></div>
	  <div class="FormLeftColumn"><label title="Required Field"><strong>Required Field:</strong></label></div>
	  <div class="FormRightColumn">
		<select name="Required" id="Required" onchange="ShowHideArea(this.value, 'RequiredAdditional');">
		  <option value="0"<?php if ((isset($_POST['Required'])) && ($_POST['Required'] == 0)) { echo' selected="selected"'; } ?>>No</option>
		  <option value="1"<?php if ((isset($_POST['Required'])) && ($_POST['Required'] == 1)) { echo' selected="selected"'; } ?>>Yes</option>
		</select>
	  </div>
	  <br class="clearfix" />
	  <div class="paddingBottom10"></div>
	  <div id="RequiredAdditional" class="show">
		<div class="FormLeftColumn"><label title="Error Message (if required)"><strong>Error Message (if required):</strong></label></div>
		<div class="FormRightColumn"><input type="text" size="20" id="ErrorMessage" name="ErrorMessage" value="<?php if (isset($_POST['ErrorMessage'])) { echo $_POST['ErrorMessage']; } ?>" /></div>
		<br class="clearfix" />
		<div class="paddingBottom10"></div>
		<div class="FormLeftColumn"><label title="Email Label Type"><strong>Email Label Type:</strong></label></div>
		<div class="FormRightColumn">
		  <select name="ForEmail" id="ForEmail">
			<option value=""<?php if ((isset($_POST['ForEmail'])) && ($_POST['ForEmail'] == '')) { echo' selected="selected"'; } ?>>None</option>
			<option value="email"<?php if ((isset($_POST['ForEmail'])) && ($_POST['ForEmail'] == 'email')) { echo' selected="selected"'; } ?>>Email</option>
			<option value="name"<?php if ((isset($_POST['ForEmail'])) && ($_POST['ForEmail'] == 'name')) { echo' selected="selected"'; } ?>>Name</option>
		  </select>
		</div>
		<br class="clearfix" />
		<div class="paddingBottom10"></div>
	  </div>
	  <div class="FormLeftColumn"><label title="Field Class"><strong>Field Class:</strong></label></div>
	  <div class="FormRightColumn"><input type="text" size="15" id="Class" name="Class" value="<?php if (isset($_POST['Class'])) { echo $_POST['Class']; } ?>" /></div>
	  <br class="clearfix" />
	  <div class="paddingBottom10"></div>
	  <div class="FormLeftColumn"><label title="Optional Field Attributes"><strong>Optional Field Attributes:</strong></label></div>
	  <div class="FormRightColumn"><input type="text" size="20" id="Optional" name="Optional" value="<?php if (isset($_POST['Optional'])) { echo $_POST['Optional']; } ?>" /></div>
	  <br class="clearfix" />
	  <div class="paddingBottom10"></div>
	  <div class="alignLeft">
		<input type="submit" id="SubmitField" name="SubmitField" value="Add Field" />
	  </div>
	</form>
	<script type="text/javascript">
	/* <![CDATA[ */
	<?php if ((isset($_POST['Required'])) && ($_POST['Required'] == 1)) { ?>
		document.getElementById('RequiredAdditional').className = 'show';
	<?php } else { ?>
		document.getElementById('RequiredAdditional').className = 'hide';
	<?php } ?>
	<?php if ((isset($_POST['Type'])) && ($_POST['Type'] == 'text')) { ?>
		document.getElementById('TextAdditional').className = 'show';
	<?php } else if ((isset($_POST['Type'])) && ($_POST['Type'] == 'email')) { ?>
		document.getElementById('TextAdditional').className = 'show';
	<?php } else { ?>
		document.getElementById('TextAdditional').className = 'hide';
	<?php } ?>
	<?php if ((isset($_POST['Type'])) && ($_POST['Type'] == 'textarea')) { ?>
		document.getElementById('TextAreaAdditional').className = 'show';
	<?php } else { ?>
		document.getElementById('TextAreaAdditional').className = 'hide';
	<?php } ?>
	<?php if ((isset($_POST['Type'])) && ($_POST['Type'] == 'select')) { ?>
		document.getElementById('SelectOptionsAdditional').className = 'show';
	<?php } else { ?>
		document.getElementById('SelectOptionsAdditional').className = 'hide';
	<?php } ?>
	<?php if ((isset($_POST['Type'])) && ($_POST['Type'] == 'checkbox')) { ?>
		document.getElementById('SelectCheckboxesAdditional').className = 'show';
	<?php } else { ?>
		document.getElementById('SelectCheckboxesAdditional').className = 'hide';
	<?php } ?>
	<?php if ((isset($_POST['Type'])) && ($_POST['Type'] == 'radio')) { ?>
		document.getElementById('SelectRadioAdditional').className = 'show';
	<?php } else { ?>
		document.getElementById('SelectRadioAdditional').className = 'hide';
	<?php } ?>
	<?php if ((isset($_SERVER['REQUEST_METHOD'])) or ($_SERVER['REQUEST_METHOD'] != 'POST')) { ?>
		document.getElementById('RequiredAdditional').className = 'show';
	<?php } else if ((isset($_POST['Required'])) && ($_POST['Required'] == 1)) { ?>
		document.getElementById('RequiredAdditional').className = 'show';
	<?php } else { ?>
		document.getElementById('RequiredAdditional').className = 'hide';
	<?php } ?>
	/* ]]> */
	</script>
<?php } ?>