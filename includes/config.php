<?php
	/* Defining Database Credentials */
	define('DB_SERVER','localhost');
	define('DB_USERNAME','root');
	define('DB_PASSWORD','');
	define('DB_NAME','authenticationsystem');

	/* Connecting to Database */
	$conn = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_NAME);

	/* Checking the connection */
	if(!$conn) {
		die("ERROR: Could not connect:" .mysqli_connect_error());
	}
?>