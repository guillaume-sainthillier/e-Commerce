<?php 

	$fic = 'includes/header.php';
	if(file_exists($fic))
		include($fic);
		
	
	echo "<fieldset style='text-align:center;'>";
	
		$res = query("select idCat,nomCat from categorie;");
		if(mysql_num_rows($res) > 0)
		{
			echo "<select id='cat' onChange='majCommissionType()'>";
				echo "<option value='0'>Cat&eacute;gorie... </option>";
				while($row = fetch($res))
					echo "<option value='".$row[0]."'>".$row[1]."</option>";
			echo "</select><br /><br />";
		}
		echo "<legend>Informations</legend>";
		echo "Montant total des produits<br />";
		echo "<input type='text' value='' id='montant' readonly /><br /><br />";
		echo "Montant total des commissions (10%) <br />";
		echo "<input type='text' value='' id='com' readonly' /><br /><br />";		
	echo "</fieldset>";
	$fic = 'includes/footer.php';
	if(file_exists($fic))
		include('includes/footer.php');
?>  