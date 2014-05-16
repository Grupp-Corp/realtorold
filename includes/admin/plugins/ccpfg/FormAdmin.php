<?php
class FormAdmin extends CCTemplate
{
	// Construct
	public function __construct() {
		// Connection
		parent::__construct();
	}
	/////////////////////////
	//	  Table Returns    //
	/////////////////////////
	// Form Field List
	public function GetFormList() {
		// Get form list
		$GetRow = $this->db_conn->query('SELECT * FROM ' . $this->config['table_prefix'] . 'form_list ORDER BY Subject ASC');
		$repeat_rows = DBMySQLi::fetch_assoc($GetRow);
		// Get affected rows
		$TotalRows = $this->db_conn->affected_rows;
		// Check Rows
		if ($TotalRows > 0) { // Rows exist
			return array('RowArray' => $repeat_rows, 'TotalRows' => $TotalRows); // Data population
		} else { // Rows do not exist
			return array('RowArray' => array(), 'TotalRows' => 0); // Defaulting
		}
	}
	// Get Form Details
	public function GetFormSelect($id, $page = 1, $sortCol = '', $sortby = '', $limit = 0) {
		// ID Check
		if (isset($id)) {
			if (!is_numeric($id)) {
				$skip = 1;	
			} else {
				if ($id > 0) {
					$skip = 0;
				} else {
					$skip = 1;	
				}
			}
		} else {
			$skip = 1;
		}
		if ($skip == 0) {
			$row = $this->db_conn->query("SELECT * FROM " . $this->config['table_prefix'] . "form_list WHERE fid=" . $id . ""); // Get user db data
			$fetch_single = DBMySQLi::fetch_single($row);
			return $fetch_single;
		} else {
			return false;
		}
	}
	// Get all form Fields
	public function GetFormFieldList($fid) {
		$queryAppend = '';
		if ((((isset($sortCol)) && ($sortCol > '') && (isset($sortby)) && ($sortby > '')))) {
			$queryAppend .= 'ORDER BY ' . $sortCol . $sortby;
		}
		if ((isset($limit)) && ($limit == 0)) {
			$queryAppend .= 'LIMIT 0, ' . $limit;
		}
		// Get form list
		$querySelect = $this->db_conn->query('SELECT * FROM ' . $this->config['table_prefix'] . 'form_fields WHERE fid=' . $fid . '');
		$GetRow = DBMySQLi::fetch_assoc($querySelect);
		// Get affected rows
		$TotalRows = $this->db_conn->affected_rows;
		// Check Rows
		if ($TotalRows > 0) { // Rows exist
			return array('RowArray' => $GetRow, 'TotalRows' => $TotalRows); // Data population
		} else { // Rows do not exist
			return array('RowArray' => array(), 'TotalRows' => 0); // Defaulting
		}
	}
	// Get Form Field details
	public function GetFormFieldSelect($id) {
		// ID Check
		if (isset($id)) {
			if (!is_numeric($id)) {
				$skip = 1;	
			} else {
				if ($id > 0) {
					$skip = 0;
				} else {
					$skip = 1;	
				}
			}
		} else {
			$skip = 1;
		}
		if ($skip == 0) {
			$row = $this->db_conn->query("SELECT * FROM " . $this->config['table_prefix'] . "form_fields WHERE field_id=" . $id . ""); // Get user db data
			$fetch_single = DBMySQLi::fetch_single($row);
			return $fetch_single;
		} else {
			return false;
		}
	}
	// Form Field Options
	public function GetFormFieldOptionsSelect($id) {
		// ID Check
		if (isset($id)) {
			if (!is_numeric($id)) {
				$skip = 1;	
			} else {
				if ($id > 0) {
					$skip = 0;
				} else {
					$skip = 1;	
				}
			}
		} else {
			$skip = 1;
		}
		if ($skip == 0) {
			$rows = $this->db_conn->query("SELECT * FROM " . $this->config['table_prefix'] . "form_field_options WHERE field_id=" . $id . ""); // Get user db data
			$finalRows = DBMySQLi::fetch_assoc($rows);
			// Get affected rows
			$TotalRows = $this->db_conn->affected_rows;
			// Check Rows
			if ($TotalRows > 0) { // Rows exist
				return array('Rows'=>$finalRows, 'TotalRows'=>$TotalRows);
			} else {
				return array('Rows'=>false, 'TotalRows'=>0);
			}
		} else {
			return array('Rows'=>false, 'TotalRows'=>0);
		}
	}
	// Get form field option by ID
	public function GetFormFieldOptionById($id) {
		// ID Check
		if (isset($id)) {
			if (!is_numeric($id)) {
				$skip = 1;	
			} else {
				if ($id > 0) {
					$skip = 0;
				} else {
					$skip = 1;	
				}
			}
		} else {
			$skip = 1;
		}
		if ($skip == 0) {
			$rows = $this->db_conn->query("SELECT * FROM " . $this->config['table_prefix'] . "form_field_options WHERE fid=" . $id . ""); // Get user db data
			$fetch_single = DBMySQLi::fetch_single($row);
			return $fetch_single;
		} else {
			return false;
		}
	}
	/////////////////////////
	//	Table Updates Area //
	/////////////////////////
	/////////////////////////
	//     Form Updates    //
	/////////////////////////
	// Add Form
	public function AddForm($FormName, $FormID, $RedirectPage, $HTML5, $Captcha, $EmailAddressRequired, $SendMail, $Subject, $Message, $ToField, $ToName, $DomainFrom, $HTML) {
		// Insert query
		$query_string = "INSERT INTO " . $this->config['table_prefix'] . "form_list (FormName, FormID, RedirectPage, HTML5, Captcha, EmailAddressRequired, SendMail, Subject, Message, ToField, ToName, DomainFrom, HTML) VALUES ('" . $FormName . "', '" . $FormID . "', '" . $RedirectPage . "', " . $HTML5 . ", " . $Captcha . ", " . $EmailAddressRequired . ", " . $SendMail . ", '" . $Subject . "', '" . $Message . "', '" . $ToField . "', '" . $ToName . "', '" . $DomainFrom . "', " . $HTML . ")";
		$query = $this->db_conn->query($query_string);
		$insertdID = $this->db_conn->insert_id; // The current inserted ID
		// Was query successful
		if ($query) {
			return true;
		} else {
			return false;
		}
	}
	// Update Form
	public function UpdateForm($FormName, $FormID, $RedirectPage, $HTML5, $Captcha, $EmailAddressRequired, $SendMail, $Subject, $Message, $ToField, $ToName, $DomainFrom, $HTML, $fid) {
		// Update Query
		$query_string = "UPDATE " . $this->config['table_prefix'] . "form_list SET FormName='" . $FormName . "', FormID='" . $FormID . "', RedirectPage='" . $RedirectPage  ."', HTML5=" . $HTML5 . ", Captcha=" . $Captcha . ", EmailAddressRequired=" . $EmailAddressRequired . ", SendMail=" . $SendMail . ", Subject='" . $Subject . "', Message='" . $Message . "', ToField='" . $ToField . "', ToName='" . $ToName . "', DomainFrom='" . $DomainFrom . "', HTML=" . $HTML . " WHERE fid=" . $fid . "";
		$query = $this->db_conn->query($query_string);
		// Was query successful
		if ($query) {
			return true;
		} else {
			return false;
		}
	}
	// Delete Form
	public function DeleteForm($id) {
		// ID Check
		if (isset($id)) {
			if (!is_numeric($id)) {
				$skip = 1;	
			} else {
				$skip = 0;
			}
		} else {
			$skip = 1;
		}
		if ($skip == 0) {
			$query_string = "DELETE FROM " . $this->config['table_prefix'] . "form_list WHERE fid=" . $id . "";
			$query = $this->db_conn->query($query_string);
			$query_string = "DELETE FROM " . $this->config['table_prefix'] . "form_fields WHERE fid=" . $id . "";
			$query = $this->db_conn->query($query_string);
			$query_string = "DELETE FROM " . $this->config['table_prefix'] . "form_field_options WHERE fid=" . $id . "";
			$query = $this->db_conn->query($query_string);
			return true;
		} else {
			return false;
		}
	}
	/////////////////////////
	// Form Field Updates  //
	/////////////////////////
	// Add Form Field
	public function AddFormField($LabelName, $FieldID, $Type, $rows, $cols, $Value, $Required, $Size, $Maxlength, $ErrorMessage, $ForEmail, $Class, $Optional, $weight, $fid) {
		// Vars
		$error = 0;
		// Check Form ID
		if (!isset($fid)) {
			$error = 1;
		} else if (!is_numeric($fid)) {
			$error = 1;
		} else if ($fid == 0) {
			$error = 1;
		}
		//$this->GetFormFieldList($fid, 1, 'weight', 'DESC', 1);
		// Other checks
		if (!isset($Size)) {
			$Size = 0;
		} else if (!is_numeric($Size)) {
			$Size = 0;
		}
		if (!isset($Maxlength)) {
			$Maxlength = 0;
		} else if (!is_numeric($Maxlength)) {
			$Maxlength = 0;
		}
		if (!isset($rows)) {
			$rows = 0;
		} else if (!is_numeric($rows)) {
			$rows = 0;
		}
		if (!isset($cols)) {
			$cols = 0;
		} else if (!is_numeric($cols)) {
			$cols = 0;
		}
		if (!isset($weight)) {
			$weight = 1;
		} else if (!is_numeric($weight)) {
			$weight = 1;
		}
		// Check errors
		if ($error == 0) {
			// Insert query
			$query_string = "INSERT INTO " . $this->config['table_prefix'] . "form_fields (
																		fid, 
																		LabelName, 
																		FieldID, 
																		ForEmail, 
																		Required, 
																		Size, 
																		Maxlength, 
																		Class, 
																		Type, 
																		Optional, 
																		Value, 
																		ErrorMessage, 
																		rows, 
																		cols, 
																		weight
																		) 
																		VALUES 
																		(
																		 " . $fid . ", 
																		 '" . $LabelName . "', 
																		 '" . $FieldID . "', 
																		 '" . $ForEmail . "', 
																		 " . $Required . ", 
																		 " . $Size . ", 
																		 " . $Maxlength . ", 
																		 '" . $Class . "', 
																		 '" . $Type . "', 
																		 '" . $Optional . "', 
																		 '" . $Value . "', 
																		 '" . $ErrorMessage . "', 
																		 " . $rows . ", 
																		 " . $cols . ", 
																		 " . $weight . "
																		 )";
			$query = $this->db_conn->query($query_string);
			$insertdID = $this->db_conn->insert_id; // The current inserted ID
			// Was query successful
			if ($query) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	// Update Form Field
	public function UpdateFormField($LabelName, $FieldID, $Type, $rows, $cols, $Value, $Required, $Size, $Maxlength, $ErrorMessage, $ForEmail, $Class, $Optional, $field_id) {
		// Vars
		$error = 0;
		// Check Form ID
		if (!isset($field_id)) {
			$error = 1;
		} else if (!is_numeric($field_id)) {
			$error = 1;
		} else if ($field_id == 0) {
			$error = 1;
		}
		// Other checks
		if (!isset($Size)) {
			$Size = 0;
		} else if (!is_numeric($Size)) {
			$Size = 0;
		}
		if (!isset($Maxlength)) {
			$Maxlength = 0;
		} else if (!is_numeric($Maxlength)) {
			$Maxlength = 0;
		}
		if (!isset($rows)) {
			$rows = 0;
		} else if (!is_numeric($rows)) {
			$rows = 0;
		}
		if (!isset($cols)) {
			$cols = 0;
		} else if (!is_numeric($cols)) {
			$cols = 0;
		}
		// Check errors
		if ($error == 0) {
			// Update Query
			$query_string = "UPDATE " . $this->config['table_prefix'] . "form_fields SET LabelName='" . $LabelName . "', FieldID='" . $FieldID . "', Type='" . $Type  ."', rows=" . $rows . ", cols=" . $cols . ", Value='" . $Value . "', Required=" . $Required . ", Size=" . $Size . ", Maxlength=" . $Maxlength . ", ErrorMessage='" . $ErrorMessage . "', ForEmail='" . $ForEmail . "', Class='" . $Class . "', Optional='" . $Optional . "' WHERE field_id=" . $field_id . "";
			$query = $this->db_conn->query($query_string);
			// Was query successful
			if ($query) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	// Delete Form Field
	public function DeleteFormField($id) {
		// ID Check
		if (isset($id)) {
			if (!is_numeric($id)) {
				$skip = 1;	
			} else {
				$skip = 0;
			}
		} else {
			$skip = 1;
		}
		if ($skip == 0) {
			$query_string = "DELETE FROM " . $this->config['table_prefix'] . "form_fields WHERE field_id=" . $id . "";
			$query = $this->db_conn->query($query_string);
			$query_string = "DELETE FROM " . $this->config['table_prefix'] . "form_field_options WHERE field_id=" . $id . "";
			$query = $this->db_conn->query($query_string);
			return true;
		} else {
			return false;
		}
	}
	////////////////////////////////
	// Form Field Option Updates  //
	////////////////////////////////
	// Add Form Field Option
	public function AddFieldOption($fid, $value, $name, $selected) {
		// Check Form ID
		if (!isset($fid)) {
			$error = 1;
		} else if (!is_numeric($fid)) {
			$error = 1;
		} else if ($fid == 0) {
			$error = 1;
		}
		// Other checks
		if (!isset($selected)) {
			$selected = 0;
		} else if (!is_numeric($selected)) {
			$selected = 0;
		}
		if (!isset($value)) {
			$value = '';
		}
		if (!isset($name)) {
			$name = '';
		}
		if (!isset($selected)) {
			$selected = 0;
		} else if (!is_numeric($selected)) {
			$selected = 0;
		}
		// Insert query
		$query_string = "INSERT INTO " . $this->config['table_prefix'] . "form_field_options (field_id, value, name, option_selected) VALUES (" . $fid . ", '" . $value . "', '" . $name . "', " . $selected . ")";
		$query = $this->db_conn->query($query_string);
		$insertdID = $this->db_conn->insert_id; // The current inserted ID
		// Was query successful
		if ($query) {
			return true;
		} else {
			return false;
		}
	}
	// Update Form Field Option
	public function UpdateFieldOption($id, $value, $name, $selected) {
		// Vars
		$error = 0;
		// Check Form ID
		if (!isset($id)) {
			$error = 1;
		} else if (!is_numeric($id)) {
			$error = 1;
		} else if ($id == 0) {
			$error = 1;
		}
		// Other checks
		if (!isset($selected)) {
			$selected = 0;
		} else if (!is_numeric($selected)) {
			$selected = 0;
		}
		if (!isset($value)) {
			$value = '';
		}
		if (!isset($name)) {
			$name = '';
		}
		if (!isset($selected)) {
			$selected = 0;
		} else if (!is_numeric($selected)) {
			$selected = 0;
		}
		// Check errors
		if ($error == 0) {
			// Update Query
			$query_string = "UPDATE " . $this->config['table_prefix'] . "form_field_options SET value='" . $value . "', name='" . $name  ."', option_selected=" . $selected . " WHERE fid=" . $id . "";
			$query = $this->db_conn->query($query_string);
			// Was query successful
			if ($query) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	// Delete Form Field Option
	public function DeleteFieldOption($fid) {
		// ID Check
		if (isset($fid)) {
			if (!is_numeric($fid)) {
				$skip = 1;	
			} else {
				$skip = 0;
			}
		} else {
			$skip = 1;
		}
		if ($skip == 0) {
			$query_string = "DELETE FROM " . $this->config['table_prefix'] . "form_field_options WHERE fid=" . $fid . "";
			$query = $this->db_conn->query($query_string);
			return true;
		} else {
			return false;
		}
	}
}
?>