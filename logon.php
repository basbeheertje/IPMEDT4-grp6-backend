<?php
	/*
		Creator:	Bas van Beers
		Klas:		INF2C
		School:		HSLeiden
		Project:	IPMEDT4
		Groep:		6
	*/
	
	//Database gegevens klaarzetten
	$DB_host = "localhost";
	$DB_database = "ipmedt4";
	$DB_user = "school";
	$DB_password = "ipmedt4";
	
	//Connectie aanmaken
	mysql_connect($DB_host,$DB_user,$DB_password);
	
	//Database selecteren
	mysql_select_db($DB_database);
	
	//Query uitvoer, klaarzetten en opvangen
	$result = mysql_query("select * from `users` where username = '".$_GET["Name"]."' and password = '".md5($_GET["Password"])."'");
	
	//selecteer gegevens
	while ($row = mysql_fetch_object($result)) {
		$Name = $row->name;
		$Succes = "Yes";
	}

	//Stuur een succes terug met gebruikersnaam en normale naam
	echo $_GET["jsoncallback"] . "({\"Succes\": \"" . $Succes . "\", \"Naam\": \"".$Name."\", \"Name\": \"".$username."\"})";
?>