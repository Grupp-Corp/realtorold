<?php
/**
 * This is the main index which all calls pass through if the .htaccess is properly configured.
 * @name index.php
 *
 * @author Guido Media
 * @copyright (c) 2013, Guido Media
 */
date_default_timezone_set('America/New_York');

// Set the Globals for Docment Paths
$GLOBALS['APPLICATION_PATH'] = __DIR__ . "/";
$GLOBALS['INCLUDES_PATH'] = __DIR__ . "/includes/";

$globalSetArray = explode("/", $_SERVER['REQUEST_URI']);

// Add any other subdirectories the site has here.
if (count($globalSetArray) === 2 || $globalSetArray[1] === 'admin') {
	$GLOBALS['CLIENT_ROOT'] = '/';
} else { 
	$GLOBALS['CLIENT_ROOT'] = "/" . $globalSetArray[1] . "/";
}

ini_set('include_path', $GLOBALS['INCLUDES_PATH']); // Set Include path (LIVE)

// Calling classes based on .ini setting of the include path above
include('Template.class.php');
include('TemplateDesign.class.php');
include('extensions/index.php');
include('plugins/index.php');

// Getting build class
$Build = new TemplateDesign();
// Check if redirect in content page
if (isset($_POST['redirect']) && $_POST['redirect'] == '1') {
	$redirect = true;
} else {
	$redirect = false;
}
// Getting URL from GET
if (isset($_GET['url']) && $_GET['url'] > '') {
    $url = $_GET['url'] . '';
} else {
    $url = 'index.php';
}
// Debug options
$debug = 0; // urn on php error options
$phpInfo = 0; // show php info page
// Return FullSite Content
$Build->FullSite($url, $redirect, $debug, $phpInfo);
?>