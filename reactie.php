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
	
	//Maak een MYSQL connectie aan
	mysql_connect($DB_host,$DB_user,$DB_password);
	
	//Selecteer de database
	mysql_select_db($DB_database);
		
	//Voeg de reactie toe
	mysql_query("INSERT INTO `reacties` (`melding`, `user`, `text`) VALUES ('".$_GET["Melding"]."', '".$_GET["User"]."', '".$_GET['Reactie']."')");
	$Succes = "true";

	//Geef een melding terug
	echo $_GET["jsoncallback"] . "({\"Succes\": \"" . $Succes . "\"})";
?>