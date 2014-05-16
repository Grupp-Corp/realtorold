<?php
class Parsers extends CCTemplate
{
	// PHP Array into comma delimited (', ') string
	public function ArrayToCommaDelimitedString($array) {
		$array_to_string = $array;
		$string = '';
		$c = count($array_to_string);
		for($i = 0; $i <= $c; $i++) {
			if ($c == $i) {
				$string .= $array_to_string[$i];
			} else {
				$string .= $array_to_string[$i] . ', ';
			}	
		}
		return $string;
	}
	// Comma delimited (', ') string into a PHP Array
	public function CommaDelimitedStringToArray($string) {
		$original_val = explode(", ", $string);
		$c = count($original_val);
		$mynewarray = array();
		for($i = 0; $i <= $c; $i++) {
			array_push($mynewarray, $original_val[$i]);
		}
		return $mynewarray;
	}
	public function PipeLanguageSeperator($string) {
		$string_seperated = explode (' | ', $string);	
	}
}
?>