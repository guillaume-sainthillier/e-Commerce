<?php

$fic = '../includes/session.php';
if(file_exists($fic))
	include($fic);
$fic = '../includes/utils.php';
 if(file_exists($fic))
	include($fic);

//ééé

	
 header("Content-type: text/xml");
echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';   
    echo "<news>";
    	echo "<connec>".$_SESSION['id']."</connec>";
    	echo "<admin>".intval($_SESSION['admin'])."</admin>";
    if(isset($_POST['id']))
    {
    	$id = e($_POST['id']);
		if($_SESSION['admin'] > 1 && $_SESSION['id'] != 0 )
		{
			query("delete from commentaires WHERE idCom = '".$id."' ; ");
			echo "<ok>OK</ok>";
		}else
			echo "<ok>NOK</ok>";
	}else
		echo "<ok>NOK</ok>";
    echo "</news>";
?>