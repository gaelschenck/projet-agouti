<?php
session_start();

include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
  header('location:../adminlogin.php');
} else {
      $sql = "SELECT * FROM tblauthors WHERE Status=1";
          $query = $dbh->prepare($sql);
          $query->execute();

      $sql1= "SELECT * FROM tblcategory WHERE Status=1";
      $query1 = $dbh->prepare($sql1);
      $query1->execute();

      if (isset($_POST['create'])){
      $bookname= valid_donnees($_POST['bookname']);
      $catid= valid_donnees($_POST['catid']);
      $authorid= valid_donnees($_POST['authorid']);
      $isbnnumber= valid_donnees($_POST['isbnnumber']);
      $bookprice= valid_donnees($_POST['bookprice']);
        if (!empty($bookname)
        && strlen($bookname) <= 30
        && preg_match("#^[A-Za-z0-9 '-]+$#",$bookname)
        && !empty($isbnnumber)
        && strlen($isbnnumber) <= 10
        && preg_match("#^[0-9]{6,}#",$isbnnumber)
        && !empty($bookprice)
        && strlen($bookprice) <= 3
        && preg_match("#^[0-9]{3,}#",$bookprice))
      {
      $sql2= "INSERT INTO tblbooks (BookName, CatId, AuthorId, ISBNNumber, BookPrice) VALUES (:bookname, :catid, :authorid, :isbnnumber, :bookprice) ";
      $query2 = $dbh->prepare($sql2);
      $query2->bindParam(':bookname', $bookname , PDO::PARAM_STR);
      $query2->bindParam(':catid', $catid , PDO::PARAM_STR);
      $query2->bindParam(':authorid', $authorid , PDO::PARAM_STR);
      $query2->bindParam(':isbnnumber', $isbnnumber , PDO::PARAM_INT);
      $query2->bindParam(':bookprice', $bookprice , PDO::PARAM_INT);
      $query2->execute();
      }
}
}
?>
<!DOCTYPE html>
<html lang="FR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliothèque en ligne | Ajout de livres</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet"/>
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet"/>
</head>
<body>
      <!------MENU SECTION START-->
<?php include('includes/header.php');?>
<!-- MENU SECTION END-->
<div class="container">
        <div class="row">
            <div class="col-md-12">
                <h4 class="header-line">AJOUTER UN LIVRE</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
            <form method="post" action="add-book.php" >
                    <div class="form-group">
                    <div><label>Titre</label></div>
                      <input type="text" name="bookname" required maxlength="30" pattern="^[A-Za-z0-9 '-]+$">
                    </div>
                    <div class="form-group">
                      <div><label>Catégorie</label></div>
                      
                      <div>
                        <select name="catid" required>
                        <option value="">Séléctionnez une catégorie</option>
                        <?php while($result1 = $query1->fetch(PDO::FETCH_ASSOC)){?>
                        <option value="<?php echo $result1['id']; ?>"><?php echo $result1['CategoryName']; ?></option><?php } ?>
                      </select>
                    </div>
                    </div>
                    <div class="form-group">
                    <div><label>Auteur</label></div>
                      <!-- <input type="text" name="authorid" required> -->
                      <div>
                        <select name="authorid" required>
                        <option value="">Sélectionnez un auteur</option>
                        <?php while($result = $query->fetch(PDO::FETCH_ASSOC)){?>
                        <option value="<?php echo $result['id']; ?>"><?php echo $result['AuthorName']; ?></option><?php } ?>
                      </select>
                    </div>
                    </div>
                    <div class="form-group">
                    <div><label>ISBN</label></div>
                      <input type="number" name="isbnnumber" required maxlength="10" pattern="^[0-9]{6,}">
                      <div>Le numéro ISBN doit être unique</div>
                    </div>
                    <div class="form-group">
                    <div><label>Prix</label></div>
                      <input type="number" name="bookprice" required maxlength="3" pattern="^[0-9]{3}">
                    </div>
                        <button type="submit" name="create" class="btn btn-info">Créer</button>
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
