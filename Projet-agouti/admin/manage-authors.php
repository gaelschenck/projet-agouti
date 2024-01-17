<?php
session_start();

include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
      header('location:../adminlogin.php');
  } else { 
      $sql = "SELECT * FROM tblauthors";
          $query = $dbh->prepare($sql);
  }
         
    
  if (isset($_GET['edit'])){
      $id= valid_donnees($_GET['edit']);
       if (!empty($id)
        && strlen($id) <= 3
        && preg_match("#^[0-9]{1,}#",$id))
        {
      $dbh->exec("SELECT * FROM tblauthors");
    }
  };

  if (isset($_GET['suppress'])){ 
      $id= valid_donnees($_GET['suppress']);
       if (!empty($id)
        && strlen($id) <= 3
        && preg_match("#^[0-9]{1,}#",$id))
        {
      $dbh->exec("UPDATE tblauthors SET Status=0 WHERE id=$id");
    }
  };
?>

<!DOCTYPE html>
<html lang="FR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliothèque en ligne | Gestion des auteurs</title>
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
        <div class="col">
          <h3>GÉRER LES AUTEURS</h3>
        </div>
      </div>
      <div class="row">
			<div class="col">
                <table class="table">
                <tr>
                    <th>
                        #
                    </th>
                    <th>
                    Nom
                    </th>
                    <th>
                        Statut
                    </th>
                    <th>
                        Crée le
                    </th>
                    <th>
                        Mis à jour le 
                    </th>
                    <th>
                        Action
                    </th>
                </tr>
                <?php  $query->execute();
                 while($result = $query->fetch(PDO::FETCH_ASSOC)){
                    if($result['Status']== 1 ){
                        $result['Status']= "Actif";
                    } else{
                        $result['Status']= "Inactif";
                    }
                     ?>
                <tr>
                    <th>
                       <?php echo($result['id']); ?>
                    </th>
                    <th>
                    <?php echo($result['AuthorName']); ?>
                    </th>
                    <th>
                    <?php echo($result['Status']); ?>
                    </th>
                    <th>
                    <?php echo($result['creationDate']); ?>
                    </th>
                    <th>
                    <?php echo($result['UpdationDate']); ?>
                    </th>
                    <th><a href="edit-author.php?edit=<?php echo ($result['id']) ?>"><button type="submit" name="edit" class="btn btn-info">Éditer</button></a>
                    <a href="manage-authors.php?suppress=<?php echo ($result['id']) ?>"><button type="submit" name="suppress" class="btn btn-danger">Supprimer</button></a>
                    
                    </th>
                </tr>
                <?php 
                  }
             

           
 ?>
                </table>
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
