<?php 
require_once("includes/config.php");

$readername = $_GET['readerid'];
		if ($readername){
			$sql = "SELECT FullName, Status FROM tblreaders WHERE ReaderId = :readerid";
			$query = $dbh -> prepare($sql);
			$query->bindParam(":readerid" , $readername, PDO::PARAM_STR);
			$query->execute();
			$result = $query->fetch();
			if ($result['Status'] === "0"){
				echo json_encode (['sprout' => "Lecteur bloqué"]);
			}else{
				echo json_encode (['sprout' => "{$result['FullName']}"]);	
			}
		}else{
			echo json_encode (['sprout' => "Lecteur non valide"]);			
		};
/* Cette fonction est declenchee au moyen d'un appel AJAX depuis le formulaire de sortie de livre */
/* On recupere le numero l'identifiant du lecteur SID---*/
// On prepare la requete de recherche du lecteur correspondnat
// On execute la requete
// Si un resultat est trouve
	// On affiche le nom du lecteur
	// On active le bouton de soumission du formulaire
// Sinon
	// Si le lecteur n existe pas
		// On affiche que "Le lecteur est non valide"
		// On desactive le bouton de soumission du formulaire
	// Si le lecteur est bloque
		// On affiche lecteur bloque
		// On desactive le bouton de soumission du formulaire

?>
