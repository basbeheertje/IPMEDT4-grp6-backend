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
	
	//JSON callback terugsturen voor de variabele aan de client kant
	echo $_GET["jsoncallback"];
	
	//Kijken of er een nummer is meegestuurd
	if(isset($_GET['id']) and $_GET['id'] != NULL and $_GET['id'] != ''){
		//Nummer is meegestuurd dus nu halen we de gegevens van de melding op
		
		//Eerst een connectie maken met de mysql database
		$con = mysql_connect($DB_host,$DB_user,$DB_password);
		
		//Kijken of de connectie is mislukt
		if (!$con){
			die('Could not connect: ' . mysql_error());
		}
		
		//Selecteer database
		mysql_select_db($DB_database, $con);

		//Selecteer bestaande projecten
		$result = mysql_query("SELECT * 
			FROM  `projects` 
			WHERE `id` = '".$_GET['id']."'");
			
		//Teller resetten
		$count = 0;
		
		//Gegevens opslaan in vars
		while($row = mysql_fetch_array($result)){
			$title = $row['titel'];
			$date = $row['date'] . " " . $row['time'];
			$latitude = $row['latitude'];
			$longitude = $row['longitutde'];
			$id = $row['id'];
		}

	//Database connectie sluiten
	mysql_close($con);
	
	//Gegevens weergeven om het terug te sturen	
?>({
        "title":"<?=$title?>",
		"date":"<?=$date?>",
		"latitude":"<?=$latitude?>",
		"longitutde":"<?=$longitude?>",
		"id":"<?=$id?>",
        "Reacties":[
        <?php
			$con = mysql_connect($DB_host,$DB_user,$DB_password);
			if (!$con){
				die('Could not connect: ' . mysql_error());
			}
			mysql_select_db($DB_database, $con);

			$result = mysql_query("SELECT * 
				FROM  `reacties` 
				WHERE `melding` = '".$id."'
				order by id Desc
				");
					
			$count = 0;
					
			while($row = mysql_fetch_array($result)){
				$count++;
				print "
				{
					\"gebruiker\":\"".$row['user']."\",
					\"reactie\":\"".$row['text']."\",
					\"create_date\":\"".$row['date']."\"
				}";
				
				if($count < count(mysql_fetch_array($result))){
					print ",";
				}
			}

			mysql_close($con);
		?>],
		"Fotos":[
        <?php
			print "
				{
					\"id\":\"1\",
					\"name\":\"test.jpg\",
					\"create_date\":\"2012-03-28 21:46:54\"
				}";
		
			$con = mysql_connect($DB_host,$DB_user,$DB_password);
			if (!$con){
				die('Could not connect: ' . mysql_error());
			}
			mysql_select_db($DB_database, $con);

			$result = mysql_query("SELECT * 
				FROM  `foto` 
				WHERE `melding` = '".$id."'
				order by id Desc
				");
					
			$count = 0;
					
			while($row = mysql_fetch_array($result)){
				$count++;
				print "
				{
					\"id\":\"".$row['id']."\",
					\"name\":\"".$row['originalname']."\",
					\"create_date\":\"".$row['date']."\"
				}";
				
				if($count < count(mysql_fetch_array($result))){
					print ",";
				}
			}

			mysql_close($con);
		?>],
		"Meldingen":[
		<?php
			$con = mysql_connect($DB_host,$DB_user,$DB_password);
			if (!$con){
				die('Could not connect: ' . mysql_error());
			}
			mysql_select_db($DB_database, $con);

			$result = mysql_query("SELECT * 
				FROM  `meldingen` 
				WHERE `latitude` = '".$latitude."'
				AND `longitutde` = '".$longitude."'
				");
					
			$count = 0;
					
			while($row = mysql_fetch_array($result)){
				$count++;
				print "
				{
					\"title\":\"".$row['title']."\",
					\"message\":\"".$row['type']."-".str_replace("\n", "", $row['message'])."\",
					\"date\":\"".$row['date']."\"
				}";
				
				if($count < count(mysql_fetch_array($result))){
					print ",";
				}
			}

			mysql_close($con);
		?>]
})<?php
	}else{
		//Gegevens weergeven indien er geen id is opgegeven
?>({
        "title":"PRIO 1 8941 HOOGT DE WOZOCO WILLIBRORDSTRAAT 1 ALPHEN NB Br OMS (INC: 01)",
		"message":"BRAN | Midden- en West-Brabant | 1201999 BRW Midden en West Brabant ( Monitorcode ) BRAN | Midden- en West-Brabant | 1201921 BRW Alphen ( Lichtkrant ) BRAN | Midden- en West-Brabant | 1201352 BRW Midden en West Brabant BRAN | Midden- en West-Brabant | 1200148 BRW Alphen ( Blusgroep )",
		"date":"Wed, 28 Mar 2012 12:53:59",
		"latitude":"51.4831910",
		"longitutde":"4.9537840",
		"id":"2",
        "reacties":[
        {
            "title":"B AKKERWINDEVELD WOERDEN",
            "message":"AMBU | Utrecht | 0726128 RAV Utrecht ( Ambu 09-128 )",
            "date":"Wed, 28 Mar 2012 12:54:01",
			"latitude":"52.079707",
			"longitutde":"4.8626876",
			"id":"1"
        },
        {
            "title":"PRIO 1 8941 HOOGT DE WOZOCO WILLIBRORDSTRAAT 1 ALPHEN NB Br OMS (INC: 01)",
            "message":"BRAN | Midden- en West-Brabant | 1201999 BRW Midden en West Brabant ( Monitorcode ) BRAN | Midden- en West-Brabant | 1201921 BRW Alphen ( Lichtkrant ) BRAN | Midden- en West-Brabant | 1201352 BRW Midden en West Brabant BRAN | Midden- en West-Brabant | 1200148 BRW Alphen ( Blusgroep )",
            "date":"Wed, 28 Mar 2012 12:53:59",
			"latitude":"51.4831910",
			"longitutde":"4.9537840",
			"id":"2"
        }] 
})<?php
}
?>