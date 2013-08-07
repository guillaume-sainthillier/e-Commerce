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
    	echo "<connec>".$_SESSION['id']."</connec>";
    	echo "<admin>".intval($_SESSION['admin'])."</admin>";
    if(isset($_POST['id']))
    {
    	$id = e($_POST['id']);
    	$ip = e($_POST['ip']);
    	
    	$res = query("select count(*) from commentaires WHERE idArticle = '".$id."' ;");
    
    	if($row = fetch($res))
    		echo "<nb>".$row[0]."</nb>";
    	else
    		echo "<nb>0</nb>";
    	$nbComParPage = 5;
    	
    	$debut = $ip * $nbComParPage;
    	$fin = $debut;
    	
    	   		
    	
    	$sql = "select u.login ,heure,commentaire,idCom,u.avatar,c.idUser from commentaires c, client u 
  						WHERE c.idUser = u.idClient AND idArticle = '".$id."' order by heure desc
  						LIMIT ".$debut.",".$nbComParPage.";";
   		$res = query($sql);
   		
  		
		while($row = fetch($res))
		{
			$datetime = datetimetostring($row[1]);
			$date = $datetime[0];
			$heure = $datetime[1];
			
			$retourFic = "images/avatar.png";
			if($row[4] != "")
			{
				$fic = "../profils/";
				if(!file_exists($fic)){
					mkdir($fic,0777);
					$retourFic = "images/avatar.png";
				}else
				{
					$fic .= $row[4];
					if(!file_exists($fic))
						$retourFic = "images/avatar.png";
					else 
						$retourFic = "profils/".$row[4];
				}
			}
			
				
			echo "<reponse>
				
				<login>".xml($row[0])."</login>
				<date>".xml($date)."</date>
				<heure>".xml($heure)."</heure>
				<commentaire>".xml($row[2])."</commentaire>
				<id>".xml($row[3])."</id>
				<img>".xml($retourFic)."</img>
			</reponse>";
		}
	}
    echo "</news>";
?>