<?php 
	$fic = 'includes/header.php';
	if(file_exists($fic))
		include($fic);
	else
		die($fic." inexistant ");

	if($_SESSION['admin'] <= 1)
	{
		erreur("Votre rang d'administration ne vous permet pas d'acceder à cette page.<br />Veuillez vous <a href='identification.php'> connecter </a>");
	}else
	{
		echo "<script type='text/javascript'>";
			echo "autoProd('rechProd');";
		echo "</script>";
		
		isset($_POST['mode']) ? $mode = $_POST['mode'] : $mode = 2;
		isset($_POST['rechProd']) ? $nomProd = $_POST['rechProd'] : $nomProd = "";
		isset($_POST['idProd']) ? $idProd = $_POST['idProd'] : $idProd = "";
		isset($_POST['idProd2']) ? $idProd2 = $_POST['idProd2'] : $idProd2 = "";
		
		if(!isset($_POST['idProd']) && isset($_GET['i']))
		{
			$idProd = $_GET['i'];
			$idProd2 = $_GET['i'];
		}
		
		if($mode == 0)
				$idProd = $idProd2;
				
		if($row2 = fetch(query("select titreProd from produit WHERE idProd = '".$idProd."';")))
			$nomP = $row2[0];
		else
			$nomP = "";
			
		echo "<br /><br />";
		echo "<form action='".$_SERVER['PHP_SELF']."' method='POST' >";
		
		echo "<input type='radio' name='mode' value='2' onClick='afficherChoix(2);' id='br2' ";
		if($mode == 2)
			echo "checked ";
		echo "/><label for='br2'>Mise à jour automatique</label><br />";
		echo "<input type='radio' name='mode' value='0' onClick='afficherChoix(0);' id='br0' ";
		if($mode == 0)
			echo "checked ";
		echo "/><label for='br0'>Recherche par description du produit</label>";
			echo "<div id='commandes' style='margin-left:5%;";
			if($mode != 0)
				echo "display:none;";
			echo "'>Entrez le titre ou le détail du produit <input type='text' id='rechProd' name='rechProd' value='$nomP' /><input type='hidden' id='idProd2' name='idProd2' value='$idProd2' /></div>";
			
		echo "<br /><input type='radio' name='mode' value='1' onClick='afficherChoix(1);' id='br1' ";
		if($mode == 1)
			echo "checked ";
		echo "/><label for='br1'>Recherche par id du produit</label>";
			echo "<div id='produits' style='margin-left:5%;";
			if($mode != 1)
				echo "display:none;";
			echo "'>Entrez l'id du produit <input type='text' name='idProd' value='$idProd' size='5' maxlength='5'/></div><br />";
			
			
		echo "<br /><input type='submit' name='chercher' value='Chercher' />";	
		
		if(isset($_POST['chercher']) or isset($_POST['modifier']) or isset($_GET['i']))
		{
			if($mode == 2)
			{
				//SELECT idCom from commande WHERE idCom = '0' OR '1=1'
				query("delete from proposer");
				maj_proposer("0' OR '1=1");
			}else
			{
				echo "<br />";
				if(trim($nomP) == "")
				{
					erreur("Le produit n'existe pas <br />");
				}else
				{
					if(isset($_POST['modifier']))	
					{
						$tabIdProd = array();
						for($i = 0;isset($_POST['prod'.$i]);$i++)
						{
							$tabIdProd[] = $_POST['prod'.$i];
						}
						
						$req = "delete from proposer WHERE idProd1 = '".$idProd."' AND idProd2 NOT IN ('".implode("','",$tabIdProd)."') ";
						if(!isset($_POST['sensUnique']))
							$req .= " OR (idProd2 = '".$idProd."' AND idProd1 NOT IN ('".implode("','",$tabIdProd)."') )";
						$req .= ";";
						
						query($req);
						
						for($i = 0;isset($_POST['prod'.$i]);$i++)
						{
							$idProd2 = e($_POST['prod'.$i]);
							if($idProd != $idProd2)
							{
								if(is_numeric($_POST['nbfois'.$i]))
								{
									$nbFois = intval($_POST['nbfois'.$i]);
									$res = query("select nbFois from proposer WHERE idProd1 = '".$idProd."' AND idProd2 = '".$idProd2."' ;");
									if(fetch($res)) //Association déjà existante entre les produits
									{
										if(!isset($_POST['sup'.$i]))
										{
											$req = "UPDATE proposer SET nbFois = '".$nbFois."' WHERE idProd1 = '".$idProd."' AND idProd2 = '".$idProd2."' ";
											if(!isset($_POST['sensUnique']))
												$req .= "OR (idProd1 = '".$idProd2."' AND idProd2 = '".$idProd."' )";
											$req .= ";";
											
											query($req);
										}else //Suppression
										{
											$req = "delete from proposer WHERE idProd1 = '".$idProd."' AND idProd2 = '".$idProd2."' ";
											if(!isset($_POST['sensUnique']))
												$req .= "OR (idProd1 = '".$idProd2."' AND idProd2 = '".$idProd."' )";
											$req .= ";";
											
											query($req);
										}
									}else //LE produit n'existe pas encore dans l'association (ou pas du tout)
									{
										if(fetch(query("select idProd from produit WHERE idProd = '".$idProd2."' ;")))
										{
											if(!isset($_POST['sup'.$i]))
											{
												query("INSERT INTO proposer(idProd1,idProd2,nbFois) VALUES('".$idProd."','".$idProd2."','".$nbFois."');");
												if(!isset($_POST['sensUnique']))
													query("INSERT INTO proposer(idProd1,idProd2,nbFois) VALUES('".$idProd2."','".$idProd."','".$nbFois."');");
											}
										}else
										{
											erreur("Le produit n°".$idProd2." n'existe pas <br />");
										}
									}
									
								}else
								{
									if($row2 = fetch(query("select titreProd  from produit WHERE idProd = '".$idProd2."';")))
									{
										erreur("La ligne (".$row2[0].") n'a pas de nombre de fois valide <br />");
									}else
										echo "Erreur <br />";
										
								}
							}else
								erreur("Vous ne pouvez proposer le produit lui même <br />");
						}
					}
					
					$res = query("select p.titreProd, pr.nbFois, p.idProd from produit p ,proposer pr WHERE p.idProd = pr.idProd2 AND pr.idProd1 = '".e($idProd)."' order by pr.nbFois desc;");
					
					{
						$tabNbFois = array();
						echo "<br /><b>$nomP</b><table id='tableProp'><tr><th>Produit</th><th>Nombre de fois</th><th>Action</th></tr>";
						for($i = 0;$row = fetch($res);$i++)
						{
							echo "<tr><td><input type='hidden' name='prod".$i."' value='".$row[2]."' />".$row[0]."</td>";
							echo "<td><input type='text' name='nbfois".$i."' value='".$row[1]."' size='5' maxlength='5' /></td>";
							echo "<td><input type='checkbox' name='sup".$i."' id='sup".$i."' />
									  <img src='images/supprimer.png' alt='supprimer' title='Supprimer cette proposition' 
												onClick='document.getElementById(\"sup".$i."\").checked=!(document.getElementById(\"sup".$i."\").checked);'/>
								</td></tr>";
						}
						echo "<tr style='border:none;'><td style='border:none;' ></td><td style='border:none;' ></td><td style='border:none;'>";
						echo "<img onClick='addPropo();' src='images/ajouter.png' alt='Ajouter' title='Ajouter une proposition pour ce produit' />
								</td></tr>";
						echo "</table>";
						echo "<input type='hidden' value='".$i."' id='nbProp' />";
						echo "<input type='checkbox' name='sensUnique' id='su'/><label for='su' >Sens unique (les propositions seront uniquement modifiées pour ce produit vers les autres,
									<br /> sans modifier les propositions des autres produits vers celui-ci)</label>";
						echo "<br /><br /><input type='submit' name='modifier' value='Modifier' />";
					}
				}
			}
		}
		echo "</form>";
	}
	$fic = 'includes/footer.php';
	if(file_exists($fic))
		include($fic);
	else
		die($fic." inexistant ");
?>