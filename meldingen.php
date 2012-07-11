<?php
$DB_host = "localhost";
	$DB_database = "ipmedt4";
	$DB_user = "school";
	$DB_password = "ipmedt4";
echo $_GET["jsoncallback"];
?>({
        "name":"Meldingen",
        "items":[
<?php
	if(3 == 3){
		//Get meldingen
			$con = mysql_connect($DB_host,$DB_user,$DB_password);
			if (!$con){
				die('Could not connect: ' . mysql_error());
			}

			mysql_select_db($DB_database, $con);

			$result = mysql_query("SELECT * 
	FROM  `projects` 
	ORDER BY  `projects`.`id` DESC 
	LIMIT 0 , 30") or die ("Probleem met ophalen:".mysql_error());
			
			$count = 0;
			
			while($item = mysql_fetch_object($result)){
				$data[] = $item;
			}
			
			foreach($data as $henk){
				$count++;
				print "
				{
					\"title\":\"".str_replace("\n", "", $henk->titel)."\",
					\"date\":\"".$henk->date."\",
					\"latitude\":\"".$henk->latitude."\",
					\"longitutde\":\"".$henk->longitutde."\",
					\"id\":\"".$henk->id."\"
				}";
				if($count < count($data)){
					print ",";
				}
				
			}

			mysql_close($con);
		}
?>
		] 
})