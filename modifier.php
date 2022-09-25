<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Page pour modifier un Argonaute</title>
</head>
<body>
<?php
require_once('../../Base_de_données/MaBDD.php');
try {
    $form = <<<EOD
        <!-- New member form -->
        <h2>Modifiez un(e) Argonaute</h2>
        <form class="new-member-form" method="POST">
            <input type="hidden" name="id" value="%d"/>
            <p>
                <label for="name">Nom de l&apos;Argonaute</label>
                <input id="name" name="name" type="text" placeholder="%s" />
            </p>
            <p>
                <label for="forname">Prénom de l&apos;Argonaute</label>
                <input id="forname" name="forname" type="text" placeholder="%s" />
            </p>
            <p>
                <label for="qualif">Qualificatif</label>
                <input id="qualif" name="qualif" type="text" placeholder="%s" />
            </p>
        <button type="submit" name="modifier">Modifier</button>
        </form>
    EOD;
  if (!isset($_REQUEST['modifier'])){
        if (isset($_POST['id'])){
            $id= $_POST['id'] ?? 1;
            $membre = new PDO($dsn, $user, $pwd);
            $membre->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $nom = $membre->query("SELECT nom FROM argonaute WHERE id=$id");
            $prenom = $membre->query("SELECT prenom FROM argonaute WHERE id=$id");
            $qualif = $membre->query("SELECT qualificatif FROM argonaute WHERE id=$id");

            $reqnom =$nom->fetch();
            $nomRecup = $reqnom[0];

            $reqPrenom = $prenom->fetch();
            $prenomRecup = $reqPrenom[0];

            $reqQualif= $qualif->fetch();
            $qualificatif = $reqQualif[0];
            printf($form, $id, $nomRecup, $prenomRecup, $qualificatif); 
        } 
    } 
    else {
        $dbh = new PDO($dsn, $user, $pwd);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $id= $_POST['id'];  
        $res = sprintf("UPDATE argonaute SET nom=%s, prenom=%s, qualificatif=%s WHERE id=%d",
                                     $dbh->quote($_POST['name']),  $dbh->quote($_POST['forname']),
                                     $dbh->quote($_POST['qualif']), $id);
        $stmt = $dbh->query($res);
        echo '<a href="http://localhost/ProjetEntretienTechnique/PHP/page_dacceuil/">Revenir à la page d\'acceuil pour voir la modification</a>';
        $ras = $stmt->fetch();

}} catch (PDOException $myexep) {
  die(sprintf('<p class="error">la connexion à la base de données à été refusée <em>%s</em></p>' .
    "\n", htmlspecialchars($myexep->getMessage())));
}
?>
</body>
</html>
