<?php 

	$fic = 'includes/header.php';
	if(file_exists($fic))
		include($fic);

		
	function faire_graphique($titre ='Graphique',$donnees,$mode,$labelX,$legendeX='',$legendeY='',$maxY = 20,$stepX = 1,$stepY = 2)
	{
		$graphique = new open_flash_chart(); //Création du graphique
		$graphique->set_title( new title($titre));//On met le titre
		
		if($mode != "bar_sketch")
		{
			$bar = new $mode(); //Mode = type de graphique
			if($mode == "line")
			{
				 $d = new dot();
				$bar->set_default_dot_style($d->size(4)->halo_size(0));
				$bar->set_width( 2 );
			}
		}else
		{
			$options = rand(0,15);
			$bar = new bar_sketch('#81AC00', '#567300',$options);
		}
		
		$bar->set_values( $donnees ); //On y met les données
		$bar->set_on_show(new bar_on_show("grow-up",0,0)); //Les barres montent petit à petit à l'affichage du graphique
		$graphique->add_element( $bar ); //On ajout les barres au graphique
		
		
		//String des X en bas
		$x_labels = new x_axis_labels();
		$x_labels->rotate(45);
		$x_labels->set_labels( $labelX ); //On y met les labelX

		$x = new x_axis();
		$nb = count($donnees);
		if($nb == 0) $nb++;
		if($nb > 1)
			$nb--;

		$x->set_range(0,$nb,$stepX);
		$x->set_labels( $x_labels ); //On ajout à l'axe X les labels X
		$graphique->set_x_axis( $x ); //On ajoute au graphique l'axe x
		
		if($legendeX != '')
		{
			$x_legend = new x_legend($legendeX);
			$x_legend->set_style( '{font-size: 16px; color: #778877}' );
			$graphique->set_x_legend( $x_legend ); // On ajout au graphique la légende en bas
		}
		
		if($legendeY != '')
		{
			$y_legend = new y_legend($legendeY);
			$y_legend->set_style('{font-size: 16px; color: #778877}');
			$graphique->set_y_legend($y_legend);
		}
		
		$y = new y_axis();
		if($maxY == 0)
			$maxY = 20;
		$y->set_range( 0,$maxY+1,$stepY);
		$graphique->set_y_axis( $y );
		
		return $graphique;
	}
	
	
	if($_SESSION['admin'] > 0)
	{
		$res = query("select min(dateCom),max(dateCom) from commande");
		
		isset($_POST['date1']) ? $date1 = $_POST['date1'] : $date1 = date("d/m/Y");
		isset($_POST['date2']) ? $date2 = $_POST['date2'] : $date2 = "";
		isset($_POST['date3']) ? $date3 = $_POST['date3'] : $date3 = date("d/m/Y");
		isset($_POST['date4']) ? $date4 = $_POST['date4'] : $date4 = "";
		isset($_POST['mode'])  ? $mode  = $_POST['mode']  : $mode  = 1;
		isset($_POST['mode2']) ? $mode2 = $_POST['mode2'] : $mode2 = "bar_filled";
		isset($_POST['cat'])   ? $idCat = $_POST['cat']   : $idCat = 0;
		isset($_POST['choix']) ? $choix = $_POST['choix'] : $choix = 0;
		isset($_POST['prix']) ?  $prix = $_POST['prix']   : $prix = 0;
		
		
		if($mode == 1)
			include 'lib/php-ofc-library/open-flash-chart.php';
		else
			include "lib/libchart/classes/libchart.php";
		
		
		
		
		//Partie constante
		$tabPrix = array(1000,2000);
		$tabReq = array(" >= 0 "," <= ".$tabPrix[0],"BETWEEN ".$tabPrix[0]." AND ".$tabPrix[1]." "," >= ".$tabPrix[1]);
		$tabNomsPrix = array("Tous les prix","En dessous de ".$tabPrix[0]."€","Entre ".$tabPrix[0]." et ".$tabPrix[1]."€","Au dessus de ".$tabPrix[1]."€");
		
		$tabCat = array("Toute catégorie");
		
		$liste = array("libchart","open-flash-chart");
		
		$listeBars = array("bar_filled","bar_glass","bar_3d","bar_sketch","bar_cylinder","bar_cylinder_outline","bar_rounded_glass","bar_round","bar_dome");
		$listeTraduc = array("Normal","barres glass","barres 3D","barres fun","barres cylindre","barres cynlindre souligné","barres glass arrondies","barres arrondies","bar dôme");
		
		$tabCouleursCat = array("#FF1D00","#794F8E","#00FF00","#710CFF","#FF1D00");
		
		echo "<br /><br /><form action='".$_SERVER['PHP_SELF']."' method='POST' >";
		echo "<input type='radio' onClick='afficherChoix(0);' id='choix0' name='choix' value='0' ";
		if($choix == 0)
			echo "checked ";
		echo "/><label for='choix0' >Stats de commandes </label><br /><div id='commandes' style='margin-left:5%; ";
		
		if($choix != 0)
			echo " display:none;";
		echo " '>Du <input type='text' class='datepicker' name='date1' value='$date1' /> ";
		echo " Au <input type='text' class='datepicker' name='date2' value='$date2' />(Optionnel)<br /><br /></div>";
		
		echo "<input type='radio' onClick='afficherChoix(3);' id='choix3' name='choix' value='3' ";
		if($choix == 3)
			echo "checked ";
		echo " /><label for='choix3' >Stats de catégorie de produits les plus vendus </label><br />  <div id='catprod' style='margin-left:5%; ";
		if($choix != 3)
			echo " display:none;";
		echo " '>Du <input type='text' class='datepicker' name='date3' value='$date3' /> ";
		echo " Au <input type='text' class='datepicker' name='date4' value='$date4' />(Optionnel)<br /><br /></div>";
		
		
		echo "<input type='radio' onClick='afficherChoix(1);' id='choix1' name='choix' value='1' ";
		if($choix == 1)
			echo "checked ";
		echo " /><label for='choix1' >Stats de produits </label><br /> <div id='produits' style='margin-left:5%; ";
		
		if($choix != 1)
			echo " display:none;";
		echo "' ><select name='cat' >";
		
			echo "<option value='0' ";
				if($idCat == 0)
					echo "selected";
			echo " >Toute catégorie</option>";
			$res2 = query("select idCat,nomCat from categorie");
			while($row2 = fetch($res2))
			{
				echo "<option value='".$row2[0]."' ";
				if($idCat == $row2[0])
					echo "selected";
				echo " >".$row2[1]."</option>";
				$tabCat[] = $row2[1];
			}
		echo "</select>";
		
		
		echo "Trier par catégorie <br />";
			echo "<select name='prix'>";	
			for($i = 0;$i < count($tabNomsPrix);$i++)
			{
				echo "<option value='".$i."' ";
				if($i == $prix)
					echo " selected ";
				echo ">".$tabNomsPrix[$i]."</option>";
			}			
			echo "</select> Trier par prix";
		echo "</div><br />";
		
		
		echo "<input type='radio' onClick='afficherChoix(2);' id='choix2' name='choix' value='2' ";
		if($choix == 2)
			echo "checked ";
		echo " /><label for='choix2' >Stats de connexions</label><br />";
		
		echo "<br /><b>Options</b><br />";
		
		
		$i = 0;
		foreach($liste as $elem)
		{
			
			echo "<input onClick='afficherOption(".$i.");'type='radio' name='mode' id='br".$i."' value='".$i."'";
			if($i == $mode)
				echo " checked ";
			echo "/><label for='br".$i."'>".$elem."</label><br />";			
			
			$i++;
		}
		
		echo "<div id='optionsOpenChart'  style='";
		if($mode != 1)
			echo"display:none; ";
		echo "'><select style='margin-left:5%;' name='mode2'>";
			for($i = 0;$i < count($listeBars);$i++)
			{
				echo "<option value='".$listeBars[$i]."' ";
				if($mode2== $listeBars[$i])
					echo "selected";				
				echo ">".$listeTraduc[$i]."</option>";
			}
		echo "</select></div>";
		
		echo "<br /><br /><input type='submit' value='Générer' name='bouton'/>";
		echo "</form><br />";

		//Fin partie constante
		if(isset($_POST['bouton']))
		{
			if($choix == 0) //Commandes
			{
				if(trim($date2) == "")
				{
					$date2 = $date1;
					$titre = "Commandes du ".$date1;
				}else
					$titre = "Commandes entre le ".($date1)." et le ".($date2);
				
				$requete = "select count(*),dateCom from commande WHERE dateCom BETWEEN '".e(stringtodate($date1))."' AND '".e(stringtodate($date2))."' GROUP BY dateCom;";
				
				if($mode == 0) //LibChart
				{
					$chart = new VerticalBarChart(700,300); //Création du graphique (500x300)
					$dataSet = new XYDataSet(); //Création des données
					
					$res = query($requete);
					while($row = fetch($res))
					{
						$dataSet->addPoint(new Point(datetostring2($row[1]), $row[0]));
					}
					
					$chart->setDataSet($dataSet);
				
					$chart->setTitle($titre);
					$chart->render("images/chart.png");
					echo "<img src='images/chart.png' alt='Image Graphique' />";
					echo "<br /><br />";
				}else
					if($mode == 1)//Open-Flash-Chart
					{
						$donnees = array(); //Tableau de BAR
						$labelX = array();
						
						$res = query($requete);
						$max = 0;
						while($row = fetch($res))
						{
							if($max < $row[0])
								$max =  $row[0];
							$bar = new bar_value(intval($row[0]));
							$bar->set_colour( '#900000' );
							$bar->set_on_click("getInfoCom('".$row[1]."')");
							$string = "Le ".datetostring2($row[1]).'<br>#val# commande';
							if($row[0] > 1)
								$string .= "s";
							$bar->set_tooltip( $string );
							$donnees[] = $bar; //Données contient un tableau de BAR_VALUE
							$labelX[] = datetostring2($row[1]);
						} 
						$graphique  = faire_graphique($titre,$donnees,$mode2,$labelX,'Date de commande','Nombre de commandes',$max);
					}//Fin Commande-OpenFlash
			}//Fin commande
			else 
			if($choix == 1)//Produits
			{
			
				$titre = "Produits";
				if($idCat == 0)
				{
					$ext = '1=1';
				}else
				{
					$titre .= " de catégorie ".$tabCat[($idCat/100)]; //Un peu sale
					$ext = "c.idCat = '".$idCat."'";
				}
				if($prix > 0)
				{
					$titre .= " ".strtolower($tabNomsPrix[$prix]);
				}
				
				$requete = "select count(dc.idProd),c.nomCat,p.titreProd,p.prixHtProd,dc.idProd,c.idCat from detail_commande dc,categorie c,produit p
							WHERE p.idProd = dc.idProd AND c.idCat = p.idCat AND $ext AND p.prixHtProd ".$tabReq[$prix]." GROUP BY dc.idProd ORDER BY c.idCat asc,p.prixHtProd asc;";
				
				if($mode == 0)//LibChart
				{
					$chart = new VerticalBarChart(700,300); //Création du graphique (500x300)
					$dataSet = new XYDataSet(); //Création des données
					
					$res = query($requete);
					while($row = fetch($res))
					{
						$dataSet->addPoint(new Point("Prod n°".$row[4], $row[0]));
					}
					
					$chart->setDataSet($dataSet);
				
					$chart->setTitle($titre);
					$chart->render("images/chart.png");
					echo "<img src='images/chart.png' alt='Image Graphique' />";
					echo "<br /><br />";
				}//Fin produit-libChart
				
				if($mode == 1)// OpenFlash
				{
					$donnees = array(); //Tableau de BAR
					$labelX = array();
					
					$res = query($requete);
					$max  = 0;
					while($row = fetch($res))
					{
						if($max < $row[0])
									$max =  $row[0];
						$bar = new bar_value(intval($row[0]));
						$bar->set_colour($tabCouleursCat[($row[5]/100)-1]);
						// $bar->set_on_click("getInfoCom('".$row[1]."')");
						$string = "Prix: ".s($row[3]).'€<br>#val# commande';
						if($row[0] > 1)
							$string .= "s";
						$bar->set_tooltip( $string );
						$donnees[] = $bar; //Données contient un tableau de BAR_VALUE
						$labelX[] = ($row[2]);
					}
							
					$graphique = faire_graphique($titre,$donnees,$mode2,$labelX,'Produits','Nombre de commandes',$max);
				}//Fin produit-openChart
			}else
				if($choix == 2)//Connexions
				{
					$titre = "Répartition des connexions";
					if($mode == 1)
					{
						$res = query("select DATEDIFF(MAX(date),MIN(date)) from connections;");//On choppe le nombre de jours entre la première et la dernière co
						if($row = fetch($res))		
						{
							$nbJours = $row[0];
						
							$tabDate = array();
							$tabNbCo = array();
							$maxCo = 0;
							$res = query("SELECT SUBSTRING(date,1,10), count( * ) FROM connections GROUP BY SUBSTRING(date,1,10) order by date asc;");
							for($i = 0;$row = fetch($res);$i++) //On rentre le nombre de connections et la date de co dans un tableau
							{
								$tabDate[$i] = $row[0];
								
								$d = new dot(intval($row[1]));
								$tabNbCo[$i] = $d->colour('#D02020')->tooltip("Le ".datetostring2($tabDate[$i]).'<br>#val# connexions');
								if($row[1] > $maxCo)
									$maxCo = $row[1];
							}
							$tabDateFinal = array();
							$tabNbCoFinal = array();
							$j = 0;
							for($i = 0; $i < (count($tabDate)-1);$i++)
							{
								$tabDateFinal[$j] = datetostring2($tabDate[$i]);
								$tabNbCoFinal[$j] = $tabNbCo[$i];

								$j++;
								$res3 = query("select DATEDIFF('".$tabDate[($i+1)]."','".$tabDate[$i]."') ;");
								if($row3 = fetch($res3))
								{
									if($row3[0] > 0)
									{
										for($k = 1;$k < $row3[0];$k++)
										{
											$tabDateFinal[$j] = "";
											$tabNbCoFinal[$j] = 0;
											$j++;
										}
									}
								}
							}
							$tabDateFinal[$nbJours] = $tabDate[$i];
							$tabNbCoFinal[$nbJours] = $tabNbCo[$i];

							$graphique = faire_graphique($titre,$tabNbCoFinal,"line",$tabDateFinal,'Date des connexions','Nombre de connexions',$maxCo,1,1);
						}
					}
				}else
				{
					if($choix == 3)
					{
						if(trim($date4) == "")
						{
							$date4 = $date3;
							$titre = "Ventes du ".$date3;
						}else
							$titre = "Ventes du ".($date3)." au ".($date4);
						
						$requete = "select count(co.idCom),c.nomCat,c.idCat from commande co ,categorie c,detail_commande dc,produit p WHERE 
									co.idCom = dc.idCom AND
									dc.idProd = p.idProd AND
									p.idCat = c.idCat AND
									dateCom BETWEEN '".e(stringtodate($date3))."' AND '".e(stringtodate($date4))."' GROUP BY c.idCat ORDER BY 1;";
						
						if($mode == 0) //LibChart
						{
							$chart = new VerticalBarChart(700,300); //Création du graphique (500x300)
							$dataSet = new XYDataSet(); //Création des données
							
							$res = query($requete);
							while($row = fetch($res))
							{
								$dataSet->addPoint(new Point(datetostring2($row[1]), $row[0]));
							}
							
							$chart->setDataSet($dataSet);
						
							$chart->setTitle($titre);
							$chart->render("images/chart.png");
							echo "<img src='images/chart.png' alt='Image Graphique' />";
							echo "<br /><br />";
						}else
							if($mode == 1)//Open-Flash-Chart
							{
								$donnees = array(); //Tableau de BAR
								$labelX = array();
								
								$res = query($requete);
								$max = 0;
								while($row = fetch($res))
								{
									if($max < $row[0])
										$max =  $row[0];
									$bar = new bar_value(intval($row[0]));
									$bar->set_colour( '#900000' );
									$bar->set_colour($tabCouleursCat[($row[2]/100)-1]);
									 $string = "#val# commande";
									 if($row[0] > 1)
										  $string .= "s";
									 $bar->set_tooltip( $string );
									$donnees[] = $bar; //Données contient un tableau de BAR_VALUE
									$labelX[] = ($row[1]);
								} 
								$graphique  = faire_graphique($titre,$donnees,$mode2,$labelX,'Catégorie','Nombre de commandes',$max);
							}//Fin Commande-OpenFlash
					}
				}
				
			

			
			//Intégration du swf dans la page
			if($mode == 1)//Open chart
			{
				//Partie HTML
				?>	
				<script type="text/javascript">
				
				swfobject.embedSWF("lib/open-flash-chart.swf", "my_chart", "700", "300", "9.0.0",{"loading":"Graphique en cours de chargement"} );
				</script>

				<script type="text/javascript">

 
				OFC = {};
				 
				OFC.jquery = {
					name: "jQuery",
					version: function(src) { return $('#'+ src)[0].get_version() },
					rasterize: function (src, dst) { $('#'+ dst).replaceWith(OFC.jquery.image(src)) },
					image: function(src) { return "<img src='data:image/png;base64," + $('#'+src)[0].get_img_binary() + "' />"},
					popup: function(src) {
						var img_win = window.open('', 'Exportation du graphique')
						with(img_win.document) {
							write('<html><head><title>Exportation du graphique<\/title><\/head><body>' + OFC.jquery.image(src) + '<\/body><\/html>') }
						// stop the 'loading...' message
						img_win.document.close();
					 }
				}
				 
				// Using an object as namespaces is JS Best Practice. I like the Control.XXX style.
				//if (!Control) {var Control = {}}
				//if (typeof(Control == "undefined")) {var Control = {}}
				if (typeof(Control == "undefined")) {var Control = {OFC: OFC.jquery}}
				 
				 
				// By default, right-clicking on OFC and choosing "save image locally" calls this function.
				// You are free to change the code in OFC and call my wrapper (Control.OFC.your_favorite_save_method)
				// function save_image() { alert(1); Control.OFC.popup('my_chart') }
				function save_image()
				{
					OFC.jquery.popup('my_chart');
				}
				function moo() 
				{
				}

				function ofc_ready()
				{
					
				}

				function open_flash_chart_data()
				{
					return JSON.stringify(data);
				}

				function findSWF(movieName) {
				  if (navigator.appName.indexOf("Microsoft")!= -1) {
					return window[movieName];
				  } else {
					return document[movieName];
				  }
				}

				var data = <?php echo $graphique->toPrettyString(); ?>;
				
				</script>
				<?php
			}
		}//Fin isset

		echo "<div id='my_chart' ></div><br /><br />";
		echo "<div id='info' style='display:none;'></div>";
	}else
	{
		echo "Vous devez être <a href='identification.php' >administrateur</a> pour effectuer cette action <br />";
	}
	
	$fic = 'includes/footer.php';
	if(file_exists($fic))
		include('includes/footer.php');
?>  