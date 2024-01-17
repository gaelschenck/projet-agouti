<?php
session_start();

include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
      header('location:../adminlogin.php');
  } else { 
$id= valid_donnees($_GET['edit']);
 if (!empty($id)
        && strlen($id) <= 3
        && preg_match("#^[0-9]{1,}#",$id))
        {
      $sql="SELECT * FROM tblissuedbookdetails WHERE id= $id ";
   $query = $dbh->prepare($sql);
   $query->execute();
    $result=$query->fetch(PDO::FETCH_ASSOC);
    }
    $sql2 = "SELECT tibd.id, tr.FullName, tb.BookName, tb.ISBNNumber, tibd.IssuesDate, tibd.ReturnDate
    FROM tblissuedbookdetails tibd
    CROSS JOIN tblbooks tb ON tibd.BookId = tb.id 
    JOIN tblreaders tr ON tibd.ReaderID = tr.ReaderId
    WHERE tibd.id =$id";
        $query2 = $dbh->prepare($sql2);
        $query2->execute();
            $result2 = $query2->fetch(PDO::FETCH_ASSOC);

 if (isset($_POST['update'])){
      $returnstatus =1;
      $returndate=date('Y-m-d H:i:s');
      $sql3= "UPDATE tblissuedbookdetails SET ReturnStatus=:returnstatus, ReturnDate=:returndate WHERE id=$id ";
      $query3 = $dbh->prepare($sql3);
      $query3->bindParam(':returnstatus', $returnstatus, PDO::PARAM_INT);
      $query3->bindParam(':returndate', $returndate, PDO::PARAM_STR);
      $query3->execute();
      $result3= $query3->fetchAll(PDO::FETCH_ASSOC);
            echo "<script>alert('Livre modifié')</script>";
            header ('location:manage-issued-books.php');
  }
}
?>
<!DOCTYPE html>
<html lang="FR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliothèque en ligne | Sorties</title>
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
                <h4 class="header-line">ÉDITER UN RETOUR</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
            <form method="post"  >
                    <div class="form-group">
                    <div><label>Utilisateur</label></div>
                      <input type="text" name="readerid" value="<?php echo $result['ReaderID'] ?>" required>
                      <div><span><?php echo $result2['FullName'] ?></span></div>
                    </div>
                    <div class="form-group">
                      <div><label>ISBN </label></div>
                      <input type="text" name="isbn" value="<?php echo $result2['ISBNNumber'] ?>" required>
                    </div>
                    <div><span><?php echo $result2['BookName'] ?></span></div>
                    <button type="submit" name="update" class="btn btn-info" id="button" >Mise à jour</button>
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
