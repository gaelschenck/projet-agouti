<?php 

// On inclue le fichier de configuration et de connexion a la base de donnees
require_once("includes/config.php");
// On recupere dans $_GET l email soumis par l'utilisateur
$adresse = valid_donnees($_GET['mail']);
			if (!empty($adresse)
            && strlen($adresse) <= 50
            && preg_match("#[-0-9a-zA-Z.+_]+@[-0-9a-zA-Z.+_]+.[a-zA-Z]{2,4}#",$adresse))
		{
	error_log($adresse);
	$verif = filter_var($adresse, FILTER_VALIDATE_EMAIL);
	error_log($verif);
	// On verifie que l'email est un email valide (fonction php filter_var)
		if ($verif){
			//echo ("$adresse est une adresse valide<br>");
			// Si c'est bon
			// On prepare la requete qui recherche la presence de l'email dans la table tblreaders
			$sql = "SELECT * FROM tblreaders WHERE EmailId = :EmailId";
			$query = $dbh -> prepare($sql);
			$query->bindParam(":EmailId" , $adresse, PDO::PARAM_STR);
			
			// On execute la requete et on stocke le resultat de recherche
			$query->execute();
			$result = $query->fetch();
			
			
			// Si le resultat n'est pas vide. On signale a l'utilisateur que cet email existe deja et on desactive le bouton
			// de soumission du formulaire
				if ($result){
					echo  "1";//(" $adresse est un mail dejà pris ! ");
					$status = "disabled='disabled'";
				} else {
					// Sinon on signale a l'utlisateur que l'email est disponible et on active le bouton du formulaire
					echo "2";//("$adresse est une adresse disponible");
					$status = "disabled='enabled'";
				}
		}else{
		// Si ce n'est pas le cas, on fait un echo qui signale l'erreur
		echo "3";//("$adresse n'est pas une adresse valide");
		
		};	}	
		?>
		