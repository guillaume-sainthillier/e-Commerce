<?php
	session_start();
		
	if(!isset($_SESSION['login']))
		$_SESSION['login'] = "";
		
	if(!isset($_SESSION['id']))
	{
		// if(!isset($_COOKIE['sainthillier']))
		// {
			// setcookie("sainthillier",0,time()+60*60*24*7);
			// $_SESSION['id'] = 0;
		// }elseif(isset($_COOKIE['sainthillier']) and $_COOKIE['sainthillier'] != 0)			
			// $_SESSION['id'] = $_COOKIE['sainthillier'];
		// else
			$_SESSION['id'] = 0;
	}
		
	if(!isset($_SESSION['admin']))
		$_SESSION['admin'] = 0;
		
	if(!isset($_SESSION['skin']))
		$_SESSION['skin'] = 1;
		
	if(!isset($_SESSION['panier'.$_SESSION['id']]))
		$_SESSION['panier'.$_SESSION['id']] = array();
		
	if(!isset($_SESSION['qte'.$_SESSION['id']]))
		$_SESSION['qte'.$_SESSION['id']] = array();
		
	if(!isset($_SESSION['mode']))
		$_SESSION['mode'] = 0;
		
	if(!isset($_SESSION['nbEssai']))
		$_SESSION['nbEssai'] = 5;
		
	if(!isset($_SESSION['temps']))
		$_SESSION['temps'] = 0;
?>
