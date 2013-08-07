<?php 

	$fic = 'includes/header.php';
	if(file_exists($fic))
		include($fic);
		
	echo "<br />Renseignements sur un produit <br /><br /><div style='height:50px;'><div id='info' style='display:none;'></div></div><br />";
	
	
	echo "Identifiant du produit : <input type='text' id='idProd' value='' size='5' maxlength='5' onKeyUp='detailProd();'/><br /><br />";
	echo "<form action='".$_SERVER['PHP_SELF']."' method='POST' id='form'>";
	echo "<table id='none' class='infoprod'>";
		echo "<tr><td>Titre </td><td> <input type='text' id='titre' value='' readonly /></td></tr>";
		echo "<tr><td>Détail </td><td> <input type='text' id='detail' value='' readonly /></td></tr>";
		echo "<tr><td>Catégorie </td> <td><input type='text' id='cat' value='' readonly /></td></tr>";
		echo "<tr><td>Prix </td><td> <input style='float:left;' type='text' id='prix' value='' size='5' maxlength='5' readonly/></td></tr>";
	echo "</table>";
	echo "</form>";
	
	echo "<input style='margin-left:20%;' id='suite' type='button' value='Tous les détails' disabled='true' onClick=''/>";
	
	
	$fic = 'includes/footer.php';
	if(file_exists($fic))
		include('includes/footer.php');
?>  