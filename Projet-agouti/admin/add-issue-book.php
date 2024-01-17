<?php
session_start();

include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
    header('location:../adminlogin.php');
  } else {
    if (isset($_POST['create'])){
        $isbn=valid_donnees($_POST['isbn']);
        $readerid=valid_donnees($_POST['readerid']);
            if (!empty($bookname)
            && strlen($bookname) <= 7
            && preg_match("#^SID+[0-9]{3,}#",$bookname)
            && !empty($isbnnumber)
            && strlen($isbnnumber) <= 10
            && preg_match("#^[0-9]{6,}#",$isbnnumber))
            {
        $sql1= "SELECT * FROM tblbooks WHERE ISBNNumber=:isbn";
      $query1 = $dbh->prepare($sql1);
      $query1->bindParam(':isbn',$isbn , PDO::PARAM_INT);
      $query1->execute();
      $result = $query1->fetch(PDO::FETCH_ASSOC);
      $bookid= $result['id'];

        
        $null= NULL;
        $status=0;
        $sql2= "INSERT INTO tblissuedbookdetails ( BookId, ReaderId, ReturnDate, ReturnStatus, fine) VALUES ( :bookid, :readerid, :returndate, :returnstatus, :fine)";
      $query2 = $dbh->prepare($sql2);
      $query2->bindParam(':bookid',$bookid , PDO::PARAM_INT);
      $query2->bindParam(':readerid', $readerid , PDO::PARAM_STR);
      $query2->bindParam(':returndate', $null , PDO::PARAM_STR);
      $query2->bindParam(':returnstatus', $status , PDO::PARAM_INT);
      $query2->bindParam(':fine', $null , PDO::PARAM_INT);
      $query2->execute();
    }}
}
?>
<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliotheque en ligne | Ajout de sortie</title>
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
            <div class="col-md-12">
                <h4 class="header-line">SORTIE D'UN LIVRE</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
            <form method="post" action="add-issue-book.php" >
                    <div class="form-group">
                        <div><label>Identifiant lecteur</label></div>
                        <input type="text" name="readerid" id="readerid" required pattern="^SID+[0-9]{3,}" maxlength="7" placeholder="SID000" onblur="get_reader(readerid)">
                        <div><span id="answer"></span></div>
                    </div>
                    <div class="form-group">
                        <div><label>ISBN</label></div>
                        <input type="text" name="isbn" id="isbn" required maxlength="10" pattern="^[0-9]{6,}" placeholder="Saisir ISBN" onblur="get_book(isbn)">
                        <div><span id="answer2"></span></div>
                    </div>
                    <button type="submit" name="create" class="btn btn-info" id="button">Créer la sortie
                    </button>
                </form>
            </div>
        </div>
    </div>
    <!-- Dans le formulaire du sortie, on appelle les fonctions JS de recuperation du nom du lecteur et du titre du livre 
 sur evenement onBlur-->

    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('includes/footer.php'); ?>
    <!-- FOOTER SECTION END-->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script>
        // On crée une fonction JS pour récuperer le nom du lecteur à partir de son identifiant
        function get_reader(readerid){
            let span = document.getElementById("answer");
            let button = document.getElementById("button");
                console.log(readerid);
                console.log(readerid.value);
            fetch('get_reader.php?readerid='+readerid.value)
            .then(rep => rep.json())
            .then(data => {
                span.innerHTML = data.sprout;
                if ((span.innerHTML = "Lecteur bloqué") || (span.innerHTML ="Lecteur non valide")){
                    button.disabled = true;
                }
                }
            )               
        }

        function get_book(isbn){
                let span2 = document.getElementById("answer2");
                let button = document.getElementById("button");
                    console.log(isbn);
                    console.log(isbn.value);
                fetch('get_book.php?isbn='+isbn.value)
                .then(rep2 => rep2.json())
                
                .then(data2 => {
                    console.log(data2)
                    span2.innerHTML = data2.sprout2; 
                    if ((span.innerHTML = "ISBN non valide")){
                    button.disabled = true;
                } 
                }
            )               
        }
        
        // On crée une fonction JS pour recuperer le titre du livre a partir de son identifiant ISBN
        </script>
        
</body>

</html>