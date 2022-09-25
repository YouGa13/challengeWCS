<?php
 require_once('../Base_de_données/MaBDD.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projet pour entretien technique</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <!-- Header section -->
  <header>
    <h1>
      <img src="https://www.wildcodeschool.com/assets/logo_main-e4f3f744c8e717f1b7df3858dce55a86c63d4766d5d9a7f454250145f097c2fe.png" alt="Wild Code School logo" />
      Les Argonautes
    </h1>
  </header>
  
 
<?php
  try {
    // formulaire pour ajouter
    $form= <<<EOD
            <section>
            <!-- New member form -->
            <h2>Ajouter un(e) Argonaute</h2>
            <form class="new-member-form" method="POST">
                <p>
                    <label for="name">Nom de l&apos;Argonaute</label>
                    <input id="name" name="name" type="text" placeholder="Entrez le nom" />
                </p>
                <p>
                    <label for="forname">Prénom de l&apos;Argonaute</label>
                    <input id="forname" name="forname" type="text" placeholder="Entrez le prénom" />
                </p>
                <p>
                    <label for="qualif">Qualificatif</label>
                    <input id="qualif" name="qualif" type="text" placeholder="Entrez un ou des qualificatifs" />
                </p>
            <button type="submit" name="submit">Envoyer</button>
            </form>
            </section>
    EOD;
  

    // le nombre de valeurs ou de données dans la table Argonaute
    $compter= new PDO($dsn, $user, $pwd);
    $compter->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $res = $compter->query("SELECT COUNT(*) FROM argonaute");
    $row = $res->fetch(); // fetch Extrait une ligne d’un jeu de résultats associé à un objet PDOStatement.
    $nbre = $row[0]; 

    // partie affichant le nombre d'argonaute
    $nombre_argonaute= <<<EOD
            <section>
            <h2> Nombre de personnes enregistrées</h2>
            <p>Vous venez d'enregistrer le $nbre ème argonaute</p>
            </section>
    EOD;

    // la liste des argonautes
    $membre = new PDO($dsn, $user, $pwd);
    $membre->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $resultat = $membre->query("SELECT * FROM argonaute");
   


// vérification qu'il n'y a pas d'envoi
    if(!isset($_REQUEST["submit"])){
      // page d'acceuil et unique affichant le formulaire
      // la liste des membres enregistrés
      // le nombre exact de l'équipage 
      echo " <!-- Main section 
      la partie importante de la page -->
      <main>";
        echo $form; // le formulaire d'ajout
?>
        <!-- Member list -->
        <section class='member-list'>
          <h2>Membres de l'équipage</h2>
          <div id="scroll">
<?php
          while ($membres = $resultat->fetch(PDO::FETCH_ASSOC)) { 
            // PDO::FETCH_ASSOC: est un mode de fetch qui renvoie un tableau indexé par nom de colonne
            // tel qu’il est renvoyé dans votre jeu de résultats
            // liste des membres
                  echo "<div class='member-item'>";
                  echo $membres['nom']."  ".$membres['prenom']."   ".$membres['qualificatif'];
?>
                  <form action="supprimer" method="post">
                      <input type="hidden" name="id" value="<?php echo $membres['id'] ?>">
                      <input type="submit" name="submit" value="Supprimer">
                  </form>
                  <form action="supprimer" method="post">
                      <input type="hidden" name="id" value="<?php echo $membres['id'] ?>">
                      <input type="submit" name="submit" value="Modifier">
                  </form>
<?php
                  echo "</div>";
            
          } 
        echo "</div>";
        echo " </section>";
        echo $nombre_argonaute; // le nbre d'argonaute
      echo "</main>";
    } elseif (($_POST["name"]) == "" || ($_POST["forname"]) == "" 
                                       || ($_POST["qualif"]) == ""){
              echo "une ou plusieurs case(s) est ou sont vide(s)"."</br>";
              echo  "veuillez revenir en arrière ou appuyer"."</br>";
      } else {
          // ajout d'argonaute dans la base de données
          $dbh = new PDO($dsn, $user, $pwd);
          //$dbh->exec("DROP TABLE IF EXISTS argonaute");
          $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          $req=$dbh->prepare("INSERT INTO argonaute (nom,prenom ,qualificatif)
                              VALUES (:nom,:prenom,:qualif) ");
          $req->bindValue(':nom', htmlspecialchars($_POST['name']), PDO::PARAM_STR);
          $req->bindValue(':prenom', htmlspecialchars($_POST['forname']), PDO::PARAM_STR);
          $req->bindValue(':qualif', htmlspecialchars($_POST['qualif']), PDO::PARAM_STR);

          $valider=$req->execute(); 
          if($valider){
            echo " <!-- Main section 
            la partie importante de la page -->
            <main>";
              echo $form;
?>
              <!-- Member list -->
              <section class='member-list'>
                  <h2>Membres de l'équipage</h2>
                  <div id="scroll">
<?php
                  // renouvellemnt de la liste
                  $list = new PDO($dsn, $user, $pwd);
                  $list->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                  $resultat = $list->query("SELECT * FROM argonaute");
                  while ($liste = $resultat->fetch(PDO::FETCH_ASSOC)) { 
                          echo "<div class='member-item'>";
                          echo $liste['nom']."  ".$liste['prenom']."   ".$liste['qualificatif'];
?>
                                <form action="supprimer.php" method="post">
                                  <input type="hidden" name="id" value="<?php echo $liste['id'] ?>">
                                  <input type="submit" name="submit" value="Supprimer">
                                </form>
                                <form action="modifier.php" method="post">
                                    <input type="hidden" name="id" value="<?php echo $liste['id'] ?>">
                                    <input type="submit" name="submit" value="Modifier">
                                </form>
<?php
                          echo "</div>";
                  }
              echo "</div>";
              echo "</section>";

              // renouvellement du comptage
              $compteur= new PDO($dsn, $user, $pwd);
              $compteur->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
              $ras = $compteur->query("SELECT COUNT(*) FROM argonaute");
              $row = $ras->fetch(); // fetch Extrait une ligne d’un jeu de résultats associé à un objet PDOStatement.
              $newNbre = $row[0]; 
              echo $aafiche_nbre =<<<EOD
                          <section>
                          <h2> Nombre de personnes enregistrées</h2>
                          <p>Vous venez d'enregistrer le $newNbre ème argonaute</p>
                          </section>
              EOD ;
?>
            </main>
<?php
          } else echo "l'enregistrement a échoué";
        }
  } catch (PDOException $myexep) {
      die(sprintf('<p class="error">Erreur SQL : <em>%s</em></p>'.
          "\n", htmlspecialchars($myexep->getMessage())));
    }

?>
  
  
  <footer>
    <p>Réalisé par Jason en Anthestérion de l'an 515 avant JC</p>
  </footer>
</body>
</html>
