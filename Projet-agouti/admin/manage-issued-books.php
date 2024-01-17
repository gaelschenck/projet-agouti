<?php
session_start();

include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
    header('location:../adminlogin.php');
} else { 
    $sql2 = "SELECT tibd.id, tr.FullName, tb.BookName, tb.ISBNNumber, tibd.IssuesDate, tibd.ReturnDate
    FROM tblissuedbookdetails tibd
    CROSS JOIN tblbooks tb ON tibd.BookId = tb.id 
    JOIN tblreaders tr ON tibd.ReaderID = tr.ReaderId";
        $query2 = $dbh->prepare($sql2);
}    
  
if (isset($_GET['edit'])){
    $id= valid_donnees($_GET['edit']);
     if (!empty($id)
        && strlen($id) <= 3
        && preg_match("#^[0-9]{1,}#",$id))
        {
    $dbh->exec('SELECT * FROM tblissuedbookdetails');
}
};
?>
<!DOCTYPE html>
<html lang="FR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliothèque en ligne | Gestion des sorties</title>
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
          <h3>GESTION DES SORTIES</h3>
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
                        Nom du lecteur
                    </th>
                    <th>
                        Titre du livre
                    </th>
                    <th>
                        Numéro ISBN
                    </th>
                    <th>
                        Date de sortie du livre 
                    </th>
                    <th>
                        Date de retour
                    </th>
                    <th>
                        Action
                    </th>
                </tr>
                <?php  $query2->execute();
                 while($result = $query2->fetch(PDO::FETCH_ASSOC)){
                    
                    if($result['ReturnDate']== NULL ){
                        $result['ReturnDate']= "Non retourné";
                    } else{
                        $result['ReturnDate']=  $result['ReturnDate'];
                    };
                     ?>
                <tr>
                    <th>
                       <?php echo($result['id']); ?>
                    </th>
                    <th>
                    <?php echo($result['FullName']); ?>
                    </th>
                    <th>
                    <?php echo($result['BookName']); ?>
                    </th>
                    <th>
                    <?php echo($result['ISBNNumber']); ?>
                    </th>
                    <th>
                    <?php echo($result['IssuesDate']); ?>
                    </th>
                    <th>
                    <?php echo($result['ReturnDate']); ?>
                    </th>
                    <th>
                        <a href="edit-issue-book.php?edit=<?php echo ($result['id']) ?>"><button type="submit" name="edit" class="btn btn-info">Éditer</button></a>
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

