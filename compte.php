<?php 

	$fic = 'includes/header.php';
	if(file_exists($fic))
		include($fic);
	
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	if($_SESSION['id'] == 0)
	{
		echo erreur("Vous devez vous <a href='identification.php'>connecter</a> .<br />");
	}else
	{
		$extSite = "";
		if(isset($_GET['i']) && $_SESSION['admin'] > 2)
		{	
			$extSite = "?i=".$_GET['i'];
			$idClient = e($_GET['i']);
		}else
			$idClient = e($_SESSION['id']);
		$erreur = 1;
		if(isset($_POST['nom']))
		{
			$res = query("select nomClient,prenomClient,emailClient,password,avatar,admin from client WHERE idClient = '".$idClient."';");
			if($row = fetch($res))
			{		
				$row = s_tab($row);
				
				$pwd = ($_POST['pwd']);
				$pwd2 = ($_POST['pwd2']);
				$nom = ($_POST['nom']);
				$prenom = ($_POST['prenom']);
				$mail = ($_POST['mail']);
				$adresse = ($_POST['adresse']);
				$ville = ($_POST['ville']);
				$cp = ($_POST['cp']);
				$region = ($_POST['region']);
				$tel = ($_POST['tel']);
				$fax = ($_POST['fax']);
				$avatar = $_FILES['avatar']['name'];
				$maxsize = ($_POST['MAX_FILE_SIZE']);			
				if(isset($_POST['supprimer']))
					$sup = 1;
				else
					$sup = 0;				
				$dossier = 'profils/';
				$fic = $dossier.$avatar;
				
				$admin = $_POST['admin'];
				if(!($_SESSION['admin'] > 2 && is_numeric($admin) && $admin <= $_SESSION['admin']))
					$admin = $row[5];
				
				if(!file_exists($dossier))
					mkdir($dossier,0777);
				//Vérif champs
				$Syntaxe='#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$#i';	
				if(trim($nom) == "")  // NB: trim déjà effectué dans la fonction e ^^
				{
					erreur("Votre nom est incomplet");
					$nom = $row[0];
				}elseif(trim($prenom) == "")
				{
					erreur("Votre prénom est incomplet");
					$prenom = $row[1];
				}elseif(trim($mail) == "")
				{
					erreur("Votre mail est incomplet");
					$mail = $row[2];
				}elseif(!preg_match($Syntaxe,$mail))
				{
					erreur("Votre mail est invalide");
					$mail = $row[2];
				}elseif(trim($pwd) != trim($pwd2))
				{
					erreur("Les mots de passe saisis sont differents");
					$pwd = $row[3];
				}else					
					$erreur = 0;					
				
				
				if(trim($pwd) == "")
						$pwd = $row[3];
					elseif($erreur == 0)
						$pwd = md5($pwd);
						
				if($erreur)
					echo "<br />";
					
				if($sup == 1 && is_file($dossier.$row[4]) && file_exists($dossier.$row[4]))
					unlink($dossier.$row[4]);
				
				//Vérif images + upload dans dossier profiles/
				if(trim($avatar) != "")
				{
					$erreur = 1;
					if ($_FILES['avatar']['error'] > 0)
					{
						erreur("Erreur lors du transfert du fichier");
						$avatar = $row[4];					
					}elseif ($_FILES['avatar']['size'] > $maxsize)
					{
						erreur("Le fichier est trop gros");
						$avatar = $row[4];
					}elseif(substr($avatar,-3) != 'png' && substr($avatar,-3) != 'gif' && substr($avatar,-3) != 'jpg' && substr($avatar,-3) != "peg" )
					{
						erreur("Le type de fichier est non supporté");
						$avatar = $row[4];
					}else
					{
						$ext = substr($avatar,-3);
						if($ext == "jpg" or $ext== "peg")
							$ext = "jpeg";
							
						$avatar = 'c'.$idClient.'.'.$ext;
						$erreur = 0;
						
						$fic = $dossier.$avatar;
						
						
							
						$fonction = "imagecreatefrom$ext";
						$fonction2 = "image$ext";

						if(move_uploaded_file($_FILES['avatar']['tmp_name'],$dossier.$avatar) && $ImageChoisie = @$fonction($fic))
						{
							$TailleImageChoisie = getimagesize($fic);
							$NouvelleLargeur = 40; //Largeur choisie à 40 px mais modifiable

							$NouvelleHauteur = ( ($TailleImageChoisie[1] * (($NouvelleLargeur)/$TailleImageChoisie[0])) );

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
							$avatar = $row[4];
							erreur("L'upload a échoué ! ");
						}
					}
					
				}else
					$avatar = $row[4];
				
				if($sup == 1)
				{
					$avatar = "";	
					if(is_file($dossier.$row[4]) && file_exists($dossier.$row[4]))					
						unlink($dossier.$row[4]);											
					
					if(is_file($dossier.'o_'.$row[4]) && file_exists($dossier.'o_'.$row[4]))
							unlink($dossier.'o_'.$row[4]);	
				}
				
				query("UPDATE client SET nomClient = '".e($nom)."',
									   prenomClient = '".e($prenom)."',
									   emailClient = '".e($mail)."',
									   adresseClient = '".e($adresse)."',
									   postalClient = '".e($cp)."',
									   villeClient = '".e($ville)."',
									   regionClient = '".e($region)."',
									   telClient = '".e($tel)."',
									   faxClient = '".e($fax)."',
									   password = '".$pwd."',
									   avatar = '".$avatar."' ,
									   admin = '".$admin."' 
									   WHERE idClient = '".$idClient."' ;");
									
				if(!$erreur)							
					aide("Vos modifications ont bien été éffectuées.<br />");
			}else
				erreur("Aucun utilisateur trouvé, veuillez vous <a href='identification.php'>reconnecter</a>.<br />");
		}	
		
			if($_SESSION['admin'] > 2)
			{
				echo "<br /><span style='color:red;'>Admin</span>";
				echo "<script type='text/javascript'>autoPersonne(\"recherchePersonne\");</script>";
				echo "<br /><br />Entrez le nom ou prénom du client à modifier <input type='text' id='recherchePersonne' />";
			}
			$res = query("select nomClient,prenomClient,emailClient,adresseClient,postalClient,
								villeClient,regionClient,telClient,faxClient,avatar,
								admin,derniereConnexion from client WHERE idClient = '".$idClient."';");
			if($row = fetch($res))
			{
				$row = s_tab($row);
				echo '<div style="margin-left:25%;">
						<br /><br />	
						<form action="'.$_SERVER['PHP_SELF'].$extSite.'" method="post" enctype="multipart/form-data">
							<input type="hidden" name="MAX_FILE_SIZE" value="2500000" />
							
							<table id="none">';
							
							echo'
								<tr><td>Dernière connexion:</td><td style="float:left;">&nbsp;'.datetostring($row[11]).'</td></tr>
								<tr><td>Password:</td><td style="float:left;"><input type="text" name="pwd" class="input" value=""   /></td></tr>
								<tr><td>Veuillez retaper le password :</td><td style="float:left;"><input  value="" type="text" name="pwd2" class="input"/></td></tr>
								<tr><td>Nom :</td><td style="float:left;"><input type="text" name="nom" class="input" value="'.$row[0].'" /></td></tr>
								<tr><td>Prénom :</td><td style="float:left;"><input type="text" name="prenom" class="input" value="'.$row[1].'" /></td></tr>								
								<tr><td>Mail :</td><td style="float:left;"><input type="text" name="mail" class="input" value="'.$row[2].'" /></td></tr>
								<tr><td>Adresse :</td><td style="float:left;"><input type="text" name="adresse" class="input" value="'.$row[3].'" /></td></tr>
								<tr><td>Code Postal :</td><td style="float:left;"><input type="text" name="cp" class="input" value="'.$row[4].'" /></td></tr>
								<tr><td>Ville :</td><td style="float:left;"><input type="text" name="ville" class="input" value="'.$row[5].'" /></td></tr>
								<tr><td>Région :</td><td style="float:left;"><input type="text" name="region" class="input" value="'.$row[6].'" /></td></tr>
								<tr><td>Téléphone :</td><td style="float:left;"><input type="text" name="tel" class="input" value="'.$row[7].'" /></td></tr>
								<tr><td>Fax :</td><td style="float:left;"><input type="text" name="fax" class="input" value="'.$row[8].'" /></td></tr>
								<tr><td>Avatar :</td><td style="float:left;">';
								
								$fic = "profils/".$row[9];
								
								if($row[9] != null && file_exists($fic))
								{
									echo "<span style='float:left;'><img src='".$fic."' alt='Avatar' />&nbsp;&nbsp;&nbsp;<input type='checkbox' name='supprimer' />Supprimer</span><br />";
								}
									echo '<input type="file" name="avatar" class="input" /></td></tr>';
								
							
							echo "<tr ";
							if($_SESSION['admin'] <= 2 )
								echo "style='display:none;'";
							echo "><td>Admin :</td><td style='float:left;'>";
							
							if($row[10] >= $_SESSION['admin'] && $idClient != $_SESSION['id'])
							{
								echo "<input type='hidden' name='admin' value='".$row[10]."'/>";
								$res4 = query("select libelle from permission WHERE admin = '".$row[10]."' ;");
								if($row4 = fetch($res4))
									echo $row4[0];
							}else
							{
								echo "<select name='admin'>";
								$res3 = query("select admin,libelle from permission WHERE admin <= '".$_SESSION['admin']."' ;");
								for($i = 0;$row3 = fetch($res3);$i++)
								{
									echo "<option value='".$row3[0]."' ";
									if((isset($_POST['admin']) && $_POST['admin'] == $i) OR (!isset($_POST['admin']) && $row[10] == $i))
										echo "selected ";
									echo " >".$row3[1]."</option>";
								}
								echo "</select>";
							}
							
							echo "</td></tr>";
							echo '</table>
							<p>
								<input style="margin-left:26%;" type="submit" value="Modifier" />
							</p>
						</form>
					</div>';
			}else
				erreur("Aucun utilisateur trouvé, veuillez vous <a href='identification.php'>reconnecter</a>.<br />");
	}

	
	$fic = 'includes/footer.php';
	if(file_exists($fic))
		include('includes/footer.php');
?>  