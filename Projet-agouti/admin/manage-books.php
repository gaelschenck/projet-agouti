<?php
session_start();
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
      header('location:../adminlogin.php');
  } else { 
      $sql = "SELECT tb.id, tb.BookName, tb.ISBNNumber, tb.BookPrice, tc.CategoryName, ta.AuthorName
      FROM tblbooks tb 
      JOIN tblcategory tc ON tc.id =tb.CatId
      JOIN tblauthors ta ON ta.id = tb.AuthorId";
          $query = $dbh->prepare($sql);
  }    
  if (isset($_GET['edit'])){
      $id= valid_donnees($_GET['edit']);
       if (!empty($id)
        && strlen($id) <= 3
        && preg_match("#^[0-9]{1,}#",$id))
        {
      $dbh->exec('SELECT * FROM tblbooks');
    }
  };

  if (isset($_GET['suppress'])){ 
      $id= valid_donnees($_GET['suppress']);
       if (!empty($id)
        && strlen($id) <= 3
        && preg_match("#^[0-9]{1,}#",$id))
        {
      $dbh->exec("DELETE FROM tblbooks WHERE id=$id");
      header ('location:manage-books.php'); 
    }    
};
?>

<!DOCTYPE html>
<html lang="FR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliothèque en ligne | Gestion livres</title>
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
          <h3>GÉRER LES LIVRES</h3>
        </div>
      </div>
      <div class="row">
			<div class="col">
                <table class="table">
                <thead class="thead-light">
                <tr>
                    <th>
                        #
                    </th>
                    <th>
                        Titre du livre
                    </th>
                    <th>
                        Catégorie
                    </th>
                    <th>
                        Nom de l'auteur
                    </th>
                    <th>
                        Numéro ISBN 
                    </th>
                    <th>
                        Prix
                    </th>
                    <th>
                        Action
                    </th>
                </tr>
                </thead>
                <?php  $query->execute();
                 while($result = $query->fetch(PDO::FETCH_ASSOC)){
                  var_dump($result)
                  //   if($result['Status']== 1 ){
                  //       $result['Status']= "Actif";
                  //   } else{
                  //       $result['Status']= "Inactif";
                  //   }
                     ?>
                <tr>
                    <th>
                       <?php echo($result['id']); ?>
                    </th>
                    <th>
                    <?php echo($result['BookName']); ?>
                    </th>
                    <th>
                    <?php echo($result['CategoryName']); ?>
                    </th>
                    <th>
                    <?php echo($result['AuthorName']); ?>
                    </th>
                    <th>
                    <?php echo($result['ISBNNumber']); ?>
                    </th>
                    <th>
                    <?php echo($result['BookPrice']); ?>
                    </th>
                    <th><a href="edit-book.php?edit=<?php echo ($result['id']) ?>"><button type="submit" name="edit" class="btn btn-info">Éditer</button></a>
                    <a href="manage-books.php?suppress=<?php echo ($result['id']) ?>">
                    <button type="submit" name="suppress" class="btn btn-danger" onclick="return confirm('Voulez-vous vraiment supprimer ce livre ?')">Supprimer</button>
                        </a>
                    </th>
                </tr>
                <?php 
                  }           
 ?>
                </table>
            </div>
		</div>
    </div>
<?php include('includes/footer.php');?>
      <!-- FOOTER SECTION END-->
     <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>   
</body>
</html>

