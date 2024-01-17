<?php
session_start();

include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
    header('location:../adminlogin.php');
} else {
    if (TRUE === isset($_POST['submit'])) {
    $categoryname = valid_donnees($_POST['categoryname']);
    $status = valid_donnees($_POST['status']);
    $possible = array('1', '0');
    if (!empty($categoryname)
    && strlen($categoryname) <= 30
    && preg_match("#^[A-Za-z '-]+$#",$categoryname)
    && in_array($status ,$possible))
    { 
    $query = $dbh->prepare("INSERT INTO tblcategory (CategoryName, Status) VALUES (:categoryname, :status)");
    $query->bindParam(':categoryname', $categoryname, PDO::PARAM_STR);
    $query->bindParam(':status', $status, PDO::PARAM_INT);
    $query->execute();
    $lastid = $dbh ->lastInsertId();
    if ($lastid){
        echo "<script>alert('Catégorie crée avec succès')</script>";
    }else{
        echo "<script>alert('Erreur dans la création de catégorie')</script>";
    }}
    }
}
// Si l'utilisateur n'est plus logué
// On le redirige vers la page de login
// Sinon on peut continuer. Après soumission du formulaire de creation
// On recupere le nom et le statut de la categorie
// On prepare la requete d'insertion dans la table tblcategory
// On execute la requete
// On stocke dans $_SESSION le message correspondant au resultat de loperation
?>

<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliothèque en ligne | Ajout de categories</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
</head>

<body>
    <!------MENU SECTION START-->
    <?php include('includes/header.php'); ?>
    <!-- MENU SECTION END-->
    <div class="container">
      <div class="row">
        <div class="col">
          <h3>AJOUTER UNE CATÉGORIE</h3>
        </div>
      </div>
      <div class="row">
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-8 offset-md-3">
				<form method="post" action="add-category.php" >
                    <div class="form-group">
						<label>NOM</label>
						<input type="text" name="categoryname" required pattern="^[A-Za-z '-]+$" maxlength="30">
					</div>
                    <div class="form-group">
						<label>STATUT</label>

						<div><input type="radio" name="status" value="1" checked>
                            <label> Active</label>
                        </div>
                        <div><input type="radio" name="status" value="0">
                        <label> Inactive</label>
                        </div>
                        <button type="submit" name="submit" class="btn btn-info">Créer</button>
					</div>
                </form>
            </div>
        </div>
    </div>
    <!-- On affiche le titre de la page-->
    <!-- On affiche le formulaire de creation-->
    <!-- Par defaut, la categorie est active-->

    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('includes/footer.php'); ?>
    <!-- FOOTER SECTION END-->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>