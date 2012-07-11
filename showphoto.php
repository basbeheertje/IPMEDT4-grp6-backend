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
	
	//Als er een nummer is meegegeven
	if(isset($_GET['id']) and $_GET['id'] != NULL and $_GET['id'] != ''){
	
		//Maak een connectie met de MYSQL server
		$con = mysql_connect($DB_host,$DB_user,$DB_password);
	
		//Controleer de connectie
		if (!$con){
			//Geef de foutmelding van de connectie weer
			die('Could not connect: ' . mysql_error());
		}
		
		//Database selecteren
		mysql_select_db($DB_database, $con);
	
		//Foto selecteren
		$sql = "SELECT originalname as name, filetype as type, filesize as size, file as content " .
			"FROM `foto` WHERE id = '".$id."'";
			
		//Query uitvoeren	
		$bestand = mysql_query($sql);

		//Bestandslengte meegeven
		header("Content-length: $bestand->size");
		
		//Bestandstype meegeven
		header("Content-type: $bestand->type");
		
		//Bestandsnaam meegeven
		header("Content-Disposition: attachment; filename=$bestand->name");
		
		//Bestand afdrukken
		echo $bestand->content;

		exit;
	}
?>