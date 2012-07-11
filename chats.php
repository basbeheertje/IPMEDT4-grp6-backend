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
	
	//JSON Object terugsturen
	echo $_GET["jsoncallback"];
	
	//Kijken of er een nummer is meegegeven en of deze niet leeg is
	if(isset($_GET['id']) and $_GET['id'] != NULL and $_GET['id'] != ''){
		//Laad melding
		
		//Maak een connectie met de database aan
		$con = mysql_connect($DB_host,$DB_user,$DB_password);
		
		//Controleren of de connectie werkt
		if (!$con){
			//Connectie werkt niet dus geef de melding weer en sluit af
			die('Could not connect: ' . mysql_error());
		}
		
		//Selecteren van de database
		mysql_select_db($DB_database, $con);

		//selecteren van de gegevens voor de gebruiker omtrent de chats die openstaan
		$result = mysql_query("SELECT * 
			FROM  `users` 
			WHERE `username` = '".$_GET['id']."'");
			
		$count = 0;

		//Connectie verbreken
		mysql_close($con);
	
?>({
        "Reacties":[
        <?php
			
			//Connectie aanmaken met de database
			$con = mysql_connect($DB_host,$DB_user,$DB_password);
			
			//Connectie controleren
			if (!$con){
				//Connectie is niet mogelijk dus geef de fout weer
				die('Could not connect: ' . mysql_error());
			}
			
			//Database selecteren
			mysql_select_db($DB_database, $con);

			//Chats van de gebruiker selecteren
			$result = mysql_query("SELECT r.text as reactie, p.titel as meldingstitel, p.id as nummer
				FROM  `reacties` as r, `projects` as p
				WHERE r.`user` = '".$_GET['id']."'
				and p.id = r.`melding`
				GROUP BY r.`melding`
				ORDER BY r.`create_date` DESC
				");
			
			//Teller resetten
			$count = 0;
			
			//Items printen en versturen naar de client
			while($row = mysql_fetch_array($result)){
				$count++;
				print "
				{
					\"melding\":\"".substr($row['meldingstitel'], 0, 37)."\",
					\"nummer\":\"".$row['nummer']."\",
					\"reactie\":\"".substr($row['reactie'], 0, 37)."\"
				}";
				
				if($count < count(mysql_fetch_array($result))){
					print ",";
				}
			}

			//MYSQL connectie afsluiten
			mysql_close($con);
			
		?>]
})<?php
	}
?>