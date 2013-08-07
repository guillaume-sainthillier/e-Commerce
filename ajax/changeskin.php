<?php

$fic = '../includes/session.php';
if(file_exists($fic))
	include($fic);
$fic = '../includes/utils.php';
 if(file_exists($fic))
	include($fic);
	
//é
	
 header("Content-type: text/xml");
	// encoding="ISO-8859-1"
echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';   
    echo "<news>";
    if(isset($_POST['id']))
    {
    	if($_SESSION['skin'] == 4)
			$_SESSION['skin'] = 1;
		else
			$_SESSION['skin']++;
			
		if($_SESSION['id'] != 0)
		{
			query("UPDATE client SET skin = '".$_SESSION['skin']."' WHERE idClient = '".$_SESSION['id']."' ;");
		}
	}
    echo "</news>";
?>