<?php
class BuildForm extends GetFormData 
{
	// Main Vars
	protected $FormOptions = array();
	protected $FormName = 'AutoForm';
	protected $FormID = 'AutoForm';
	protected $SendMail = 0;
	protected $EmailAddressRequired = 0;
	protected $ToField = '';
	protected $ToName = '';
	protected $FromNameCheck = 0;
	protected $FromField = '';
	protected $FromName = '';
	protected $Subject = '';
	protected $Message = '';
	protected $HTMLOpt = '';
	protected $DomainFrom = '';
	protected $PostMethod = '';
	protected $FieldArray = array();
	protected $SubmitName = 'Submit Form';
	protected $HiddenFormCompare = 'FormID';
	protected $InitError = 0;
	protected $EmailFieldSet = 0;
	protected $EmailError = 0;
	protected $EmailValidator;
	protected $GoogleCaptchaOption = 0;
	// Construct Form Options
	public function __construct($FormOptions = array(), $PostMethod = 'POST', $Fields = array(), $SubmitName = 'Submit Form') {
		// Vars
		$html = '';
		// Check if this is already available
		if (!class_exists('EmailAddressValidator')) {
			include('extensions/utilities/EmailAddressValidator.php'); // Set this to an email address class of your choice
		}
		// Check for class again
		if (class_exists('EmailAddressValidator')) {
			$this->EmailValidator = new EmailAddressValidator;
		} else {
			$this->InitError = 1;
			$html .= '<div class="alignLeft"><strong class="red">[Class Error] Class cannot be found.</strong></div>';
		}
		// Shared Form Options
		// Check form options
		$this->FormOptions = $FormOptions;
		// Checking if array/empty
		if ((!is_array($this->FormOptions)) or (empty($this->FormOptions))) { // Not set
			// Error
			$this->InitError = 1;
		} else { // It's ok
			// Checking array entries we need
			if (!isset($this->FormOptions['FormName'])) { // Checking Form Name Option
				// Error
				$this->InitError = 1;
				$html .= '<div class="alignLeft"><strong class="red">No Form Name option.</strong></div>';
			} else if (!isset($this->FormOptions['FormID'])) { // Checking Form ID Option
				// Error
				$this->InitError = 1;
				$html .= '<div class="alignLeft"><strong class="red">No Form ID option (can be empty).</strong></div>';
			} else if (!isset($this->FormOptions['HTML5'])) { // Checking Form HTML5 Option
				// Error
				$this->InitError = 1;
				$html .= '<div class="alignLeft"><strong class="red">No HTML5 option (must be 1 or 0).</strong></div>';
            } else if (!isset($this->FormOptions['ExtJS'])) { // Checking Form HTML5 Option
				// Error
				$this->InitError = 1;
				$html .= '<div class="alignLeft"><strong class="red">No ExtJS option (must be 1 or 0).</strong></div>';
			} else if (!isset($this->FormOptions['Captcha'])) { // Checking Form ExtJS Option
				// Error
				$this->InitError = 1;
				$html .= '<div class="alignLeft"><strong class="red">No Captcha option (must be 1 or 0).</strong></div>';
			} else if (!is_array($this->FormOptions['SendEmail'])) { // Checking Form Email Option
				// Error
				$this->InitError = 1;
				$html .= '<div class="alignLeft"><strong class="red">No SendMail option.</strong></div>';
			} else if (!isset($this->FormOptions['RedirectPage'])) { // Checking Form Redirect Option
				// Error
				$this->InitError = 1;
				$html .= '<div class="alignLeft"><strong class="red">No Redirect page option (can be empty).</strong></div>';
			} else { // All is fine above
				// Checking SendEmail Array Send Value
				if ((isset($this->FormOptions['SendEmail']['Send'])) or (is_numeric($this->FormOptions['SendEmail']['Send']))) { // Check Send Option
					// Set send option
					$this->SendMail = $this->FormOptions['SendEmail']['Send'];
					// Check if Send option is on
					if ($this->SendMail == 1) {	// On
						// Check Email Address Required option
						if ((isset($this->FormOptions['SendEmail']['EmailAddressRequired'])) && (is_numeric($this->FormOptions['SendEmail']['EmailAddressRequired']))) { // its set
							// Set Email Address Required Field
							$this->EmailAddressRequired = $this->FormOptions['SendEmail']['EmailAddressRequired'];
						} else {
							// Error
							$this->InitError = 1;
							$html .= '<div class="alignLeft"><strong class="red">No Email Address Required option found (must be 1 or 0).</strong></div>';
						}
						// Check to name field
						if ((isset($this->FormOptions['SendEmail']['ToName'])) && ($this->FormOptions['SendEmail']['ToName'] > '')) { // its set
							$this->ToName = $this->FormOptions['SendEmail']['ToName'];
						}
						// Check to field
						if ((isset($this->FormOptions['SendEmail']['ToField'])) && ($this->FormOptions['SendEmail']['ToField'] > '')) { // its set
							// Check if class exists and we require valid emails
							if (class_exists('EmailAddressValidator')) {
								// Email address validation
								if ($this->EmailValidator->check_email_address($this->FormOptions['SendEmail']['ToField']) === true) {
									// Set to field
									$this->ToField = $this->FormOptions['SendEmail']['ToField'];
								} else {
									// Error
									$this->InitError = 1;
									$this->EmailError = 1;
									$html .= '<div class="alignLeft"><strong class="red">Email address entered for the &quot;to&quot; field is not valid.</strong></div>';
								}
							} else {
								// Set to field
								$this->ToField = $this->FormOptions['SendEmail']['ToField'];
							}
						}
						// Check Subject
						if ((isset($this->FormOptions['SendEmail']['Subject'])) && ($this->FormOptions['SendEmail']['Subject'] > '')) { // its set
							$this->Subject = $this->FormOptions['SendEmail']['Subject'];
						} else {
							$html .= '<div class="alignLeft"><strong class="red">[Dev Error] No &quot;Subject&quot; field set.</strong></div>';
						}
						// Check Post Message
						if ((isset($this->FormOptions['SendEmail']['Message'])) && ($this->FormOptions['SendEmail']['Message'] > '')) { // its set
							$this->Message = $this->FormOptions['SendEmail']['Message'];
						}
						// Check HTML Option
						if ((isset($this->FormOptions['SendEmail']['HTML'])) && (is_numeric($this->FormOptions['SendEmail']['HTML']))) { // its set
							$this->HTMLOpt = $this->FormOptions['SendEmail']['HTML'];
						}
						// Check Domain From Field (additional paramater for mail()
						if ((isset($this->FormOptions['SendEmail']['DomainFrom'])) && ($this->FormOptions['SendEmail']['DomainFrom'] > '')) { // its set
							$this->DomainFrom = $this->FormOptions['SendEmail']['DomainFrom'];
						} else {
							$this->DomainFrom = '';
						}
					}
				} else { // No option value found
					// Error
					$this->InitError = 1;
					$html .= '<div class="alignLeft"><strong class="red">No SendMail option found (must be 1 or 0).</strong></div>';
				}
			}
		}
		// Check Post Method
		$this->PostMethod = $PostMethod;
		// Check post method
		if ((!isset($this->PostMethod)) or ($this->PostMethod == '')) { // Not set
			$this->InitError = 1;
			$html .= '<div class="alignLeft"><strong class="red">Form has no Post Method.</strong></div>';
		} else { // It's ok
			// Checking if POST or GET
			if ((strtoupper($this->PostMethod) != "POST") && (strtoupper($this->PostMethod) != "GET")) { // Not POST or GET
				$this->InitError = 1;
				$html .= '<div class="alignLeft"><strong class="red">Form is not set to POST or GET method.</strong></div>';
			} else {
				// Check request type and ensure we only get that.
				if (strtoupper($this->PostMethod) == "GET") {
					$_REQUEST = $_GET;
				} else if (strtoupper($this->PostMethod) == "POST") {
					$_REQUEST = $_POST;
				}
			}
		}
		// Check Fields
		$this->FieldArray = $Fields;
		if ((!is_array($this->FieldArray)) or (empty($this->FieldArray))) {
			$this->InitError = 1;
			$html .= '<div class="alignLeft"><strong class="red">No Fields available.</strong></div>';
		}
		// Check Submit Name
		$this->SubmitName = $SubmitName;
		// Check submit name
		if ((!isset($this->SubmitName)) or ($this->SubmitName == '')) {
			$this->InitError = 1;
			$html .= '<div class="alignLeft"><strong class="red">No Submit Button Name entered.</strong></div>';
		}
		// Check Initialize Error
		if ($this->InitError == 1) {
			$htmlFinal = '<div class="alignLeft"><strong class="red">[Dev Error] There was a problem with your configuration of the form.</strong></div><br />' . $html . '<br /><br />';
			echo $htmlFinal;
		} else {
			$this->FormID = $this->FormOptions['FormID'];
			$this->FormName = $this->FormOptions['FormName'];
			$this->GoogleCaptchaOption = $this->FormOptions['Captcha'];
		}
	}
	// Build Text Fields
	protected function TextFieldBuilder($FKey, $FieldAtts, $ToolTipText = 1) {
		// Vars
		$HTMLTxtFld = '';
		$FieldError = '';
		$Required = 0;
		// Check if array
		if (is_array($FieldAtts)) {
			// Start Label
			$KeyTitle = str_replace('_', ' ', $FKey);
			$HTMLTxtFld .= "\t\t\t\t\t" . '<div class="FormLeftColumn">' . PHP_EOL;
			$HTMLTxtFld .= "\t\t\t\t\t" . '<label title="' . $KeyTitle . '">' . PHP_EOL;
			$HTMLTxtFld .= "\t\t\t\t\t" . '<strong>' . $KeyTitle . ':</strong>' . PHP_EOL;
			$HTMLTxtFld .= "\t\t\t\t\t" . '</label>' . PHP_EOL;
			$HTMLTxtFld .= "\t\t\t\t\t" . '</div>' . PHP_EOL;
			$HTMLTxtFld .= "\t\t\t\t\t" . '<div class="FormRightColumn">' . PHP_EOL;
			// Start text field
			if ((isset($FKey)) && ($FKey > '')) {
				// Start input
				if ($ToolTipText == 1) {
					//$HTMLTxtFld .= '<a class="tooltip">' . PHP_EOL;
				}
				$HTMLTxtFld .= "\t\t\t\t\t" . '<input type="text" name="' . $FKey . '"';
				// Checking field attributes
				// Check size
				if ((isset($FieldAtts['size'])) && (is_numeric($FieldAtts['size']))) {
					$HTMLTxtFld .= ' size="' . $FieldAtts['size'] . '"';
				}
				// Check max length
				if ((isset($FieldAtts['maxlength'])) && (is_numeric($FieldAtts['maxlength']))) {
					$HTMLTxtFld .= ' maxlength="' . $FieldAtts['maxlength'] . '"';
				}
				// Check class
				if ((isset($FieldAtts['class'])) && ($FieldAtts['class'] > '')) {
					$HTMLTxtFld .= ' class="' . $FieldAtts['class'] . '"';
				}
				// Check ID
				if ((isset($FieldAtts['id'])) && ($FieldAtts['id'] > '')) {
					$HTMLTxtFld .= ' id="' . $FieldAtts['id'] . '"';
				}
				// Check if HTML5 option is on
				if ($this->FormOptions['HTML5'] == 1) {
					// Check if required Field (HTML5)
					if (((isset($FieldAtts['required'])) && (is_numeric($FieldAtts['required'])) && ($FieldAtts['required'] == 1))) {
						$HTMLTxtFld .= ' required="required"';
						$Required = 1;
					}
				} else if (((isset($FieldAtts['required'])) && (is_numeric($FieldAtts['required'])) && ($FieldAtts['required'] == 1))) {
					$Required = 1;
				}
				// Check for optional attributes
				if ((isset($FieldAtts['optional'])) && ($FieldAtts['optional'] > '')) {
					$HTMLTxtFld .= ' ' . $FieldAtts['optional'] . '';
				}
				// Post value
				$HTMLTxtFld .= ' value="';
				// Check Post Value/Entered Default Value
				if (isset($_REQUEST[$FKey])) {
					$HTMLTxtFld .= $this->CleanseString($_REQUEST[$FKey]);
				} else if (isset($FieldAtts['value'])) {
					$HTMLTxtFld .= $this->CleanseString($FieldAtts['value']);
				}
				// Close text field
				$HTMLTxtFld .= '" />' . PHP_EOL;
				if ($ToolTipText == 1) {
					$HTMLTxtFld .= '<span class="classic">' . $FieldAtts['ErrorMessage'] . '</span>' . PHP_EOL;
					//$HTMLTxtFld .= '</a>' . PHP_EOL;
				}
				// Check if post submitted and if required and if not set
				if (((((($_SERVER['REQUEST_METHOD'] == $this->PostMethod) && (isset($_REQUEST[$this->HiddenFormCompare])) && ($_REQUEST[$this->HiddenFormCompare] == $this->FormID))) && ((!isset($_REQUEST[$FKey])) or ($_REQUEST[$FKey] == '')) && ($Required == 1)))) {
					// Build label
					$HTMLTxtFld .= '&nbsp;&nbsp;<label title="' . $KeyTitle . ' is a required Entry"><strong class="red">*Required Field</strong></label>' . PHP_EOL;
					// checking for Error Message
					if ((isset($FieldAtts['ErrorMessage'])) && ( $FieldAtts['ErrorMessage'] > '')) {
						$FieldError = '<div class="alignLeft"><label title="' . $FieldAtts['ErrorMessage'] . '" class="red">*&nbsp;' . $FieldAtts['ErrorMessage'] . '</label></div>';
					} else {
						// Checking our field name
						if ((isset($FKey)) && ($FKey > '')) {
							$KeyTitle = str_replace('_', ' ', $FKey); // Parse out underscores
							$FieldError = '<div class="alignLeft"><strong class="red">[DEV Error]: The field &quot;' . $KeyTitle . '&quot; is required but has no error message.</strong></div>';
						} else {
							$FieldError = '<div class="alignLeft"><strong class="red">[DEV Error]: The field is required but has no error message. Unable to retrieve the required field name.</strong></div>';
						}
					}
				}
				// Close Row
				$HTMLTxtFld .= "\t\t\t\t\t" . '</div>' . PHP_EOL;
				// Seeing is this is the name field for emailing
				if ((isset($FieldAtts['ForEmail'])) && ($FieldAtts['ForEmail'] == 'name')) {
					$this->FromNameCheck = 1;
					if (($_SERVER['REQUEST_METHOD'] == $this->PostMethod) && (isset($_REQUEST[$FKey]))) {
						$this->FromName = $_REQUEST[$FKey];
					}
				}
				// Return
				return array('HTMLReturn' => $HTMLTxtFld, 'FieldError' => $Required, 'ErrorMessage' => $FieldError);
			} else {
				return "\t\t\t\t\t" . '<div class="alignLeft"><strong class="red">[DEV Error]: There was a problem with one of the Text Fields. Unable to retrieve the required field name.</strong></div>' . PHP_EOL;
			}
		} else {
			// Do we have the field name?
			if ((isset($FKey)) && ($FKey > '')) {
				$KeyTitle = str_replace('_', ' ', $FKey); // Parse out underscores
				return "\t\t\t\t\t" . '<div class="alignLeft"><strong class="red">[DEV Error]: There was a problem with the &quot;' . $KeyTitle . '&quot; Text Field.</strong>' . PHP_EOL;
			} else {
				return "\t\t\t\t\t" . '<div class="alignLeft"><strong class="red">[DEV Error]: There was a problem with one of the Text Fields. Unable to retrieve the required field name.</strong></div>' . PHP_EOL;
			}
		}
	}
	// Cleanse Request string
	public function CleanseString($String) {
		if(get_magic_quotes_gpc()){
			$TheVal = stripslashes($String);
		} else {
			$TheVal = $String;
		}
		$TheVal = htmlentities($TheVal);
		return $TheVal;
	}
	// Build Email Fields
	protected function EmailFieldBuilder($FKey, $FieldAtts, $ToolTipText = 1) {
		// Vars
		$HTMLTxtFld = '';
		$FieldError = '';
		$Required = 0;
		// Check if array
		if (is_array($FieldAtts)) {
			// Start Label
			$KeyTitle = str_replace('_', ' ', $FKey);
			$HTMLTxtFld .= "\t\t\t\t\t" . '<div class="FormLeftColumn">' . PHP_EOL;
			$HTMLTxtFld .= "\t\t\t\t\t" . '<label title="' . $KeyTitle . '">' . PHP_EOL;
			$HTMLTxtFld .= "\t\t\t\t\t" . '<strong>' . $KeyTitle . ':</strong>' . PHP_EOL;
			$HTMLTxtFld .= "\t\t\t\t\t" . '</label>' . PHP_EOL;
			$HTMLTxtFld .= "\t\t\t\t\t" . '</div>' . PHP_EOL;
			$HTMLTxtFld .= "\t\t\t\t\t" . '<div class="FormRightColumn">' . PHP_EOL;
			// Start email text field
			if ((isset($FKey)) && ($FKey > '')) {
				// Start input
				if ($ToolTipText == 1) {
					//$HTMLTxtFld .= "\t\t\t\t\t" . '<a class="tooltip">';
				}
				$HTMLTxtFld .= "\t\t\t\t\t" . '<input type="text" name="' . $FKey . '"';
				// Checking field attributes
				// Check size
				if ((isset($FieldAtts['size'])) && (is_numeric($FieldAtts['size']))) {
					$HTMLTxtFld .= ' size="' . $FieldAtts['size'] . '"';
				}
				// Check max length
				if ((isset($FieldAtts['maxlength'])) && (is_numeric($FieldAtts['maxlength']))) {
					$HTMLTxtFld .= ' maxlength="' . $FieldAtts['maxlength'] . '"';
				}
				// Check class
				if ((isset($FieldAtts['class'])) && ($FieldAtts['class'] > '')) {
					$HTMLTxtFld .= ' class="' . $FieldAtts['class'] . '"';
				}
				// Check ID
				if ((isset($FieldAtts['id'])) && ($FieldAtts['id'] > '')) {
					$HTMLTxtFld .= ' id="' . $FieldAtts['id'] . '"';
				}
				// Check if HTML5 option is on
				if ($this->FormOptions['HTML5'] == 1) {
					// Check if required Field (HTML5)
					if (((isset($FieldAtts['required'])) && (is_numeric($FieldAtts['required'])) && ($FieldAtts['required'] == 1))) {
						$HTMLTxtFld .= ' required="required"';
						$Required = 1;
					}
				} else if (((isset($FieldAtts['required'])) && (is_numeric($FieldAtts['required'])) && ($FieldAtts['required'] == 1))) {
					$Required = 1;
				}
				// Check for optional attributes
				if ((isset($FieldAtts['optional'])) && ($FieldAtts['optional'] > '')) {
					$HTMLTxtFld .= ' ' . $FieldAtts['optional'] . '';
				}
				// Post value
				$HTMLTxtFld .= ' value="';
				// Check Post Value/Entered Default Value
				if (isset($_REQUEST[$FKey])) {
					$HTMLTxtFld .= $this->CleanseString($_REQUEST[$FKey]);
				} else if (isset($FieldAtts['value'])) {
					$HTMLTxtFld .= $this->CleanseString($FieldAtts['value']);
				}
				// Close text field
				$HTMLTxtFld .= '" />' . PHP_EOL;
				if ($ToolTipText == 1) {
					$HTMLTxtFld .= '<span class="classic">' . $FieldAtts['ErrorMessage'] . '</span>' . PHP_EOL;
					$HTMLTxtFld .= '</a>' . PHP_EOL;
				}
				// Check if post submitted and if required and if not set
				if (((((($_SERVER['REQUEST_METHOD'] == $this->PostMethod) && (isset($_REQUEST[$this->HiddenFormCompare])) && ($_REQUEST[$this->HiddenFormCompare] == $this->FormID))) && ((!isset($_REQUEST[$FKey])) or ($_REQUEST[$FKey] == '')) && ($Required == 1)))) {
					// Build label
					$HTMLTxtFld .= '&nbsp;&nbsp;<label title="' . $KeyTitle . ' is a required Entry"><strong class="red">*Required Field</strong></label>' . PHP_EOL;
					// checking for Error Message
					if ((isset($FieldAtts['ErrorMessage'])) && ( $FieldAtts['ErrorMessage'] > '')) {
						$FieldError = '<div class="alignLeft"><label title="' . $FieldAtts['ErrorMessage'] . '" class="red">*&nbsp;' . $FieldAtts['ErrorMessage'] . '</label></div>';
					} else {
						// Checking our field name
						if ((isset($FKey)) && ($FKey > '')) {
							$KeyTitle = str_replace('_', ' ', $FKey); // Parse out underscores
							$FieldError = '<div class="alignLeft"><strong class="red">[DEV Error]: The field &quot;' . $KeyTitle . '&quot; is required but has no error message.</strong></div>';
						} else {
							$FieldError = '<div class="alignLeft"><strong class="red">[DEV Error]: The field is required but has no error message. Unable to retrieve the required field name.</strong></div>';
						}
					}
				} else if (((($_SERVER['REQUEST_METHOD'] == $this->PostMethod) && (isset($_REQUEST[$this->HiddenFormCompare])) && ($_REQUEST[$this->HiddenFormCompare] == $this->FormID) && (isset($_REQUEST[$FKey]))))) {
					// Check if class exists and we require valid emails
					if (((class_exists('EmailAddressValidator')) && (isset($this->FormOptions['SendEmail']['Send'])) && ($this->FormOptions['SendEmail']['Send'] == 1))) {
						// Email address validation
						if ($this->EmailValidator->check_email_address($_REQUEST[$FKey]) === false) {
							$Required = 1;
							$HTMLTxtFld .= '&nbsp;&nbsp;<label title="' . $KeyTitle . ' is a required Entry"><strong class="red">*Required Field</strong></label>' . PHP_EOL;
							$FieldError = '<div class="alignLeft"><label title="' . $FieldAtts['ErrorMessage'] . '" class="red">*&nbsp;' . $FieldAtts['ErrorMessage'] . '</label></div>';
						} else if ($this->EmailValidator->check_email_address($_REQUEST[$FKey]) === true) {
							if ((isset($FieldAtts['ForEmail'])) && ($FieldAtts['ForEmail'] == 'email')) {
								$this->FromField = $_REQUEST[$FKey];
							}
						}
					}
				}
				// Close Row
				$HTMLTxtFld .= "\t\t\t\t\t" . '</div>' . PHP_EOL;
				// Setting global field set variable
				$this->EmailFieldSet = 1;
				// Return
				return array('HTMLReturn' => $HTMLTxtFld, 'FieldError' => $Required, 'ErrorMessage' => $FieldError);
			} else {
				return "\t\t\t\t\t" . '<div class="alignLeft"><strong class="red">[DEV Error]: There was a problem with one of the Text Fields. Unable to retrieve the required field name.</strong></div>' . PHP_EOL;
			}
		} else {
			// Do we have the field name?
			if ((isset($FKey)) && ($FKey > '')) {
				$KeyTitle = str_replace('_', ' ', $FKey); // Parse out underscores
				return "\t\t\t\t\t" . '<div class="alignLeft"><strong class="red">[DEV Error]: There was a problem with the &quot;' . $KeyTitle . '&quot; Text Field.</strong>' . PHP_EOL;
			} else {
				return "\t\t\t\t\t" . '<div class="alignLeft"><strong class="red">[DEV Error]: There was a problem with one of the Text Fields. Unable to retrieve the required field name.</strong></div>' . PHP_EOL;
			}
		}
	}
	// Build Textarea Fields
	protected function TextAreaFieldBuilder($FKey, $FieldAtts, $ToolTipText = 1) {
		// Vars
		$HTMLTxtFld = '';
		$FieldError = '';
		$Required = 0;
		// Check if array
		if (is_array($FieldAtts)) {
			// Start Label
			$KeyTitle = str_replace('_', ' ', $FKey);
			$HTMLTxtFld .= "\t\t\t\t\t" . '<div>' . PHP_EOL;
			$HTMLTxtFld .= "\t\t\t\t\t" . '<label title="' . $KeyTitle . '">' . PHP_EOL;
			$HTMLTxtFld .= "\t\t\t\t\t" . '<strong>' . $KeyTitle . ':</strong>' . PHP_EOL;
			$HTMLTxtFld .= "\t\t\t\t\t" . '</label>' . PHP_EOL;
			$HTMLTxtFld .= "\t\t\t\t\t" . '</div>' . PHP_EOL;
			$HTMLTxtFld .= "\t\t\t\t\t" . '<div>' . PHP_EOL;
			// Start text field
			if ((isset($FKey)) && ($FKey > '')) {
				// Start input
				if ($ToolTipText == 1) {
					//$HTMLTxtFld .= "\t\t\t\t\t" . '<a class="tooltip">';
				}
				$HTMLTxtFld .= "\t\t\t\t\t" . '<textarea name="' . $FKey . '"';
				// Checking field attributes
				// Check cols
				if ((isset($FieldAtts['cols'])) && (is_numeric($FieldAtts['cols']))) {
					$HTMLTxtFld .= ' cols="' . $FieldAtts['cols'] . '"';
				}
				// Check rows
				if ((isset($FieldAtts['rows'])) && (is_numeric($FieldAtts['rows']))) {
					$HTMLTxtFld .= ' rows="' . $FieldAtts['rows'] . '"';
				}
				// Check class
				if ((isset($FieldAtts['class'])) && ($FieldAtts['class'] > '')) {
					$HTMLTxtFld .= ' class="' . $FieldAtts['class'] . '"';
				}
				// Check ID
				if ((isset($FieldAtts['id'])) && ($FieldAtts['id'] > '')) {
					$HTMLTxtFld .= ' id="' . $FieldAtts['id'] . '"';
				}
				// Check if HTML5 option is on
				if ($this->FormOptions['HTML5'] == 1) {
					// Check if required Field (HTML5)
					if (((isset($FieldAtts['required'])) && (is_numeric($FieldAtts['required'])) && ($FieldAtts['required'] == 1))) {
						$HTMLTxtFld .= ' required="required"';
						$Required = 1;
					}
				} else if (((isset($FieldAtts['required'])) && (is_numeric($FieldAtts['required'])) && ($FieldAtts['required'] == 1))) {
					$Required = 1;
				}
				// Check for optional attributes
				if ((isset($FieldAtts['optional'])) && ($FieldAtts['optional'] > '')) {
					$HTMLTxtFld .= ' ' . $FieldAtts['optional'] . '';
				}
				// Post value
				$HTMLTxtFld .= '>';
				// Check Post Value/Entered Default Value
				if (isset($_REQUEST[$FKey])) {
					$HTMLTxtFld .= $this->CleanseString($_REQUEST[$FKey]);
				} else if (isset($FieldAtts['value'])) {
					$HTMLTxtFld .= $this->CleanseString($FieldAtts['value']);
				}
				// Close text field
				$HTMLTxtFld .= '</textarea>' . PHP_EOL;
				if ($ToolTipText == 1) {
					$HTMLTxtFld .= '<span class="classic">' . $FieldAtts['ErrorMessage'] . '</span>' . PHP_EOL;
					//$HTMLTxtFld .= '</a>' . PHP_EOL;
				}
				// Check if post submitted and if required and if not set
				if (((((($_SERVER['REQUEST_METHOD'] == $this->PostMethod) && (isset($_REQUEST[$this->HiddenFormCompare])) && ($_REQUEST[$this->HiddenFormCompare] == $this->FormID))) && ((!isset($_REQUEST[$FKey])) or ($_REQUEST[$FKey] == '')) && ($Required == 1)))) {
					// Build label
					$HTMLTxtFld .= '&nbsp;&nbsp;<label title="' . $KeyTitle . ' is a required Entry"><strong class="red">*Required Field</strong></label>' . PHP_EOL;
					// checking for Error Message
					if ((isset($FieldAtts['ErrorMessage'])) && ( $FieldAtts['ErrorMessage'] > '')) {
						$FieldError = '<div class="alignLeft"><label title="' . $FieldAtts['ErrorMessage'] . '" class="red">*&nbsp;' . $FieldAtts['ErrorMessage'] . '</label></div>';
					} else {
						// Checking our field name
						if ((isset($FKey)) && ($FKey > '')) {
							$KeyTitle = str_replace('_', ' ', $FKey); // Parse out underscores
							$FieldError = '<div class="alignLeft"><strong class="red">[DEV Error]: The field &quot;' . $KeyTitle . '&quot; is required but has no error message.</strong></div>';
						} else {
							$FieldError = '<div class="alignLeft"><strong class="red">[DEV Error]: The field is required but has no error message. Unable to retrieve the required field name.</strong></div>';
						}
					}
				}
				// Close Row
				$HTMLTxtFld .= "\t\t\t\t\t" . '</div>' . PHP_EOL;
				// Return
				return array('HTMLReturn' => $HTMLTxtFld, 'FieldError' => $Required, 'ErrorMessage' => $FieldError);
			} else {
				return "\t\t\t\t\t" . '<div class="alignLeft"><strong class="red">[DEV Error]: There was a problem with one of the Text Fields. Unable to retrieve the required field name.</strong></div>' . PHP_EOL;
			}
		} else {
			// Do we have the field name?
			if ((isset($FKey)) && ($FKey > '')) {
				$KeyTitle = str_replace('_', ' ', $FKey); // Parse out underscores
				return "\t\t\t\t\t" . '<div class="alignLeft"><strong class="red">[DEV Error]: There was a problem with the &quot;' . $KeyTitle . '&quot; Text Field.</strong>' . PHP_EOL;
			} else {
				return "\t\t\t\t\t" . '<div class="alignLeft"><strong class="red">[DEV Error]: There was a problem with one of the Text Fields. Unable to retrieve the required field name.</strong></div>' . PHP_EOL;
			}
		}
	}
	// Build Hidden Fields
	protected function HiddenFieldBuilder($FKey, $FieldAtts) {
		// Vars
		$HTMLTxtFld = '';
		// Check if array
		if (is_array($FieldAtts)) {
			// Start text field
			if ((isset($FKey)) && ($FKey > '')) {
				// Start input
				$HTMLTxtFld .= "\t\t\t\t\t" . '<input type="hidden" name="' . $FKey . '"';
				// Checking field attributes
				// Check ID
				if ((isset($FieldAtts['id'])) && ($FieldAtts['id'] > '')) {
					$HTMLTxtFld .= ' id="' . $FieldAtts['id'] . '"';
				}
				// Post value
				$HTMLTxtFld .= ' value="';
				// Check Post Value/Entered Default Value
				if (isset($_REQUEST[$FKey])) {
					$HTMLTxtFld .= $this->CleanseString($_REQUEST[$FKey]);
				} else if (isset($FieldAtts['value'])) {
					$HTMLTxtFld .= $this->CleanseString($FieldAtts['value']);
				}
				// Close text field
				$HTMLTxtFld .= '" />' . PHP_EOL;
				// Close Row
				$HTMLTxtFld .= "\t\t\t\t\t" . '' . PHP_EOL;
				// Return
				return array('HTMLReturn' => $HTMLTxtFld);
			} else {
				return "\t\t\t\t\t" . '<div class="alignLeft"><strong class="red">[DEV Error]: There was a problem with one of the Hidden Fields. Unable to retrieve the required field name.</strong></div>' . PHP_EOL;
			}
		} else {
			// Do we have the field name?
			if ((isset($FKey)) && ($FKey > '')) {
				$KeyTitle = str_replace('_', ' ', $FKey); // Parse out underscores
				return "\t\t\t\t\t" . '<div class="alignLeft"><strong class="red">[DEV Error]: There was a problem with the &quot;' . $KeyTitle . '&quot; Hidden Field.</strong>' . PHP_EOL;
			} else {
				return "\t\t\t\t\t" . '<div class="alignLeft"><strong class="red">[DEV Error]: There was a problem with one of the Hidden Fields. Unable to retrieve the required field name.</strong></div>' . PHP_EOL;
			}
		}
	}
	// Build Checkbox Fields
	protected function CheckBoxBuilder($FKey, $FieldAtts) {
		// Vars
		$HTMLTxtFld = '';
		$FieldError = '';
		$Required = 0;
		// Check if array
		if (is_array($FieldAtts)) {
			// Start Label
			$KeyTitle = str_replace('_', ' ', $FKey);
			$HTMLTxtFld .= "\t\t\t\t\t" . '<div class="FormLeftColumn">' . PHP_EOL;
			$HTMLTxtFld .= "\t\t\t\t\t" . '<label title="' . $KeyTitle . '">' . PHP_EOL;
			$HTMLTxtFld .= "\t\t\t\t\t" . '<strong>' . $KeyTitle . ':</strong>' . PHP_EOL;
			$HTMLTxtFld .= "\t\t\t\t\t" . '</label>' . PHP_EOL;
			$HTMLTxtFld .= "\t\t\t\t\t" . '</div>' . PHP_EOL;
			$HTMLTxtFld .= "\t\t\t\t\t" . '<div class="FormRightColumn">' . PHP_EOL;
			// Start text field
			if ((isset($FKey)) && ($FKey > '')) {
				// Check for optional attributes
				if (is_array($FieldAtts['boxes'])) {
					// Loop through check boxes
					foreach($FieldAtts['boxes'] as $CBKey => $CBVals) {
						// Start input
						$HTMLTxtFld .= "\t\t\t\t\t" . '' . $CBKey . '&nbsp;<input type="checkbox" name="' . $FKey . '[]" id="' . $FKey . '[]"';
						// Checking field attributes
						// Check class
						if ((isset($FieldAtts['class'])) && ($FieldAtts['class'] > '')) {
							$HTMLTxtFld .= ' class="' . $FieldAtts['class'] . '"';
						}
						// Check if HTML5 option is on
						if ($this->FormOptions['HTML5'] == 1) {
							// Check if required Field (HTML5)
							if (((isset($FieldAtts['required'])) && (is_numeric($FieldAtts['required'])) && ($FieldAtts['required'] == 1))) {
								$Required = 1;
							}
						} else if (((isset($FieldAtts['required'])) && (is_numeric($FieldAtts['required'])) && ($FieldAtts['required'] == 1))) {
							$Required = 1;
						}
						// Check for optional attributes
						if ((isset($FieldAtts['optional'])) && ($FieldAtts['optional'] > '')) {
							$HTMLTxtFld .= ' ' . $FieldAtts['optional'] . '';
						}
						// Check Post Value/Entered Default Value
						if ((isset($_REQUEST[$FKey])) && (is_array($_REQUEST[$FKey])))  {
							foreach ($_REQUEST[$FKey] as $postkey => $postval) {
								if ($postval == $CBKey) {
									$HTMLTxtFld .= ' checked="checked"';
								}
							}
						} else if ((isset($CBVals)) && ($CBVals == 1)) {
							$HTMLTxtFld .= ' checked="checked"';
						}
						// Post value
						$HTMLTxtFld .= ' value="';
						// Close text field
						$HTMLTxtFld .= $this->CleanseString($CBKey);
						$HTMLTxtFld .= '" />&nbsp;&nbsp;&nbsp;' . PHP_EOL;
					}
					// Check if post submitted and if required and if not set
					if (((((($_SERVER['REQUEST_METHOD'] == $this->PostMethod) && (isset($_REQUEST[$this->HiddenFormCompare])) && ($_REQUEST[$this->HiddenFormCompare] == $this->FormID))) && ((!isset($_REQUEST[$FKey])) or ($_REQUEST[$FKey] == '')) && ($Required == 1)))) {
						// Build label
						$HTMLTxtFld .= '&nbsp;&nbsp;<label title="' . $KeyTitle . ' is a required Entry"><strong class="red">*Required Field</strong></label>' . PHP_EOL;
						// checking for Error Message
						if ((isset($FieldAtts['ErrorMessage'])) && ( $FieldAtts['ErrorMessage'] > '')) {
							$FieldError = '<div class="alignLeft"><label title="' . $FieldAtts['ErrorMessage'] . '" class="red">*&nbsp;' . $FieldAtts['ErrorMessage'] . '</label></div>';
						} else {
							// Checking our field name
							if ((isset($FKey)) && ($FKey > '')) {
								$KeyTitle = str_replace('_', ' ', $FKey); // Parse out underscores
								$FieldError = '<div class="alignLeft"><strong class="red">[DEV Error]: The field &quot;' . $KeyTitle . '&quot; is required but has no error message.</strong></div>';
							} else {
								$FieldError = '<div class="alignLeft"><strong class="red">[DEV Error]: The field is required but has no error message. Unable to retrieve the required field name.</strong></div>';
							}
						}
					}
					// Close Row
					$HTMLTxtFld .= "\t\t\t\t\t" . '</div>' . PHP_EOL;
					// Return
					return array('HTMLReturn' => $HTMLTxtFld, 'FieldError' => $Required, 'ErrorMessage' => $FieldError);
				} else {
					// Checking our field name
					if ((isset($FKey)) && ($FKey > '')) {
						$KeyTitle = str_replace('_', ' ', $FKey); // Parse out underscores
						return '<div class="alignLeft"><strong class="red">[DEV Error]: [DEV Error]: There was a problem with the &quot;' . $KeyTitle . '&quot; Checkbox Field.</strong></div>';
					} else {
						return "\t\t\t\t\t" . '<div class="alignLeft"><strong class="red">[DEV Error]: There was a problem with one of the Checkbox Fields. Unable to retrieve the required field name.</strong></div>' . PHP_EOL;
					}
				}
			} else {
				return "\t\t\t\t\t" . '<div class="alignLeft"><strong class="red">[DEV Error]: There was a problem with one of the Checkbox Fields. Unable to retrieve the required field name.</strong></div>' . PHP_EOL;
			}
		} else {
			// Do we have the field name?
			if ((isset($FKey)) && ($FKey > '')) {
				$KeyTitle = str_replace('_', ' ', $FKey); // Parse out underscores
				return "\t\t\t\t\t" . '<div class="alignLeft"><strong class="red">[DEV Error]: There was a problem with the &quot;' . $KeyTitle . '&quot; Text Field.</strong>' . PHP_EOL;
			} else {
				return "\t\t\t\t\t" . '<div class="alignLeft"><strong class="red">[DEV Error]: There was a problem with one of the Text Fields. Unable to retrieve the required field name.</strong></div>' . PHP_EOL;
			}
		}
	}
	// Radio Fields
	protected function RadioButtonBuilder($FKey, $FieldAtts) {
		// Vars
		$HTMLTxtFld = '';
		$FieldError = '';
		$Required = 0;
		// Check if array
		if (is_array($FieldAtts)) {
			// Start Label
			$KeyTitle = str_replace('_', ' ', $FKey);
			$HTMLTxtFld .= "\t\t\t\t\t" . '<div class="FormLeftColumn">' . PHP_EOL;
			$HTMLTxtFld .= "\t\t\t\t\t" . '<label title="' . $KeyTitle . '">' . PHP_EOL;
			$HTMLTxtFld .= "\t\t\t\t\t" . '<strong>' . $KeyTitle . ':</strong>' . PHP_EOL;
			$HTMLTxtFld .= "\t\t\t\t\t" . '</label>' . PHP_EOL;
			$HTMLTxtFld .= "\t\t\t\t\t" . '</div>' . PHP_EOL;
			$HTMLTxtFld .= "\t\t\t\t\t" . '<div class="FormRightColumn">' . PHP_EOL;
			// Start text field
			if ((isset($FKey)) && ($FKey > '')) {
				// Check for optional attributes
				if (is_array($FieldAtts['radio'])) {
					// Loop through radios
					foreach($FieldAtts['radio'] as $CBKey => $CBVals) {
						// Start input
						$HTMLTxtFld .= "\t\t\t\t\t" . '' . $CBKey . '&nbsp;<input type="radio" name="' . $FKey . '" id="' . $FKey . '"';
						// Checking field attributes
						// Check class
						if ((isset($FieldAtts['class'])) && ($FieldAtts['class'] > '')) {
							$HTMLTxtFld .= ' class="' . $FieldAtts['class'] . '"';
						}
						// Check if HTML5 option is on
						if ($this->FormOptions['HTML5'] == 1) {
							// Check if required Field (HTML5)
							if (((isset($FieldAtts['required'])) && (is_numeric($FieldAtts['required'])) && ($FieldAtts['required'] == 1))) {
								$HTMLTxtFld .= ' required="required"';
								$Required = 1;
							}
						} else if (((isset($FieldAtts['required'])) && (is_numeric($FieldAtts['required'])) && ($FieldAtts['required'] == 1))) {
							$Required = 1;
						}
						// Check for optional attributes
						if ((isset($FieldAtts['optional'])) && ($FieldAtts['optional'] > '')) {
							$HTMLTxtFld .= ' ' . $FieldAtts['optional'] . '';
						}
						// Check Post Value/Entered Default Value
						if ((isset($_REQUEST[$FKey])) && ($_REQUEST[$FKey] == stripslashes(htmlentities($CBKey)))) {
							$HTMLTxtFld .= ' checked="checked"';
						} else if ((isset($CBVals)) && ($CBVals == 1)) {
							$HTMLTxtFld .= ' checked="checked"';
						}
						// Post value
						$HTMLTxtFld .= ' value="';
						// Close text field
						$HTMLTxtFld .= $this->CleanseString($CBKey);
						$HTMLTxtFld .= '" />&nbsp;&nbsp;&nbsp;' . PHP_EOL;
					}
					// Check if post submitted and if required and if not set
					if (((((($_SERVER['REQUEST_METHOD'] == $this->PostMethod) && (isset($_REQUEST[$this->HiddenFormCompare])) && ($_REQUEST[$this->HiddenFormCompare] == $this->FormID))) && ((!isset($_REQUEST[$FKey])) or ($_REQUEST[$FKey] == '')) && ($Required == 1)))) {
						// Build label
						$HTMLTxtFld .= '&nbsp;&nbsp;<label title="' . $KeyTitle . ' is a required Entry"><strong class="red">*Required Field</strong></label>' . PHP_EOL;
						// checking for Error Message
						if ((isset($FieldAtts['ErrorMessage'])) && ( $FieldAtts['ErrorMessage'] > '')) {
							$FieldError = '<div class="alignLeft"><label title="' . $FieldAtts['ErrorMessage'] . '" class="red">*&nbsp;' . $FieldAtts['ErrorMessage'] . '</label></div>';
						} else {
							// Checking our field name
							if ((isset($FKey)) && ($FKey > '')) {
								$KeyTitle = str_replace('_', ' ', $FKey); // Parse out underscores
								$FieldError = '<div class="alignLeft"><strong class="red">[DEV Error]: The field &quot;' . $KeyTitle . '&quot; is required but has no error message.</strong></div>';
							} else {
								$FieldError = '<div class="alignLeft"><strong class="red">[DEV Error]: The field is required but has no error message. Unable to retrieve the required field name.</strong></div>';
							}
						}
					}
					// Close Row
					$HTMLTxtFld .= "\t\t\t\t\t" . '</div>' . PHP_EOL;
					// Return
					return array('HTMLReturn' => $HTMLTxtFld, 'FieldError' => $Required, 'ErrorMessage' => $FieldError);
				} else {
					// Checking our field name
					if ((isset($FKey)) && ($FKey > '')) {
						$KeyTitle = str_replace('_', ' ', $FKey); // Parse out underscores
						return '<div class="alignLeft"><strong class="red">[DEV Error]: [DEV Error]: There was a problem with the &quot;' . $KeyTitle . '&quot; Checkbox Field.</strong></div>';
					} else {
						return "\t\t\t\t\t" . '<div class="alignLeft"><strong class="red">[DEV Error]: There was a problem with one of the Checkbox Fields. Unable to retrieve the required field name.</strong></div>' . PHP_EOL;
					}
				}
			} else {
				return "\t\t\t\t\t" . '<div class="alignLeft"><strong class="red">[DEV Error]: There was a problem with one of the Checkbox Fields. Unable to retrieve the required field name.</strong></div>' . PHP_EOL;
			}
		} else {
			// Do we have the field name?
			if ((isset($FKey)) && ($FKey > '')) {
				$KeyTitle = str_replace('_', ' ', $FKey); // Parse out underscores
				return "\t\t\t\t\t" . '<div class="alignLeft"><strong class="red">[DEV Error]: There was a problem with the &quot;' . $KeyTitle . '&quot; Text Field.</strong>' . PHP_EOL;
			} else {
				return "\t\t\t\t\t" . '<div class="alignLeft"><strong class="red">[DEV Error]: There was a problem with one of the Text Fields. Unable to retrieve the required field name.</strong></div>' . PHP_EOL;
			}
		}
	}
	// Radio Fields
	protected function SelectOptionsBuilder($FKey, $FieldAtts, $ToolTipText = 1) {
		// Vars
		$HTMLOptionsFld = '';
		$FieldError = '';
		$Required = 0;
		$Class = '';
		$Optional = '';
		$IDSelect = '';
		$HTMLTxtFld_Pre = '';
		$HTMLOptionsFinal = '';
		// Check if array
		if (is_array($FieldAtts)) {
			// Start Select field
			if ((isset($FKey)) && ($FKey > '')) {
				// Check for optional attributes
				if (is_array($FieldAtts['options'])) {
					// Loop through select options
					foreach($FieldAtts['options'] as $CBKey => $CBVals) {
						// Start input
						$HTMLOptionsFld .= "\t\t\t\t\t" . '<option';
						// Checking field attributes
						// Check class
						if ((isset($FieldAtts['class'])) && ($FieldAtts['class'] > '')) {
							$Class = ' class="' . $FieldAtts['class'] . '"';
						}
						// Check ID
						if ((isset($FieldAtts['id'])) && ($FieldAtts['id'] > '')) {
							$IDSelect = ' id="' . $FieldAtts['id'] . '"';
						}
						// Check if HTML5 option is on
						if ($this->FormOptions['HTML5'] == 1) {
							// Check if required Field (HTML5)
							if (((isset($FieldAtts['required'])) && (is_numeric($FieldAtts['required'])) && ($FieldAtts['required'] == 1))) {
								$Required = 1;
							}
						} else if (((isset($FieldAtts['required'])) && (is_numeric($FieldAtts['required'])) && ($FieldAtts['required'] == 1))) {
							$Required = 1;
						}
						// Check for optional attributes
						if ((isset($FieldAtts['optional'])) && ($FieldAtts['optional'] > '')) {
							$Optional = ' ' . $FieldAtts['optional'] . '';
						}
						// Check Post Value/Entered Default Value
						if ((isset($_REQUEST[$FKey])) && ($_REQUEST[$FKey] == $CBKey) && ($_REQUEST[$FKey] == $CBKey)) {
							$HTMLOptionsFld .= ' selected="selected"';
						} else if ((isset($CBVals)) && ($CBVals == 1)) {
							$HTMLOptionsFld .= ' selected="selected"';
						}
						// Post value
						$HTMLOptionsFld .= ' value="';
						if ($CBKey != $FieldAtts['emptyoption']) {
							// Close text field
							$HTMLOptionsFld .= $this->CleanseString($CBKey);
						}
						$HTMLOptionsFld .= '">' . $this->CleanseString($CBKey) . '</option>' . PHP_EOL;
					}
					// Start Label
					$KeyTitle = str_replace('_', ' ', $FKey);
					$HTMLTxtFld_Pre .= "\t\t\t\t\t" . '<div class="FormLeftColumn">' . PHP_EOL;
					$HTMLTxtFld_Pre .= "\t\t\t\t\t" . '<label title="' . $KeyTitle . '">' . PHP_EOL;
					$HTMLTxtFld_Pre .= "\t\t\t\t\t" . '<strong>' . $KeyTitle . ':</strong>' . PHP_EOL;
					$HTMLTxtFld_Pre .= "\t\t\t\t\t" . '</label>' . PHP_EOL;
					$HTMLTxtFld_Pre .= "\t\t\t\t\t" . '</div>' . PHP_EOL;
					$HTMLTxtFld_Pre .= "\t\t\t\t\t" . '<div class="FormRightColumn">' . PHP_EOL;
					if ($ToolTipText == 1) {
						//$HTMLTxtFld_Pre .= '<a class="tooltip">' . PHP_EOL;
					}
					$HTMLTxtFld_Pre .= "\t\t\t\t\t" . '<select id="" name="' . $FKey . '" id="' . $IDSelect . '"' . $Class . '' . $Optional . '>' . PHP_EOL;
					$HTMLOptionsFinal .= $HTMLTxtFld_Pre . $HTMLOptionsFld;
					$HTMLOptionsFinal .= "\t\t\t\t\t" . '</select>' . PHP_EOL;
					if ($ToolTipText == 1) {
						$HTMLOptionsFinal .= '<span class="classic">' . $FieldAtts['ErrorMessage'] . '</span>' . PHP_EOL;
						//$HTMLOptionsFinal .= '</a>' . PHP_EOL;
					}
					// Check if post submitted and if required and if not set
					if (((((($_SERVER['REQUEST_METHOD'] == $this->PostMethod) && (isset($_REQUEST[$this->HiddenFormCompare])) && ($_REQUEST[$this->HiddenFormCompare] == $this->FormID))) && ((!isset($_REQUEST[$FKey])) or ($_REQUEST[$FKey] == '')) && ($Required == 1)))) {
						// Build label
						$HTMLOptionsFinal .= '&nbsp;&nbsp;<label title="' . $KeyTitle . ' is a required Entry"><strong class="red">*Required Field</strong></label>' . PHP_EOL;
						// checking for Error Message
						if ((isset($FieldAtts['ErrorMessage'])) && ($FieldAtts['ErrorMessage'] > '')) {
							$FieldError = '<div class="alignLeft"><label title="' . $FieldAtts['ErrorMessage'] . '" class="red">*&nbsp;' . $FieldAtts['ErrorMessage'] . '</label></div>';
						} else {
							// Checking our field name
							if ((isset($FKey)) && ($FKey > '')) {
								$KeyTitle = str_replace('_', ' ', $FKey); // Parse out underscores
								$FieldError = '<div class="alignLeft"><strong class="red">[DEV Error]: The field &quot;' . $KeyTitle . '&quot; is required but has no error message.</strong></div>';
							} else {
								$FieldError = '<div class="alignLeft"><strong class="red">[DEV Error]: The field is required but has no error message. Unable to retrieve the required field name.</strong></div>';
							}
						}
					}
					// Close Row
					$HTMLOptionsFinal .= "\t\t\t\t\t" . '</div>' . PHP_EOL;
					// Return
					return array('HTMLReturn' => $HTMLOptionsFinal, 'FieldError' => $Required, 'ErrorMessage' => $FieldError);
				} else {
					// Checking our field name
					if ((isset($FKey)) && ($FKey > '')) {
						$KeyTitle = str_replace('_', ' ', $FKey); // Parse out underscores
						return '<div class="alignLeft"><strong class="red">[DEV Error]: [DEV Error]: There was a problem with the &quot;' . $KeyTitle . '&quot; Select Field.</strong></div>';
					} else {
						return "\t\t\t\t\t" . '<div class="alignLeft"><strong class="red">[DEV Error]: There was a problem with one of the Select Fields. Unable to retrieve the required field name.</strong></div>' . PHP_EOL;
					}
				}
			} else {
				return "\t\t\t\t\t" . '<div class="alignLeft"><strong class="red">[DEV Error]: There was a problem with one of the Select Fields. Unable to retrieve the required field name.</strong></div>' . PHP_EOL;
			}
		} else {
			// Do we have the field name?
			if ((isset($FKey)) && ($FKey > '')) {
				$KeyTitle = str_replace('_', ' ', $FKey); // Parse out underscores
				return "\t\t\t\t\t" . '<div class="alignLeft"><strong class="red">[DEV Error]: There was a problem with the &quot;' . $KeyTitle . '&quot; Select Field.</strong>' . PHP_EOL;
			} else {
				return "\t\t\t\t\t" . '<div class="alignLeft"><strong class="red">[DEV Error]: There was a problem with one of the Select Fields. Unable to retrieve the required field name.</strong></div>' . PHP_EOL;
			}
		}
	}
	// Field Type Determination/Return
	protected function FieldReturn() {
		// Vars
		$FieldHTML = '';
		$TopErrorBuild = '';
		// Loop through field array
		foreach ($this->FieldArray as $FKey => $FieldAtts) {
			switch ($FieldAtts['type']) {
				case "text":
					// Text Field Method
					$BuildField = $this->TextFieldBuilder($FKey, $FieldAtts);
					// Check if we got an array return
					if (is_array($BuildField)) { // array
						// Return our HTML input field
						$FieldHTML .= $BuildField['HTMLReturn'];
						// Check field set
						if ((isset($BuildField['FieldError'])) && ($BuildField['FieldError'] == 1)) { // error found
							$TopErrorBuild .= $BuildField['ErrorMessage'];
						}
					} elseif (isset($BuildField)) { // returned a string (Dev Error)
						$FieldHTML .= $BuildField;
					}
					// Clear row and pad it
					$FieldHTML .= "\t\t\t\t\t" . '<br class="clearfix" />' . PHP_EOL;
					$FieldHTML .= "\t\t\t\t\t" . '<div class="paddingBottom10 clearfix"></div>' . PHP_EOL;
					break;
				case "email":
					// Text Field Method
					$BuildField = $this->EmailFieldBuilder($FKey, $FieldAtts);
					// Check if we got an array return
					if (is_array($BuildField)) { // array
						// Return our HTML input field
						$FieldHTML .= $BuildField['HTMLReturn'];
						// Check field set
						if ((isset($BuildField['FieldError'])) && ($BuildField['FieldError'] == 1)) { // error found
							$TopErrorBuild .= $BuildField['ErrorMessage'];
						}
					} elseif (isset($BuildField)) { // returned a string (Dev Error)
						$FieldHTML .= $BuildField;
					}
					// Clear row and pad it
					$FieldHTML .= "\t\t\t\t\t" . '<br class="clearfix" />' . PHP_EOL;
					$FieldHTML .= "\t\t\t\t\t" . '<div class="paddingBottom10 clearfix"></div>' . PHP_EOL;
					break;
				case "hidden":
					// Text Field Method
					$BuildField = $this->HiddenFieldBuilder($FKey, $FieldAtts);
					// Check if we got an array return
					if (is_array($BuildField)) { // array
						// Return our HTML input field
						$FieldHTML .= $BuildField['HTMLReturn'];
						// Check field set
						if ((isset($BuildField['FieldError'])) && ($BuildField['FieldError'] == 1)) { // error found
							$TopErrorBuild .= $BuildField['ErrorMessage'];
						}
					} elseif (isset($BuildField)) { // returned a string (Dev Error)
						$FieldHTML .= $BuildField;
					}
					break;
				case "checkbox":
					// Checkbox Group Method
					$BuildField = $this->CheckBoxBuilder($FKey, $FieldAtts);
					// Check if we got an array return
					if (is_array($BuildField)) { // array
						// Return our HTML input field
						$FieldHTML .= $BuildField['HTMLReturn'];
						// Check field set
						if ((isset($BuildField['FieldError'])) && ($BuildField['FieldError'] == 1)) { // error found
							$TopErrorBuild .= $BuildField['ErrorMessage'];
						}
					} elseif (isset($BuildField)) { // string
						$FieldHTML .= $BuildField;
					}
					// Clear row and pad it
					$FieldHTML .= "\t\t\t\t\t" . '<br class="clearfix" />' . PHP_EOL;
					$FieldHTML .= "\t\t\t\t\t" . '<div class="paddingBottom10 clearfix"></div>' . PHP_EOL;
					break;
				case "radio":
					// Radio Buttons Method
					$BuildField = $this->RadioButtonBuilder($FKey, $FieldAtts);
					// Check if we got an array return
					if (is_array($BuildField)) { // array
						// Return our HTML input field
						$FieldHTML .= $BuildField['HTMLReturn'];
						// Check field set
						if ((isset($BuildField['FieldError'])) && ($BuildField['FieldError'] == 1)) { // error found
							$TopErrorBuild .= $BuildField['ErrorMessage'];
						}
					} elseif (isset($BuildField)) { // string
						$FieldHTML .= $BuildField;
					}
					// Clear row and pad it
					$FieldHTML .= "\t\t\t\t\t" . '<br class="clearfix" />' . PHP_EOL;
					$FieldHTML .= "\t\t\t\t\t" . '<div class="paddingBottom10 clearfix"></div>' . PHP_EOL;
					break;
				case "select":
					// Select Field Method
					$BuildField = $this->SelectOptionsBuilder($FKey, $FieldAtts);
					// Check if we got an array return
					if (is_array($BuildField)) { // array
						// Return our HTML input field
						$FieldHTML .= $BuildField['HTMLReturn'];
						// Check field set
						if ((isset($BuildField['FieldError'])) && ($BuildField['FieldError'] == 1)) { // error found
							$TopErrorBuild .= $BuildField['ErrorMessage'];
						}
					} elseif (isset($BuildField)) { // string
						$FieldHTML .= $BuildField;
					}
					// Clear row and pad it
					$FieldHTML .= "\t\t\t\t\t" . '<br class="clearfix" />' . PHP_EOL;
					$FieldHTML .= "\t\t\t\t\t" . '<div class="paddingBottom10 clearfix"></div>' . PHP_EOL;
					break;
				case "textarea":
					// Text Field Method
					$BuildField = $this->TextAreaFieldBuilder($FKey, $FieldAtts);
					// Check if we got an array return
					if (is_array($BuildField)) { // array
						// Return our HTML input field
						$FieldHTML .= $BuildField['HTMLReturn'];
						// Check field set
						if ((isset($BuildField['FieldError'])) && ($BuildField['FieldError'] == 1)) { // error found
							$TopErrorBuild .= $BuildField['ErrorMessage'];
						}
					} elseif (isset($BuildField)) { // returned a string (Dev Error)
						$FieldHTML .= $BuildField;
					}
					// Clear row and pad it
					//$FieldHTML .= "\t\t\t\t\t" . '<br class="clearfix" />' . PHP_EOL;
					//$FieldHTML .= "\t\t\t\t\t" . '<div class="paddingBottom10 clearfix"></div>' . PHP_EOL;
					break;
			}
		}
		// Return
		return array('Fields' => $FieldHTML, 'Errors' => $TopErrorBuild);
	}
	// Print ExtJS
    public function ExtJS($formType = 1) {
        $HTML = '<script>';
        $HTML .= "Ext.require([
                //'Ext.form.*',
                //'Ext.layout.container.Column',
                //'Ext.tab.Panel'
                '*'
            ]);
            ";
        $HTML .= "
            Ext.onReady(function() {
                Ext.QuickTips.init();
                Ext.define('Employee', {
                    extend: 'Ext.data.Model',
                    fields: [
                        {name: 'email',     type: 'string'},
                        {name: 'title',     type: 'string'},
                        {name: 'firstName', type: 'string'},
                        {name: 'lastName',  type: 'string'},
                        {name: 'phone-1',   type: 'string'},
                        {name: 'phone-2',   type: 'string'},
                        {name: 'phone-3',   type: 'string'},
                        {name: 'hours',     type: 'number'},
                        {name: 'minutes',   type: 'number'},
                        {name: 'startDate', type: 'date'},
                        {name: 'endDate',   type: 'date'}
                    ]
                });
                var required = '<span style=\"color:red;font-weight:bold\" data-qtip=\"Required\">*</span>';
        ";
        if ($formType == 1) {
            $HTML .= "
                var simple = Ext.create('Ext.form.Panel', {
                    xtype: 'form',
                    collapsible: false,
                    id: '" . $this->FormOptions['FormID'] . "',
                    closable:false,
                    renderTo: 'form-ct',
                    /*title: '',*/
                    bodyPadding: '5 5 0',
                    combineErrors: false,
                    width: 350,
                    defaultType: 'textfield',
                    fieldDefaults: {
                        labelAlign: 'left',
                        labelWidth: 90,
                        anchor: '100%'
                    },
                    items: [
                    " . PHP_EOL;
            $Stop = count($this->FieldArray);
            $i = 1;
            foreach($this->FieldArray as $Field => $Options) {
                if ($Options['type'] == "checkbox") {
                    // Checkbox Group
                    $HTML .= "{xtype: 'fieldset',
                                flex: 1,
                                title: '" . str_replace("_", " ", $Field) . "',
                                layout: 'anchor',
                                defaults: {
                                    anchor: '100%',
                                    hideEmptyLabel: true
                                },
                                items: [";
                    $StopSub = count($Options['boxes']);
                    $k = 1;
                    foreach ($Options['boxes'] as $Label => $Selected) {
                        $HTML .= "{" . PHP_EOL;
                        $HTML .= "xtype: 'checkbox'," . PHP_EOL;
                        if ($Selected == 1) {
                            $HTML .= "checked: true," . PHP_EOL;
                        }
                        if (isset($Options['ErrorMessage']) && $Options['ErrorMessage'] > '') {
                            $HTML .= "blankText: '" . $Options['ErrorMessage'] . "'," . PHP_EOL;
                        }
                        $HTML .= "boxLabel: '" . str_replace("_", " ", $Label) . "'," . PHP_EOL;
                        $HTML .= "name: '" . $Field . "'," . PHP_EOL;
                        $HTML .= "id: '" . $Field . "'," . PHP_EOL;
                        $HTML .= "inputValue: '" . $Field . "'" . PHP_EOL;
                        $HTML .= "}" . PHP_EOL;
                        if ($StopSub != $k) {
                            $HTML .= ", " . PHP_EOL;
                        }
                        $k++;
                    }
                    $HTML .= "]} ";
                    if ($Stop != $i) {
                        $HTML .= ',' . PHP_EOL;
                    }
                    $i++;
                } else if ($Options['type'] == "radio") {
                    // Radio Group
                    $HTML .= "{xtype: 'fieldset',
                                flex: 1,
                                title: '" . str_replace("_", " ", $Field) . "',
                                layout: 'anchor',
                                defaults: {
                                    labelAlign: 'left',
                                    labelWidth: 90,
                                    anchor: '100%'
                                },
                                items: [";
                    $StopSub = count($Options['boxes']);
                    $k = 1;
                    foreach ($Options['radio'] as $Label => $Selected) {
                        $HTML .= "{" . PHP_EOL;
                        $HTML .= "xtype: 'radio'," . PHP_EOL;
                        if ($Selected == 1) {
                            $HTML .= "checked: true," . PHP_EOL;
                        }
                        $HTML .= "boxLabel: '" . str_replace("_", " ", $Label) . "',";
                        if (isset($Options['ErrorMessage']) && $Options['ErrorMessage'] > '') {
                            $HTML .= "blankText: '" . $Options['ErrorMessage'] . "'," . PHP_EOL;
                        }
                        $HTML .= "name: '" . $Field . "'," . PHP_EOL;
                        $HTML .= "id: '" . $Field . "'," . PHP_EOL;
                        $HTML .= "inputValue: '" . $Field . "'" . PHP_EOL;
                        $HTML .= "}" . PHP_EOL;
                        if ($StopSub != $k) {
                            $HTML .= ", " . PHP_EOL;
                        }
                        $k++;
                    }
                    $HTML .= "]} ";
                    if ($Stop != $i) {
                        $HTML .= ',' . PHP_EOL;
                    }
                    $i++;
                } else if ($Options['type'] == "select") {
                    // Select Field
                    $HTML .= "{xtype: 'combo'," . PHP_EOL;
                    $HTML .= "fieldLabel: '" . str_replace("_", " ", $Field) . "'," . PHP_EOL;
                    $HTML .= "name: '" . $Field . "'," . PHP_EOL;
                    $HTML .= "id: '" . $Field . "'," . PHP_EOL;
                    if (isset($Options['ErrorMessage']) && $Options['ErrorMessage'] > '') {
                        $HTML .= "blankText: '" . $Options['ErrorMessage'] . "'," . PHP_EOL;
                    }
                    foreach ($Options['options'] as $Label => $Selected) {
                        if ($Selected == 1) {
                            $SelectedOption = $Label;
                        }
                    }
                    $HTML .= "mode:         'local',
                            value:          '" . $SelectedOption . "',
                            triggerAction:  'all',
                            forceSelection: true,
                            editable:       false,
                            displayField:   'name',
                            valueField:     'value',
                            queryMode: 'local',
                            store:          Ext.create('Ext.data.Store', {
                                fields : ['name', 'value'],
                                data   : [";
                    $StopSub = count($Options['options']);
                    $k = 1;
                    foreach ($Options['options'] as $Label => $Selected) {
                        $HTML .= "{name : '" . $Label . "',   value: '$Label'}" . PHP_EOL;
                        if ($StopSub != $k) {
                            $HTML .= ", " . PHP_EOL;
                        }
                        $k++;
                    }               
                    $HTML .= "]" . PHP_EOL;
                    $HTML .= "})" . PHP_EOL;
                    $HTML .= "}" . PHP_EOL;
                    if ($Stop != $i) {
                        $HTML .= ',' . PHP_EOL;
                    }
                    $i++; 
                } else if ($Options['type'] == "hidden") {
                    // Hidden Field
                    $HTML .= "{xtype: 'hidden'," . PHP_EOL;
                    $HTML .= "fieldLabel: '" . str_replace("_", " ", $Field) . "'," . PHP_EOL;
                    if (isset($Options['ErrorMessage']) && $Options['ErrorMessage'] > '') {
                        $HTML .= "blankText: '" . $Options['ErrorMessage'] . "'," . PHP_EOL;
                    }
                    $HTML .= "name: '" . $Field . "'," . PHP_EOL;
                    $HTML .= "id: '" . $Field . "'," . PHP_EOL;
                    $HTML .= "}" . PHP_EOL;
                    if ($Stop != $i) {
                        $HTML .= ',' . PHP_EOL;
                    }
                    $i++;
                } else if ($Options['type'] == "textarea") {
                    // Text Field
                    $HTML .= "{fieldLabel: '" . str_replace("_", " ", $Field) . "'," . PHP_EOL;
                    $HTML .= "xtype: 'textarea'," . PHP_EOL;
                    if (isset($Options['ErrorMessage']) && $Options['ErrorMessage'] > '') {
                        $HTML .= "blankText: '" . $Options['ErrorMessage'] . "'," . PHP_EOL;
                    }
                    if ($Options['required'] == 1) {
                        $HTML .= "afterLabelTextTpl: required," . PHP_EOL;
                    } 
                    //
                    $HTML .= "name: '" . $Field . "'," . PHP_EOL;
                    $HTML .= "id: '" . $Field . "'," . PHP_EOL;
                    $HTML .= "allowBlank: false" . PHP_EOL;
                    $HTML .= "}" . PHP_EOL;
                    if ($Stop != $i) {
                        $HTML .= ',' . PHP_EOL;
                    }
                    $i++; 
                } else {
                    // Text Field
                    $HTML .= "{fieldLabel: '" . str_replace("_", " ", $Field) . "'," . PHP_EOL;
                    if ($Options['required'] == 1) {
                        $HTML .= "afterLabelTextTpl: required," . PHP_EOL;
                    } 
                    if ($Options['type'] == 'email') {
                        $HTML .= "vtype:'email'," . PHP_EOL;
                    }
                    //
                    $HTML .= "name: '" . $Field . "'," . PHP_EOL;
                    $HTML .= "id: '" . $Field . "'," . PHP_EOL;
                    $HTML .= "allowBlank: false" . PHP_EOL;
                    $HTML .= "}" . PHP_EOL;
                    if ($Stop != $i) {
                        $HTML .= ',' . PHP_EOL;
                    }
                    $i++; 
                }
            }
            // Hidden Field
            $HTML .= ",{xtype: 'hidden'," . PHP_EOL;
            $HTML .= "name:'FormID'," . PHP_EOL;
            $HTML .= "id:'FormID'," . PHP_EOL;
            $HTML .= "value:'" . $this->FormOptions['FormID'] . "'," . PHP_EOL;
            $HTML .= "}" . PHP_EOL;
            $HTML .= "
                    ],
                    buttons: [{
                        text: 'Send',
                        formBind: true, //only enabled once the form is valid
                        disabled: true,
                        handler: function() {
                            var form = this.up('form').getForm();
                            if (form.isValid()) {
                                form.submit({
                                    clientValidation: true,
                                    url: '" . $_SERVER['REQUEST_URI'] . "',
                                    standardSubmit: true,
                                    params: {
                                        newStatus: 'delivered'
                                    },
                                    success: function(form, action) {
                                       Ext.Msg.alert('Success', action.result.msg);
                                    },
                                    failure: function(form, action) {
                                        switch (action.failureType) {
                                            case Ext.form.action.Action.CLIENT_INVALID:
                                                Ext.Msg.alert('Failure', 'Form fields may not be submitted with invalid values');
                                                break;
                                            case Ext.form.action.Action.CONNECT_FAILURE:
                                                Ext.Msg.alert('Failure', 'Ajax communication failed');
                                                break;
                                            case Ext.form.action.Action.SERVER_INVALID:
                                               Ext.Msg.alert('Failure', action.result.msg);
                                       }
                                    }
                                });
                            }
                        }
                    }, {
                        text: 'Reset',
                        handler: function() {
                            this.up('form').getForm().reset();
                        }
                    }]
                });
            });
            ";
        }
        $HTML .= '</script>';
        return $HTML;
    }
    // Print Our Form 
	public function PrintForm() {
		$publickey = '';
		// Check for intitialization error
		if ($this->InitError != 1) {
            // Vars
            $TopErrorBuild = '';
            if ($this->FormOptions['ExtJS'] == 1) {
                $FinalHTML = $this->ExtJS();
                $FinalHTML .= "\t\t\t\t" . "<div id=\"form-ct\"></div>";
            } else if (is_array($this->FieldArray)) {
                // Begin Form Container
                $FinalHTML = "\t\t\t" . '<div class="FormContainer">' . PHP_EOL;
                // Begin Form
                $FinalHTML .= "\t\t\t\t" . '<form action="' . $_SERVER['REQUEST_URI'] . '" method="' . $this->PostMethod . '" id="' . $this->FormID . '" name="' . $this->FormName . '">' . PHP_EOL;
				// Getting our fields/Data Return
				$FieldAndData = $this->FieldReturn();
				$TopErrorBuild .= $FieldAndData['Errors'];
				$FinalHTML .= $FieldAndData['Fields'];
                // Check for captcha option
                if ($this->GoogleCaptchaOption == 1) {
                    $CaptchaError = '';
                    if ((($_SERVER['REQUEST_METHOD'] == $this->PostMethod) && (isset($_REQUEST[$this->HiddenFormCompare])) && ($_REQUEST[$this->HiddenFormCompare] == $this->FormID))) {
                        if ((isset($_REQUEST["recaptcha_challenge_field"])) && (isset($_REQUEST["recaptcha_response_field"]))) {
                            $ReCaptcha = $this->GoogleCaptcha($_SERVER["REMOTE_ADDR"], $_REQUEST["recaptcha_challenge_field"], $_REQUEST["recaptcha_response_field"]);
                            // Check captcha erors
                            if ($ReCaptcha['error'] == 1) { // google captcha errors
                                $CaptchaError = $ReCaptcha['return'];
                                $TopErrorBuild .= $ReCaptcha['return'];
                            }
                            if ($_REQUEST["recaptcha_response_field"] == '') {
                                $TopErrorBuild .= '<div class="alignLeft"><label title="Please enter the captcha words." class="red">*&nbsp;Please enter the captcha words.</label></div>';
                            }
                        }
                    }
                    $FinalHTML .= "\t\t\t\t" . recaptcha_get_html($publickey, $CaptchaError) . '<br />' . PHP_EOL;
                }
                // Submit Button/Hidden Fields
                if ($this->ToField > '') {
                    // Form To Field
                    $FinalHTML .= "\t\t\t\t" . '<input type="hidden" id="ToField" name="ToField" value="' . $this->ToField . '" />' . PHP_EOL;
                }
                // Form ID
                $FinalHTML .= "\t\t\t\t" . '<input type="hidden" id="FormID" name="FormID" value="' . $this->FormID . '" />' . PHP_EOL;
                // Submit Button
                $FinalHTML .= "\t\t\t\t" . '<input class="btn btn-primary btn-lg" type="submit" id="SubmitAutoForm" name="SubmitAutoForm" value="' . $this->SubmitName . '" />' . PHP_EOL;
                // End Form
                $FinalHTML .= "\t\t\t\t" . '</form>' . PHP_EOL;
                // End Form Containor
                $FinalHTML .= "\t\t\t" . '</div>' . PHP_EOL;
            }
			// Check errors
			if ($TopErrorBuild > '') {
				$FinalErrorMessage = '<label title="There was an error with your form submission."><strong class="red">There was an error with your form submission.</strong></label>';
				$FinalErrorMessage .= '<div class="alignLeft">' . $TopErrorBuild . '</div><br />' . PHP_EOL;
				$FinalHTML = $FinalErrorMessage . $FinalHTML;
			} else if ((($_SERVER['REQUEST_METHOD'] == $this->PostMethod) && (isset($_REQUEST[$this->HiddenFormCompare])) && ($_REQUEST[$this->HiddenFormCompare] == $this->FormID))) {
				$FinalSuccessMessage = '<label title="Form submission successful."><strong class="red">Form submission successful.</strong></label><br /><br />';
				// Check Send Mail Options
				if ($this->SendMail == 1) {
					$MailProc = $this->EmailFormProc($this->ToField, $this->ToName, $this->FromName, $this->FromField, $_REQUEST);
				}
				$FinalHTML = $FinalSuccessMessage . $FinalHTML;
			}
			// The return
			return $FinalHTML;
		}
	}
	// Captcha
	public function GoogleCaptcha($RemoteAddr, $ReCaptchaChallengeField, $ReCaptchaResponseField) {
		// Var
        $privatekey = $this->config['privatekey'];
		$resp = NULL;
		$error = 0;
		$message = '';
		// Was there a reCAPTCHA response?
		if ($ReCaptchaResponseField != '') {
			$resp = recaptcha_check_answer($privatekey, $RemoteAddr, $ReCaptchaChallengeField, $ReCaptchaResponseField);
			if ($resp->is_valid) {
				$error = 0;
			} else {
				if ($resp->error == 'incorrect-captcha-sol') {
					$error = 1;
					$message .= '<div class="alignLeft"><label title="Incorrect captcha words." class="red">*&nbsp;Incorrect captcha words.</label></div>';
				} else {
					$error = 1;
					$message .= '<div class="alignLeft"><label title="' . $resp->error . '" class="red">*&nbsp;' . $resp->error . '</label></div>';
				}
			}
		} else {
			$message .= '<div class="alignLeft"><label title="Please enter the captcha words." class="red">*&nbsp;Please enter the captcha words.</label></div>';
		}
		// Return
		return array('error' => $error, 'return' => $message);
	}
	// Form Mailing
	protected function EmailFormProc($To, $ToName, $FromName, $FromEmail, $postdata) {
		// Fields to ignore
		$field = array("recipient", "redirect", "recipient", "Submit", "submit", "To", "to", "referrer", 'SubmitAutoForm', 'recaptcha_challenge_field', 'recaptcha_response_field', 'FormID', 'ToField');
		// Vars
		$ErrorReport = '';
		// Checks
		// To 
		if ((!isset($To)) && ($To == '')) {
			$ErrorReport .= '<div class="alignLeft"><strong class="red">[Dev Error] No &quot;To Email&quot; field set.</strong></div>';
			$this->EmailError = 1;
		}
		// From Email
		if ((!isset($FromEmail)) && ($FromEmail == '')) {
			$ErrorReport .= '<div class="alignLeft"><strong class="red">[Dev Error] No &quot;From Email&quot; field set.</strong></div>';
			$this->EmailError = 1;
		}
		// Message
		$BeginMessage = $this->Message;
		$Message = $BeginMessage . '<br /><br />';
		// Message Builder
		if ((isset($postdata)) && (is_array($postdata))) {
			// Get all the post data and begin building the message...
			$es = $postdata;
			// Loop through Keys and values of the post data and concatenate them together in a nice way...
			while (list($key, $val) = each($es)) {
				//Lets make sure there is a value first...
				if (isset($val)) {
					//Lets filter out a few fields that don't need to be in the message...
					$process_key = 1;
					foreach ($field as $fval) {
						if ($key == $fval) {
							$process_key = 0;
						}
					}
					// Value exists and key is ok
					if (($val > "") && ($process_key == 1)) {
						//Building our string for each key and value...
						$key = str_replace("_", " ", $key);
						$key = str_replace("--", "'", $key);
						$key = ucfirst($key);
						$Message .= "<strong>".$key." : </strong> " . $this->CleanseString($val) . "<br />";
					}
				}
			}
		}
		// Not required, set to default
		// To Name
		if ((!isset($ToName)) or ($ToName == '')) {
			$ToName = NULL;
		}
		// From Name
		if ((!isset($FromName)) or ($FromName == '')) {
			$FromName = NULL;
		}
		// Check if class exists
		if (!class_exists('Mailer')) {
			include('extensions/utilities/Mailer.class.php');
		}
        if (!class_exists('Mailer')) {
            exit('Mail Error.');
        }
		// Checking once again before object call
		if (class_exists('Mailer')) { // class found
			$MailClass = new Mailer(); // Mailer object
		} else { // Fatal email error
			$ErrorReport .= '<div class="alignLeft"><strong class="red">[Dev Error] Email Class not found</strong></div>';
			$this->EmailError = 1;
		}
		// Checking email error
		if ($this->EmailError == 0) { // No error
			// Check to name
			if ($ToName > '') {
				$To = $ToName . ' <' . $To . '>';
			} else {
				$To = $To;
			}
			// Checking from name
			if ($FromName > '') {
				$From = $FromName . ' (' . $FromEmail . ')';
			} else {
				$From = $FromEmail;
			}
			//$DomainFrom = 'steve.scharf@canuckcoder.com';
			// Send the email
			if ($MailClass->sendSimpleMail($To, $From, $this->Subject, $Message, $this->HTMLOpt, $this->DomainFrom) === true) {
				$return = '<div class="alignLeft"><strong class="red">Email Sent.</strong></div>';
				$EmailSent = 1;
			} else {
				$EmailSent = 0;
				$return = '<div class="alignLeft"><strong class="red">[Class Error] Email send failed.</strong></div>'; // Email Failed
			}
			// Return
			return array('Return' => $return, 'EmailSent' => $EmailSent);
		} else { // Fatal error
			// Return
			return array('Return' => $ErrorReport, 'EmailSent' => 0);
		}
	}
}
?>