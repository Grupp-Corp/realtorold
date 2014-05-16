<?php
class FormHelper
{
	public function CheckRadioButtonPost($input_id, $value_to_match) {
		if ((isset($_POST[$input_id])) && ($_POST[$input_id] == $value_to_match)) {
			return ' checked="checked"';
		} else {
			return NULL;
		}
	}
	public function CheckTextFieldPost($input_id, $value_to_match) {
		if ((isset($_POST[$input_id])) && ($_POST[$input_id] > '')) {
			return $value_to_match;
		} else {
			return NULL;
		}
	}
}
?>