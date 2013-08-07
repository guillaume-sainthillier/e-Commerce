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
    if(isset($_POST['id']))
    {
    	$idProd = e($_POST['id']);
		
		$res = query("select produit.idCat,titreProd,detailProd,imageProd,nouvProd,
							promProd,selProd,poidsProd,dispoProd,delaiProd,
							prixhtProd,prixhtPromProd,tauxtvaProd,categorie.nomCat from produit,categorie WHERE produit.idCat = categorie.idCat
							AND idProd = '".$idProd."';");
		
		if($row = fetch($res))
		{
			echo "<ok>OK</ok>";
			echo "<reponse>";
				echo "<titre>".xml($row[1])."</titre>";
				echo "<cat>".xml($row[13])."</cat>";
				echo "<detail>".xml($row[2])."</detail>";
				$prix = $row[11] + $row[11]*$row[12]/100;
				echo "<prix>".xml($prix)."</prix>";
			echo "</reponse>";
		}else
		{
			echo "<ok>NOK</ok>";
		}
	}
    echo "</news>";
?>