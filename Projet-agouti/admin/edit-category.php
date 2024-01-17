<?php
session_start();

include('includes/config.php');

// Si l'utilisateur n'est plus logué
if (strlen($_SESSION['alogin']) == 0) {
    // On le redirige vers la page de login  
    header('location:../index.php');
} else {
    $id = valid_donnees($_GET['edit']);
    // echo $id;
     if (!empty($id)
        && strlen($id) <= 3
        && preg_match("#^[0-9]{1,}#",$id))
        {

   
    if (isset($_POST['update'])){
       
        $categoryname= valid_donnees($_POST['categoryname']);
        $status= valid_donnees($_POST['status']);
        $possible = array('1', '0');
        if (!empty($categoryname)
        && strlen($categoryname) <= 30
        && preg_match("#^[A-Za-z '-]+$#",$categoryname)
        && in_array($status ,$possible))
        {
        $sql = "UPDATE  tblcategory SET CategoryName =:categoryname, Status=:status WHERE id=:id ";
            $query = $dbh->prepare($sql);
            $query->bindParam(':categoryname', $categoryname, PDO::PARAM_STR);
            $query->bindParam(':status', $status, PDO::PARAM_INT);
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->execute();
            
                echo "<script>alert('Catégorie modifiée')</script>";
                header ('location:manage-categories.php');
           }
        }}
        }
   
    // Sinon
    // Apres soumission du formulaire de categorie

    // On recupere l'identifiant, le statut, le nom

    // On prepare la requete de mise a jour

    // On prepare la requete de recherche des elements de la categorie dans tblcategory

    // On execute la requete

    // On stocke dans $_SESSION le message "Categorie mise a jour"

    // On redirige l'utilisateur vers edit-categories.php


?>
<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliothèque en ligne | Categories</title>
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
    <!-- On affiche le titre de la page "Editer la categorie-->
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h4 class="header-line">Editer la categorie</h4>
            </div>
        </div>
        <!-- On affiche le formulaire dedition-->
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
            <form method="post" >
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
                        <button type="submit" name="update" class="btn btn-info">Créer</button>
					</div>
                </form>
            </div>
        </div>
    </div>
                <!-- On affiche ici le formulaire d'édition -->
           
        <!-- Si la categorie est active (status == 1)-->
        <!-- On coche le bouton radio "actif"-->
        <!-- Sinon-->
        <!-- On coche le bouton radio "inactif"-->

        <!-- CONTENT-WRAPPER SECTION END-->
        <?php include('includes/footer.php'); ?>
        <!-- FOOTER SECTION END-->
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>