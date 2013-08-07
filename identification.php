<?php 

	$fic = 'includes/header.php';
	if(file_exists($fic))
		include($fic);

	if(isset($_POST['login']) && isset($_POST['pwd']))
	{
		$login = e($_POST['login']);
		$pwd = md5(e($_POST['pwd']));
		
		$res = query("select admin,idClient from client WHERE login = '".$login."' AND password = '".$pwd."' ;");
		if($row = fetch($res))
		{
			$_SESSION['login'] = s($login);
			$_SESSION['id'] = e($row[1]);
			setcookie("sainthillier",e($row[1]),time()+3600*24*7);
			header('Location:index.php');
		}else
		{	
			if($_SESSION['nbEssai'] > 0)
			{
				$_SESSION['nbEssai']-- ;
				if($_SESSION['nbEssai'] > 0)
				{
					$res = query("select login,password from client WHERE login = '".$login."';");
					if(!fetch($res))
						erreur("Le nom d'utilisateur ".$login." est inconnu<br />");
					else
						erreur("Utilisateur reconnu mais mot de passe incorrect<br />");
					echo "Il vous reste ".$_SESSION['nbEssai']." tentatives <br />";
				}
				$_SESSION['temps'] = time();
			}
		}
	}
	
	if($_SESSION['id'] == 0)
	{		
		if($_SESSION['nbEssai'] > 0)
		{
			echo "<br /><form action='identification.php' method='post'>";
			echo "<div style='width:100px;float:left;'>
						Login
						<br /> Password 
				   </div>
				<div style='float:left;'>				
						<input type='text' name='login' class='input' /><br />
						<input type='password' name='pwd' class='input'/><br />
						<input type='submit' value='connexion' />	
				</div>
				</form>
				<br /><br /><br /><a href='inscription.php'>Pas encore inscrit?</a>"; 
		}else
		{
			if($_SESSION['nbEssai'] == 0 && (5*60-(time()-$_SESSION['temps'])) > 0)
			{
				erreur("Veuillez attendre ".(5*60-(time()-$_SESSION['temps']))." secondes avant de vous reconnecter <br />");
			}else
			{
				$_SESSION['nbEssai'] = 5;
				header("Location :identification.php");
			}
		}
	}
	
	if($_SESSION['id'] != 0)
	{
		echo "Bienvenue <b>".$_SESSION['login'].'</b> !<br />';
		header('Location:index.php');
	}

	
	$fic = 'includes/footer.php';
	if(file_exists($fic))
		include('includes/footer.php');
?>  