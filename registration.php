<?php
	$Succes = "";
	
	$DB_host = "localhost";
	$DB_database = "ipmedt4";
	$DB_user = "school";
	$DB_password = "ipmedt4";
	
	mysql_connect($DB_host,$DB_user,$DB_password);
	mysql_select_db($DB_database);
	$result = mysql_query("select * from `users` where username = '".$_GET["Name"]."'");
	if(mysql_fetch_object($result) == false){
		
		mysql_query("INSERT INTO `users` (`username`, `password`, `name`) VALUES ('".$_GET["Name"]."', '".md5($_GET["Password"])."', '".$_GET['Naam']."')");
		$Succes = "true";
		
	}

	echo $_GET["jsoncallback"] . "({\"Succes\": \"" . $Succes . "\"})";
?>