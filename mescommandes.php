<?php 

	$fic = 'includes/header.php';
	if(file_exists($fic))
		include($fic);
		
	
	if($_SESSION['id'] == 0)
	{ 
		erreur("Vous devez vous <a href='identification.php' >connecter</a> <br />");
	}else
	{
		echo "<br /><br />";
		$id = $_SESSION['id'];
		$res = query("select idCom,dateCom,dateEnvoiCom,totalCom,fin,
							 refPaiement from commande WHERE idClient = '".$id."' order by dateCom desc,idCom desc;");
		if(mysql_num_rows($res) == 0)
		{
			echo "Vous n'avez passé aucune commande.";
		}else
		{
			
			$res2 = query("select concat(nomClient,' ',prenomClient) from client WHERE idClient = '".$id."' ;");
			if($row = fetch($res2))
				echo s($row[0]);
			echo ", client n°".$id."<br /><br />";
			for($i= 0;$row = fetch($res);$i++)
			{
				$row = s_tab($row);
				
				echo "<div><input type='hidden' id='etat".$row[0]."' value='0' />Commande n°".$row[0]." du ".datetostring($row[1]);
				if($row[3] != null && $row[3] != "")
					echo ", envoyée le ".datetostring($row[2]);
				echo ".<span onclick='afficherCmd(\"".$row[0]."\");' class='lien'>";
				info();
				echo "</span><br />Référence <b>".$row[5]."</b>. Montant : ".$row[3]."€</div><div style='display: none;' id='cmd".$row[0]."' ></div><br />";
			}
		}
	}
	
	$fic = 'includes/footer.php';
	if(file_exists($fic))
		include('includes/footer.php');
?>  