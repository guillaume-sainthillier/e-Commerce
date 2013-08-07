<?php 

	$fic = 'includes/header.php';
	if(file_exists($fic))
		include($fic);
		

	if($_SESSION['id'] != 0)
	{
		//setcookie("sainthillier",0,time()-3600);
		//unset($_COOKIE['sainthillier']);
		unset($_SESSION['login']);
		unset($_SESSION['id']);
		
		unset($_SESSION['admin']);
		//echo "Vous avez été deconnecté.";
		echo "<script>
			window.location.href='index.php';
			</script>";
	}
	$fic = 'includes/footer.php';
	if(file_exists($fic))
		include('includes/footer.php');
?>  