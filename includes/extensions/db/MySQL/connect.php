<?php
// Initialize the database
$db_conn = DBMySQL::obtain(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_NAME);
// connect to the server 
$db_conn->connect();
?>