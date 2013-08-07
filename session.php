 <?php
 
 echo '<br /><h2>Session</h2>';
  echo "(Spécial IUT): ";
  echo "<br /><form action='".$_SERVER['PHP_SELF']."' method='POST'>Réinitialiser session <input type='submit' value='Reset' /><input type='hidden' name='reset' value='' /></form>";
  
  
	$i = 0;
	foreach($_SESSION as $cle => $valeur)
	{
		$tabVal[$i] = $cle;
		$i++;
	}
	
	if(isset($_POST['reset']))
	{
		session_destroy();
		include("includes/session.php");
	}
	
	
	echo "<ul>\n";
	
	$i = 0;
	foreach($_SESSION as $ligne)
	{
		if(!is_array($ligne))
		{
			if($ligne === false)
				$ligne = "Non";

			echo "  <li><strong>".ucfirst($tabVal[$i])." : </strong><em>".$ligne."</em></li>\n";
		}else
		{
			echo "  <li><strong>".ucfirst($tabVal[$i])." : Tableau ";
			if(!isset($ligne[0]))
				echo "(vide)";
			echo "</li>\n";
				for($j = 0; isset($ligne[$j]);$j++)
				{
					echo "  <li style='margin-left:5%;'>[".$j."]: <em>".$ligne[$j]."</em></li>\n";
				}
		}
		$i++;
	}
	echo "</ul>\n";
	
	
	
	
?>