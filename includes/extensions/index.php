<?php
// Load extensions
include('extensions/db/MySQL/MySQL.class.php'); // MySQL Class (Complete)
include('extensions/db/MySQLi/MySQLi.class.php'); // MySQL Class (Complete)
include('extensions/security/Exception.class.php'); // Extending PHP's Exception Class
include('extensions/security/PermsPub.class.php'); // Permissions (Public) Class
include('extensions/security/XSS.class.php'); // XSS Protections
include('extensions/utilities/EmailAddressValidator.class.php'); // Email Address Validation Class
include('extensions/utilities/MenuBuild.class.php'); // Menu Building Class
include('extensions/utilities/Obfuscation.class.php');  // Obfuscation Class
include('extensions/utilities/Pagination.class.php'); // Pagination Class
include('extensions/utilities/Parsers.class.php'); // String/Array Parsing Class
include('extensions/utilities/Sorting.class.php');  // Sorting Class
include('extensions/utilities/StringCheckers.class.php');  // String Checking Class
include('extensions/utilities/Time.class.php'); // Time Class
//include('extensions/utilities/Mailer.class.php');
?>
