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
	
	//Connectie maken met de server
	mysql_connect($DB_host,$DB_user,$DB_password);
	
	//Database selecteren
	mysql_select_db($DB_database);
	
	//Variabele van de foto resetten
		//Inhoud var
		$content = "";
		
		//Bestandsgrote
		$fileSize = "";
		
		//Bestandstype
		$fileType = "";
	
	//Kijken of het bestand niet leeg is
	if($_FILES['file']['size'] > 0){
		//Variabele invullen
		$fileName = $_FILES['file']['name'];
		$tmpName  = $url;
		$fileSize = $_FILES['file']['size'];
		$fileType = $_FILES['file']['type'];

		//Verstuurde bestand openen
		$fp = fopen($tmpName, 'r');
			//bestand overschrijven naar var
			$content = fread($fp, filesize($tmpName));
			
			//Ervoor zorgen dat het bestand klaar is voor MYSQL
			$content = addslashes($content);
		//Bestand sluiten	
		fclose($fp);
		
		//Als magic quotes uitstaat
		if(!get_magic_quotes_gpc()){
			//Dan slashes toevoegen aan de bestandsnaam
			$fileName = addslashes($fileName);
		}
				
	}
		
	//QUERY om de foto toe te voegen
	mysql_query("INSERT INTO 
		`foto` (
			`user`, 
			`melding`, 
			`originalname`,
			`url`,
			`file`,
			`filesize`,
			`filetype`
		) VALUES (
			'0', 
			'0',
			'".$originele_naam."',
			'".$url."',
			'".$content."',
			'".$fileSize."',
			'".$fileType."'
		)");
	
	$Succes = "true";
	
	$new_image_name = "namethisimage.jpg";
	//Bestand verplaatsen
	move_uploaded_file($_FILES["file"]["tmp_name"], "".$new_image_name);
		
	//Stuur terug dat het gelukt is
	echo $_GET["jsoncallback"] . "({\"Succes\": \"" . $Succes . "\"})";
?>