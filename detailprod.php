<?php 

	$fic = 'includes/header.php';
	if(file_exists($fic))
		include($fic);
		
	if(!isset($_GET['i']))
		echo "Aucun détail de produit demandé ! <br />";
	else
	{
		$id = e($_GET['i']);
		$res = query("select produit.idCat,titreProd,detailProd,imageProd,nouvProd,
							promProd,selProd,poidsProd,dispoProd,delaiProd,
							prixhtProd,prixhtPromProd,tauxtvaProd,categorie.nomCat from produit,categorie WHERE produit.idCat = categorie.idCat
							AND idProd = '".$id."';");
		if(mysql_num_rows($res) == 0)
		{
			echo "Ce produit n'existe pas ou a été supprimé !<br />";
		}else
		{
			$row = fetch($res);
			$row = s_tab($row);
			$dossier = 'produits/';

			echo "<br /><center>";
			if(is_file($dossier.$row[3]) && file_exists($dossier.$row[3]) && is_file($dossier.'o_'.$row[3]) && file_exists($dossier.'o_'.$row[3]))
						echo '<a class="lightbox" href="produits/o_'.$row[3].'" title="'.e($row[1]).'"><img src="produits/'.$row[3].'" width="60" height="60" alt="Image produit" /></a>';
					else
						echo '-';
			echo"</center><br />";
			if($_SESSION['admin'] > 1)
			{
				echo "<span style='color:red;'> Admin : </span><a href='modifproduits.php?i=".$id."' >Modifier le produit <img src ='images/modif.png' alt='Modifier' /></a><br />";
				echo "<span style='color:red;'> Admin : </span><a href='modifproposer.php?i=".$id."' >Modifier les propositions du produit <img src ='images/modif.png' alt='Modifier' /></a><br /><br />";
			}
			echo "<b>".$row[1]."</b> : ".$row[2]."<br />";
			$str = "À partir de ";
			$prix = $row[11] + $row[11]*$row[12]/100;
			$prixSansPromo = $row[10] + $row[10]*$row[12]/100;
			if($row[5] == "oui") //Promotion
			{
				$str.= "<span style='text-decoration:line-through;'>".$prixSansPromo."</span> ";
			}
			$str .= $prix." €.";
			echo $str."<br/>";
			echo "Catégorie : ".$row[13]."<br />";
			if($row[5] == "oui")//Promo
				promo();
				
			if($row[4] == "oui")//Nouveauté
				nouv();
					
			if($row[6] == "oui")
				coup_coeur();
			
			if($row[4] == "non" && $row[5] == "non" && $row[6] == "non")//Aucun
				echo "<span style='color:red;'>Exclusivité !</span>";
				
			echo "<br />Poids: ".$row[7]." kg<br />";
			echo "Disponibilité: ";
			if($row[8] == "oui")
				echo "<span style='color:green;'> En stock </span>";
			else
				echo "<span style='color:red;'> Rupture </span>";
				
			echo "<br />Livré sous <b>".$row[9]."</b>.<br />";
			if($row[8] == "oui")
				echo "Alors qu'attendez vous , <span class='lien' onclick='addPanier(\"".$id."\");'>ajoutez ce produit à votre panier !</span><img title='Ajouter au panier' onclick='addPanier(\"".$id."\");' src ='images/panier.png' alt='Ajouter' /></a>"; 
				echo "<br /><br /><div id='info' style='display:none;'></div>";
			echo "<br />".addCom($id);
			$res = query("select p.idProd, p.titreProd, c.nomCat,p.imageProd from produit p,categorie c
						WHERE
						c.idCat = p.idCat AND
						p.idProd IN (
							select idProd2 from proposer WHERE idProd1 = '".$id."'								
							order by nbFois desc) LIMIT 0,4;");
							
			$res = query("select idProd2 from proposer WHERE idProd1 = '".$id."' order by nbFois desc LIMIT 0,4;");
			$tabProd = array();
			for($i = 0;$row = fetch($res);$i++)
				$tabProd[$i] = $row[0];
				
			if(count($tabProd) > 0)
			{
				
				echo "<br /><fieldset style='width:90%;'><legend>Suggestions</legend>";
				
				echo "Les internautes fous ont aussi acheté :<br /><br />";
				echo"<table>";
				for($i = 0;$i < count($tabProd);$i++)
				{
					$res = query("select p.idProd, p.titreProd, c.nomCat,p.imageProd from produit p,categorie c
						WHERE
						idProd = '".$tabProd[$i]."' AND
						c.idCat = p.idCat
						");
					if($row = fetch($res))
					{
						$row = s_tab($row);
						if( ($i %2) == 0)
							echo "<tr style='border:2px solid;'>";
						echo "<td>"; //Panier
							echo "<img title='Ajouter au panier' onclick='addPanier(\"".$row[0]."\");' src ='images/panier.png' alt='Ajouter' />";
						echo "</td>";
						echo "<td>";//Image
							if(is_file($dossier.$row[3]) && file_exists($dossier.$row[3]))
							echo "<a href='detailprod.php?i=".$row[0]."' >".'<img src="produits/'.$row[3].'" width="60" height="60" alt="Image produit" /></a>';
						echo "</td>";
						if( ($i%2) == 0)
							echo "<td style='border-right:3px solid;'>";
						else
							echo "<td>";					
								echo "<a href='detailprod.php?i=".$row[0]."' >".$row[2].' : '.$row[1]."</a>";
							echo "</td>";
						
						if( ($i %2) == 1)
							echo "</tr>";
					}					
				}
				echo "</table></fieldset>";
			}	
		}
	}
	$fic = 'includes/footer.php';
	if(file_exists($fic))
		include('includes/footer.php');
?>  