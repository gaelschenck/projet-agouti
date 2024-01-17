<?php
// On r�cup�re la session courante
session_start();

// On inclue le fichier de configuration et de connexion � la base de donn�es
include('includes/config.php');

if(strlen($_SESSION['login'])==0) {
    header('location:index.php');
}else{
    if (isset($_SESSION['rdid']));{
        $readerid = $_SESSION['rdid'];
    $sql = "SELECT * 
            FROM tblissuedbookdetails tib
            JOIN tblbooks tbb 
            ON tib.BookId=tbb.id
            WHERE tib.ReaderId = :rdid";
		$query = $dbh->prepare($sql);
		$query->bindParam(':rdid', $readerid, PDO::PARAM_STR);
        $query->execute();
   
// Si l'utilisateur n'est pas connecte, on le dirige vers la page de login
// Sinon on peut continuer
//	Si le bouton de suppression a ete clique($_GET['del'] existe)
		//On recupere l'identifiant du livre
		// On supprime le livre en base
		// On redirige l'utilisateur vers issued-book.php
?>

<!DOCTYPE html>
<html lang="FR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliotheque en ligne | Gestion des livres</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
</head>
<body>
      <!--On insere ici le menu de navigation T-->
<?php include('includes/header.php');?>
	<!-- On affiche le titre de la page : LIVRES SORTIS --> 
    <div class="container">
		<div class="row">
			<div class="col">
				<h3>LIVRES EMPRUNTÉS</h3>
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
                    Titre
                    </th>
                    <th>
                        ISBN
                    </th>
                    <th>
                        Date de sortie
                    </th>
                    <th>
                        Date de retour
                    </th>
                </tr>
                <?php while($result = $query->fetch(PDO::FETCH_ASSOC)){
                    if($result['ReturnDate']== NULL ){
                        $result['ReturnDate']= "Non retourné";
                    }
                    ?>
                <tr>
                    <th>
                       <?php echo($result['id']) ?>
                    </th>
                    <th>
                    <?php echo($result['BookName']) ?>
                    </th>
                    <th>
                    <?php echo($result['ISBNNumber']) ?>
                    </th>
                    <th>
                    <?php echo($result['IssuesDate']) ?>
                    </th>
                    <th>
                    <?php echo($result['ReturnDate']) ?>
                    </th>
                </tr>
                <?php 
                  } }
} ?>
                </table>
            </div>
		</div>
    </div>
           <!-- On affiche le titre de la page : LIVRES SORTIS -->      
           <!-- On affiche la liste des sorties contenus dans $results sous la forme d'un tableau -->
           <!-- Si il n'y a pas de date de retour, on affiche non retourne --> 


  <?php include('includes/footer.php');?>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>

