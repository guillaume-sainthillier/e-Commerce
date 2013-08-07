<?php

$fic = '../includes/session.php';
if(file_exists($fic))
	include($fic);
$fic = '../includes/utils.php';
 if(file_exists($fic))
	include($fic);
	
 header("Content-type: text/xml");
echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';   
    echo "<news>";
   
		$res = query("select SUM(totalCom) from commande;");
		if($row = fetch($res))
		{
			$montant = $row[0];
			$com = round(($montant*10)/100,2);
			
			echo "<montant>".$montant."</montant>";
			echo "<com>".$com."</com>";
		}
    echo "</news>";
?>