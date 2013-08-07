<?php
		$res = query("select count(*) AS nb from connections;");
		$row = mysql_fetch_array($res);
		$nbConnect = $row['nb'];
		
	//	$row = query("select idConnec from connections WHERE ip = '".$_SERVER["REMOTE_ADDR"]."' 
						//		AND date  > FROM_UNIXTIME(unix_timestamp() - 3600)",1);
								
		$row = query("select * from connections WHERE ip = '".$_SERVER["REMOTE_ADDR"]."' 
								AND UNIX_TIMESTAMP()- UNIX_TIMESTAMP(date) < 3600 ");
		if(!($row = fetch($row)))
		{
			query("INSERT INTO connections(ip,date) VALUES ('".$_SERVER["REMOTE_ADDR"]."',FROM_UNIXTIME(UNIX_TIMESTAMP() ))");
		}
		
?>