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
    if(isset($_POST['id']))
    {
    	$id = e($_POST['id']);
		$idCli = $_SESSION['id'];
		$nbA = count($_SESSION['panier'.$idCli]);
		
		$ok = false;
		$indice = 0;
		for($i = 0;$i < $nbA && !$ok;$i++)
		{
			if($_SESSION['panier'.$idCli][$i] == $id)
			{
				$ok = true;
				$indice = $i;
			}
		}
		if($ok)
		{
			for($i = $indice;$i < $nbA-1;$i++)
			{
				$_SESSION['panier'.$idCli][$i] = $_SESSION['panier'.$idCli][($i+1)];
				$_SESSION['qte'.$idCli][$i] = $_SESSION['qte'.$idCli][($i+1)];
			}
			unset($_SESSION['qte'.$idCli][($nbA-1)]);
			unset($_SESSION['panier'.$idCli][($nbA-1)]);
			echo "<ok>OK</ok>";	
		}else
			echo "<ok>NOK</ok>";
			
		
	}else
		echo "<ok>NOK</ok>";
    echo "</news>";
?>