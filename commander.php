<?php 

	$fic = 'includes/header.php';
	if(file_exists($fic))
		include($fic);
		
	
	$id = $_SESSION['id'];
	$nbA = count($_SESSION['panier'.$id]);
	
	for($i = 0;$i < $nbA;$i++)
	{
		if(isset($_POST['qte'.$i]))
		{
			if(ctype_digit($_POST['qte'.$i]))
				$_SESSION['qte'.$id][$i] = $_POST['qte'.$i];
			else
				$_SESSION['qte'.$id][$i] = 1;
		}
	}
	if($_SESSION['id'] == 0)
	{ 
		echo "Vous n'êtes pas connecté, vous pouvez le faire <a href='identification.php' >ici </a> <br />";
	}else
	{
		if($nbA == 0)
		{
			echo "Vous n'avez aucun article à commander dans votre panier <br />";
		}else
		{
			//ADDDATE(NOW(), INTERVAL 7 DAY)
			if(isset($_POST['selP']))
				$idP = $_POST['selP'];
			else
				$idP = 0;
			
			$totalPrix = 0;
			$erreur = 1;
			
			if(isset($_POST['ref']))
			{
				if(!ctype_digit($_POST['ref']))
					erreur("La référence de Paiement n'est pas un nombre");
				elseif(trim($_POST['ref']) == "")
					erreur("La référence de paiement n'est pas remplie");
				else
					$erreur = 0;
			}
			// SELECT MAX(CAST(SUBSTRING_INDEX(delaiProd,' ',1) AS SIGNED)) from produit
			if($erreur == 0)
			{
				
				$res = query("SELECT MAX(CAST(SUBSTRING_INDEX(delaiProd,' ',1) AS SIGNED)) from produit");
				if($row = fetch($res))
					$max = $row[0];
					
				$res = query("INSERT INTO commande(dateCom,idClient,idPaiement,dateEnvoiCom,refPaiement,
													totalCom,fin)
											VALUES(NOW(),'".$id."','".e($_POST['selP'])."',ADDDATE(NOW(), INTERVAL ".$max." DAY),'".e($_POST['ref'])."',
													'".e($_POST['tot'])."','non') ;");
													
				$idCom = last_num();
				for($i = 0;$i < $nbA ;$i++)
				{
					if(!isset($_POST['totprix'.$i]))
						$_POST['totprix'.$i] = 1;
					query("INSERT INTO detail_commande(idProd,idCom,qteDc,prixDc)
							VALUES('".e($_SESSION['panier'.$id][$i])."','".$idCom."','".e($_SESSION['qte'.$id][$i])."','".e($_POST['totprix'.$i])."') ;");
				}			
				
													
				//maj_proposer($idCom);					
				unset($_SESSION['panier'.$id]);
				unset($_SESSION['qte'.$id]);
				echo "<br /><br />Vous avez été enregistré avec le n° client suivant :".$id." <br />
									Votre commande a bien été enregistrée avec le n° commande  suivant : ".$idCom." <br />";
									
				echo "Vous pouvez consulter toutes vos commandes <a href='mescommandes.php' >ici</a>";
			}else
			{
				echo "<form action='commander.php' method='POST' >";
				echo "<table><tr style='text-align:center;'><th>Article</th><th>Prix</th><th>Qté</th><th>TOTAL</th></tr><br />";
				echo "Récapitulatif <br />";
				for($i = 0; $i < $nbA;$i++)
				{
						$tabQte[$i] = $_SESSION['qte'.$id][$i];
						$idProd = $_SESSION['panier'.$id][$i];
						$res = query("select titreProd,detailProd,imageProd,prixhtPromProd,tauxtvaProd from produit 
										WHERE idProd = '".$idProd."' ;");
										
						
						if($row = fetch($res))
						{					
							echo "<tr>";												
								echo "<td>"; //Article							
									$str = "<a target='_blank' href='detailprod.php?i=".$_SESSION['panier'.$id][$i]."' >".$row[0].': '.$row[1]."</a>";
								$str .= "</td>
									<td>"; //Prix
										$prix = $row[3] + $row[3]*$row[4]/100;	
										$str .= $prix.'€</td>';
							echo $str."</td>
										<td style='text-align: center;'>"; //Qté
											echo $tabQte[$i];
										echo"</td>";
										
										echo "<td style='text-align:center;' >";//TOTAL
											echo '<input type="hidden" name="totprix'.$i.'" value="'.($prix*$tabQte[$i]).'" />'.$prix*$tabQte[$i].'€';
											$totalPrix += $prix*$tabQte[$i];
										echo "</td>";
						
						}					
					
					
				}
				echo "<tr><td>TOTAL </td><td></td><td></td><td><input type='hidden' name='tot' value='".$totalPrix."' />".$totalPrix."€</td></tr>";
				echo "</table>";
				
				$tabPaiement = array('Chèque','Carte bleue');
				$tabIdPaiement = array('ch','ca');
				
				echo "<br />Paiement par <select name='selP'>";
				for($i = 0;$i < count($tabPaiement);$i++)
				{
					
					echo "<option value='".$tabIdPaiement[$i]."'";
						if($tabIdPaiement[$i] == $idP)
							echo " selected ";
					echo ">".$tabPaiement[$i]."</option>";
				}
				echo "</select> , Numéro <input class='input' type='text' name='ref' value=''/>";
				echo "<br /><br /><input type='submit' value='Commander' /></form>";
			}
		}
	}
	
	$fic = 'includes/footer.php';
	if(file_exists($fic))
		include('includes/footer.php');
?>  