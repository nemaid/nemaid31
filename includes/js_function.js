function warningMessChangeTab(sessionStatut, dirPage) {
	if(sessionStatut == true) {
		if (confirm("Warning: Change tab during a session will reset all default values and erase all what you have done so far (except what you have saved on your own computer of course). Are you sure you want to do that?"))
		{
		window.location.replace(dirPage);}
	}
}

//Function to hide list on select genus
function hide() {
	document.getElementById('listHeli').style.visibility = 'hidden';
}

function show() {
	document.getElementById('listHeli').style.visibility = 'visible';
}

// Fonctionnement des boutons + pour afficher/masquer les détails
function showHideDetails(id) {
	if ($("tr.details").eq(id).is(":visible")){
		$("tr.details").eq(id).hide();		$("button").eq(id).text('+');
	} else {
		$("tr.details").eq(id).show();		$("button").eq(id).text('-');
	}
}
////// Fonction qui regarde si la table choisie est characters et si c'est le cas qui redirige vers sa page
function ifCharacters(idSelect)
{	//document.write("On est dans la fonction !");
	//document.write("Id du select : " + idSelect);
	if (document.getElementById(idSelect).value == "characters")
	{
		if (idSelect == "selTableDel")
			window.location.replace("delChar.php");
		if (idSelect == "selTableMod")
			window.location.replace("ModChar.php");
	}
	if (document.getElementById(idSelect).value == "genera")
	{
		if (idSelect == "selTableDel")
			window.location.replace("delGen.php");
		if (idSelect == "selTableMod")
			window.location.replace("ModGen.php");
	}
}
	
////// Fonction qui envoie vers le lien cocnerné par la lettre de l'alphabet
function envoyerVers(action, lettre)
{
	//document.write("On est dans la fonction !");
	//document.write(" -> ID de la lettre sélectionnée : " + lettre + " Action : " + action);
	if (action == "mod")
	{
		var table = "selTableMod";
		switch (document.getElementById(table).value)
		{
			case "data":
				window.location.replace("modGeneral.php?lettre=" + lettre);
				break;
			case "genera":
				window.location.replace("modGen.php?lettre=" + lettre);
				break;
			case "references":
				window.location.replace("modRef.php?lettre=" + lettre);
				break;
			case "species":
				window.location.replace("modSpe.php?lettre=" + lettre);
				break;
			case "characters":
				window.location.replace("modChar.php?lettre=" + lettre);
				break;
			case "users":
				window.location.replace("modUser.php?lettre=" + lettre);
				break;
			default : "NULL";
		}
	}
	else
	{
		if (action == "del")
		{
			var table = "selTableDel";
			switch (document.getElementById(table).value)
			{
				case "data":
					window.location.replace("delGeneral.php?lettre=" + lettre);
					break;
				case "genera":
					window.location.replace("delGen.php?lettre=" + lettre);
					break;
				case "references":
					window.location.replace("delRef.php?lettre=" + lettre);
					break;
				case "species":
					window.location.replace("delSpe.php?lettre=" + lettre);
					break;
				case "characters":
					window.location.replace("delChar.php?lettre=" + lettre);
					break;
				case "users":
					window.location.replace("delUser.php?lettre=" + lettre);
					break;
				default : "NULL";
			}
		}
	}
}

////// Fonction qui coche automatiquement toutes les descriptions d'une espèce quand cette même espèce est cochée.
function cocherDescription(nbSpe, nbDesc)
{
	//document.write("On est dans la fonction !");
	//document.write("Num spé : " + nbSpe + ", Nb desc : " + nbDesc);
	for (j = 0 ; j < nbDesc ; j++)
	{
		var ssCB = 'cb_' + nbSpe + '_' + j;
		var main = 'cb_' + nbSpe;
		//document.write("Main : " + main + ", ssCB : " + ssCB);
		if (document.getElementById(main).checked)
		{
			document.getElementById(ssCB).checked = 1;
			document.getElementById(ssCB).disabled = 1;
			//document.write("Ca boucle et ça coche :-)");
		}
		else
		{
			document.getElementById(ssCB).checked = 0;
			document.getElementById(ssCB).disabled = 0;
			//document.write("Ca boucle et ça décoche :-)");
		}
	}
}

////// Fonction qui coche automatiquement une espèce quand toutes ses descriptions sont cochées
function cocherEspece(nbSpe, nbDesc)
{
	//document.write("On est dans la fonction !");
	//document.write("Num spé : " + nbSpe + ", Nb desc : " + nbDesc);
	var cpt = 0;
	for (k = 0 ; k < nbDesc ; k++)
	{
		var ssCB = 'cb_' + nbSpe + '_' + k;
		if (document.getElementById(ssCB).checked)
			cpt++;
	}
	var main = 'cb_' + nbSpe;
	if (cpt == nbDesc)
	{
		document.getElementById(main).checked = 1;
		for (l = 0 ; l < nbDesc ; l++)
		{
			var ssCB = 'cb_' + nbSpe + '_' + l;
			document.getElementById(ssCB).disabled = 1;
		}
	}
}
