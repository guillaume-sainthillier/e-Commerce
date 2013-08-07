<?php 

	$fic = 'includes/header.php';
	if(file_exists($fic))
		include($fic);
		
	
	
		echo "<br /><br />";
		$id = $_SESSION['id'];

		$nbArticles = count($_SESSION['panier'.$id]);

		$ok = true;
		if( $nbArticles == 0)
		{
		
			$ok = false;
			if(isset($_POST['import']))
			{
				$ok  = true;
				$_SESSION['panier'.$id] = $_SESSION['panier0'];
				$_SESSION['qte'.$id] = $_SESSION['qte0'];
				$nbArticles = count($_SESSION['panier'.$id]);
				unset($_SESSION['panier0']);
				unset($_SESSION['qte0']);
				$_SESSION['qte0'] = array();
				$_SESSION['panier0'] = array();
			}else
			{
				aide("Vous n'avez aucun produit dans votre panier.<br />");
				if($id != 0)
				{
					$nbArticle2  = count($_SESSION['panier0']);
					if($nbArticle2 > 0)
					{
						echo "<br /><form action='panier.php' method='POST'><input type='hidden' name='import' value='1' />Cependant, vous avez mis des articles dans votre panier sans être connecté.<br />";
						echo "Si vous souhaitez importer le panier depuis la session non connectée, cliquez sur le bouton Importer son panier<br />";
						echo "<input type='submit' value='Importer' /></form>";
					}
				}
			}
		}

		if($ok) //ok vaut false lorsque le client n'a pas d'articles dans le caddie et n'a pas importé ses articles de la session générale
		{			
			echo "<form action='commander.php' id='form' method='POST'>"; //le action peut être changé par JS
			echo "<input type='submit' value='Commander' />";
			echo "<input type='hidden' id='sup' name='sup' value='0'/>";
			echo "<table><tr style='text-align:center;'><th>Image</th><th>Article</th><th>Prix</th><th>Qté</th><th>Action</th></tr>";
			
			$tabQte = array();
			if(isset($_POST['sup']))
				$sup = $_POST['sup'];
			else
				$sup = 0;
			for($i = 0;$i < $nbArticles;$i++)
			{	
				if(isset($_POST['qte'.$i]) && $sup == 0)				
					$_SESSION['qte'.$id][$i] = $_POST['qte'.$i];
			
				$tabQte[$i] = $_SESSION['qte'.$id][$i];
				$idProd = $_SESSION['panier'.$id][$i];
				$res = query("select titreProd,detailProd,imageProd,prixhtPromProd,tauxtvaProd from produit 
								WHERE idProd = '".$idProd."' ;");
				if($row = fetch($res))
				{					
					echo "<tr>";
						echo "<td style='text-align:center;'>";	 //Image				
							$dossier = 'produits/';
							if(is_file($dossier.$row[2]) 
								&& file_exists($dossier.$row[2])								
								&& is_file($dossier.'o_'.$row[2])
								&& file_exists($dossier.'o_'.$row[2]))
								echo '<a class="lightbox" href="produits/o_'.$row[2].'" title="'.s($row[0]).'"><img src="produits/'.$row[2].'" width="50" height="50" alt="Image produit" /></a>';
							else
								echo '-';
						echo "</td>";
						
						echo "<td>"; //Article							
							$str = "<a target='_blank' href='detailprod.php?i=".$_SESSION['panier'.$id][$i]."' >".$row[0].': '.$row[1]."</a>";
						$str .= "</td>
							<td>"; //Prix
								$prix = $row[3] + $row[3]*$row[4]/100;	
								$str .= $prix.'€</td>';
					echo $str."</td>
								<td>"; //Qté
									echo"<input type='text' name='qte".$i."' value='".$tabQte[$i]."' maxlength='4' size='3' />";
								echo"</td>";
								
								echo "<td style='text-align:center;' >";//Action
									echo "<img title='Supprimer du panier' onclick='supPanier(\"".$_SESSION['panier'.$id][$i]."\");' src ='images/supprimer.png' alt='Supprimer' />";
								echo "</td>";
					echo "</tr>";
				}					
			}
			echo "</table>";
			echo "</form>";
		}
	$fic = 'includes/footer.php';
	if(file_exists($fic))
		include('includes/footer.php');
?>  