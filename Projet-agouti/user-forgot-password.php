<?php
// On récupère la session courante
session_start();
// On inclue le fichier de configuration et de connexion à la base de données
include('includes/config.php');
// Après la soumission du formulaire de login ($_POST['change'] existe
// On verifie si le code captcha est correct en comparant ce que l'utilisateur a saisi dans le formulaire
// $_POST["vercode"] et la valeur initialisee $_SESSION["vercode"] lors de l'appel a captcha.php (voir plus bas)
if (TRUE === isset($_POST['change'])) {
     if ($_POST['vercode'] != $_SESSION['vercode']) {
             
     

// Si le code est incorrect on informe l'utilisateur par une fenetre pop_up
echo '<script>alert ("Wrong Lever !"); </script>';
     
// Sinon on continue
 }else{
// on recupere l'email et le numero de portable saisi par l'utilisateur
// et le nouveau mot de passe que l'on encode (fonction password_hash)
     $mail = valid_donnees($_POST['emailid']);
     $phone = valid_donnees($_POST['mobilenumber']);
     $mdp = valid_donnees($_POST['password']);
     $password = password_hash($mdp, PASSWORD_DEFAULT);
     if(!empty($phone)
     && strlen($phone) <= 10
     && preg_match("#^[0-9]{10}#",$phone)
     && !empty($mail)
     && strlen($mail) <= 30
     && preg_match("#[-0-9a-zA-Z.+_]+@[-0-9a-zA-Z.+_]+.[a-zA-Z]{2,4}#",$mail)
     &&!empty($mdp)
     && strlen($mdp) <= 30
     && preg_match("#[-0-9a-zA-Z.+_@]#",$mdp))
     {
    

    
// On cherche en base le lecteur avec cet email et ce numero de tel dans la table tblreaders
$sql = "SELECT EmailId, MobileNumber FROM tblreaders WHERE EmailId=:emailid AND MobileNumber=:mobilenumber";
$query=$dbh->prepare($sql);
$query->bindParam(':emailid', $mail, PDO::PARAM_STR);
$query->bindParam(':mobilenumber',$phone, PDO::PARAM_INT);
$query->execute();
$result= $query->fetch(PDO::FETCH_OBJ);
// Si le resultat de recherche n'est pas vide
if($result){
        

// On met a jour la table tblreaders avec le nouveau mot de passe
$sql = "UPDATE tblreaders SET Password=:password WHERE EmailId=:emailid AND MobileNumber=:mobilenumber";
     $query = $dbh->prepare($sql);
     $query->bindParam(':password',$password, PDO::PARAM_STR);
     $query->execute();
     echo '<script>alert ("mot de passe modifié"); </script>';
     header ('location:index.php');
// On informa l'utilisateur par une fenetre popup de la reussite ou de l'echec de l'operation
}else{
     echo '<script>alert ("Le mail et/ou le numéro de téléphone ne correspond pas"); </script>';
}
}}
}
?>

<!DOCTYPE html>
<html lang="FR">

<head>
     <meta charset="utf-8" />
     <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

     <title>Gestion de bibliotheque en ligne | Recuperation de mot de passe </title>
     <!-- BOOTSTRAP CORE STYLE  -->
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
     <!-- FONT AWESOME STYLE  -->
     <link href="assets/css/font-awesome.css" rel="stylesheet" />
     <!-- CUSTOM STYLE  -->
     <link href="assets/css/style.css" rel="stylesheet" />

     <script type="text/javascript">
          // On cree une fonction nommee valid() qui verifie que les deux mots de passe saisis par l'utilisateur sont identiques.
     </script>

</head>

<body>
     <!--On inclue ici le menu de navigation includes/header.php-->
     <?php include('includes/header.php'); ?>
     <!-- On insere le titre de la page (RECUPERATION MOT DE PASSE -->
     <div class="container">
		<div class="row">
			<div class="col">
				<h3>MOT DE PASSE OUBLIÉ</h3>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-8 offset-md-3">
				<form method="post" action="user-forgot-password.php" >
                    <div class="form-group">
						<label>Entrez votre numéro de téléphone</label>
						<input type="tel" name="mobilenumber" required pattern="#^[0-9]{10}#" maxlength="10">
					</div>
                    <div class="form-group">
                         <label>Entrez votre email</label>
                         <input type="text" name="emailid" id ="mail"required pattern="#[-0-9a-zA-Z.+_]+@[-0-9a-zA-Z.+_]+.[a-zA-Z]{2,4}#" maxlength="30">
                    <span id="answer"></span>
                    </div>

                    <div class="form-group">
                         <label>Entrez votre mot de passe</label>
                         <input type="password" name="password" id="password" required required pattern ="#[-0-9a-zA-Z.+_@]#" maxlength="30">
                    </div>
                    <div class="form-group">
                         <label>Veuillez vérifier votre mot de passe</label>
                         <input type="password" name="password2" id="password2" required required pattern ="#[-0-9a-zA-Z.+_@]#" maxlength="30">
                    </div>

                    <div class="form-group">
                         <label>Code de vérification</label>
                         <input type="text" name="vercode" required style="height:25px;">&nbsp;&nbsp;&nbsp;<img src="captcha.php">
                    </div>

                    <button type="submit" name="change" class="btn btn-info" <?php $status ?>>VALIDER</button>
				</form>
			</div>
		</div>
	</div>
     <!--On insere le formulaire de recuperation-->
     <!--L'appel de la fonction valid() se fait dans la balise <form> au moyen de la propri�t� onSubmit="return valid();"-->


     <?php include('includes/footer.php'); ?>
     <!-- FOOTER SECTION END-->
     <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
     <script>
          window.addEventListener("load", () =>{
        let password = document.getElementById("password");
        let confirm = document.getElementById("password2");
        let button = document.querySelector("button[name='change']");
               valid(); 
          function valid(){
          confirm.addEventListener("keyup" ,() => {
          if (password.value != confirm.value){
               button.disabled = true;
          } else {
               button.disabled = false;
               alert ("Concordance du mot de passe");
          }})
          }
     })
     </script>
</body>

</html>