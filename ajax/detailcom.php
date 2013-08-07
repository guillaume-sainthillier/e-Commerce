<?php

$fic = '../includes/session.php';
if(file_exists($fic))
	include($fic);
$fic = '../includes/utils.php';
 if(file_exists($fic))
	include($fic);
	
//é
	
 header("Content-type: text/xml");
echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';   
    echo "<news>";
    	echo "<connec>".$_SESSION['id']."</connec>";
    	echo "<admin>".intval($_SESSION['admin'])."</admin>";
    if(isset($_POST['date']))
    {
    	$date = e($_POST['date']);
    	
    	$res = query("select idCom,client.idClient,idPaiement,totalCom,concat(client.nomClient,' ',client.prenomClient) from commande,client
						WHERE commande.idClient = client.idClient AND dateCom = '".$date."' ;");
      		
		while($row = fetch($res))
		{
			echo "<reponse>
				<idcom>".xml($row[0])."</idcom>
				<idclient>".xml($row[1])."</idclient>
				<idpaiement>".($row[2])."</idpaiement>
				<totalcom>".xml($row[3])."</totalcom>
				<nomclient>".xml($row[4])."</nomclient>
			</reponse>";
		}
	}
    echo "</news>";
?>