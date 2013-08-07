<?php
header("Content-type: image/svg+xml");
echo '<?xml version="1.0" encoding="iso-8859-1"?>';
echo '<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.0//EN"
"http://www.w3.org/TR/2001/
REC-SVG-20010904/DTD/svg10.dtd">';

$hauteur = 400;
$largeur = 650;
echo '<svg width="'.$largeur.'" height="'.$hauteur.'" 
xmlns="http://www.w3.org/2000/svg"         
xmlns:xlink="http://www.w3.org/1999/xlink">';

$fic = 'includes/utils.php';
	if(file_exists($fic))
		include($fic);
		
$fic = 'includes/session.php';
	if(file_exists($fic))
		include($fic);
		
	echo '<g style="stroke-width:1;fill:lightgreen"  transform="translate(30,30)">';
	
		$margeY = 100; //Marge en Y du bas
		$margeX = 50;
		$largeurBatons = 2; //Largeur des rectangles de connection
		$largeurTrait = 4; //Largeur des traits d'abscisse et d'ordonnée
		echo '<rect x="10" y="10" width="'.$largeurTrait.'" height="'.($hauteur-$margeY).'" style="fill:black"/>';
		echo '<rect x="10" y="'.($hauteur-$margeY+9).'" width="'.($largeur-$margeX).'" height="'.$largeurTrait.'" style="fill:black"/>';
	
		$res = query("select DATEDIFF(MAX(date),MIN(date)) from connections;");//On choppe le nombre de jours entre la première et la dernière co
		if($row = fetch($res))		
		{
			
			$mode = $_SESSION['mode'];
			$nbJours = $row[0];
			$largeurX = (($largeur-$margeX)/$nbJours) -$largeurBatons/2; //Espacement par bâtons			

			$tabDate = array();
			$tabNbCo = array();
			$maxCo = 0;
			$res = query("SELECT SUBSTRING(date,1,10), count( * ) FROM connections GROUP BY SUBSTRING(date,1,10) order by date asc;");
			for($i = 0;$row = fetch($res);$i++) //On rentre le nombre de connections et la date de co dans un tableau
			{
				$tabDate[$i] = $row[0];
				$tabNbCo[$i] = $row[1];
				if($row[1] > $maxCo)
					$maxCo = $row[1];
			}
			if($maxCo == 0)
				$largeurY = 10;
			else
				$largeurY = ($hauteur-$margeY)/($maxCo)-1;
			
			$res2= query("SELECT DISTINCT count( * ) FROM connections 
							GROUP BY SUBSTRING( date, 1, 10 ) ORDER BY 1");
				
			$tabNbCoUnique = array();
			for($i = 0;$row2 = fetch($res2);$i++)
				$tabNbCoUnique[$i] = $row2[0];
				
			
			for($i = 0;$i < count($tabNbCoUnique);$i++)
			{
				$hauteurColonne = $largeurY*$tabNbCoUnique[$i];
				if($hauteurColonne > 0)
				{
					$y = round($hauteur-$margeY-($hauteurColonne)+9);
					echo '<text x="0" y="'.($y-5).'" font-size="12">'.$tabNbCoUnique[$i].'</text>';
					echo '<rect x="'.$largeurBatons.'" y="'.$y.'" 
							width="20" height="'.($largeurBatons-0.5).'" style="fill:black;"/>';
				}				
			}
			
			$tabDateFinal = array();
			$tabNbCoFinal = array();
			$j = 0;
			for($i = 0; $i < (count($tabDate)-1);$i++)
			{
				$tabDateFinal[$j] = $tabDate[$i];
				$tabNbCoFinal[$j] = $tabNbCo[$i];

				$j++;
				$res3 = query("select DATEDIFF('".$tabDate[($i+1)]."','".$tabDate[$i]."') ;");
				if($row3 = fetch($res3))
				{
					if($row3[0] > 0)
					{
						for($k = 1;$k < $row3[0];$k++)
						{
							$tabDateFinal[$j] = null;
							$tabNbCoFinal[$j] = 0;
							$j++;
						}
					}
				}
			}
			
			$tabDateFinal[$nbJours] = $tabDate[$i];
			$tabNbCoFinal[$nbJours] = $tabNbCo[$i];
			$debY = ($hauteur-$margeY)+9;
		
			$str = array();
			$str[1] = '<polyline fill="none" stroke="lightgreen" stroke-width="'.($largeurBatons-1).'" 
						points="10,'.$debY.' ';
			$str[0] = "";
			for($i =0;$i < $nbJours+1;$i++)
			{
				$hauteurColonne = round($largeurY*$tabNbCoFinal[$i]);
				$x = round(15+$largeurTrait+$i*$largeurX);
				if($hauteurColonne > 0)
				{				
					$y = round($hauteur-$margeY-($hauteurColonne)+9);
					$str[1] .= " ".$x.",".$y." ";
					$str[0] .= '<rect x="'.$x.'" y="'.$y.'" 
							width="'.$largeurBatons.'" height="'.($hauteurColonne).'" style="fill:black;"/>';
				}
				else
					$str[1] .= " ".$x.",".$debY." ";
			}
			$str[1] .= '"/>';
			$str[2] = $str[0].' '.$str[1];
			echo $str[$mode];
			
			$texte = "Nombre de connections";
			echo '<text x="0" y="0" font-size="13">'.$texte.'</text>';
				
		}
	echo '</g>';
echo '</svg>';
?>