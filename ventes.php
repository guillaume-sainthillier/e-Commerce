<?php 

	$fic = 'includes/header.php';
	if(file_exists($fic))
		include($fic);
		
	
	echo "<fieldset style='text-align:center;'>";
		echo "<legend>Informations</legend>";
		echo "Montant total des produits<br />";
		echo "<input type='text' value='' id='montant' readonly /><br /><br />";
		echo "Montant total des commissions (10%) <br />";
		echo "<input type='text' value='' id='com' readonly /><br /><br />";		
		echo "<input type='button' value='Actualiser' onClick='majCommission();' />";
	echo "</fieldset>";
	$fic = 'includes/footer.php';
	if(file_exists($fic))
		include('includes/footer.php');
?>  