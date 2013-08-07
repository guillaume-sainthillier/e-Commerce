<?php

$fic = '../includes/session.php';
 if(file_exists($fic))
	include($fic);
	
$fic = '../includes/utils.php';
 if(file_exists($fic))
	include($fic);
	

//é 

 header("Content-type: text/xml");
echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';  
    echo "<news>";
    
    if(isset($_POST['id']) && isset($_POST['com']))
    {
    	$idA = e($_POST['id']);
    	$com = e($_POST['com']);
    	$id = $_SESSION['id'];
    	
		
		echo "<reponse>";
		if(trim($com) != "" && $_SESSION['id'] != 0 && fetch(query("select * from client WHERE idClient = '".$id."';")))
		{
			$sql = "INSERT INTO commentaires(idUser,idArticle,heure,commentaire) VALUES
											('".$id."','".$idA."',NOW(),'".$com."'); ";
			if(query($sql) )
				echo "<ok>OK</ok>";
			else
				echo "<ok>NOK</ok>";	
		}else 
			echo "<ok>NOK</ok>";	
		echo "</reponse>";
		
	}

    echo "</news>";
?>