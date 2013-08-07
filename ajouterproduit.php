<?php 

	$fic = 'includes/header.php';
	if(file_exists($fic))
		include($fic);
			
	if($_SESSION['id'] == 0 or $_SESSION['admin']  <= 1)
	{
		echo erreur("Vous devez vous <a href='identification.php'>connecter</a> .<br />");
	}else
	{
		$erreur = 1;
		if(isset($_POST['titre']))
		{		
			$cat = ($_POST['cat']);
			$titre = ($_POST['titre']);
			$detail = ($_POST['detail']);
			$poids = ($_POST['poids']);
			$delai = ($_POST['delai']);
			$prixht = ($_POST['prixht']);
			$prixhtProm = ($_POST['prixhtprom']);
			$tva = ($_POST['tva']);
		
			$avatar = $_FILES['avatar']['name'];
			$maxsize = ($_POST['MAX_FILE_SIZE']);						
			$post = true;
		}else
		{
			$cat = 0;
			$titre = "";
			$detail = "";			 
			$poids = "";
			$delai = "";
			$prixht = "";
			$prixhtProm = "";
			$tva = "";
			$avatar = "";
			$maxsize = "";
			$sup = "";	
			$post = false;
		}
		
		isset($_POST['nouv'])? $nouv="oui" : $nouv="non";
		isset($_POST['promo'])? $promo="oui" : $promo="non";
		isset($_POST['sel'])? $sel="oui" : $sel="non";
		isset($_POST['dispo'])? $dispo="oui" : $dispo="non";
		isset($_POST['supprimer'])? $sup=1 : $sup=0;
	
		
		if($post)
		{
			$dossier = 'produits/';
			$fic = $dossier.$avatar;
						
			if(!file_exists($dossier))
				mkdir($dossier,0777);
			
			//Vérif champs
			if($titre == "" ) 
			{
				erreur("Le titre est incomplet");
			}elseif($prixht == "" or $prixht <= 0 )
			{
				erreur("Le prix hors taxe est incomplet");
			}else					
				$erreur = 0;					
			
			if($promo == "non" or $prixhtProm <= 0 or $prixhtProm == "")
				$prixhtProm = $prixht;
			//Vérif images + upload dans dossier profiles/
			if(trim($avatar) != "")
			{
				if ($_FILES['avatar']['error'] > 0)
				{
					erreur("Erreur lors du transfert du fichier");				
				}elseif ($_FILES['avatar']['size'] > $maxsize)
				{
					erreur("Le fichier est trop gros");
				}elseif(substr($avatar,-3) != 'png' && substr($avatar,-3) != 'gif' && substr($avatar,-3) != 'jpg' && substr($avatar,-3) != "peg" )
				{
					erreur("Le type de fichier est non supporté");
				}else
				{		
					$ext = substr($avatar,-3);
					
					if($ext == "jpg" or $ext== "peg")
						$ext = "jpeg";
						
					$avatar = "prod".$id.".".$ext;
					$fic = $dossier.$avatar;
					
					
					

					$fonction = "imagecreatefrom$ext";
					$fonction2 = "image$ext";
					if(move_uploaded_file($_FILES['avatar']['tmp_name'],$dossier.$avatar) && $ImageChoisie = @$fonction($fic))
					{
						$TailleImageChoisie = getimagesize($fic);
						$NouvelleLargeur = 100;
						
						$NouvelleHauteur = ceil( ($TailleImageChoisie[1] * (($NouvelleLargeur)/$TailleImageChoisie[0])) );
						$NouvelleImage = imagecreatetruecolor($NouvelleLargeur , $NouvelleHauteur);
						if($ext == "png")
						{
							imagealphablending($NouvelleImage,false);
							imagesavealpha($NouvelleImage,true);
						}
						
						imagecopyresampled($NouvelleImage , $ImageChoisie  , 0,0, 0,0, $NouvelleLargeur, $NouvelleHauteur, $TailleImageChoisie[0],$TailleImageChoisie[1]);
						
						if(is_file($dossier.$avatar) && file_exists($dossier.$avatar))
						{
							if(is_file($dossier.'o_'.$avatar) && file_exists($dossier.'o_'.$avatar))
								unlink($dossier.'o_'.$avatar);
							copy($dossier.$avatar,$dossier.'o_'.$avatar);
						}
															
						imagedestroy($ImageChoisie);					
						$fonction2($NouvelleImage , $dossier.$avatar);
					}else
					{
						if(is_file($fic) && file_exists($fic))
							unlink($fic);
						$erreur = 1;
						$avatar = "";
						erreur("L'upload a échoué ! ");
					}
				}
			}
			 
			if(!$erreur)
			{		
				$indice = 10;
				$idCat = substr($cat,0,1);
				$ok = false;
				while(!$ok)
				{
					$id = $idCat.$indice; //Très sale
					$sql = "select * from produit WHERE idProd = '".$id."' ;";
					$res =  query($sql);
					if(fetch($res))						
						$indice++;
					elseif($indice == 100)
						erreur("Il n'y a plus de place pour ajouter ce produit à cette catégorie");
					else
						$ok = true;
				}
				
				if($ok)
				{
					query("INSERT INTO produit(idProd,idCat,titreProd,detailProd,imageProd,
												nouvProd,promProd,selProd,poidsProd,dispoProd,
												delaiProd,prixhtProd,prixhtPromProd,tauxtvaProd)
										VALUES('".e($id)."','".e($cat)."','".e($titre)."','".e($detail)."','".($avatar)."',
												'".e($nouv)."','".e($promo)."','".e($sel)."','".e($poids)."','".e($dispo)."',
												'".e($delai)."','".e($prixht)."','".e($prixhtProm)."','".e($tva)."') ;");
					aide("Vos modifications ont bien été éffectuées.<br />");
				}else
					erreur("Erreur dans la génération de l'id ");
			}			
		}
		if(!$post or $erreur == 1)
		{
			echo '<div style="margin-left:25%;">
				<br /><br />	
				<form action="ajouterproduit.php" method="post" enctype="multipart/form-data">
					<input type="hidden" name="MAX_FILE_SIZE" value="2500000" />					
					<table id="none">';
					
			$tabCheck = array(" "," "," "," ");
			
			if($promo == "oui")					
			$tabCheck[0] = 'checked="checked"';
			
			if($sel == "oui")
			$tabCheck[1] = 'checked="checked"';

			if($dispo == "oui")
			$tabCheck[2] = 'checked="checked"';	

			if($nouv == "oui")
			$tabCheck[3] = 'checked="checked"';

			$res = query("select idCat,nomCat from categorie");
			$str =  "<select name='cat'>";

			while($row = fetch($res))
			{
				$str .= "<option value='".$row[0]."'";
				if($cat == $row[0])
					$str .= "selected";
				$str .= ">".$row[1]."</option>";
			}						
			$str .= "</select>";

			$tabTva = array("5.5","19.6");
			$str2 = "<select name='tva'>";
			for($i = 0; $i < count($tabTva);$i++)
			{
			$str2 .= "<option value='".$tabTva[$i]."'";
			if($tabTva[$i] == $tva)
				$str2 .= "selected";
			$str2 .= ">".$tabTva[$i]."</option>";
			}
			$str2 .= "</select>";						
					echo'
						<tr><td>Catégorie :</td><td style="float:left;">'.$str.'</td></tr>
						<tr><td>Titre :</td><td style="float:left;"><input type="text" name="titre" class="input" value="'.s($titre).'" /></td></tr>								
						<tr><td>Détail :</td><td style="float:left;"><input type="text" name="detail" class="input" value="'.s($detail).'" /></td></tr>
						<tr><td>Prix H.T :</td><td style="float:left;"><input type="text" name="prixht" class="input" value="'.s($prixht).'" size="10" maxlength="10" />€</td></tr>
						<tr><td>Promotion </td><td style="float:left;"><input type="checkbox" name="promo" '.($tabCheck[0]).' /></td></tr>
						<tr><td>Prix H.T de promotion :</td><td style="float:left;"><input type="text" id="prix" name="prixhtprom" class="input" value="'.$prixhtProm.'"  size="10" maxlength="10" />€</td></tr>
						<tr><td>TVA :</td><td style="float:left;">'.$str2.' %</td></tr>
						<tr><td>Coup de coeur :</td><td style="float:left;"><input type="checkbox" name="sel" '.($tabCheck[1]).'/></td></tr>
						<tr><td>Nouveauté :</td><td style="float:left;"><input type="checkbox" name="nouv"  '.($tabCheck[3]).'/></td></tr>
						<tr><td>Poids :</td><td style="float:left;"><input type="text" name="poids" class="input" value="'.s($poids).'" size=3"/>kg</td></tr>
						<tr><td>Disponible :</td><td style="float:left;"><input type="checkbox" name="dispo" value="'.s($dispo).'" '.$tabCheck[2].'/></td></tr>
						<tr><td>Délai de livraison :</td><td style="float:left;"><input type="text" name="delai" class="input" value="'.s($delai).'" /></td></tr>
						<tr><td>Image :</td><td style="float:left;">';
						
						$fic = "produits/".$avatar;
						
						if($avatar != null && file_exists($fic))
						{
							echo "<span style='float:left;'><img src='".$fic."' alt='Avatar' />&nbsp;&nbsp;&nbsp;<input type='checkbox' name='supprimer' />Supprimer</span><br />";
						}
							echo '<input type="file" name="avatar" class="input" /></td></tr>';
						
					echo '</table>
					<p>
						<input style="margin-left:26%;" type="submit" value="Modifier" />
					</p>
				</form>
			</div>';
		}
	}

	
	$fic = 'includes/footer.php';
	if(file_exists($fic))
		include('includes/footer.php');
?>  