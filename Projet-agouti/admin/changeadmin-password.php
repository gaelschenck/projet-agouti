<?php
session_start();

include('includes/config.php');
if(strlen($_SESSION['alogin'])==0) {
    header('location:index.php');
}else{
	if (TRUE === isset($_POST['change'])) {
		$name = valid_donnees($_SESSION['alogin']);
		if (!empty($name)
		&& strlen($name) <= 7
		&& preg_match("#[-0-9a-zA-Z.+_]+@[-0-9a-zA-Z.+_]+.[a-zA-Z]{2,4}#",$name))
{
		$sql = "SELECT * FROM admin WHERE UserName=:username";
		$query=$dbh->prepare($sql);
		// $query->bindParam(':password', $password, PDO::PARAM_STR);
		$query->bindParam(':username', $name, PDO::PARAM_STR);
		$query->execute();
		$result= $query->fetch(PDO::FETCH_OBJ);
}
	if(!empty($result) && password_verify($_POST['password'], $result->Password)){
		$newmdp = valid_donnees($_POST['newpassword']);
		if (!empty($newmdp)
		&& strlen($newmdp) <=30
		&& preg_match("#[-0-9a-zA-Z.+_@]#",$newmdp))
		{
		$newpassword = password_hash($newmdp, PASSWORD_DEFAULT);
		$sql = "UPDATE admin SET Password=:newpassword WHERE UserName=:username";
     $query = $dbh->prepare($sql);
     $query->bindParam(':newpassword',$newpassword, PDO::PARAM_STR);
	 $query->bindParam(':username', $name, PDO::PARAM_STR);
     $query->execute();
     echo '<script>alert ("mot de passe modifié"); </script>';
	}else{
		echo '<script>alert ("erreur dans la modification du mot de passe"); </script>';
	}}
	}
}

// Si l'utilisateur n'est plus logué
// On le redirige vers la page de login
// Sinon on peut continuer. Après soumission du formulaire de modification du mot de passe
// Si le formulaire a bien ete soumis
// On recupere le mot de passe courant
// On recupere le nouveau mot de passe
// On recupere le nom de l'utilisateur stocké dans $_SESSION

// On prepare la requete de recherche pour recuperer l'id de l'administrateur (table admin)
// dont on connait le nom et le mot de passe actuel
// On execute la requete

// Si on trouve un resultat
// On prepare la requete de mise a jour du nouveau mot de passe de cet id
// On execute la requete
// On stocke un message de succès de l'operation
// On purge le message d'erreur
// Sinon on a trouve personne	
// On stocke un message d'erreur

// Sinon le formulaire n'a pas encore ete soumis
// On initialise le message de succes et le message d'erreur (chaines vides)
?>

<!DOCTYPE html>
<html lang="FR">

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<title>Gestion bibliotheque en ligne</title>
	<!-- BOOTSTRAP CORE STYLE  -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<!-- FONT AWESOME STYLE  -->
	<link href="assets/css/font-awesome.css" rel="stylesheet" />
	<!-- CUSTOM STYLE  -->
	<link href="assets/css/style.css" rel="stylesheet" />
	<!-- Penser a mettre dans la feuille de style les classes pour afficher le message de succes ou d'erreur  -->
</head>
<script type="text/javascript">
	function valid() {
	}
</script>

<body>
	<!------MENU SECTION START-->
	<?php include('includes/header.php'); ?>
	<!-- MENU SECTION END-->
	<div class="container">
		<div class="row">
			<div class="col">
				<h3>CHANGER MOT DE PASSE</h3>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-8 offset-md-3">
				<form method="post" action="changeadmin-password.php" >
                    <div class="form-group">
						<label>Mot de passe actuel</label>
						<input type="password" name="password" required pattern="[-0-9a-zA-Z.+_@]" maxlength="30">
					</div>
                    <div class="form-group">
                         <label>Entrez votre nouveau mot de passe</label>
                         <input type="password" name="newpassword" id ="password"required pattern="[-0-9a-zA-Z.+_@]" maxlength="30">
                    <span id="answer"></span>
                    </div>

                    <div class="form-group">
                         <label>Confirmez votre nouveau mot de passe</label>
                         <input type="password" name="confirmnewpassword" id="password2" required required pattern="[-0-9a-zA-Z.+_@]" maxlength="30">
                    </div>
					<button type="submit" name="change" class="btn btn-info" <?php $status ?>>VALIDER</button>
				</form>
			</div>
		</div>
	</div>
	<!-- On affiche le titre de la page "Changer de mot de passe"  -->
	<!-- On affiche le message de succes ou d'erreur  -->

	<!-- On affiche le formulaire de changement de mot de passe-->
	<!-- La fonction JS valid() est appelee lors de la soumission du formulaire onSubmit="return valid();" -->

	<!-- CONTENT-WRAPPER SECTION END-->
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