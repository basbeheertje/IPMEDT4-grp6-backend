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
	
?><html>
	<head>
		<meta http-equiv="refresh" content="60" />
		<title>
			Melding parser voor Ramptoeristen APP
		</title>
	</head>
	<body>
		<h1>
			Laatste meldingen toegevoegd!
		</h1>
		<table>
			<tr>
				<td>
					<b>
						Melding
					</b>
				</td>
				<td>
					<b>
						Type
					</b>
				</td>
			</tr>
<?php
	$insert = "";

	//Algemene strip functie
	function strip_tags_into($tmp_var){
		return(htmlspecialchars(strip_tags(str_replace("\n", "", str_replace("]]>", "", str_replace("<![CDATA[", "", str_replace("\r\n", "", $tmp_var)))))));
	}
	
	function strip_date_into($tmp_var){
		return(str_replace(" +0300", "", str_replace(" +0200", "", str_replace(" +0100", "", $tmp_var))));
	}
	
	function strip_date_only($tmp_var){
		//Deze functie zorgt voor een juiste datum
		$tmp_var = strip_date_into($tmp_var);
		
		//Pak alleen het getal voor de maand
		$tmp_var = substr($tmp_var, 5, -9);
		
		//Maak van de waarde Jan 01
		$tmp_var = str_replace("Jan", "01", $tmp_var);
		
		//Maak van de waarde Feb 02
		$tmp_var = str_replace("Feb", "02", $tmp_var);
		
		//Vervang de waarde Mar 03
		$tmp_var = str_replace("Mar", "03", $tmp_var);
		
		//Vervang de waarde Apr voor 04
		$tmp_var = str_replace("Apr", "04", $tmp_var);
		
		//Vervang de waarde May voor 05
		$tmp_var = str_replace("May", "05", $tmp_var);
		
		//Vervang de waarde Jun voor 06
		$tmp_var = str_replace("Jun", "06", $tmp_var);
		
		//Vervang de waarde Jul voor 07
		$tmp_var = str_replace("Jul", "07", $tmp_var);
		
		//Vervang de waarde Aug voor 08
		$tmp_var = str_replace("Aug", "08", $tmp_var);
		
		//Vervang de waarde Sep voor 09
		$tmp_var = str_replace("Sep", "09", $tmp_var);
		
		//Vervang de waarde Oct voor 10
		$tmp_var = str_replace("Oct", "10", $tmp_var);
		
		//Vervang de waarde Nov voor 11
		$tmp_var = str_replace("Nov", "11", $tmp_var);
		
		//Vervang de waarde Dec voor 12
		$tmp_var = str_replace("Dec", "12", $tmp_var);
		
		//Vervang spatie door -
		$tmp_var = str_replace(" ", "-", $tmp_var);
		
		//Return het en maar van +300 etc. NIKS
		return(str_replace(" +0300", "", str_replace(" +0200", "", str_replace(" +0100", "", $tmp_var))));
	}
	
	function strip_time_only($tmp_var){
		//Pas de datum aan naar het juiste formaat
		$tmp_var = strip_date_into($tmp_var);
		
		//Pak alleen de tijd
		$tmp_var = substr($tmp_var, -8, 8);
		
		//Strip de tijd
		return(str_replace(" +0300", "", str_replace(" +0200", "", str_replace(" +0100", "", $tmp_var))));
	}

	//Brandweer
		//Maak een nieuwe connectie aan
		$con = mysql_connect($DB_host,$DB_user,$DB_password);
		
		//Kijk of de connectie werkt
		if (!$con){
			//Connectie werkt niet geef dan een melding en stop
			die('Could not connect: ' . mysql_error());
		}

		//Selecteer de database
		mysql_select_db($DB_database, $con);

		//Selecteer de brandweer meldingen
		$result = mysql_query("SELECT `meldingen`.`link` FROM `meldingen` WHERE `meldingen`.`type` = 'brandweer'");
		
		//Maak een array aan met alle links
		$brandweer_links = array();
		while($row = mysql_fetch_array($result)){
			array_push($brandweer_links, $row['link']);
		}

		//Verwerk nieuwe brandweer meldingen
		$doc = new DOMDocument();
		//$doc->load('http://feeds.livep2000.nl/geo/index.php?d=1000');<-- IS DE OUDE LINK
		$doc->load('http://feeds.livep2000.nl/?d=1');
		
		//Voor elke item in de feed
		foreach ($doc->getElementsByTagName('item') as $node) {
			
			//Haal de link op
			$link = $node->getElementsByTagName('link')->item(0)->nodeValue;
			
			//Kijken of de link nog niet aanwezig is in de database en dus nog niet bestaat
			if(!in_array($link, $brandweer_links) and $node->getElementsByTagName('lat')->item(0)->nodeValue != null and $node->getElementsByTagName('lat')->item(0)->nodeValue != "" and !empty($node->getElementsByTagName('lat')->item(0)->nodeValue)){
			
				//Kijken of project al bestaat
					$result = mysql_query("SELECT `projects`.`id` FROM `projects` WHERE `projects`.`longitutde` = '".$node->getElementsByTagName('long')->item(0)->nodeValue."' AND `projects`.`latitude` = '".$node->getElementsByTagName('lat')->item(0)->nodeValue."'");

					$bestaat = false;
					//Kijken of het project al voorkomt
					while($row = mysql_fetch_array($result)){
						$bestaat = true;
					}
					
					//Als het project nog niet bestaat voeg dan een nieuw project toe
					if($bestaat == false){

						mysql_query("INSERT INTO `projects` (`titel`,`date`,`latitude`,`longitutde`,`time`) VALUES ('".strip_tags_into($node->getElementsByTagName('title')->item(0)->nodeValue)."','".strip_date_only($node->getElementsByTagName('pubDate')->item(0)->nodeValue)."','".$node->getElementsByTagName('lat')->item(0)->nodeValue."','".$node->getElementsByTagName('long')->item(0)->nodeValue."','".strip_time_only($node->getElementsByTagName('pubDate')->item(0)->nodeValue)."');");
					}

				//Melding toevoegen
				mysql_query("INSERT INTO `meldingen` (`title`,`message`,`date`,`latitude`,`longitutde`,`link`,`type`) VALUES ('".strip_tags_into($node->getElementsByTagName('title')->item(0)->nodeValue)."','".strip_tags_into($node->getElementsByTagName('description')->item(0)->nodeValue)."','".strip_date_into($node->getElementsByTagName('pubDate')->item(0)->nodeValue)."','".$node->getElementsByTagName('lat')->item(0)->nodeValue."','".$node->getElementsByTagName('long')->item(0)->nodeValue."','".$node->getElementsByTagName('link')->item(0)->nodeValue."','brandweer');");

				//Melding toevoegen aan de lijst met toegevoegde meldingen
				print "<tr><td>".$node->getElementsByTagName('title')->item(0)->nodeValue."</td><td>Brandweer</td></tr>";
			
			}
		}
		
	//Politie
		//Get all politie meldingen
		$result = mysql_query("SELECT `meldingen`.`link` FROM `meldingen` WHERE `meldingen`.`type` = 'politie'");
		
		$politie_links = array();
		while($row = mysql_fetch_array($result)){
			array_push($politie_links, $row['link']);
		}

		//Verwerk nieuwe politie meldingen
		$doc = new DOMDocument();
		//$doc->load('http://feeds.livep2000.nl/geo/index.php?d=0010');<-- OUDE LINK
		$doc->load('http://feeds.livep2000.nl/?d=1');
		foreach ($doc->getElementsByTagName('item') as $node) {
			//Kijken of de melding al bestaat
			$link = $node->getElementsByTagName('link')->item(0)->nodeValue;
			if(!in_array($link, $politie_links) and $node->getElementsByTagName('lat')->item(0)->nodeValue != null and $node->getElementsByTagName('lat')->item(0)->nodeValue != "" and !empty($node->getElementsByTagName('lat')->item(0)->nodeValue)){
			
				//Kijken of project al bestaat
					$result = mysql_query("SELECT `projects`.`id` FROM `projects` WHERE `projects`.`longitutde` = '".$node->getElementsByTagName('long')->item(0)->nodeValue."' AND `projects`.`latitude` = '".$node->getElementsByTagName('lat')->item(0)->nodeValue."'");
					$bestaat = false;
					while($row = mysql_fetch_array($result)){
						$bestaat = true;
					}
					if($bestaat == false){
						mysql_query("INSERT INTO `projects` (`titel`,`date`,`latitude`,`longitutde`,`time`) VALUES ('".strip_tags_into($node->getElementsByTagName('title')->item(0)->nodeValue)."','".strip_date_only($node->getElementsByTagName('pubDate')->item(0)->nodeValue)."','".$node->getElementsByTagName('lat')->item(0)->nodeValue."','".$node->getElementsByTagName('long')->item(0)->nodeValue."','".strip_time_only($node->getElementsByTagName('pubDate')->item(0)->nodeValue)."');") or die ("ERROR op politie project toevoeger!".mysql_error());
					}

				mysql_query("INSERT INTO `meldingen` (`title`,`message`,`date`,`latitude`,`longitutde`,`link`,`type`) VALUES ('".strip_tags_into($node->getElementsByTagName('title')->item(0)->nodeValue)."','".strip_tags_into($node->getElementsByTagName('description')->item(0)->nodeValue)."','".strip_date_into($node->getElementsByTagName('pubDate')->item(0)->nodeValue)."','".$node->getElementsByTagName('lat')->item(0)->nodeValue."','".$node->getElementsByTagName('long')->item(0)->nodeValue."','".$node->getElementsByTagName('link')->item(0)->nodeValue."','politie');");

				//Melding toevoegen aan de lijst met toegevoegde meldingen
				print "<tr><td>".$node->getElementsByTagName('title')->item(0)->nodeValue."</td><td>Politie</td></tr>";
			
			}
		}
		
	//Ambulance
		//Get all Ambulance meldingen
		$result = mysql_query("SELECT `meldingen`.`link` FROM `meldingen` WHERE `meldingen`.`type` = 'ambulance'");
		
		$ambulance_links = array();
		while($row = mysql_fetch_array($result)){
			array_push($ambulance_links, $row['link']);
		}

		//Verwerk nieuwe politie meldingen
		$doc = new DOMDocument();
		//$doc->load('http://feeds.livep2000.nl/geo/index.php?d=0100');<-- OUDE LINK
		$doc->load('http://feeds.livep2000.nl/?d=3');
		foreach ($doc->getElementsByTagName('item') as $node) {
			//Kijken of de melding al bestaat
			$link = $node->getElementsByTagName('link')->item(0)->nodeValue;
			if(!in_array($link, $ambulance_links) and $node->getElementsByTagName('lat')->item(0)->nodeValue != null and $node->getElementsByTagName('lat')->item(0)->nodeValue != "" and !empty($node->getElementsByTagName('lat')->item(0)->nodeValue)){
			
				//Kijken of project al bestaat
					$result = mysql_query("SELECT `projects`.`id` FROM `projects` WHERE `projects`.`longitutde` = '".$node->getElementsByTagName('long')->item(0)->nodeValue."' AND `projects`.`latitude` = '".$node->getElementsByTagName('lat')->item(0)->nodeValue."'");
					$bestaat = false;
					while($row = mysql_fetch_array($result)){
						$bestaat = true;
					}
					if($bestaat == false){
						mysql_query("INSERT INTO `projects` (`titel`,`date`,`latitude`,`longitutde`,`time`) VALUES ('".strip_tags_into($node->getElementsByTagName('title')->item(0)->nodeValue)."','".strip_date_only($node->getElementsByTagName('pubDate')->item(0)->nodeValue)."','".$node->getElementsByTagName('lat')->item(0)->nodeValue."','".$node->getElementsByTagName('long')->item(0)->nodeValue."','".strip_time_only($node->getElementsByTagName('pubDate')->item(0)->nodeValue)."');") or die("Error op de ambulance project toevoeger!".mysql_error());
					}

				mysql_query("INSERT INTO `meldingen` (`title`, `message`, `date`,`latitude`,`longitutde`,`link`,`type`) VALUES ('".strip_tags_into($node->getElementsByTagName('title')->item(0)->nodeValue)."','".strip_tags_into($node->getElementsByTagName('description')->item(0)->nodeValue)."','".strip_date_into($node->getElementsByTagName('pubDate')->item(0)->nodeValue)."','".$node->getElementsByTagName('lat')->item(0)->nodeValue."','".$node->getElementsByTagName('long')->item(0)->nodeValue."','".$node->getElementsByTagName('link')->item(0)->nodeValue."','ambulance');") or die ("Error op ambulance melding toevoeger!".mysql_error());

			}
		}
		mysql_close($con);
?>
		</table>
	</body>
</html>