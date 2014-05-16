<?php
/**
 * This is the main index which all calls pass through if the .htaccess is properly configured.
 * @name index.php
 *
 * @author Guido Media
 * @copyright (c) 2013, Guido Media
 */
date_default_timezone_set('America/New_York');
ini_set('include_path', '/var/www/vhosts/myrealtorcliq.com/httpdocs/includes/'); // Set Include path (LIVE)
//ini_set('include_path', 'M:/Development/DaDaCliq/Development/includes/'); // Set Include path
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