<?php
if (isset($_SESSION[$sess_prefix . 'passok'])) {
	if ($_SESSION[$sess_prefix . 'passok'] == 1) {
		$printset = 1;
	} else {
		if (isset($printset)) {
			$printset = $printset;
		} else {
			$printset = '';
		}
	}
} else {
	if (isset($printset)) {
		$printset = $printset;
	} else {
		$printset = '';
	}
}
$Form = $LoginUser;
if ($printset == 1) {
	$GetLogOut = new UserChecks();
	echo '<div class="loggedin">' . $GetLogOut->LogOutLink() . '</div>';
} else {
	echo $Form;
}
?>
