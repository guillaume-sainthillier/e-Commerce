<?php
	$fic = 'includes/header.php';
	if(file_exists($fic))
		include($fic);
	else
		die($fic." inexistant ");
	$fic = 'session.php';
	if(file_exists($fic))
		include($fic);
	else
		die($fic." inexistant ");
		
		
	$fic = 'includes/footer.php';
	if(file_exists($fic))
		include($fic);
	else
		die($fic." inexistant ");
		

?>