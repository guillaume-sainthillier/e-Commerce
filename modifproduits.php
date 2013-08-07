<?php 

	$fic = 'includes/header.php';
	if(file_exists($fic))
		include($fic);
			
	if($_SESSION['id'] == 0 or $_SESSION['admin'] < 2)
	{
		erreur("Votre rang d'administration ne vous permet pas d'acceder à cette page.<br />Veuillez vous <a href='identification.php'> connecter </a>");
	}else
	{
		$erreur = 1;
		
		echo "<script type='text/javascript'>autoProd3(\"rechProd\");</script>";
		echo "<br /><input type='text' id='rechProd' /> Entrez le nom ou le détail d'un produit<br />";
		
		if(isset($_GET['i']))
		{
			$idProd = e($_GET['i']);
			if(isset($_POST['titre']))
			{
				
				$res = query("select idCat,titreProd,detailProd,imageProd,nouvProd,
							  promProd,selProd,poidsProd,dispoProd,delaiProd,
							  prixhtProd,prixhtPromProd,tauxtvaProd
							  FROM produit WHERE idProd = '".$idProd."';");
							  
				if($row = fetch($res))
				{										
					$cat = $_POST['cat'];
					$titre = $_POST['titre'];
					$detail = $_POST['detail'];
					$poids = $_POST['poids'];
					$delai = $_POST['delai'];
					$prixht = $_POST['prixht'];
					$prixhtProm = $_POST['prixhtprom'];
					$tva = $_POST['tva'];
					
					$avatar = $_FILES['avatar']['name'];
					$maxsize = ($_POST['MAX_FILE_SIZE']);			
					
					isset($_POST['nouv'])? $nouv="oui" : $nouv="non";
					isset($_POST['promo'])? $promo="oui" : $promo="non";
					isset($_POST['sel'])? $sel="oui" : $sel="non";
					isset($_POST['dispo'])? $dispo="oui" : $dispo="non";
					isset($_POST['supprimer'])? $sup=1 : $sup=0;
					$dossier = 'produits/';
					$fic = $dossier.$avatar;
									
					if(!file_exists($dossier))
						mkdir($dossier,0777);
					//Vérif champs
					if($titre == "" ) 
					{
						erreur("Le titre est incomplet");
						$titre = $row[1];
					}elseif($prixht == "" or $prixht <= 0 )
					{
						erreur("Le prix hors taxe est incomplet");
						$prixht = $row[10];
					}else					
						$erreur = 0;					
					
					if($promo == "non" or $prixhtProm <= 0 or $prixhtProm == "")
						$prixhtProm = $prixht;					
					if($sup == 1 && is_file($dossier.$row[3]) && file_exists($dossier.$row[3]))
						unlink($dossier.$row[3]);
					
					//Vérif images + upload dans dossier profiles/
					if(trim($avatar) != "")
					{						
						if ($_FILES['avatar']['error'] > 0)
						{
							erreur("Erreur lors du transfert du fichier");
							$avatar = $row[3];					
						}elseif ($_FILES['avatar']['size'] > $maxsize)
						{
							erreur("Le fichier est trop gros");
							$avatar = $row[3];
						}elseif(substr($avatar,-3) != 'png' && substr($avatar,-3) != 'gif' && substr($avatar,-3) != 'jpg' && substr($avatar,-3) != "peg" )
						{
							erreur("Le type de fichier est non supporté");
							$avatar = $row[3];
						}else
						{
						
							
							$ext = substr($avatar,-3);
							
							if($ext == "jpg" or $ext== "peg")
								$ext = "jpeg";
								
							$avatar = "prod".$idProd.".".$ext;
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
								$avatar = $row[3];
								erreur("L'upload a échoué ! ");
							}
						}
					}else
						$avatar = $row[3];
					
					if($sup == 1)
					{
						$avatar = "";	
						if(is_file($dossier.$row[3]) && file_exists($dossier.$row[3]))					
							unlink($dossier.$row[3]);											
						
						if(is_file($dossier.'o_'.$row[3]) && file_exists($dossier.'o_'.$row[3]))
								unlink($dossier.'o_'.$row[3]);	
					}
					
					query("UPDATE produit SET idCat = '".e($cat)."' ,
											titreProd = '".e($titre)."',
											detailProd = '".e($detail)."',
											imageProd = '".$avatar."',
											nouvProd = '".e($nouv)."',
											promProd = '".e($promo)."',
											selProd = '".e($sel)."',
											poidsProd = '".e($poids)."',
											dispoProd = '".e($dispo)."',
											delaiProd = '".e($delai)."',
											prixhtProd = '".e($prixht)."',
											prixhtPromProd = '".e($prixhtProm)."',
											tauxtvaProd = '".e($tva)."'
										   WHERE idProd = '".$idProd."' ;");
					if(!$erreur)							
						aide("Vos modifications ont bien été éffectuées.<br />");
				}else
					erreur("Aucun produit trouvé.");
			}	
				$res = query("select idCat,titreProd,detailProd,imageProd,nouvProd,
							  promProd,selProd,poidsProd,dispoProd,delaiProd,
							  prixhtProd,prixhtPromProd,tauxtvaProd
							  FROM produit WHERE idProd = '".$idProd."';");
							  
				if($row = fetch($res))
				{
					echo '<div style="margin-left:25%;">
							<br /><br />	
							<form action="modifproduits.php?i='.$idProd.'" method="post" enctype="multipart/form-data">
								<input type="hidden" name="MAX_FILE_SIZE" value="2500000" />							
								<table id="none">';
								
					$tabCheck = array(" "," "," "," ");
					if($row[5] == "oui")					
						$tabCheck[0] = 'checked="checked"';
						
					if($row[6] == "oui")
						$tabCheck[1] = 'checked="checked"';
						
					if($row[8] == "oui")
						$tabCheck[2] = 'checked="checked"';	
					
					if($row[4] == "oui")
						$tabCheck[3] = 'checked="checked"';
						
					$res2 = query("select idCat,nomCat from categorie");
					$str =  "<select name='cat'>";
					
					while($row2 = fetch($res2))
					{
						
						$str .= "<option value='".$row2[0]."'";
						if($row[0] == $row2[0])
							$str .= "selected";
						$str .= ">".$row2[1]."</option>";
					}						
					$str .= "</select>";
					
					$tabTva = array("5.5","19.6");
					$str2 = "<select name='tva'>";
					for($i = 0; $i < count($tabTva);$i++)
					{
						$str2 .= "<option value='".$tabTva[$i]."'";
						if($tabTva[$i] == $row[12])
							$str2 .= "selected";
						$str2 .= ">".$tabTva[$i]."</option>";
					}
					$str2 .= "</select>";
					$row = s_tab($row);
								echo'
									<tr><td>Catégorie :</td><td style="float:left;">'.$str.'</td></tr>
									<tr><td>Titre :</td><td style="float:left;"><input type="text" name="titre" class="input" value="'.$row[1].'" /></td></tr>								
									<tr><td>Détail :</td><td style="float:left;"><input type="text" name="detail" class="input" value="'.$row[2].'" /></td></tr>
									<tr><td>Prix H.T :</td><td style="float:left;"><input type="text" name="prixht" class="input" value="'.$row[10].'" size="10" maxlength="10" />€</td></tr>
									<tr><td>Promotion </td><td style="float:left;"><input type="checkbox" name="promo" '.$tabCheck[0].' /></td></tr>
									<tr><td>Prix H.T de promotion :</td><td style="float:left;"><input type="text" id="prix" name="prixhtprom" class="input" value="'.$row[11].'"  size="10" maxlength="10" />€</td></tr>
									<tr><td>TVA :</td><td style="float:left;">'.$str2.' %</td></tr>
									<tr><td>Coup de coeur :</td><td style="float:left;"><input type="checkbox" name="sel" '.$tabCheck[1].'/></td></tr>
									<tr><td>Nouveauté :</td><td style="float:left;"><input type="checkbox" name="nouv"  '.$tabCheck[3].'/></td></tr>
									<tr><td>Poids :</td><td style="float:left;"><input type="text" name="poids" class="input" value="'.$row[7].'" size=3"/>kg</td></tr>
									<tr><td>Disponible :</td><td style="float:left;"><input type="checkbox" name="dispo" value="'.$row[8].'" '.$tabCheck[2].'/></td></tr>
									<tr><td>Délai de livraison :</td><td style="float:left;"><input type="text" name="delai" class="input" value="'.$row[9].'" /></td></tr>
									<tr><td>Image :</td><td style="float:left;">';
									
									$fic = "produits/".$row[3];
									
									if($row[3] != null && file_exists($fic))
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
				}else
					erreur("Aucun produit trouvé.");
		}
	}

	
	$fic = 'includes/footer.php';
	if(file_exists($fic))
		include('includes/footer.php');
?>  