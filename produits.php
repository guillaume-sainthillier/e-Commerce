<?php 

	$fic = 'includes/header.php';
	if(file_exists($fic))
		include($fic);
	
	if(isset($_POST['cat']))
		$cat = s($_POST['cat']);
	else
		$cat = 0;
	
		
	if(isset($_POST['prix2']) && (isset($_POST['prix2']) && ($_POST['prix2']> 0 or $_POST['prix2'] <= 2)))
		$prix = s($_POST['prix2']);
	else
		$prix = 0;
		
	$tabPrix = array(1000,2000);
	$tabReq = array(" >= 0 "," <= ".$tabPrix[0],"BETWEEN ".$tabPrix[0]." AND ".$tabPrix[1]." "," >= ".$tabPrix[1]);
	
	if($cat != 0)
		$suffixe = "idCat = '".$cat."' ";
	else
		$suffixe = " 1=1 ";
	
	if(isset($_POST['dispo']))
		$dispo = e($_POST['dispo']);
	else
		$dispo = 0;
		
	if($dispo == 1)
		$suffixe2 = "dispoProd = 'oui' ";
	elseif($dispo == 2)
		$suffixe2 = "dispoProd = 'non' ";
	else
		$suffixe2 = "1=1";
		
	if(isset($_POST['selection']))
		$selChoix = e($_POST['selection']);
	else
		$selChoix = 0;
		
	if($selChoix == 1)
		$suffixe3 = " promProd='oui' ";
		elseif($selChoix == 2)
			$suffixe3 = " nouvProd='oui' ";
			elseif($selChoix == 3)
				$suffixe3 = " selProd='oui' ";
				else
					$suffixe3 = " 1=1 ";
	
	

	$res = query("select titreProd,detailProd,nouvProd,promProd,selProd,
					prixhtProd,prixhtPromProd,tauxtvaProd,idProd,imageProd from produit WHERE ".$suffixe." 
					AND ".$suffixe2."
					AND ".$suffixe3."
					AND (prixhtPromProd +((prixhtPromProd * tauxtvaProd)/100)) ".$tabReq[$prix]." order by prixhtPromProd ;");
	
	
	
		echo "<form action='produits.php' method='post' id='pref'><br />";
		
			echo "<div style='float:left;'>Trier par catégorie<br />";
				echo "<select name='cat'>";		
					echo "<option value='0'>Toutes les catégories</option>";
					$res2 = query("select idCat,nomCat from categorie order by idCat asc;");
					while($row2 = fetch($res2))
					{
						echo "<option value=".$row2[0];
						if($cat == $row2[0])
							echo " selected";				
						
						echo ">".$row2[1]."</option>";
					}
				echo "</select>";
			echo"</div>";
			
			echo "<div style='float:left;margin-left:50px;'>";
				$tabDispo = array("Toute disponibilité","Disponible","Rupture");
				for($i = 0; $i < count($tabDispo);$i++)
				{
					echo "<input type='radio' name='dispo' id='r".$i."' value='".$i."' ";
					if($i == $dispo)
						echo " checked ";
					echo "/><label for='r".$i."' >".$tabDispo[$i]."</label><br />";
				}
			echo "</div>";		
			
			$tabNomsPrix = array("Tous les prix","En dessous de ".$tabPrix[0]."€","Entre ".$tabPrix[0]." et ".$tabPrix[1]."€","Au dessus de ".$tabPrix[1]."€");
			echo "<div style='float:left;margin-left:40px;'>Trier par prix <br />";
				echo "<select name='prix'>";	
				for($i = 0;$i < count($tabNomsPrix);$i++)
				{
					echo "<option value='".$i."' ";
					if($i == $prix)
						echo " selected ";
					echo ">".$tabNomsPrix[$i]."</option>";
				}			
				echo "</select>";
			echo "</div>";
			
			echo "<div style='float:left;margin-left:20px;'>";
			$tabSelection = array("Tout produit","Produits en promotion","Produits nouveaux","Coups de coeur");
			for($i = 0;$i < count($tabSelection);$i++)
			{
				echo "<input type='radio' name='selection' id='c".$i."' value='".$i."' ";
				if($i == $selChoix)
					echo " checked ";
				echo "/><label for='c".$i."' >".$tabSelection[$i]."</label><br />";
			}
			echo "</div>";
		
			echo "<div style='margin-top:80px;'><input type='submit' method='post' value='Afficher'></div>";
			echo "</form>";
			echo "<div><br />
					<div id='info' style='display:none;'>
					</div><br />";
				
			if(mysql_num_rows($res) == 0)			
				echo "Aucun produit trouvé.<br />";
			else
			{
				echo "<table style='width:100%;'>
					<tr style='text-align:center;'>
						<th>Titre</th>
						<th>Détail</th>
						<th>Image</th>
						<th>Prix</th>
						<th>Panier</th>";
						if($_SESSION['admin'] > 1)
							echo "<th>Admin</th>";
					echo "</tr>";
			
				while($row = fetch($res))
				{		
					$row = s_tab($row);
					echo "<tr style='text-align:center;'>";							
						echo "<td>"; //Titre
							echo "<a href='detailprod.php?i=".$row[8]."' >".$row[0]."</a>";
						echo "</td>";
						
						echo "<td>"; //Détail
							echo $row[1];
						echo "</td>";
						
						echo "<td>"; //Image
							$dossier = 'produits/';
							if(is_file($dossier.$row[9]) && file_exists($dossier.$row[9]) && is_file($dossier.'o_'.$row[9]) && file_exists($dossier.'o_'.$row[9]))
								echo "<a href='detailprod.php?i=".$row[8]."' ><img src='produits/".$row[9]."' width='50' height='50' alt='Image produit' /></a>";
							else
								echo '-';
						echo "</td>";
						
						$str = "<td>"; //Prix
							$prix = round(($row[6] + $row[6]*$row[7]/100),2);
							$prixSansPromo = $row[5] + $row[5]*$row[7]/100;
							if($row[3] == "oui" && $prix < $prixSansPromo)							
								$str.= "<span style='text-decoration:line-through;'>".$prixSansPromo."</span> ";
							
							$str .= $prix.'€';						
							echo $str;
						echo "</td>";
						
						echo "<td>"; //Panier
							echo "<img title='Ajouter au panier' onclick='addPanier(\"".$row[8]."\");' src ='images/panier.png' alt='Ajouter' />";
						echo "</td>";
						
						if($_SESSION['admin'] > 1) //Partie admin
						{
							echo "<td>"; //Modif&Supprimer
								echo "<a href='modifproduits.php?i=".$row[8]."' title=''><img src ='images/modif.png' alt='Modifier' /></a>";
							
								echo "<img onclick = 'supProd(\"".$row[8]."\");' src ='images/supprimer.png' alt='Supprimer' />";
							echo "</td>";
						}
					echo "</tr>";
				}
				echo "</table>";
			}
			echo "<br /></div>";
	

	$fic = 'includes/footer.php';
	if(file_exists($fic))
		include('includes/footer.php');
?>  