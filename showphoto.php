<?php

$DB_host = "localhost";
	$DB_database = "ipmedt4";
	$DB_user = "school";
	$DB_password = "ipmedt4";
if(isset($_GET['id']) and $_GET['id'] != NULL and $_GET['id'] != ''){
	$con = mysql_connect($DB_host,$DB_user,$DB_password);
	if (!$con){
		die('Could not connect: ' . mysql_error());
	}
	mysql_select_db($DB_database, $con);
	
	$sql = "SELECT originalname as name, filetype as type, filesize as size, file as content " .
		"FROM `foto` WHERE id = '".$id."'";
			
	$bestand = parent::singleRowData($sql);

	
	header("Content-length: $bestand->size");
		header("Content-type: $bestand->type");
		header("Content-Disposition: attachment; filename=$bestand->name");
		echo $bestand->content;

		exit;
}
?>