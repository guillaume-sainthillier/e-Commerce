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
    	echo "<connec>".$_SESSION['id']."</connec>";
    	echo "<admin>".intval($_SESSION['admin'])."</admin>";
    if(isset($_POST['id']))
    {
    	$idCmd = e($_POST['id']);
		$id = $_SESSION['id'];
		
		$res = query("select * from commande WHERE idCom = '".$idCmd."' AND idClient = '".$id."' ;");
		if(fetch($res))
		{
			$res = query("select p.titreProd,dc.qteDc,dc.prixDc,(dc.prixDc/dc.qtedc) as 'prix',imageProd from detail_commande dc,commande c,client cli ,produit p
							WHERE cli.idClient = c.idClient AND
							dc.idCom = c.idCom AND
							p.idProd = dc.idProd AND
							c.idClient = '".$id."' AND
							c.idCom = '".$idCmd."' ;");	
			echo "<nb>".mysql_num_rows($res)."</nb>";
			
			while($row = fetch($res))
			{
				echo "<reponse>				
						<titre>".xml($row[0])."</titre>
						<qte>".xml($row[1])."</qte>
						<prixU>".xml(round($row[2],2).'€')."</prixU>
						<prix>".xml(round($row[3],2).'€')."</prix>";
						$fic = "../produits/".$row[4];
						if(is_file($fic) && file_exists($fic))
							echo "<img>".xml($row[4])."</img>";
						else
							echo "<img>NOK</img>";
					echo "</reponse>";
			}
		}
	}
    echo "</news>";
?>