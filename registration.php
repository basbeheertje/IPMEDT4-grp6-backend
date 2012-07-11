<?php

	//Zorg ervoor dat de var succes leeg is
	$Succes = "";
	
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
	
	//Maak een MYSQL connectie aan met de server
	mysql_connect($DB_host,$DB_user,$DB_password);
	
	//Selecteer de database
	mysql_select_db($DB_database);
	
	//Selecteer alle gegevens van de gebruiker met deze naam
	$result = mysql_query("select * from `users` where username = '".$_GET["Name"]."'");
	
	//Als er geen gebruiker is met deze naam
	if(mysql_fetch_object($result) == false){
		
		//Voeg de nieuwe gebruiker dan toe
		mysql_query("INSERT INTO `users` (`username`, `password`, `name`) VALUES ('".$_GET["Name"]."', '".md5($_GET["Password"])."', '".$_GET['Naam']."')");
		$Succes = "true";
		
	}

	echo $_GET["jsoncallback"] . "({\"Succes\": \"" . $Succes . "\"})";
?>