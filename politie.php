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
	
	//Kijken of er een JSON object is meegestuurd om te vullen
	if(isset($_GET["jsoncallback"])){
		//JA? Stuur de naam mee terug
		echo $_GET["jsoncallback"];
	}else{
		//Nee? Laat de site elke 60 seconde weergeven ivm Query caching
	?><html>
	<head>
		<meta http-equiv="refresh" content="60" />
	</head>
	<body><?php
		}
		/*
			Nu gaan we de meldingen terugsturen
			Dit gaat gebeuren d.m.v. een array list
		*/
		
	?>({
			"name":"Meldingen",
			"items":[
<?php
		//Alle politie meldingen ophalen uit de database van de server
			
			//Connectie opzetten
			$con = mysql_connect($DB_host,$DB_user,$DB_password);
			
			//Kijken of de connectie is mislukt
			if (!$con){
				//Ja? Geeft een melding en stop er daarna mee!
				die('Could not connect: ' . mysql_error());
			}

			//Selecteren van een database
			mysql_select_db($DB_database, $con);

			//Query klaar zetten en versturen
			$result = mysql_query("SELECT `meldingen`.*, `projects`.id as project
				FROM  `meldingen`, `projects`
				WHERE `meldingen`.`type` = 'politie'
				AND	`projects`.`latitude` = `meldingen`.`latitude`
				AND `projects`.`longitutde` = `meldingen`.`longitutde`
				ORDER BY  `meldingen`.`id` DESC 
				LIMIT 0 , 30");
			
			//Teller op 0 zetten
			$count = 0;
			
			while($item = mysql_fetch_object($result)){
				$data[] = $item;
			}
			
			foreach($data as $melding){
				//Teller met 1 ophogen
				$count++;
				
				//Melding toevoegen aan de pagina
				print "
				{
					\"title\":\"".$melding->title."\",
					\"message\":\"".str_replace("\n", "", $melding->message)."\",
					\"date\":\"".$melding->date."\",
					\"latitude\":\"".$melding->latitude."\",
					\"longitutde\":\"".$melding->longitutde."\",
					\"id\":\"".$melding->project."\"
				}";
				
				//Een comma achter het item plaatsen behalve als het de laatste betreft
				if($count < count($data)){
					print ",";
				}
			}

			//Alle laatste 30 politie meldingen zijn opgehaald. Connectie met mysql server kan gesloten worden
			mysql_close($con);
?>] 
})<?php
	if(!isset($_GET["jsoncallback"])){
		//Als er geen jsoncallback is verstuurd dan moet de pagina als html worden weergegeven
	?></body>
</html>
	<?php
}
?>