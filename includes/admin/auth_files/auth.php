<?php
// Authorize user (Admin area)
$CheckAdmin = new UserChecks(); // Get User Checking Class
// Redirect if not administrator
if ($CheckAdmin->CheckIfAdmin() === false) {
	header('Location: ' . $this->config['site_absolute_path'] . 'admin/index.php');
}
?>