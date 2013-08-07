<?php

	$fic = 'includes/header.php';
	if(file_exists($fic))
		include($fic);

	
	echo '<div><form action="graphiqueconnec.php" method="post" >';
	
	if(isset($_POST['mode']))
		$_SESSION['mode'] = $_POST['mode'];
	else
		$_SESSION['mode'] = 1;
	$mode = $_SESSION['mode'];
	
	$tabMode = array("Histogramme","Courbe","Les deux");
	echo "<br /><select name='mode' id='form' onchange='submit()'>";
	for($i = 0;$i < count($tabMode);$i++)
	{
		echo "<option value=".$i;
			if($i == $mode)
				echo " selected";
		echo ">".$tabMode[$i]."</option>";
	}
	echo "</select><br />";
	
	
	echo' <img type="image/svg" src="connec_svg.php" ></img>';
	echo "</form></div>";
		$fic = 'includes/footer.php';
	if(file_exists($fic))
		include($fic);
?>