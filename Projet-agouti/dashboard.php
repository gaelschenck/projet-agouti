<?php
// On recupere la session courante
session_start();
// var_dump($_SESSION);
// On inclue le fichier de configuration et de connexion a la base de donn�es
include('includes/config.php');


if(strlen($_SESSION['login'])==0) {
	// Si l'utilisateur est d�connect�
	// L'utilisateur est renvoy� vers la page de login : index.php
  header('location:index.php');
} else {
     if (TRUE === isset($_SESSION['rdid'])) {
          $readerId = valid_donnees($_SESSION['rdid']);
          if (!empty($readerid)
          && strlen($readerid) <= 7
          && preg_match("#^SID+[0-9]{3,}#",$readerid))
          {
          $sql = "SELECT * FROM tblissuedbookdetails  WHERE ReaderID = :rdid";
          $query= $dbh->prepare($sql);
          $query->bindParam(':rdid', $readerId, PDO::PARAM_STR);
          $query->execute();
          $emprunté= $query->fetchAll(PDO::FETCH_OBJ);
          // var_dump($emprunté);

          $sql = "SELECT * FROM tblissuedbookdetails  WHERE ReaderID = :rdid AND ReturnStatus = 0";
          $query= $dbh->prepare($sql);
          $query->bindParam(':rdid', $readerId, PDO::PARAM_STR);
          $query->execute();
          $nonrendu= $query->fetchAll(PDO::FETCH_OBJ);
          // var_dump($nonrendu);

          $sql = "SELECT * FROM tblissuedbookdetails  WHERE ReaderID = :rdid AND ReturnStatus = 1";
          $query= $dbh->prepare($sql);
          $query->bindParam(':rdid', $readerId, PDO::PARAM_STR);
          $query->execute();
          $rendu= $query->fetchAll(PDO::FETCH_OBJ);
          // var_dump($rendu);
          }

}

	// On r�cup�re l'identifiant du lecteur dans le tableau $_SESSION
	
	// On veut savoir combien de livres ce lecteur a emprunte
	// On construit la requete permettant de le savoir a partir de la table tblissuedbookdetails
	
	// On stocke le r�sultat dans une variable
	
	// On veut savoir combien de livres ce lecteur n'a pas rendu
	// On construit la requete qui permet de compter combien de livres sont associ�s � ce lecteur avec le ReturnStatus � 0 
	
	// On stocke le r�sultat dans une variable

?>

<!DOCTYPE html>
<html lang="FR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Gestion de librairie en ligne | Tableau de bord utilisateur</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
</head>
<body>
     <!--On inclue ici le menu de navigation includes/header.php-->
<?php include('includes/header.php');?>
<!-- On affiche le titre de la page : Tableau de bord utilisateur-->
<div class="container">
		<div class="row">
			<div class="col">
				<h3>Dashboard</h3>
			</div>
		</div>
     </div>
   <!-- On affiche la carte des livres emprunt�s par le lecteur-->
        <div><?php echo (count($nonrendu). " livres empruntés") ?></div>
   <!-- On affiche la carte des livres non rendus le lecteur-->
     <div><?php echo (count($rendu). " livres rendus") ?></div>
<?php include('includes/footer.php');?>
     <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
<?php } ?>
