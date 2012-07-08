<?php

	$DB_host = "localhost";
	$DB_database = "ipmedt4";
	$DB_user = "school";
	$DB_password = "ipmedt4";
if(isset($_GET["jsoncallback"])){
	echo $_GET["jsoncallback"];
}else{
	?>
<html>
	<head>
		<meta http-equiv="refresh" content="60" />
	</head>
	<body>
	<?php
}
?>({
        "name":"Meldingen",
        "items":[
<?php
	if(3 == 3){
		//Get all politie meldingen
			$con = mysql_connect($DB_host,$DB_user,$DB_password);
			if (!$con){
				die('Could not connect: ' . mysql_error());
			}

			mysql_select_db($DB_database, $con);

			/*$result = mysql_query("SELECT * 
	FROM  `meldingen` 
	WHERE `type` = 'ambulance'
	ORDER BY  `meldingen`.`id` DESC 
	LIMIT 0 , 30");*/
			$result = mysql_query("SELECT `meldingen`.*, `projects`.id as project
				FROM  `meldingen`, `projects`
				WHERE `meldingen`.`type` = 'ambulance'
				AND	`projects`.`latitude` = `meldingen`.`latitude`
				AND `projects`.`longitutde` = `meldingen`.`longitutde`
				ORDER BY  `meldingen`.`id` DESC 
				LIMIT 0 , 30");
			
			$count = 0;
			
			while($item = mysql_fetch_object($result)){
				$data[] = $item;
			}
			
			foreach($data as $henk){
				$count++;
				print "
				{
					\"title\":\"".$henk->title."\",
					\"message\":\"".str_replace("\n", "", $henk->message)."\",
					\"date\":\"".$henk->date."\",
					\"latitude\":\"".$henk->latitude."\",
					\"longitutde\":\"".$henk->longitutde."\",
					\"id\":\"".$henk->project."\"
				}";
				if($count < count($data)){
					print ",";
				}
			}

			mysql_close($con);
		}

?>
		] 
})<?php
if(!isset($_GET["jsoncallback"])){
	?>
	</body>
</html>
	<?php
}
?>