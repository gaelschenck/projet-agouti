<?php
// On démarre ou on récupère la session courante
session_start();

// On inclue le fichier de configuration et de connexion à la base de données
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0) {
    header('location:../adminlogin.php');
}else{
    $sql= "SELECT * FROM tblreaders";
      $query = $dbh->prepare($sql);
      
      
    if (isset($_GET['actif'])){
        $id= valid_donnees($_GET['actif']);
        if(!empty($id)
        && strlen($id) <= 3
        && preg_match("#^[0-9]{1,}#",$id)){
        $status=1;
        $sql1= "UPDATE tblreaders SET Status=:status WHERE id=$id";
        $query1 = $dbh->prepare($sql1);
        $query1->bindParam(':status', $status, PDO::PARAM_STR);
        $query1->execute();   
}}
    if (isset($_GET['inactif'])){
        $id= valid_donnees($_GET['inactif']);
        if(!empty($id)
        && strlen($id) <= 3
        && preg_match("#^[0-9]{1,}#",$id)){
        $status1=0;
        $sql2= "UPDATE tblreaders SET Status=:status WHERE id=$id";
        $query2 = $dbh->prepare($sql2);
        $query2->bindParam(':status', $status1, PDO::PARAM_STR);
        $query2->execute();
}}
    if (isset($_GET['suppress'])){
        $id= valid_donnees($_GET['suppress']);
        if(!empty($id)
        && strlen($id) <= 3
        && preg_match("#^[0-9]{1,}#",$id)){
        $status2=2;
        $sql3= "UPDATE tblreaders SET Status=:status WHERE id=$id";
        $query3 = $dbh->prepare($sql3);
        $query3->bindParam(':status', $status2, PDO::PARAM_STR);
        $query3->execute();
}}
}
// Si l'utilisateur n'est logué ($_SESSION['alogin'] est vide)
// On le redirige vers la page d'accueil
// Sinon on affiche la liste des lecteurs de la table tblreaders

// Lors d'un click sur un bouton "inactif", on récupère la valeur de l'identifiant
// du lecteur dans le tableau $_GET['inid']
// et on met à jour le statut (0) dans la table tblreaders pour cet identifiant de lecteur

// Lors d'un click sur un bouton "actif", on récupère la valeur de l'identifiant
// du lecteur dans le tableau $_GET['id']
// et on met à jour le statut (1) dans  table tblreaders pour cet identifiant de lecteur

// Lors d'un click sur un bouton "supprimer", on récupère la valeur de l'identifiant
// du lecteur dans le tableau $_GET['del']
// et on met à jour le statut (2) dans la table tblreaders pour cet identifiant de lecteur

// On récupère tous les lecteurs dans la base de données
?>

<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Gestion de bibliothèque en ligne | Reg lecteurs</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
</head>

<body>
    <!--On inclue ici le menu de navigation includes/header.php-->
    <?php include('includes/header.php'); ?>
    <!-- Titre de la page (Gestion du Registre des lecteurs) -->
    <div class="container">
      <div class="row">
        <div class="col">
          <h3>GESTION DU REGISTRE DES LECTEURS</h3>
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
                        ID Lecteurs
                    </th>
                    <th>
                        Nom
                    </th>
                    <th>
                        Email
                    </th>
                    <th>
                        Portable 
                    </th>
                    <th>
                        Date d'enregistrement
                    </th>
                    <th>
                        Status
                    </th>
                    <th>
                        Action
                    </th>
                </tr>
                <?php  $query->execute();
      while ($result = $query->fetch(PDO::FETCH_ASSOC)){
                    
        switch( $result['Status']){
            case 0 :
                $piou = "Actif";
                // $result['Status'] = "Actif";
                break;
            case 1:
                $piou = "Inactif";
                // $result['Status'] = "Inactif";
                break;
            case 2:
                $piou = "Supprimé";
                // $result['Status'] = "Supprimé";
                break;
            default:
                break;
        }
                    
                     ?>
                <tr>
                    <th>
                       <?php echo($result['id']); ?>
                    </th>
                    <th>
                    <?php echo($result['ReaderId']); ?>
                    </th>
                    <th>
                    <?php echo($result['FullName']); ?>
                    </th>
                    <th>
                    <?php echo($result['EmailId']); ?>
                    </th>
                    <th>
                    <?php echo($result['MobileNumber']); ?>
                    </th>
                    <th>
                    <?php echo($result['RegDate']); ?>
                    </th>
                    <th>
                    <?php echo($piou); ?>
                    </th>
                    <th>
                <?php if (($result['Status'] == 0) ||($result['Status'] == 1)){
                    if ($result['Status'] == 0){ ?>

                        <a href="reg-readers.php?actif=<?php echo ($result['id']) ?>"><button type="submit" name="actif" class="btn btn-info">Actif</button></a>
                        <?php }

                    if ($result['Status'] == 1){ ?>
                        <a href="reg-readers.php?inactif=<?php echo ($result['id']) ?>"><button type="submit" name="inactif" class="btn btn-warning">Inactif</button></a>
                        <?php } ?>

                        <a href="reg-readers.php?suppress=<?php echo ($result['id']) ?>"><button type="submit" name="suppress" class="btn btn-danger">Supprimer</button></a>
                        <?php } else{}?>
                    </th>
                </tr>
                <?php  
             }           
                    ?>
                </table>
            </div>
		</div>
    </div>
    <!--On insère ici le tableau des lecteurs.
       On gère l'affichage des boutons Actif/Inactif/Supprimer en fonction de la valeur du statut du lecteur -->

    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('includes/footer.php'); ?>
    <!-- FOOTER SECTION END-->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>