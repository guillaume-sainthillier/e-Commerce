<?php 
	$fic = 'includes/header.php';
	if(file_exists($fic))
		include($fic);
	else
		die($fic." inexistant ");
?>     

	 <!-- Titre -->
      <h1><span>BIEN</span>VENUE
      <?php
			$res = query("select concat(prenomClient,' ',nomClient) from client WHERE idClient = '".$_SESSION['id']."' ; ");
			if($row = fetch($res))
				$nom = s($row[0]);
			else
				$nom = $_SESSION['login'];
			$nom .= " !";	
			if($_SESSION['admin'] > 0)
				$nom .= "Vous bénéficiez des droits administrateurs.";
      	echo "<span>".$nom."</span>"?>
      </h1>
      
       <?php if($_SESSION['id'] == 0)			
			echo "<h2>Vous n'êtes pas connecté , vous pouvez le faire <a href='identification.php' >ICI</a></h2>";
			 //sudo chown -R www-data: /var/www/ && sudo chmod 755 /var/www
	   ?>

	   
	  
		Bienvenue sur <?php echo $nomSite ?>, le site n°1 <b>MONDIAL</b> de la vente sur internet.
		<br />Ceci est une révolution, vous y trouverez toutes les dernières technologies du monde à prix d'OR !
      <br />
	  
	 <br /> Toute l'équipe de <?php echo $nomSite ?> vous souhaite un bon voyage sur notre site.
     <br /><br />
  		
  			<?php 
  				echo addCom(1);				
  			?>
			
      <!-- Ligne de séparation -->
      <div class="hr">&nbsp;</div>
      
      <!-- Titre -->
      <h1><span>LOREM</span> IPSUM</h1>
		
      <!-- Sous Titre -->
      <h2>Dolor sit amet, consectetuer adipiscing elit</h2>  
      
      
  		<br />  
		
		Nunc volutpat orci ut enim. Vivamus hendrerit. Mauris neque wisi, aliquam quis, commodo quis, varius et, augue. Integer dui augue, tempor in, mattis a, 
		aliquet at, enim. Vestibulum tristique venenatis ante. Nulla viverra justo sit amet est. Aliquam erat volutpat. Fusce vestibulum. 
		Vivamus elementum mi at orci.Nam ullamcorper, lorem ac feugiat sollicitudin, urna elit tincidunt nibh, sit amet laoreet felis odio a diam. 
		In nulla felis, semper in, varius et laoreet.  Donec faucibus blandit neque. Donec porta tristique neque.
		Donec bibendum, odio eget tristique congue, tellus augue venenatis neque, eget sollicitudin odio mi non purus. 
		Proin lobortis posuere justo.
		<br />
		Nunc volutpat orci ut enim. Vivamus hendrerit. Mauris neque wisi, aliquam quis, commodo quis, varius et, augue. Integer dui augue, tempor in, mattis a, 
		aliquet at, enim. Vestibulum tristique venenatis ante. Nulla viverra justo sit amet est. Aliquam erat volutpat. Fusce vestibulum. 
		Vivamus elementum mi at orci.Nam ullamcorper, lorem ac feugiat sollicitudin, urna elit tincidunt nibh, sit amet laoreet felis odio a diam. 
		In nulla felis, semper in, varius et laoreet.  Donec faucibus blandit neque. Donec porta tristique neque.
		Donec bibendum, odio eget tristique congue, tellus augue venenatis neque, eget sollicitudin odio mi non purus. 
		Proin lobortis posuere justo.
		<br /><br />
		<?php 
  				echo addCom(2);				
  		?>

  <?php 
	include('includes/footer.php');
?>