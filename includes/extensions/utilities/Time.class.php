<?php
class Time extends CCTemplate
{
	public function EnglishTimeLong() {
		return date('g:i:sa \o\n l\, F jS');
	}
	public function FrenchTimeLong() {
		setlocale(LC_ALL, "fr_FR");
		$time_string = strftime("%H:%M:%S");
		if (strftime("%H") >= 12) {
			$time_string .= ' en après midi du ';
		} else {
			$time_string .= ' le matin du ';
		}
		
		if(PHP_OS == 'WINNT') { //Windows servers don't play nice with %e
			$time_string .= strftime("%A %#d %B");
		} else {
			$time_string .= strftime("%A %e %B");
		}
		return html_entity_decode(htmlentities($time_string, ENT_COMPAT, "utf-8"));
	}
}
?>