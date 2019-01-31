<?php

	/**
	author: FelipheGomez
	author URL: http://demedallo.com
	License: Creative Commons Attribution 3.0 Unported
	License URL: http://creativecommons.org/licenses/by/3.0/
	**/
	
	require('library/php-excel-reader/excel_reader2.php');
	require('library/SpreadsheetReader.php');
	
	require_once("../../v1.0/autoload.php");
	/** CONFIG SESSION USERS ----  3600 **/
	session_set_cookie_params(86400,"/");
	@session_start();

	$dbHost = DB_SERVER;
	$dbDatabase = DB_NAME;
	$dbPasswrod = DB_PASS;
	$dbUser = DB_USER;
	$mysqli = new mysqli($dbHost, $dbUser, $dbPasswrod, $dbDatabase);
	
	
?>