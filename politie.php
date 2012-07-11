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

			$result = mysql_query("SELECT `meldingen`.*, `projects`.id as project
	FROM  `meldingen`, `projects`
	WHERE `meldingen`.`type` = 'politie'
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
		if(1 == 3){
?>
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
        }<?php
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