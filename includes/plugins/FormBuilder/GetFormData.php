<?php
class GetFormData extends CCTemplate 
{
	// Global Vars
	protected $GetDB;
	protected $fid = 0;
	protected $RowData = 0;
	protected $CacheOptions = '';
	protected $CacheFields = '';
	protected $FilesAreSet = 0;
	protected $OptionsCacheArray;
	protected $FieldsCacheArray;
	// Construct
	public function __construct($fid) {
		parent::__construct();
		// Set Vars
		$this->fid = $fid;
		// Check Form ID
		if ((isset($this->fid)) && (is_numeric($this->fid))) {
			// Check if Folder path is defined
			if ($this->config['server_folder_path']) {
				// Get cache files
				$this->CacheOptions = $this->config['server_folder_path'] . '/temp/formID-' . $this->fid . '-.txt';
				$this->CacheFields = $this->config['server_folder_path'] . '/temp/formID_Fields-' . $this->fid . '-.txt';
			} else {
				// Get cache files
				$this->CacheOptions = $_SERVER['DOCUMENT_ROOT'] . '/temp/formID-' . $this->fid . '-.txt';
				$this->CacheFields = $_SERVER['DOCUMENT_ROOT'] . '/temp/formID_Fields-' . $this->fid . '-.txt';
			}
			// Check if files exists
			if ((file_exists($this->CacheOptions)) && (file_exists($this->CacheFields))) {
				// Files are set
				$this->FilesAreSet = 1;
				// Getting file contents (Options)
				$this->OptionsCacheArray = file_get_contents($this->CacheOptions, true);
				// Unserialize contents (Options)
				$this->OptionsCacheArray = json_decode($this->OptionsCacheArray);
				// Getting file contents (Fields)
				$this->FieldsCacheArray = file_get_contents($this->CacheFields, true);
				// Unserialize contents (Fields)
				$this->FieldsCacheArray = json_decode($this->FieldsCacheArray);
				// Checking arrays
				if ((is_array($this->OptionsCacheArray)) && (is_array($this->FieldsCacheArray))) {
					// Files are set
					$this->FilesAreSet = 1;
				} else if (!is_array($this->OptionsCacheArray)) {
					// Files are set
					$this->FilesAreSet = 0;
				} else if (!is_array($this->FieldsCacheArray)) {
					// Files are set
					$this->FilesAreSet = 0;
				}
			}
			// Checking if cache files are set and proper
			if ($this->FilesAreSet == 0) {
				// Set DB Vars
				$this->GetDB = $this->db_conn;
				// Get form data from MySQL
				$GetRow = $this->GetDB->query('SELECT * FROM ' . $this->config['table_prefix'] .'form_list LEFT JOIN ' . $this->config['table_prefix'] .'form_fields ON ' . $this->config['table_prefix'] .'form_list.fid = ' . $this->config['table_prefix'] .'form_fields.fid WHERE ' . $this->config['table_prefix'] .'form_list.fid = ' . $this->fid . '');
                $repeat_rows_reset = DBMySQLi::fetch_assoc($GetRow);
				// Set Row Data
				$this->RowData = $repeat_rows_reset;
				$TotalRows = $this->GetDB->affected_rows;
				// Check total rows
				if ($TotalRows == 0) {
					// Return
					echo '<p><strong class="red">Form Options not found!</strong></p>';
				}
			}
		} else {
			// Return
			echo '<p><strong class="red">No Form ID set.</strong></p>';
		}
	}
	// Form Options
	public function GetFormOptions() {
		// Check if Cache file exists
		if ($this->FilesAreSet == 1) {
			// Return
			return $this->OptionsCacheArray;
		} else {
			// Check for Row data
			if ((isset($this->RowData)) && (is_array($this->RowData))) {
				// Vars
				$NewArray = array();
				// Looping through all records
				foreach ($this->RowData as $DataArray) {
					// Loop through current row values
					foreach ($DataArray as $key => $val) {
						// Getting only data from form option columns
						switch ($key) {
							case "FormName":
								$NewArray[$key] = $val;
								break;
							case "FormID":
								$NewArray[$key] = $val;
								break;
							case "HTML5":
								$NewArray[$key] = $val;
								break;
							case "Captcha":
								$NewArray[$key] = $val;
								break;
                            case "ExtJS":
								$NewArray[$key] = $val;
								break;
							case "SendMail":
								$NewArray['SendEmail'] = array(
														'Send' => $val, 
														'EmailAddressRequired' => $this->RowData[0]['EmailAddressRequired'],
														'ToField' => $this->RowData[0]['ToField'], 
														'ToName' => $this->RowData[0]['ToName'], 
														'Subject' => $this->RowData[0]['Subject'], 
														'Message' => $this->RowData[0]['Message'], 
														'HTML' => $this->RowData[0]['HTML'], 
														'DomainFrom' => $this->RowData[0]['DomainFrom']
														);
								break;
							case "RedirectPage":
								$NewArray[$key] = $val;
								break;
						}
					}
					// Stopping at our first entry (form options only)
					break;
				}
				if (is_array($NewArray)) {
					// Begin caching method
					$NewArray_Serialized = json_encode($NewArray);
					// Write the contents back to the file
					file_put_contents($this->CacheOptions, $NewArray_Serialized);
					// Return
					return $NewArray;
				} else {
					// Return
					return '<p><strong class="red">Form Options not found!</strong></p>';
				}
			} else { // No data
				// Return
				return '<p><strong class="red">Form Options not found!</strong></p>';
			}
		}
	}
	// Form Fields
	public function GetFormFields() {
		// Check if Cache file exists
		if ($this->FilesAreSet == 1) {
			// Return
			return $this->FieldsCacheArray;
		} else {
			// Check for Row data
			if ((isset($this->RowData)) && (is_array($this->RowData))) {
				// Vars
				$NewArray = array();
				$FinalSelectOptions = array();
				$FinalCheckboxOptions = array();
				$FinalRadiosOptions = array();
				$i = 0;
				// Loop through data
				foreach ($this->RowData as $DataArray) {
					// loop through row columns
					foreach ($DataArray as $key => $val) {
						switch ($key) {
							case "LabelName":
								// Starting array
								$NewArray[$val] = $val;
								// Checking type
								switch ($this->RowData[$i]['Type']) {
									case "select":
										if ((isset($this->RowData[$i]['field_id'])) && (is_numeric($this->RowData[$i]['field_id']))) {
											$GetSelectRows = $this->GetDB->query('SELECT * FROM ' . $this->config['table_prefix'] .'form_field_options WHERE field_id = ' . $this->RowData[$i]['field_id'] . '');
											$repeat_rows_reset = DBMySQLi::fetch_assoc($GetSelectRows);
											// Set Row Data
											// Set Row Data
											foreach ($repeat_rows_reset as $subval) {
												$FinalSelectOptions[$subval['value']] = $subval['option_selected'];
											}
										}
										break;
									case "checkbox":
										if ((isset($this->RowData[$i]['field_id'])) && (is_numeric($this->RowData[$i]['field_id']))) {
											$GetCheckboxRows = $this->GetDB->query('SELECT value, option_selected FROM ' . $this->config['table_prefix'] .'form_field_options WHERE field_id = ' . $this->RowData[$i]['field_id'] . '');
											$repeat_rows_reset = DBMySQLi::fetch_assoc($GetCheckboxRows);
											// Set Row Data
											foreach ($repeat_rows_reset as $subval) {
												$FinalCheckboxOptions[$subval['value']] = $subval['option_selected'];
											}
										}
										break;
									case "radio":
										if ((isset($this->RowData[$i]['field_id'])) && (is_numeric($this->RowData[$i]['field_id']))) {
											$GetRadiosRows = $this->GetDB->query('SELECT * FROM ' . $this->config['table_prefix'] .'form_field_options WHERE field_id = ' . $this->RowData[$i]['field_id'] . '');
											$repeat_rows_reset = DBMySQLi::fetch_assoc($GetRadiosRows);
											// Set Row Data
											foreach ($repeat_rows_reset as $subval) {
												$FinalRadiosOptions[$subval['value']] = $subval['option_selected'];
											}
										}
										break;
								}
								// Start Final Array
								$NewArray[$val] = array(
														'type' => $this->RowData[$i]['Type'], 
														'ForEmail' => $this->RowData[$i]['ForEmail'], 
														'required' => $this->RowData[$i]['Required'], 
														'size' => $this->RowData[$i]['Size'], 
														'maxlength' => $this->RowData[$i]['Maxlength'], 
														'class' => $this->RowData[$i]['Class'], 
														'id' => $this->RowData[$i]['FieldID'], 
														'optional' => $this->RowData[$i]['Optional'], 
														'value' => $this->RowData[$i]['Value'], 
														'ErrorMessage' => $this->RowData[$i]['ErrorMessage'], 
														'rows' => $this->RowData[$i]['rows'], 
														'cols' => $this->RowData[$i]['cols'], 
														'options' => $FinalSelectOptions, 
														'emptyoption' => '', 
														'boxes' => $FinalCheckboxOptions, 
														'radio' => $FinalRadiosOptions
														);
								break;
						}
					}
					// Reset arrays
					$FinalSelectOptions = array();
					$FinalCheckboxOptions = array();
					$FinalRadiosOptions = array();
					// Counter
					$i++;
				}
				if (is_array($NewArray)) {
					// Begin caching method
					$NewArray_Serialized = json_encode($NewArray);
					// Write the contents back to the file
					@file_put_contents($this->CacheFields, $NewArray_Serialized);
					// Return
					return $NewArray;
				} else {
					// Return
					return '<p><strong class="red">Fields not found!</strong></p>';
				}
			} else {
				// Return
				return '<p><strong class="red">Fields not found!</strong></p>';
			}
		}
	}
}
?>