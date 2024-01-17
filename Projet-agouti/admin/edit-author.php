<?php
session_start();

include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
     header('location:../index.php');
} else {
    $id =valid_donnees($_GET['edit']);
//     echo $id;
        if (!empty($id)
        && strlen($id) <= 3
        && preg_match("#^[0-9]{3,}#",$id))
        {
        
   
    if (isset($_POST['update'])){
       
        $authorname=valid_donnees( $_POST['authorname']);
        $status= valid_donnees($_POST['status']);
         $possible = array('1', '0');
                  if (!empty($authorname)
                  && strlen($authorname) <= 30
                  && preg_match("#^[A-Za-z '-]+$#",$authorname)
                  && in_array($status ,$possible))
{
        $sql = "UPDATE  tblauthors SET AuthorName =:authorname, Status=:status WHERE id=:id ";
            $query = $dbh->prepare($sql);
            $query->bindParam(':authorname', $authorname, PDO::PARAM_STR);
            $query->bindParam(':status', $status, PDO::PARAM_INT);
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->execute();
            
                echo "<script>alert('Auteur modifié')</script>";
                header ('location:manage-authors.php');
           }}
        }
        }
?>
<!DOCTYPE html>
<html lang="FR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliothèque en ligne | Auteurs</title>
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
<!-- MENU SECTION END-->
<div class="container">
        <div class="row">
            <div class="col-md-12">
                <h4 class="header-line">Editer la categorie</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
            <form method="post" >
                    <div class="form-group">
						<label>NOM</label>
						<input type="text" name="authorname" required pattern="^[A-Za-z '-]+$" maxlength="30">
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
     <!-- CONTENT-WRAPPER SECTION END-->
<?php include('includes/footer.php');?>
      <!-- FOOTER SECTION END-->
     <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
