<?php 
require_once("includes/config.php");

$isbnnumber = $_GET['isbn'];
		if ($isbnnumber){
			$sql = "SELECT * FROM tblbooks WHERE ISBNNumber = :isbn";
			$query = $dbh -> prepare($sql);
			$query->bindParam(":isbn" , $isbnnumber, PDO::PARAM_INT);
			$query->execute();
			$result2 = $query->fetch(PDO::FETCH_ASSOC);
				if ($result2){
					 echo json_encode (['sprout2' => "{$result2['BookName']}"] );
				}else{
					echo json_encode (['sprout2' => "ISBN non valide"] );	 		
		}
	};
/* Cette fonction est declenchee au moyen d'un appel AJAX depuis le formulaire de sortie de livre */
/* On recupere le numero ISBN du livre*/
// On prepare la requete de recherche du livre correspondnat
// On execute la requete
// Si un resultat est trouve
	// On affiche le nom du livre
	// On active le bouton de soumission du formulaire
// Sinon
	// On affiche que "ISBN est non valide"
	// On desactive le bouton de soumission du formulaire 
?>
