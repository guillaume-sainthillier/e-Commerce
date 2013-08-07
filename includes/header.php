<?php
	$fic = 'includes/session.php';
	if(file_exists($fic))
		include($fic);
	else
		die(getcwd()."/".$fic." inexistant ");
		
	$fic = 'includes/utils.php';
	if(file_exists($fic))
		include($fic);
	else
		die($fic." inexistant ");
		
	$fic = 'includes/connec.php';
	
	if(file_exists($fic))
		include($fic);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
  <head>
    <title><?php echo $nomSite; ?> SAINTHILLIER - NADJAR</title>
    <meta http-equiv="Content-Type" content="image/png; charset=utf-8" />
	<script type="text/javascript" src="scripts/horloge.js"></script>
	<script type="text/javascript" src="scripts/jquery.js"></script>
	<script type="text/javascript" src="scripts/jquery-ui.js"></script>
	<script type="text/javascript" src="scripts/jquery.ui.datepicker-fr.js"></script>
	<script type="text/javascript" src="scripts/autocomplete.js"></script>
	<script type="text/javascript" src="scripts/scripts.js"></script>
	<script type="text/javascript" src="scripts/lightbox.js"></script>
	<?php
		$test = explode('/',$_SERVER['PHP_SELF']);
		$nomPage = $test[count($test)-1];
		$tabWhite = array("stats.php","stats2.php");
		if(in_array($nomPage,$tabWhite))
		{
			echo '<script type="text/javascript" src="scripts/json.js"></script>
			<script type="text/javascript" src="scripts/swfobject.js"></script>
			<link rel="stylesheet" href="scripts/jquery-ui.css" type="text/css" media="screen" />';
		}
	?>
	
    <link rel="stylesheet" href="scripts/commun.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="scripts/autocomplete.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="scripts/lightbox.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="includes/sous-header<?php echo $_SESSION['skin'];?>/style.css" type="text/css" media="screen" />
    <link rel="shortcut icon" href="includes/sous-header<?php echo $_SESSION['skin'];?>/images/favicon.ico" />

	<script type="text/javascript">
		$().ready(function()
		{
			 recherche();
			$(function() {
				$('a.lightbox').lightBox();
				$('.datepicker').datepicker({ dateFormat: 'dd/mm/yy'});
			});
		});
    </script>
  </head>
 
<body>
  <!-- Début du Conteneur de la Page -->
  <div id="container">
      <div id="header" title="<?php echo $nomSite; ?>">
      <!-- Logo -->
      <div id="logo"></div>
       <div id="dollars"></div>
      
      <!-- Menu Supérieur -->
      <div id="top-menu" >
		<div class="bloc1">
			<h4>MENU</h4>
        <dl>
			<?php
          echo '<dd><a href="index.php" title="Accueil" ';
			$tabSite = array("identification.php","inscription.php","deconnection.php","compte.php","produits.php","stats.php","bugs.php");
			if(!in_array($nomPage,$tabSite))
				echo 'id="on"' ;
			echo '>Accueil</a></dd>
          <dd><span id="horloge"><script type="text/javascript"> dT();</script></span></dd>';
							
					if($_SESSION['id'] == 0)
					{
						echo '<dd><a href="identification.php" title="Connexion" ';
						if($nomPage == "identification.php") echo "id='on'";
						echo '>Connexion</a></dd>';
						echo  '<dd><a href="inscription.php" title="S\'inscrire" ';
						if($nomPage == "inscription.php") echo "id='on'";
						echo '>S\'inscrire</a></dd>';
					}
					else
					{
						echo  '<dd><a href="deconnection.php" title="Deconnexion" ';
						if($nomPage == "deconnection.php") echo "id='on'";
						echo '>Déconnexion</a></dd>';
						echo  '<dd><a href="compte.php" title="Mon compte" ';
						if($nomPage == "compte.php") echo "id='on'";
						echo '>Mon compte</a></dd>';
					}				
			
		 echo ' <dd><a href="produits.php" title="Produits" ';
		 if($nomPage == "produits.php") echo "id='on'";
		 echo '>Nos produits</a></dd>';
       
		 echo '<dd><a href="stats.php" ';
			if($_SESSION['admin'] >= 1)
				echo "title='Obtenez des statistiques diverses' ";
			else
				echo "title='Vous devez être administrateur pour effectuer cette action' onclick='return false' ";
		 if($nomPage == "stats.php") echo "id='on'";
		 echo '>Stats</a></dd>';
		 
          echo '<dd><a href="bugs.php" title="Signaler un bug" ';
		  if($nomPage == "bugs.php") echo "id='on'";
		  echo '>Aide&amp;Bugs</a></dd>';
		  
		 ?>
        </dl>
		</div>
      </div>
      
      <!-- Top Line -->
      <div id="top-line">
      </div>      
    </div>
    <!-- Fin Header -->
    
    <!-- Image effet 3D -->
    <div id="splash"></div>
    <div id="corps">
    <!-- Debut Colonne Gauche -->
    <div id="gauche">
    	<div class="col">
	<!-- Titre -->
      <h1><span>SUB</span>MENU</h1>

      <!-- Menu supplémentaire gauche -->
	  <?php
		$res = query("select count(*) from connections");
		if(($row = fetch($res)))
			$nbVisites = $row[0];
		else
			$nbVisites = 0;
			
		echo "Compteur : <b>".$nbVisites."</b> visites<br />";
		if($_SESSION['login'] != "")
			echo "Connecté sous <a href='compte.php'> ".$_SESSION['login']."</a>";
	  ?>
      <ul>
		<?php			
			echo "<li><a href='' onclick='changeSkin();'>Changer de thème</a></li>";
			echo "<li><a href='produits.php'>Nos produits</a></li>";
			if($_SESSION['id'] == 0)
				echo "<li><a href='panier.php'>Mon panier</a></li>";
			echo '<li><a href="base.php" title="Afficher la base">Afficher la base de données</a></li>';
			echo '<li><a href="modif_session.php" title="Paramètres de session">Paramètres de session</a></li>';
			
			
			if($_SESSION['id'] != 0)
			{
				echo '<span style="color:red;">Client</span><br />';
				echo '
				<li><a href="panier.php">Mon panier</a></li>
				<li><a href="compte.php" title="Mon compte">Mon compte</a></li>
					<li><a href="mescommandes.php" title="Mes commandes">Mes commandes</a></li>';
			}
			
			
			echo '<span style="color:red;">Ajax</span><br />
			<li><a href="ventes.php" title="AJAX-Déterminez le montant total des ventes de produits ainsi que les commissions gagnées">Montant des ventes</a></li>
			<li><a href="ventestype.php" title="AJAX-Flemme_description">Montant des ventes/type</a></li>
			<li><a href="infoprod.php" title="AJAX-Informations d\'un produit">Informations produit</a></li>';
			
			
			if($_SESSION['admin'] > 0 )
			{
				echo '<span style="color:red;">Admin</span><br />';
				echo '<li><a href="stats.php" title="Obtenez de nombreuses statistiques">Statistiques</a></li>';
				if($_SESSION['admin'] > 1)
				{
					echo '<li><a href="ajouterproduit.php" title="Creer un nouveau produit">Ajouter un produit</a></li> ';
					echo '<li><a href="modifproduits.php" title="Modifiez les attributs d\'un produit">Modifier un produit</a></li> ';
					echo '<li><a href="modifproposer.php" title="Modifiez les règles de proposition des produits">Modifier propositions</a></li>';
				}
				if($_SESSION['admin'] > 2)
				{
					echo '<li><a href="compte.php" title="Modifiez les informations client">Modifier clients</a></li>';
				}
			}
			
		?>
		<li><input id="recherche" type="text" name="recherche" onfocus="this.value=''" value="Rechercher un produit..." class="input" /></li>
			
      </ul>	
      	
      	</div>
      	<div class="foot"></div>
    </div>
    <!-- Fin Colonne Gauche -->
  
    <!-- Debut Colonne Droite -->
    <div id="droite">
		
    	<div class="col">