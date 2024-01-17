<?php
session_start();

include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
      header('location:../adminlogin.php');
} else {
      if (TRUE === isset($_POST['submit'])) {
            $authorname = valid_donnees($_POST['AuthorName']);
            $status = valid_donnees($_POST['status']);
            $possible = array('1', '0');
                  if (!empty($authorname)
                  && strlen($authorname) <= 30
                  && preg_match("#^[A-Za-z '-]+$#",$authorname)
                  && in_array($status ,$possible))
{
                        
                 
      $query = $dbh->prepare("INSERT INTO tblauthors (AuthorName, Status) VALUES (:authorname, :status)");
      $query->bindParam(':authorname', $authorname, PDO::PARAM_STR);
      $query->bindParam(':status', $status, PDO::PARAM_INT);
      $query->execute();
      $lastid = $dbh ->lastInsertId();
      if ($lastid){
            echo "<script>alert('Auteur créé avec succès')</script>";
      }else{
            echo "<script>alert('Erreur dans la création de catégorie')</script>";
      } }
}
}
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
<?php include('includes/header.php');?>
<div class="container">
      <div class="row">
        <div class="col">
          <h3>AJOUTER UN AUTEUR</h3>
        </div>
      </div>
      <div class="row">
	      <div class="col-xs-12 col-sm-6 col-md-6 col-lg-8 offset-md-3">
			<form method="post" action="add-author.php" >
                        <div class="form-group">
                              <label>NOM</label>
                              <input type="text" name="AuthorName" required pattern="^[A-Za-z '-]+$" maxlength="30">
			      </div>
                        <div class="form-group">
						<label>STATUT</label>
						<div><input type="radio" name="status" value="1" checked>
                                    <label> Active</label>
                                    </div>
                                    <div><input type="radio" name="status" value="0">
                                    <label> Inactive</label>
                                    </div>
                        </div>
                        <button type="submit" name="submit" class="btn btn-info">Créer</button>
                  </form>
            </div>
      </div>
</div>
     <!-- CONTENT-WRAPPER SECTION END-->
<?php include('includes/footer.php');?>
      <!-- FOOTER SECTION END-->
     <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
