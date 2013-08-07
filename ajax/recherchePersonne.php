<?php
$fic = '../includes/session.php';
if(file_exists($fic))
	include($fic);
$fic = '../includes/utils.php';
 if(file_exists($fic))
	include($fic);

function recherche($mots,$suffixe="OR")
{
	$sql = "select concat(nomClient,' ',prenomClient),idClient from client ";
	if(count($mots) > 0)
		$sql.= "WHERE ";
		
	for($i = 0;$i < count($mots);$i++)
	{
		$sql .=" (nomClient LIKE '%".e($mots[$i])."%' OR prenomClient LIKE '%".e($mots[$i])."%' )";
		if($i != count($mots)-1)
			$sql .= $suffixe.' ';
	}

	return $sql;
}	
	$q = s(strtolower($_GET["q"]));	
	if (!$q) return;
	$mots = explode(" ",trim($q));
	
	$sql = recherche($mots,"AND");		
	$sql.=  " UNION ";
	$sql.= recherche($mots,"OR");
	$sql.= ";";
	$res = query($sql);
	
	while($row = fetch($res))
	{
		$cname = $row[0];
		$cid = $row[1];
		echo ("$cname|$cid|\n");
	}
	

?>