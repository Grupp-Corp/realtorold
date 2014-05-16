<?php
$PageTitle = 'My Profile';
// Grabbing some classes
$UserActions = new UserActions();
$UserChecks = new UserChecks();
$Build = new TemplateDesign();
// Check if user is logged in
if (isset($_SESSION[$this->config['session_prefix'] . 'id']) && $_SESSION[$this->config['session_prefix'] . 'id'] > 0) {
    // Enable/Disable Account redirection
	if ((isset($_GET['act'])) && ($_GET['act'] == 'enable')) {
            $UseChecks = new UserChecks();
            $id = $_SESSION[$this->config['session_prefix'] . 'id'];
            $active = 1;
            $UseChecks->ActiveSelf($id, $active);
            header('Location: /profile/enabled');
    } else if ((isset($_GET['act'])) && ($_GET['act'] == 'disable')) {
            $UseChecks = new UserChecks();
            $id = $_SESSION[$this->config['session_prefix'] . 'id'];
            $active = 0;
            $UseChecks->ActiveSelf($id, $active);
            header('Location: /profile/disabled');
    }
    ?>
    <h1>User Center</h1>
    <?php
	if (isset($_GET['act']) && $_GET['act'] == 'forgot') {
		$templateFile = '/profile/forgot.php'; // File to pull (relates to templates content folder
		$Build->GetTemplateFile($templateFile); // Simple way to pull files as includes from the template content folder
    } else {
		$templateFile = '/profile/profile.php';
		$Build->GetTemplateFile($templateFile);
	}
} else { // Not Logged In
	// User forms
	if (isset($_GET['recovery'])) {
		if ((isset($_GET['rid'])) && (strlen($_GET['rid']) == 32)) {
			$PasswordRecovery = $_GET['rid'];
			echo $UserActions->PasswordRecovery($PasswordRecovery);
		} else {
			echo 'Invalid Revoery ID.';
		}
	} elseif ((isset($_GET['forgot'])) && ($_GET['forgot'] == 1)) {
		echo $UserActions->ForgotPassword();
	} elseif ((isset($_GET['login'])) && ($_GET['login'] == 1)) {
		echo $UserActions->LoginUser();
	} else {
		$templateFile = '/profile/register.php';
		$Build->GetTemplateFile($templateFile);
	}
}
?>