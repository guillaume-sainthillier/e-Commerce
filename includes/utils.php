<?php
	$nomSite = "e-Commerce";
	$host='yourremote';
	$user = 'youruser';
	$pass = 'yourpass';
	$db = 'yourdb';
   
	mysql_connect($host, $user, $pass) 
		or die("Erreur dans la connexion à la base <br />".mysql_errno().':'.mysql_error());
	 
	mysql_select_db($db)
		or die("Erreur dans la selection de la base <br />".mysql_errno().':'.mysql_error());
	
	query('SET NAMES UTF8');
	if (get_magic_quotes_gpc()) 
	{
		$process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
		while (list($key, $val) = each($process)) {
			foreach ($val as $k => $v) {
				unset($process[$key][$k]);
				if (is_array($v)) {
					$process[$key][stripslashes($k)] = $v;
					$process[] = &$process[$key][stripslashes($k)];
				} else {
					$process[$key][stripslashes($k)] = stripslashes($v);
				}
			}
		}
		unset($process);
	}
	$res = query("select admin,skin from client WHERE idclient = '".$_SESSION['id']."' ;");
	if(($row = fetch($res)))
	{
		$_SESSION['admin'] = $row[0];
		$_SESSION['skin'] = $row[1];
	}
	
	function query($sql = '',$debug = 0)
	{		
		if(!$resultat = mysql_query($sql))
		{
			$str = "<div style='text-align:left;'>Erreur SQL (Spécial IUT) :";
			if($_SESSION['admin'] > 0)
				$str .= $sql;
			$str .= "<br />Erreur n°".mysql_errno();
			if($_SESSION['admin'] > 0)
				$str .= ":".mysql_error();
			else
				$str .= ": Contactez votre administrateur ";
			$str .= "</div>";
			
			// die(".$sql."<br />".mysql_errno().':'.mysql_error()."</div>");
			die($str);
		}

		if($debug)
			echo "<div style='text-align:left;'>".$sql."</div>";
			
		return $resultat;
	}
	
	function fetch($resultat)
	{
		$row = mysql_fetch_row($resultat);
		if(!$row)
			mysql_free_result($resultat);
		return $row;
	}
	
	function last_num()
	{
		return mysql_insert_id();
	}
	
	function e_tab($tab)
	{
		$retour = array();
		for($i = 0;isset($tab[$i]);$i++)
			$retour[$i] = e($tab[$i]);
			
		return $retour;
	}
	//Sécurise les données à l'envoi au serveur
	function e($string)
	{
		// On regarde si le type de string est un nombre entier (int)
		if(ctype_digit($string))
		{
			$string = intval($string);
		}
		// Pour tous les autres types
		else
		{
			$string = trim($string);
			$string = mysql_real_escape_string($string);
			$string = addcslashes($string, '%_');
		}		
		return $string;
	}
	
	//Sécurise les données sorties du serveur (failles XSS)
	function s($string)
	{
		return str_replace('\_','_',htmlspecialchars($string));
		
	}
	
	function s_tab($tableau)
	{
		$retour = array();
		for($i = 0; isset($tableau[$i]) ;$i++)	
		{
			$retour[$i] = s($tableau[$i]);
		}
			
		return $retour;
	}
	
	function erreur($texte)
	{
		echo "<img src='images/erreur.png' alt='Erreur' /> ".$texte."";
	}
	
	function aide($texte)
	{
		echo "<img src='images/aide.png' alt='Info' /> ".$texte."";
	}
	
	
	function promo()
	{
		echo "<img src='images/promo.png' alt='Promo' />";
	}
	
		
	function coup_coeur()
	{
		echo "<img src='images/coeur.png' alt='Coup de coeur' />";
	}
	
	function info()
	{
		echo "<img src='images/info.png' alt='Détails' />";
	}
	
	function nouv()
	{
		echo "<img src='images/new.png' alt='Nouveauté' />";
	}
	
	function datetimetostring($string)
	{
		$tmp = preg_split("/[\s]/",trim($string));
		$retour = array();
		if(count($tmp) == 2)
		{
			$retour[0] = datetostring($tmp[0]);
			$retour[1] = heuretostring($tmp[1]);
		}
		return $retour;
	}
	
	function heuretostring($string)
	{
		//Format HH:mm:ss
		$string = trim($string);
		if(strlen($string) != 8)
			return "00h00";
		else
		{
			$retour = explode(':',$string);
			if(count($retour) == 3)
				$date = $retour[0].'h'.$retour[1];
			else
				$date = "";
			
			return $date;
		}		
	}
	
	function stringtodate($string)
	{
		if(strlen($string) != 10 or trim($string) == "")
			return "0000-00-00";
		$retour = explode('/',$string);
		{
			if(isset($retour[2]))
				return $retour[2].'-'.$retour[1].'-'.$retour[0];
			else
				return "0000-00-00";
		}
	}
	
	function datetostring($string)
	{
		if(strlen($string) != 10 or $string == '0000-00-00')
			return "";
		else
		{
			$retour = explode('-',$string);
			if(isset($retour[2]))
			{
				switch($retour[1])
				{
					case '01':
						$mois = "Janvier";
					break;
					case '02':
						$mois = "Février";
					break;
					case '03':
						$mois = "Mars";
					break;
					case '04':
						$mois = "Avril";
					break;
					case '05':
						$mois = "Mai";
					break;
					case '06':
						$mois = "Juin";
					break;
					case '07':
						$mois = "Juillet";
					break;
					case '08':
						$mois = "Août";
					break;
					case '09':
						$mois = "Septembre";
					break;
					case '10':
						$mois = "Octobre";
					break;
					case '11':
						$mois = "Novembre";
					break;
					case '12':
						$mois = "Décembre";
					break;
					default:
						$mois = "";
					break;
					
					
				}
				$date = $retour[2].' '.$mois.' '.$retour[0];
			}else
				$date = "";
			
			return $date;
		}		
	}
		
		
	function datetostring2($string)
	{
		if(strlen($string) != 10 or $string == '0000-00-00')
			return "";
		else
		{
			$retour = explode('-',$string);
			if(isset($retour[2]))
			{
				$date = $retour[2].'/'.$retour[1].'/'.$retour[0];
			}else
				$date = "";
			
			return $date;
		}		
	}
	function xml($str)
	{
	   $str = str_ireplace('&', '&amp;', $str);
	   $str = str_ireplace('<', '&lt;', $str);
	   $str = str_ireplace('>', '&gt;', $str);
	   return $str;
	} 
	
	function addCom($idArticle,$iDepart = 0)
	{
		$res = query("select count(*) from commentaires WHERE idArticle = '".$idArticle."'; ");
		
  		if($row = fetch($res))
  		{
  			$nb = $row[0];
  			($nb == 0 or $nb ==1)? $hum="" : $hum="s";
  		}  			
  		
  					
  		$retour =  '<div class="coms">';
  		$retour .= '<span class="lien" onclick="afficherCom(\''.$idArticle.'\',\''.$iDepart.'\');">
	  					<img alt="Commentaires" src="images/commentaire.gif" />
	  					Commentaire'.$hum.'(<span id="com'.$idArticle.'">'.$nb.'</span>)
  					</span>
		  	  		 </div>
					<input type="hidden" id="etat'.$idArticle.'" value="0" />	
				
					<div id="contenu'.$idArticle.'" style="display:none;">
					</div>';
		  		
  		return $retour;
	}
	
	function maj_proposer($idCom)
	{
		$res = query("select dc.idProd,dc2.idProd,count(*) 
							from detail_commande dc,detail_commande dc2
								WHERE
									dc.idCom = dc2.idCom 
									AND dc2.idProd <> dc.idProd 
									AND dc2.idCom IN	
										(SELECT idCom from commande WHERE idCom = '".($idCom)."')  
							GROUP BY 1,2;",1);
							
		while($row = fetch($res))
		{
			$res2 = query("select idProd1 from proposer WHERE idProd1 = '".$row[0]."' AND idProd2 = '".$row[1]."' ;");
			if(!$row2 = fetch($res2))
			{
				query("INSERT INTO proposer(idProd1,idProd2,nbFois) VALUES('".$row[0]."','".$row[1]."','".$row[2]."') ;");
			}else
			{
				query("UPDATE proposer SET nbFois = nbFois + 1 WHERE idProd1 = '".$row[0]."' AND idProd2 = '".$row[1]."' ;");
			}
		}
	}
	
?>
