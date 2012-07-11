<?php
	$DB_host = "localhost";
	$DB_database = "ipmedt4";
	$DB_user = "school";
	$DB_password = "ipmedt4";
	$Succes = "";
	
	mysql_connect($DB_host,$DB_user,$DB_password);
	mysql_select_db($DB_database);
	$result = mysql_query("select * from `users` where username = '".$_GET["Name"]."' and password = '".md5($_GET["Password"])."'");
	while ($row = mysql_fetch_object($result)) {
		$Name = $row->name;
		$Succes = "Yes";
	}

	echo $_GET["jsoncallback"] . "({\"Succes\": \"" . $Succes . "\", \"Naam\": \"".$Name."\", \"Name\": \"".$username."\"})";
?>