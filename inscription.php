<?php

$fic = 'includes/header.php';
	if(file_exists($fic))
		include($fic);

	if(!isset($_POST['nom']))
	{	
		echo '<div style="margin-left:25%;">
				<br /><br />	
				<form action="inscription.php" method="post" >
					
					<table id="none">
						<tr><td>Login* :</td><td><input type="text" name="login" class="input" /></td></tr>
						<tr><td>Password* :</td><td><input type="password" name="pwd" class="input" /> </td></tr>
						<tr><td>Veuillez retaper le password* :</td><td><input type="password" name="pwd2" class="input" /></td></tr>
						<tr><td>Nom* :</td><td><input type="text" name="nom" class="input" /></td></tr>
						<tr><td>Prénom* :</td><td><input type="text" name="prenom" class="input" /></td></tr>
						<tr><td>Mail* :</td><td><input type="text" name="mail" class="input" /></td></tr>
						<tr><td>Téléphone :</td><td><input type="text" name="tel" class="input" /></td></tr>
						<tr><td>Fax :</td><td><input type="text" name="fax" class="input" /></td></tr>
						<tr><td>Adresse :</td><td><input type="text" name="adresse" class="input" /></td></tr>
						<tr><td>Code Postal :</td><td><input type="text" name="cp" class="input" /></td></tr>
						<tr><td>Ville :</td><td><input type="text" name="ville" class="input" /></td></tr>
						<tr><td>Région :</td><td><input type="text" name="region" class="input" /></td></tr>						
					</table>
					<p>
						<input style="margin-left:26%;" type="submit" value="S\'inscrire" />
					</p>
				</form>
			</div>';
	}else
	{	
		$erreur = 1;
		$login = ($_POST['login']);
		$pwd = ($_POST['pwd']);
		$pwd2 = ($_POST['pwd2']);
		$nom = ($_POST['nom']);
		$prenom = ($_POST['prenom']);
		$mail = ($_POST['mail']);
		$tel = ($_POST['tel']);
		$fax = ($_POST['fax']);
		$adresse = ($_POST['adresse']);
		$ville = ($_POST['ville']);
		$cp = ($_POST['cp']);
		$region = ($_POST['region']);
		
		//Vérif champs
		$Syntaxe='#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$#i';	
		if(trim($nom) == "")
			erreur("Votre nom est incomplet");
		elseif(trim($prenom) == "")
			erreur("Votre prénom est incomplet");
		elseif(trim($mail) == "")
			erreur("Votre mail est incomplet");
		elseif(!preg_match($Syntaxe,$mail))
			erreur("Votre mail est invalide");
		elseif (trim($pwd) == "")
			erreur("Le mot de passe est incomplet");
		else
		{
			$res = query("select * from client WHERE login = '".e($login)."' ;");
			if(fetch($res))
				erreur("Le login <b><i>".$login."</i></b> est déjà utilisé");
			elseif($pwd != $pwd2)
				erreur("Les mots de passes saisis sont differents");
			else
				$erreur = 0;
		}
			
		
			


		if($erreur)
		{
			echo '<div style="margin-left:25%;">
					<br /><br />	
					<form action="inscription.php" method="post" >
						
						<table id="none">
							<tr><td>Login* :</td><td><input type="text" name="login" class="input"  value= "'.s($login).'"/></td></tr>
							<tr><td>Password* :</td><td><input type="password" name="pwd" class="input" /> </td></tr>
							<tr><td>Veuillez retaper le password* :</td><td><input type="password" name="pwd2" class="input" /></td></tr>
							<tr><td>Nom* :</td><td><input type="text" name="nom" class="input"  value= "'.s($nom).'"/></td></tr>
							<tr><td>Prénom* :</td><td><input type="text" name="prenom" class="input" value= "'.s($prenom).'" /></td></tr>
							<tr><td>Mail* :</td><td><input type="text" name="mail" class="input" value= "'.s($mail).'" /></td></tr>
							<tr><td>Téléphone :</td><td><input type="text" name="tel" class="input" value= "'.s($tel).'" /></td></tr>
							<tr><td>Fax :</td><td><input type="text" name="fax" class="input"  value= "'.s($fax).'"/></td></tr>
							<tr><td>Adresse :</td><td><input type="text" name="adresse" class="input" value= "'.s($adresse).'" /></td></tr>
							<tr><td>Code Postal :</td><td><input type="text" name="cp" class="input" value= "'.s($cp).'" /></td></tr>
							<tr><td>Ville :</td><td><input type="text" name="ville" class="input" value= "'.s($ville).'" /></td></tr>
							<tr><td>Région :</td><td><input type="text" name="region" class="input"  value= "'.s($region).'"/></td></tr>		
							
						</table>
						<p>
							<input style="margin-left:26%;" type="submit" value="S\'inscrire" />
						</p>
					</form>
				</div>';
		}else
		{
			$pwd = md5($pwd);
			$res = query("INSERT INTO client(login,password,nomClient,prenomClient,adresseClient,postalClient,villeClient,regionClient,telClient,faxClient,emailClient)
							VALUES('".e($login)."','".e($pwd)."','".e($nom)."','".e($prenom)."','".e($adresse)."','".e($cp)."','".e($ville)."','".e($region)."','".e($tel)."','".e($fax)."','".e($mail)."') ");
			
			$res = query("select idClient from client WHERE login = '".e($login)."'");
			if(!( $row = fetch($res)))
			{
				erreur("Veuillez vous connecter");
				$_SESSION['login'] = "";
			}
			else
			{
				$_SESSION['id'] = $row[0];
				$_SESSION['login'] = $login;
				header("Location: index.php");
			}
 		}
		
	}
	
	$fic = 'includes/footer.php';
	if(file_exists($fic))
		include($fic);
?>