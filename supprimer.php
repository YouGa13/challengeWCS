<!DOCTYPE html>
  <html lang="fr">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page pour supprimer un argonaute</title>
  </head>
  <body>
<?php
  require_once('../../Base_de_données/MaBDD.php');

  try {
        if(!isset($_POST['submit'])){ 
        echo "Rien à supprimer";
        } else {
                $dbh = new PDO($dsn, $user, $pwd);
                $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $id = $_POST["id"];
                $res = sprintf("DELETE FROM argonaute WHERE id=%d LIMIT 1", $id);
                $stmt = $dbh->query($res);
                $req=$stmt->fetch();

                if($stmt){
                    $alerte = "<p>La personne a bien été supprimée</p>";
                    } else {
                        $alerte = "Échec de la suppression de la personne";
                    }
        ?>
        <h1>
            suppression
        </h1>
            <?php 
            echo $alerte ;
            echo '<a href="http://localhost/ProjetEntretienTechnique/PHP/page_dacceuil/">Revenir à la page d\'acceuil pour voir la modification</a>';
        }} catch (PDOException $myexep) {
            die(sprintf('<p class="error">Erreur SQL : <em>%s</em></p>'.
                "\n", htmlspecialchars($myexep->getMessage())));
          }
      ?>
  </body>
  </html>
