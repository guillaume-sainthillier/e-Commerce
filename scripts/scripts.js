async = false;
$().ready(function()
{
	if($.browser.mozilla && $.browser.version.slice(0,3) <= 5)
	{
		async = true;
	}
});

function addPropo()
{
	var nbProd = parseInt(g('nbProp'),10);
	
	var table = c("tableProp");
	var row = table.insertRow(nbProd+1);
	var cell1 = row.insertCell(0);
	var cell2 = row.insertCell(1);
	var cell3 = row.insertCell(2);
	cell1.innerHTML = "<input type='text' id='auto"+nbProd+"' /> / <input type='text' size='5' maxlength='5' name='prod"+nbProd+"' id='prod"+nbProd+"' />";
	cell2.innerHTML = "<input type='text' name='nbfois"+nbProd+"' value='0' size='5' maxlength='5' />";
	var str = "<input type='checkbox' name='sup"+nbProd+"' id='sup"+nbProd+"' />";
	str += "<img src='images/supprimer.png' alt='supprimer' title='Supprimer cette proposition' ";
	str += "onClick='document.getElementById(\"sup"+nbProd+"\").checked=!(document.getElementById(\"sup"+nbProd+"\").checked);'/>";
	cell3.innerHTML = str;
	
	autoProd2('auto'+nbProd,'prod'+nbProd);
	
	c('nbProp').value = nbProd+1;
}

function autoPersonne(nom)
{
	$().ready(function() {
			var id = '#'+nom;
		  $(id).autocomplete('ajax/recherchePersonne.php');	
		  $(id).result(function(event, data, formatted) {
				$(location).attr('href','compte.php?i='+data[1]);
		  });
		 });
}



function autoProd3(nom)
{
	$().ready(function() {
			var id = '#'+nom;
		  $(id).autocomplete('ajax/recherche.php');	
		  $(id).result(function(event, data, formatted) {
				c(nom).value = data[2];
				$(location).attr('href','modifproduits.php?i='+data[1]);
		  });
		 });
}


function autoProd2(nom,prod)
{
	$().ready(function() {
			var id = '#'+nom;
		  $(id).autocomplete('ajax/recherche2.php');	
		  $(id).result(function(event, data, formatted) {
				c(prod).value = data[1];
				c(nom).value = data[2];
		  });
		 });
}

function autoProd(nom)
{
	$().ready(function() {
			var id = '#'+nom;
		  $(id).autocomplete('ajax/recherche2.php');	
		  $(id).result(function(event, data, formatted) {
				c('idProd2').value = data[1];
				c(nom).value = data[2];
		  });
		 });
}
	 
function afficherOption(idChoix)
{
	var id = parseInt(idChoix,10);
	if(id == 0)
	{
		$("#optionsOpenChart").hide("slow");
	}else
	{
		$("#optionsOpenChart").show("slow");
	}
}

function afficherChoix(idChoix)
{
	var id = parseInt(idChoix,10);
	if(id == 0)
	{
		$("#produits").hide("slow");
		$("#catprod").hide("slow");
		$("#commandes").show("slow");
	}else
		if(id == 1)
		{
			$("#commandes").hide("slow");
			$("#catprod").hide("slow");
			$("#produits").show("slow");
			
		}else
			if(id == 2)
			{
				$("#commandes").hide("slow");
				$("#produits").hide("slow");
				$("#catprod").hide("slow");
			}else
				if(id == 3)
				{
					$("#commandes").hide("slow");
					$("#produits").hide("slow");
					$("#catprod").show("slow");
				}
}
function loadInfoCom(date)
{
	var dateAff = date.split('-');
	var str = "Détail des commandes du <b>"+dateAff[2]+"/"+dateAff[1]+"/"+dateAff[0]+ "</b><br />";

	var xhr = getXhr();
	xhr.onreadystatechange = function()
	{
		if(xhr.readyState == 4 && xhr.status == 200)
		{
			var rst = ParseHTTPResponse (xhr);
			var items =rst.getElementsByTagName('reponse');
			var items2 =rst.getElementsByTagName('news');
			var admin = parseInt(getValeurOfNode(items2,'admin'),10);
			
			if(admin == 0)
				alert('Vous devez être administrateur pour effectuer ce genre de choses!');
			else
			{
				if(items.length == 0)
					str += "Aucun produit à détailler <br />";
				else
				{
					str += "<table><tr><th style='text-align:center;'>Commande</th><th style='text-align:center;'>Client</th style='text-align:center;'><th>Paiement</th><th style='text-align:center;'>Prix</th></tr>";
					var total = parseInt(0,10);
					for(var i = 0 ; i< items.length ;i++)
					{
						var idCom  = (getValeurOfNode3(items,i,'idcom'));
						var idClient = (getValeurOfNode3(items,i,'idclient'));
						var nomClient = (getValeurOfNode3(items,i,'nomclient'));
						var idPaiement = (getValeurOfNode3(items,i,'idpaiement'));
						var com = (getValeurOfNode3(items,i,'totalcom'));
						
						str += "<tr><td>"+idCom+"</td><td>"+idClient+"("+nomClient+")</td><td>"+idPaiement+"</td><td>"+com+"€</td></tr>";
						total += parseInt(com,10);
					}
					str += "</table>";
					str += "Total : <b>"+total+"€</b><br />";
				}
				c('info').innerHTML = str;
			}
		}
	};
	var fic = "ajax/detailcom.php";	
	xhr.open("POST",fic,async);
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');	
	xhr.send("date="+post(date));
		
}
function getInfoCom(date)
{
	$('#info').fadeTo("slow",0,function()
	{
		loadInfoCom(date);
	});
	$('#info').fadeTo("slow",1);
}

function majCommissionType()
{
	var idCat = parseInt(c('cat').options[c('cat').selectedIndex].value,10);
	if(idCat > 0)
	{
		var xhr = getXhr();
		xhr.onreadystatechange = function()
		{
			if(xhr.readyState == 4 && xhr.status == 200)
			{
				var rst = ParseHTTPResponse (xhr);
				var items =rst.getElementsByTagName('news');
				if(items.length > 0)
				{
					montant = getValeurOfNode(items,'montant');
					com = getValeurOfNode(items,'com');
					c('montant').value = montant + '€' ;
					c('com').value = com + '€' ;
				}
			}
			
		};
		var fic = "ajax/detailventestype.php";	
		xhr.open("POST",fic,async);
		xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');	
		xhr.send("id="+idCat);
	}else
	{
		c('com').value = '';
		c('montant').value = '';
	}
}

function majCommission()
{
		
	var xhr = getXhr();
	xhr.onreadystatechange = function()
	{
		if(xhr.readyState == 4 && xhr.status == 200)
		{
			var rst = ParseHTTPResponse (xhr);
			var items =rst.getElementsByTagName('news');
			if(items.length > 0)
			{
				montant = getValeurOfNode(items,'montant');
				com = getValeurOfNode(items,'com');
				c('montant').value = montant + '€' ;
				c('com').value = com + '€' ;
			}
		}
		
	};
	var fic = "ajax/detailventes.php";	
	xhr.open("POST",fic,async);
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');	
	xhr.send();
}

function vider(idForm)
{
	c(idForm).reset();
}
function lockBouton(idB)
{
	c(idB).disabled = true;
}

function unlockBouton(idB)
{
	c(idB).disabled = false;
}
function detailProd()
{
	if(g('idProd').length >= 3)
	{
		var xhr = getXhr();
		xhr.onreadystatechange = function()
		{
			if(xhr.readyState == 4 && xhr.status == 200)
			{
				var rst = ParseHTTPResponse (xhr);
				var items =rst.getElementsByTagName('news');
				if(items.length > 0)
				{
					if(getValeurOfNode(items,'ok') == "NOK")
					{
						c('info').innerHTML = '<img src="images/aide.png" alt="Aide" />L\'identifiant est inconnu';		
						$('#info').fadeTo("slow",1);
						lockBouton('suite');
						vider('form');						
					}else
					{
						if(c('info').innerHTML != "")
						{
							$('#info').fadeTo("slow",0);
						}
						var prix = getValeurOfNode(items,'prix');
						var cat = getValeurOfNode(items,'cat');
						var detail = getValeurOfNode(items,'detail');
						var titre = getValeurOfNode(items,'titre');
						
						c('titre').value = ajax_input(titre);
						c('prix').value = ajax_input(prix+'€');
						c('cat').value = ajax_input(cat);
						c('detail').value = ajax_input(detail);
						c('suite').onclick = function() 
								{
								  document.location.href= "detailprod.php?i=" +g('idProd');
								  return false; 
								}
						unlockBouton('suite');
					}
				}
			}
		};
		var fic = "ajax/detailprod.php";	
		xhr.open("POST",fic,async);
		xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		var idProd = parseInt(g('idProd'),10);
		xhr.send("id="+post(idProd));	
	}else
	{
		vider('form');
		lockBouton('suite');
		$('#info').fadeTo("slow",0);
	}
}
function ajax_input(str)
{
	 var s = document.createElement("TEXTAREA");
	 s.innerHTML = str;
	 return s.value;
}
	
function submit()
{
	$('#form').submit();
}
function supPanier(idProd)
{
	if(window.confirm("Voulez-vous supprimer ce produit du panier ?"))
	{
		var xhr = getXhr();
		xhr.onreadystatechange = function()
		{
			if(xhr.readyState == 4 && xhr.status == 200)
			{
				var rst = ParseHTTPResponse (xhr);
				var items =rst.getElementsByTagName('news');
				if(items.length > 0)
				{
					if(getValeurOfNode(items,'ok') == "NOK")
						alert("Il y a eu un problème lors de la suppression du produit dans le panier");
					else
					{
						c('form').action = 'panier.php';
						c('sup').value = '1';
						$('#form').submit();
					}
				}
			}
		};
		var fic = "ajax/suppanier.php";	
		xhr.open("POST",fic,async);
		xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		xhr.send("id="+post(idProd));	
	}
}

function addPanier(idProd)
{
	if(window.confirm("Voulez-vous ajouter ce produit au panier ?"))
	{
		var xhr = getXhr();
		xhr.onreadystatechange = function()
		{
			if(xhr.readyState == 4 && xhr.status == 200)
			{
				var rst = ParseHTTPResponse (xhr);
				var items =rst.getElementsByTagName('news');
				if(items.length > 0)
				{
					if(getValeurOfNode(items,'ok') == "NOK")
						alert("Il y a eu un problème lors de l'ajout du produit dans le panier");
					else
					{
						$('#info').fadeTo("fast",0,function()
						{
							if(getValeurOfNode(items,'ok') == "OK2")
								c('info').innerHTML = '<img src="images/aide.png" alt="Aide" />Vous avez déjà ajouté ce produit dans votre panier';
							else
							{
								if(getValeurOfNode(items,'ok') == "OK3")
									c('info').innerHTML = '<img src="images/aide.png" alt="Aide" />Le produit est en rupture de stock pour le moment';
								else
									c('info').innerHTML = '<img src="images/aide.png" alt="Aide" />Votre produit a bien été ajouté à votre panier';
							}
							 $('#info').fadeTo("slow",1);
						});
										
					}
				}
			}
		};
		var fic = "ajax/addpanier.php";	
		xhr.open("POST",fic,async);
		xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		xhr.send("id="+post(idProd));	
	}
}

function supProd(idProd)
{
	if(window.confirm("Voulez-vous supprimer ce produit ?"))
	{
		var xhr = getXhr();
		xhr.onreadystatechange = function()
		{
			if(xhr.readyState == 4 && xhr.status == 200)
			{
				var rst = ParseHTTPResponse (xhr);
				var items =rst.getElementsByTagName('news');
				if(items.length > 0)
				{
					if(getValeurOfNode(items,'ok') == "NOK")
						alert("Il y a eu un problème lors de la suppression du produit");
					else
						$("#pref").submit();
				}
			}
		};
		var fic = "ajax/supprod.php";	
		xhr.open("POST",fic,async);
		xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		xhr.send("id="+post(idProd));	
	}
}
function afficherCmd(idCmd)
{
	$().ready(function() 
	{
		var etat = 'etat'+idCmd;
		if(g(etat) == "0")
		{
			loadCmd(idCmd);
			$("#cmd"+idCmd).show("slow");
			c(etat).value = "1";
		}else
		{
			$("#cmd"+idCmd).hide("slow",function()
			{
				c('cmd'+idCmd).innerHTML = "";
			});
			c(etat).value = "0";
		}
	});
}

function loadCmd(idCmd)
{
	var idC = 'cmd'+idCmd;
	var xhr = getXhr();
	xhr.onreadystatechange = function()
	{
		if(xhr.readyState == 4 && xhr.status == 200)
		{
			var rst = ParseHTTPResponse (xhr);
			var items =rst.getElementsByTagName('news');
			if(items.length > 0)
			{
				var str = "";
				if(parseInt(getValeurOfNode(items,'nb'),10) == 0)
					str += "Aucun produit trouvé !";
				else
				{
					str += "<div><table style='width:90%;margin-left:5%;'><tr><th>Produit</th><th style='width:100px;'>Image</th><th>Prix U.</th><th>Qté</th><th>TOTAL</th></tr>";
					items =rst.getElementsByTagName('reponse');
					for(var i = 0;i < items.length;i++)
					{						
						
						var prod = getValeurOfNode3(items,i,'titre');							
						var qte = getValeurOfNode3(items,i,'qte');
						var prixU = getValeurOfNode3(items,i,'prixU');
						var prix = getValeurOfNode3(items,i,'prix');
						var img = getValeurOfNode3(items,i,'img');
							
						str += "<tr style='text-align:center;'><td>" + prod + '</td>';
						str += "<td>";
						if(img != "NOK")
							str += "<img src='produits/"+img+"' alt='Image produit' />";
						else
							str += "-";
						str += '</td><td>' + prix +'</td><td>'+ qte + '</td><td>' + prixU + "</td></tr>";							
					}
					str += "</table></div><br />";
					
					c(idC).innerHTML = str;
				}
			}
		}
	};
	
	var fic = "ajax/loadcmd.php";	
	xhr.open("POST",fic,async);
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	xhr.send("id="+post(idCmd));	
	
}
function supprimerCom(idCom,idA)
{
	if(confirm("Voulez-vous supprimer ce commentaire ? "))
	{
		var idC = "#contenu"+idA;
		var xhr = getXhr();
		xhr.onreadystatechange = function()
		{
			if(xhr.readyState == 4 && xhr.status == 200)
			{
				var rst = ParseHTTPResponse (xhr);
				var items =rst.getElementsByTagName('news');
				if(items.length > 0)
				{
					if(getValeurOfNode(items,'ok') == "OK")
					{
						$(idC).hide("slow",function()
								{
									loadCom(idA,"0");
								});					
						$(idC).show("slow");
						var etat = 'etat'+idA;
						document.getElementById(etat).value = "1";
						document.getElementById('com'+idA).innerHTML = parseInt(document.getElementById('com'+idA).innerHTML,10)-1;
					}else
					{
						alert("Il y a eu un problème lors de la suppression du commentaire");
					}				
				}else
					alert("Veuillez recharger la page");
			}
		};
		var fic = "ajax/supcom.php";	
		xhr.open("POST",fic,async);
		xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		xhr.send("id="+post(idCom));
	}
}

function recherche()
{
	$().ready(function() {

		$('#recherche').autocomplete('ajax/recherche.php');	
		$('#recherche').result(function(event, data, formatted) {
			$(location).attr('href','detailprod.php?i='+data[1]);
		});
	 });
}

function changeSkin()
{
	 $().ready(function()
	 {
		$.ajax({
		  type: "POST",
		  url: "ajax/changeskin.php",
		  data: "id=1",
		  async: false
		});
	});
}

function posterCom(idA)
{
	document.getElementById("add"+idA).innerHTML = "";
	$("#post"+idA).show("slow");
}

function majCom(idA)
{
	var idC = "#contenu"+idA;
	var xhr = getXhr();
	xhr.onreadystatechange = function()
	{
		if(xhr.readyState == 4 && xhr.status == 200)
		{
			var rst = ParseHTTPResponse (xhr);
			var items =rst.getElementsByTagName('reponse');
			if(items.length > 0)
			{
				if(getValeurOfNode(items,'ok') == "OK")
				{
					$(idC).hide("slow",function()
							{
								loadCom(idA,"0");
							});					
					$(idC).show("slow");
					var etat = 'etat'+idA;
					document.getElementById(etat).value = "1";
					document.getElementById('com'+idA).innerHTML = parseInt(document.getElementById('com'+idA).innerHTML,10)+1;
				}else
				{
					alert("Il y a eu un problème lors de l'ajout du commentaire");
				}				
			}			
		}
	};
	var fic = "ajax/addcom.php";	
	var texte = 'texte'+idA;
	var com = document.getElementById(texte).value;
	xhr.open("POST",fic,async);
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	xhr.send("id="+post(idA)+"&com="+post(com));	
	
}
function afficherCom(idA,iPage)
{
	var etat = 'etat'+idA;
	var idC = "#contenu"+idA;
	if(document.getElementById(etat).value == "0")
	{		
		iPage = parseInt(iPage,10);
		loadCom(idA,iPage);
		$(idC).show("slow");
		document.getElementById(etat).value = "1";
	}else
	{
		$(idC).hide("slow");
		document.getElementById(etat).value = "0";
		document.getElementById("contenu"+idA).innnerHTML = "";
	}
}

function majCom2(idA,iPage)
{
	$().ready(function() 
	{
		var idC = "#contenu"+idA;
		iPage = parseInt(iPage,10);
		var etat = 'etat'+idA;
		if(document.getElementById(etat).value == "1")
		{
			$(idC).animate({
			    opacity: 0
			  }, 600, function() {
				loadCom(idA,iPage);
			  });
			$(idC).fadeTo("slow",1);
		}
		
	});
}
function loadCom(idA,iPage)
{

	$().ready(function() 
	{
		var idC = "contenu"+idA;
		iPage = parseInt(iPage,10);
		var nbComParPage = 5;
		var xhr = getXhr();
		xhr.onreadystatechange = function()
		{
			if(xhr.readyState == 4 && xhr.status == 200)
			{
				document.getElementById(idC).innerHTML = '<div id="navig'+idA+'"></div><br />';
				var rst = ParseHTTPResponse (xhr);
				var items =rst.getElementsByTagName('reponse');
				var items2 =rst.getElementsByTagName('news');
				var admin = parseInt(getValeurOfNode(items2,'admin'),10);
				for(var i = 0 ; i< items.length ;i++)
				{
					
					var log  = (getValeurOfNode3(items,i,'login'));
					var date = (getValeurOfNode3(items,i,'date'));
					var h = (getValeurOfNode3(items,i,'heure'));
					var com = (getValeurOfNode3(items,i,'commentaire'));
					
					com = com.replace(/\n/g, "<br />");
					var id = (getValeurOfNode3(items,i,'id'));	
					var image = getValeurOfNode3(items,i,'img');
					
					
					var str = '<li class="comment">';
					str += '<img alt="Avatar" src="'+image+'" class="avatar" height="40" width="40" />';	
					str += '<div class="message"><div class="haut"><div></div></div><div class="milieu"><div class="i2">';		
					str += '<span class="titre">';
					if(admin > 1)
						str += "<img style='float:left;' onclick='supprimerCom(\""+id+"\",\""+idA+"\");' src='images/supprimer.png' alt='Supprimer' /> ";
					
					str += ' Posté par <i><b>'+log+'</b></i> le '+date+' à ' +h+'</span>';
					str += '<span class="links"></span><div><p>'+com+'</p></div></div></div><div class="bas"><div></div>';
					str += '</div></div></li>';
					document.getElementById(idC).innerHTML += str;
				}
				var items =rst.getElementsByTagName('news');
				if(items.length > 0)
				{	
					var nbA = getValeurOfNode(items,'nb');
					if(nbA > nbComParPage)
					{
						var str = "<br /><br />";
						str += '<div>';
						if( iPage != 0)
						{
							str += "<span class='lien' style='position:relative;margin-left:10%;' onclick='majCom2(\""+idA+"\",\""+(iPage-1)+"\");'><img src='images/prev.png' alt='Prec &lt;' /></span>";
							var marge = 83;
						}else
							var marge = 96;
						
						
						var nbPages = Math.ceil(nbA/nbComParPage);	
						
						if((iPage+1) != nbPages)
							str += "<span class='lien' style='position:relative;margin-left:"+marge+"%;' onclick='majCom2(\""+idA+"\",\""+(iPage+1)+"\");'><img src='images/next.png' alt='Next &gt;' /></span>";
	
						str +="</div>";
						str += "<span style='position:relative;margin-left:50%;font-size: 11pt;'>Page "+(iPage+1)+"/"+nbPages+"</span>";
						document.getElementById('navig'+idA).innerHTML = str;
					}
					
					
					
					var connec = getValeurOfNode(items,'connec');
					var str ='<div class="coms" id="add'+idA+'">';
					if(connec == "1")
						str += "<span class='lien' onclick='posterCom(\""+idA+"\");' ><img alt='Ecrire' src='images/ecrire.gif' /> Poster un commentaire</span>";
					else
						str += "Vous devez être connecté pour poster un message";
					
					id = 'texte'+idA;
					str += '</div><div id="post'+idA+'" style="display:none;"><br /><br /><span style="position:relative;left:15px;bottom:30px;float:left;">Votre message:</span><textarea style="resize:none;" id="'+id+'" cols="75" rows="4" ></textarea>';
					str += "<div style='text-align:center;'><input type='button' onclick='majCom(\""+idA+"\");' value='Poster' /></div></div>";					
					document.getElementById(idC).innerHTML += str;
				}
			}
		};
		
		var fic = "ajax/loadcom.php";
		xhr.open("POST",fic,async);
		xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		xhr.send("id="+post(idA)+"&ip="+post(iPage));	
	});
}

	
function getValeurOfNode(parent,enfant)
{
	if(parent[0].getElementsByTagName(enfant)[0].childNodes.length > 0)
	{
		var retour = parent[0].getElementsByTagName(enfant)[0].firstChild.nodeValue;
	}else
	{
		var retour = "";
	}
	
	return s(retour);
}
	
function getValeurOfNode3(parent,index,enfant)
{
	if(parent[index].getElementsByTagName(enfant)[0].childNodes.length > 0)
	{
		var retour = parent[index].getElementsByTagName(enfant)[0].firstChild.nodeValue;
	}else
	{
		var retour = "";
	}	
	return s(retour);
}	

function c(id)
{
	return document.getElementById(id);
}

function g(id)
{
	return document.getElementById(id).value;
}

function post(str)
{
	if(typeof str == "string")
	{
		var retour = str.replace(/&/g, " ");
	}else
		var retour = str;
	
	return retour;
}

function s(wText)
{
 	var wText;
    wText =wText.replace(/&/g, "&amp;") ;
	wText=wText.replace(/"/g, "&quot;") ;
	wText=wText.replace(/</g, "&lt;") ;
	wText=wText.replace(/>/g, "&gt;") ;
	wText=wText.replace(/'/g, "&#146;") ;
return wText;
}

function getXhr(){
    var xhr = null; 
    if(window.XMLHttpRequest) 
       xhr = new XMLHttpRequest(); 
    else if(window.ActiveXObject){ 
			try{
				 xhr = new ActiveXObject("Msxml2.XMLHTTP");				 
			}catch(e){
				xhr = new ActiveXObject("Microsoft.XMLHTTP");				
			}
		}else{ 
            alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest..."); 
            xhr = false; 
        } 
    return xhr;
}
function ParseHTTPResponse(httpRequest) {
    var xmlDoc=httpRequest.responseXML;
    if(!xmlDoc||!xmlDoc.documentElement){
        if(window.DOMParser){
            var parser = new DOMParser();
             try{
                xmlDoc=parser.parseFromString(httpRequest.responseText, "text/xml");
             }catch (e){};
        }else{
            xmlDoc=CreateMSXMLDocumentObject();
            if(!xmlDoc)
                return null;
             xmlDoc.loadXML (httpRequest.responseText);
		}
    }
    var errorMsg = null;
    if(xmlDoc.parseError && xmlDoc.parseError.errorCode!=0){
        errorMsg = "XML Parsing Error: " + xmlDoc.parseError.reason
         + " at line " + xmlDoc.parseError.line
         + " at position " + xmlDoc.parseError.linepos;
    }else{
        if(xmlDoc.documentElement){
            if(xmlDoc.documentElement.nodeName == "parsererror"){
                 errorMsg = xmlDoc.documentElement.childNodes[0].nodeValue;
            }
        }
    }
    if(errorMsg){
         alert (errorMsg);
		 // var doc = document.getElementById("droite").innerHTML;
		 // document.getElementById("droite").innerHTML = "<span style='color:red;' ><img src='images/dead.png' alt='Erreur' />"+errorMsg+"</span>" + doc;
         return null;
    }
    return xmlDoc;
}