<?php 

	$fic = 'includes/header.php';
	if(file_exists($fic))
		include($fic);
			
	
	{
		$res = query("show tables");

		if(isset($_POST['bouton']))
			$nbT = mysql_num_rows($res);
		else
			$nbT = 0;
		echo "<form method='POST' action='base.php'>";
		if(isset($_POST['beau']))
			$checked = 'checked';
		else
			$checked = "";
		echo "<br /><label for='beau'><input type='checkbox' name='beau' id='beau' $checked />Tenter de rendre l'affichage plus beau</label><br /><br />";
		$nomTables = array();
		echo "<fieldset style='width:30%'>";
			echo "<legend>Sélectionnez vos tables</legend>";
			echo "<table id='none2'>";
		$j = 0;
		
		$tabEspaces = "<br />";
		$blackListe = array(""); //Liste des tables à ne pas afficher
		while($row = fetch($res))
		{
			if(!in_array($row[0],$blackListe))
			{
				echo "<tr><td><input type='checkbox' id='table".$j."' name='".$j."'";
				if(isset($_POST[$j]))
					echo " checked ";
				echo " value='".$row[0]."'/></td><td><label for='table".$j."'>".$row[0]."</label></td></tr>";
				$nomTables[$j] = $row[0];
				$j++;
				$tabEspaces .= "<br />";
			}else
				$nbT--;
			
		}	
		echo "</table>";
		echo "<div style='text-align:center;'><input type='submit' method='post' value='Afficher' name='bouton'/></div>";
		echo "</fieldset><br />Hum";
		echo "</form>";
		
		// if($nbT > 0)
			// echo $tabEspaces;
		echo "<div>";
		for($k = 0;$k < $nbT;$k++)
		{
			if(isset($_POST[$k]))
			{
				$res = query("select * from ".$nomTables[$k]);
				if($nomTables[$k] == "client" or $nomTables[$k] == "produit")
				{
					$nomTables[$k] .= " (Affichage pas beau)";
					if($checked != "")
						echo "<script>$('#droite').css('overflow-y','scroll');$('#droite').css('overflow-x','none');</script>";
				}
				echo "<center><b>Table ".$nomTables[$k]."</b><center>";
				echo "<table>";		
				for($i = 0;$row = fetch($res);$i++)
				{
					if($i == 0)
					{
						echo "<tr style='text-align:center;'>";
						for($j = 0; isset($row[$j]);$j++)	
							echo "<td>".mysql_field_name($res,$j)."</td>";
						echo "</tr>";
					}
							
					
					echo "<tr>";
					for($j = 0; isset($row[$j]);$j++)					
						echo "<td>".s($row[$j])."</td>";
					
					echo "</tr>";
				}
				echo "</table><br /><br />";
			}
		}
		echo "</div>";
	}
	echo "<br />";
	$fic = 'includes/footer.php';
	if(file_exists($fic))
		include('includes/footer.php');
?>  