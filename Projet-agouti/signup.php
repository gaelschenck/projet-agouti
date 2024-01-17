<?php
// On récupère la session courante
session_start();

// On inclue le fichier de configuration et de connexion à la base de données
include('includes/config.php');

if (TRUE === isset($_POST['submit'])) {
    // Aaprès la soumission du formulaire de compte (plus bas dans ce fichier)
    // On vérifie si le code captcha est correct en comparant ce que l'utilisateur a saisi dans le formulaire
    // $_POST["vercode"] et la valeur initialisée $_SESSION["vercode"] lors de l'appel à captcha.php (voir plus bas)
    if ($_POST['vercode'] != $_SESSION['vercode']) {
        echo '<script>alert ("Wrong Lever !"); </script>';
        exit();
    }
        //On lit le contenu du fichier readerid.txt au moyen de la fonction 'file'. Ce fichier contient le dernier identifiant lecteur cree.
        $readerid = file_get_contents('readerid.txt');
        // On incrémente de 1 la valeur lue
        $readerid++ ;
        // On ouvre le fichier readerid.txt en écriture
        $file = fopen('readerid.txt', 'wb');
        // On écrit dans ce fichier la nouvelle valeur
        fwrite($file, $readerid);
        // On referme le fichier
        fclose($file);
        

        // On récupère le nom saisi par le lecteur
        $fullname = valid_donnees($_POST['fullname']);
        // On récupère le numéro de portable
        $phone = valid_donnees($_POST['mobilenumber']);
        // On récupère l'email
        $mail = valid_donnees($_POST['emailid']);
        // On récupère le mot de passe
        $mdp = valid_donnees($_POST['password']);
        if (!empty($fullname)
            && strlen($fullname) <= 30
            && preg_match("#[-0-9a-zA-Z.+_@]#",$fullname)
            && !empty($phone)
            && strlen($phone) <= 10
            && preg_match("#^[0-9]{10}#",$phone)
            && !empty($mail)
            && strlen($mail) <= 30
            && preg_match("#[-0-9a-zA-Z.+_]+@[-0-9a-zA-Z.+_]+.[a-zA-Z]{2,4}#",$mail)
            &&!empty($password)
            && strlen($password) <= 30
            && preg_match("#[-0-9a-zA-Z.+_@]#",$password))
{

        $password = password_hash($mdp , PASSWORD_DEFAULT);
        // On fixe le statut du lecteur à 1 par défaut (actif)
        $status = 1;
        // On prépare la requete d'insertion en base de données de toutes ces valeurs dans la table tblreaders
        $query = $dbh->prepare("INSERT INTO tblreaders (ReaderId, FullName, MobileNumber, EmailId, Password, Status) VALUES (:readerid, :fullname, :phone, :mail, :password, :status)");
        // On éxecute la requete
        $query->bindParam(':readerid', $readerid, PDO::PARAM_STR);
        $query->bindParam(':fullname', $fullname, PDO::PARAM_STR);
        $query->bindParam(':phone', $phone, PDO::PARAM_INT);
        $query->bindParam(':mail', $mail, PDO::PARAM_STR);
        $query->bindParam(':password', $password, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_INT);
		$query->execute();
        // On récupère le dernier id inséré en bd (fonction lastInsertId)
        $lastid = $dbh ->lastInsertId();
        // Si ce dernier id existe, on affiche dans une pop-up que l'opération s'est bien déroulée, et on affiche l'identifiant lecteur (valeur de $hit[0])
        if ($lastid){
            echo "<script>alert('Opération terminée avec succès')</script>";
            header ('location:index.php');
        }else{
            echo "<script>alert('Erreur')</script>";
        }

        // Sinon on affiche qu'il y a eu un problème
        // Le code est incorrect on informe l'utilisateur par une fenetre pop_up
    }}
?>

<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <!--[if IE]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <![endif]-->
    <title>Gestion de bibliotheque en ligne | Signup</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <!-- link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' / -->
</head>

<body>
    <!-- On inclue le fichier header.php qui contient le menu de navigation-->
    <?php include('includes/header.php'); ?>
    <div class="container">
		<div class="row">
			<div class="col">
				<h3>CREER UN COMPTE</h3>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-8 offset-md-3">
				<form method="post" action="signup.php" >
                    <div class="form-group">
						<label>Entrez votre nom</label>
						<input type="text" name="fullname" required pattern ="#[-0-9a-zA-Z.+_@]#" maxlength="30">
					</div>
                    <div class="form-group">
						<label>Entrez votre numéro de téléphone</label>
						<input type="tel" name="mobilenumber" required pattern="#^[0-9]{10}#" maxlength="10">
					</div>
					<div class="form-group">
						<label>Entrez votre email</label>
						<input type="text" name="emailid" id ="mail"required pattern="#[-0-9a-zA-Z.+_]+@[-0-9a-zA-Z.+_]+.[a-zA-Z]{2,4}#" maxlength="30" onblur="availability(mail)">
                        <span id="answer"></span>
					</div>

					<div class="form-group">
						<label>Entrez votre mot de passe</label>
						<input type="password" name="password" id="password" required pattern ="#[-0-9a-zA-Z.+_@]#" maxlength="30">
					</div>
                    <div class="form-group">
						<label>Veuillez vérifier votre mot de passe</label>
						<input type="password" name="password2" id="password2" required required pattern ="#[-0-9a-zA-Z.+_@]#" maxlength="30">
					</div>

					<div class="form-group">
						<label>Code de vérification</label>
						<input type="text" name="vercode" required style="height:25px;">&nbsp;&nbsp;&nbsp;<img src="captcha.php">
					</div>

					<button type="submit" name="submit" class="btn btn-info" <?php $status ?>>VALIDER</button>
				</form>
			</div>
		</div>
	</div>
    <!--On affiche le titre de la page : CREER UN COMPTE-->
    <!--On affiche le formulaire de creation de compte-->
    <!--A la suite de la zone de saisie du captcha, on insère l'image créée par captcha.php : <img src="captcha.php">  -->
    <!-- On appelle la fonction valid() dans la balise <form> onSubmit="return valid(); -->
    <!-- On appelle la fonction checkAvailability() dans la balise <input> de l'email onBlur="checkAvailability(this.value)" -->



    <?php include('includes/footer.php'); ?>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        // On cree une fonction valid() sans paramètre qui renvoie 
        // TRUE si les mots de passe saisis dans le formulaire sont identiques
        // FALSE sinon
        window.addEventListener("load", () =>{
        let password = document.getElementById("password");
        let confirm = document.getElementById("password2");
        let button = document.querySelector("button[name='submit']");

       
        valid(); 

        function valid(){
            confirm.addEventListener("keyup" ,() => {
            if (password.value != confirm.value){
                button.disabled = true;
            } else {
                button.disabled = false;
                alert ("Concordance du mot de passe");
            }})
        }})
        
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

        // On cree une fonction avec l'email passé en paramêtre et qui vérifie la disponibilité de l'email
        // Cette fonction effectue un appel AJAX vers check_availability.php
    </script>
</body>

</html>