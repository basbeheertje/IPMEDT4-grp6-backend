<?php
	$Succes = "";
	
	$DB_host = "localhost";
	$DB_database = "ipmedt4";
	$DB_user = "school";
	$DB_password = "ipmedt4";
	
	mysql_connect($DB_host,$DB_user,$DB_password);
	mysql_select_db($DB_database);
		
	mysql_query("INSERT INTO `reacties` (`melding`, `user`, `text`) VALUES ('".$_GET["Melding"]."', '".$_GET["User"]."', '".$_GET['Reactie']."')");
	$Succes = "true";

	echo $_GET["jsoncallback"] . "({\"Succes\": \"" . $Succes . "\"})";
?>