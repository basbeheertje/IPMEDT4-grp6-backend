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
	
	//Stuur jsoncallback mee
	echo $_GET["jsoncallback"];
	
	//Start array
?>({
        "name":"Meldingen",
        "items":[
<?php
		//Get meldingen
			//Maak een nieuwe connectie aan
			$con = mysql_connect($DB_host,$DB_user,$DB_password);
			
			//Test connectie
			if (!$con){
				//Connectie met MYSQL werkt niet dus geef melding en stop
				die('Could not connect: ' . mysql_error());
			}

			//Selecteer de database
			mysql_select_db($DB_database, $con);

			//Haal de laatste 30 projecten op of geen een fout melding en stop
			$result = mysql_query("SELECT * 
				FROM  `projects` 
				ORDER BY  `projects`.`id` DESC 
				LIMIT 0 , 30") or die ("Probleem met ophalen:".mysql_error());
			
			//Zet teller op 0
			$count = 0;
			
			//Items ophalen uit de database en als object zetten
			while($item = mysql_fetch_object($result)){
				$data[] = $item;
			}
			
			//Elke melding individueel weergeven als array
			foreach($data as $melding){
			
				//Teller met 1 ophogen
				$count++;
				
				//Gegevens weergeven
				print "
				{
					\"title\":\"".str_replace("\n", "", $melding->titel)."\",
					\"date\":\"".$melding->date."\",
					\"latitude\":\"".$melding->latitude."\",
					\"longitutde\":\"".$melding->longitutde."\",
					\"id\":\"".$melding->id."\"
				}";
				
				//Voeg een comma toe behalve bij de laatste
				if($count < count($data)){
					print ",";
				}
				
			}

			//Sluit de connectie af
			mysql_close($con);
?>] 
})