<?php 
// On r�cup�re la session courante
session_start();

// On inclue le fichier de configuration et de connexion � la base de donn�es
include('includes/config.php');

if(strlen($_SESSION['login'])==0) {
    header('location:index.php');
}else{
    $readerid = $_SESSION['rdid'];
    $sql = "SELECT * FROM tblreaders WHERE ReaderId = :rdid";
		$query = $dbh->prepare($sql);
		$query->bindParam(':rdid', $readerid, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        // var_dump($result);
        if($result['Status']=== "1"){
            $result['Status'] = "actif";
        } else{
            $result['Status'] = "inactif";
        }

        $name =$result['FullName'];
        $phone=$result['MobileNumber'];
        $mail=$result['EmailId'];

if (TRUE === isset($_POST['update'])) {

    $newname =$_POST['fullname'];
    if(!$newname){
        $newname = $name;
    }
    $newphone=$_POST['mobilenumber'];
    if(!$newphone){
        $newphone = $phone;
    }
    $newmail=$_POST['emailid'];
    if(!$newmail){
        $newmail = $mail;
    }
    

    $sql = "UPDATE tblreaders SET FullName=:fullname, MobileNumber =:mobilenumber, EmailId=:emailid WHERE ReaderId=:rdid";
     $query = $dbh->prepare($sql);
     $query->bindParam(':fullname',$newname, PDO::PARAM_STR);
     $query->bindParam(':mobilenumber',$newphone, PDO::PARAM_STR);
     $query->bindParam(':emailid',$newmail, PDO::PARAM_STR);
     $query->bindParam(':rdid',$readerid, PDO::PARAM_STR);
     $query->execute();
     echo '<script>alert ("Données mises à jour");
     location.href="my-profile.php" </script>';
     
}
}
// Si l'utilisateur n'est plus logu�
// On le redirige vers la page de login
	// Sinon on peut continuer. Apr�s soumission du formulaire de profil

    	// On recupere l'id du lecteur (cle secondaire)

        // On recupere le nom complet du lecteur

        // On recupere le numero de portable

		// On update la table tblreaders avec ces valeurs
        // On informe l'utilisateur du resultat de l'operation


	// On souhaite voir la fiche de lecteur courant.
	// On recupere l'id de session dans $_SESSION

	// On prepare la requete permettant d'obtenir 

?>

<!DOCTYPE html>
<html lang="FR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliotheque en ligne | Profil</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />

</head>
<body>
    <!-- On inclue le fichier header.php qui contient le menu de navigation-->
<?php include('includes/header.php');?>
<!--On affiche le titre de la page : EDITION DU PROFIL-->
<div class="container">
		<div class="row">
			<div class="col">
				<h3>MON COMPTE</h3>
			</div>
		</div>

        <div class="row">
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-8 offset-md-3">
            <form method="post" action="my-profile.php" >
                <div class="form-group">Identifiant lecteur : <?php echo($result['ReaderId']);?></div>
                <div class="form-group">Date enregistrement : <?php echo($result['RegDate']);?></div>
                <div class="form-group">Date de la dernière mise à jour du compte : <?php echo($result['UpdateDate']);?></div>
                <div class="form-group">Statut du lecteur : <?php echo($result['Status']);?></div>
				
                    <div class="form-group">
						<label>Entrez votre nom</label>
						<input type="text" name="fullname" placeholder="<?php echo($name);?>">
					</div>
                    <div class="form-group">
						<label>Entrez votre numéro de téléphone</label>
						<input type="tel" name="mobilenumber"  placeholder="<?php echo($phone);?>">
					</div>
					<div class="form-group">
						<label>Entrez votre email</label>
						<input type="text" name="emailid" id ="mail" onblur="availability(mail)"  placeholder="<?php echo($mail);?>">
                        <span id="answer"></span>
					</div>
                    <button type="submit" name="update" class="btn btn-info" <?php $status ?>>VALIDER</button>
                </form>
            </div>
		</div>
</div>
 <!--On affiche le formulaire-->
            <!--On affiche l'identifiant - non editable-->

			<!--On affiche la date d'enregistrement - non editable-->

            <!--On affiche la date de derniere mise a jour - non editable-->

			<!--On affiche la statut du lecteur - non editable-->

			<!--On affiche le nom complet - editable-->

			<!--On affiche le numero de portable- editable-->

			<!--On affiche l'email- editable-->
 
    <?php include('includes/footer.php');?>
     <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
     <script>
     function availability(mail){
            let span = document.getElementById("answer");
                console.log(mail);
                console.log(mail.value);
            fetch('check_availability.php?mail='+mail.value)
            .then(rep => rep.json())
            .then(data => {
                console.log(data)

                switch(data){
                    case 1 :
                        span.innerHTML = "Est un mail déjà pris.";
                        break;
                    case 2:
                        span.innerHTML = "Est une adresse disponible.";
                        break;
                    case 3:
                        span.innerHTML = "N'est pas une adresse valide.";
                        break;
                    default:
                        break;
                }
       })               
    }
    </script>
</body>
</html>