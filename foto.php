<?php
	$Succes = "";
	
	$DB_host = "localhost";
	$DB_database = "ipmedt4";
	$DB_user = "school";
	$DB_password = "ipmedt4";
	
	mysql_connect($DB_host,$DB_user,$DB_password);
	mysql_select_db($DB_database);
	
		$content = "";
			$fileSize = "";
			$fileType = "";
			
			if($_FILES['file']['size'] > 0){
				$fileName = $_FILES['file']['name'];
				$tmpName  = $url;
				$fileSize = $_FILES['file']['size'];
				$fileType = $_FILES['file']['type'];

				$fp = fopen($tmpName, 'r');
					$content = fread($fp, filesize($tmpName));
					$content = addslashes($content);
				fclose($fp);

				if(!get_magic_quotes_gpc())
				{
					$fileName = addslashes($fileName);
				}
				
			}
		
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
		move_uploaded_file($_FILES["file"]["tmp_name"], "".$new_image_name);
		
	//}

	echo $_GET["jsoncallback"] . "({\"Succes\": \"" . $Succes . "\"})";
?>