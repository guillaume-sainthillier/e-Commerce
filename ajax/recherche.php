<?php
$fic = '../includes/session.php';
if(file_exists($fic))
	include($fic);
$fic = '../includes/utils.php';
 if(file_exists($fic))
	include($fic);

function recherche($mots,$suffixe="OR")
{
	$sql = "select detailProd,idProd from produit ";
	if(count($mots) > 0)
		$sql.= "WHERE ";
		
	for($i = 0;$i < count($mots);$i++)
	{
		$sql .=" (titreProd LIKE '%".e($mots[$i])."%' OR detailProd LIKE '%".e($mots[$i])."%' )";
		if($i != count($mots)-1)
			$sql .= $suffixe.' ';
	}

	return $sql;
}	
	$q = s(strtolower($_GET["q"]));	
	if (!$q) return;
	$mots = explode(" ",trim($q));
	$cherche = array();
	$remplace = array();
	for($i = 0;$i < count($mots);$i++)
	{
		if(trim($mots[$i]) != "")
		{
			if($mots[$i] != 'b')
			{
				$cherche[$i] = '/'.$mots[$i].'/';
				$remplace[$i] = ("<b>".$mots[$i]."</b>");	
			}
		}
	}
	$sql = recherche($mots,"AND");		
	$sql.=  " UNION ";
	$sql.= recherche($mots,"OR");
	$sql.= ";";
	$res = query($sql);
	
	while($row = fetch($res))
	{
		$cname = preg_replace($cherche,$remplace,$row[0]);
		$cid = $row[1];
		echo ("$cname|$cid|".$row[0]."\n");
	}
	

?>