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
		
		$res = query("select dispoProd from produit WHERE idProd = '".$id."' ");
		if($row = fetch($res))
		{
			if($row[0] == "non")
				echo "<ok>OK3</ok>";
			else
			{
				$nbA = count($_SESSION['panier'.$idCli]);
				$ok = false;
				for($i = 0;$i < $nbA && !$ok;$i++)
					if($_SESSION['panier'.$idCli][$i] == $id)
						$ok = true;
				if(!$ok)
				{
					$_SESSION['panier'.$idCli][$nbA] = $id;
					$_SESSION['qte'.$idCli][$nbA] = 1;
					echo "<ok>OK</ok>";	
				}else
					echo "<ok>OK2</ok>";
			}
		}
			
	}else
		echo "<ok>NOK</ok>";
    echo "</news>";
?>