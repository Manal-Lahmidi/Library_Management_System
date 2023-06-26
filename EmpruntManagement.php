<?php
require('config.php');

// Faire un emprunt

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['emprunter'])) {
    $idLivre = $_POST['livre'];
    $idUsager = $_POST['usager'];
    $dateEmprunt = date('Y-m-d');
    $dateRetour = date('Y-m-d', strtotime('+30 days'));

    // Vérifier si l'utilisateur a déjà emprunté cinq livres
    $query = "SELECT COUNT(*) AS total FROM emprunt WHERE id_utilisateur = $idUsager AND date_retour >= CURDATE()";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $totalEmprunts = $row['total'];
    if ($totalEmprunts >= 5) {
        echo "<script type=\"text/javascript\"> alert('Vous avez atteint la limite d\'emprunts. Vous ne pouvez pas emprunter plus de cinq livres.');
        window.location='EmpruntManagement.php';</script>";
    }else {
        // Vérifier si l'utilisateur a déjà emprunté le livre dans les 30 derniers jours
        $dateLimite = date('Y-m-d', strtotime('-30 days'));
        $queryCheckEmprunt = "SELECT COUNT(*) FROM emprunt WHERE id_livre = $idLivre AND id_utilisateur = $idUsager AND date_emprunt >= '$dateLimite'";
        $resCheckEmprunt = mysqli_query($conn, $queryCheckEmprunt);
        $empruntExist = mysqli_fetch_row($resCheckEmprunt)[0];
        if ($empruntExist) {
            echo "<script type=\"text/javascript\"> alert('Vous avez déjà emprunté un exemplaire de ce livre dans ces derniers 30 jours.');   
            window.location='EmpruntManagement.php';</script>";
            exit();
        }else
        {
            // Vérifier si le livre a des exemplaires disponibles
            $query = "SELECT nbExemplaire FROM livre WHERE id_livre = $idLivre";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($result);
            $nbExemplaire = $row['nbExemplaire'];

            if ($nbExemplaire <= 0) {
                echo "<script type=\"text/javascript\"> alert('Le livre n\'est pas disponible en ce moment.');
                window.location='EmpruntManagement.php';</script>";
                // Arrêter l'exécution du reste du code ou rediriger vers une autre page si nécessaire
            } else {
            // Insérer l'emprunt dans la table des emprunts
            $query = "INSERT INTO emprunt (id_livre, id_utilisateur, date_emprunt, date_retour) VALUES ('$idLivre', '$idUsager', '$dateEmprunt', '$dateRetour')";
            $res = mysqli_query($conn, $query);
            if ($res) {
                echo "<script type=\"text/javascript\"> alert('Emprunt ajouté avec succès.');</script>";
            } else {
                echo "<script type=\"text/javascript\"> alert('Erreur lors de l\'ajout de l\'emprunt.');</script>";
            }

            // Mettre à jour le nombre d'exemplaires disponibles
            $query = "UPDATE livre SET nbExemplaire = nbExemplaire - 1 WHERE id_livre = '$idLivre'";
            $resUpdateExemplaire = mysqli_query($conn, $query);

            // Insérer une nouvelle entrée dans la table "historique_emprunts"
            $queryInsertEmprunt = "INSERT INTO historique_emprunts (id_utilisateur, id_livre, date_emprunt) VALUES ($idUsager, $idLivre, CURDATE())";
            mysqli_query($conn, $queryInsertEmprunt);
            }
        }
    }
}

// Rendre un livre
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rendre'])) {
    $idLivre = $_POST['livre'];
    $idUsager = $_POST['usager'];

    // Vérifier si le livre a été emprunté par l'utilisateur
    $query = "SELECT COUNT(*) AS total FROM emprunt WHERE id_livre = $idLivre AND id_utilisateur = $idUsager";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $totalEmprunts = $row['total'];

    if ($totalEmprunts <= 0) {
        echo "<script type=\"text/javascript\"> alert('Le livre n\'a pas été emprunté par cet utilisateur.');
        window.location='EmpruntManagement.php';</script>";
    } else {
        // Supprimer l'emprunt de la table des emprunts
        $query = "DELETE FROM emprunt WHERE id_livre = $idLivre AND id_utilisateur = $idUsager AND date_retour >= CURDATE()";
        $result = mysqli_query($conn, $query);

        if ($result) {
            echo "<script type=\"text/javascript\"> alert('Livre rendu avec succès.');
            window.location='EmpruntManagement.php';</script>";
        } else {
            echo "<script type=\"text/javascript\"> alert('Erreur lors du rendu du livre.');
            window.location='EmpruntManagement.php';</script>";
        }

        // Mettre à jour le nombre d'exemplaires disponibles
        $query = "UPDATE livre SET nbExemplaire = nbExemplaire + 1 WHERE id_livre = $idLivre";
        $result = mysqli_query($conn, $query);

        // Enregistrer la date de retour dans l'historique des emprunts
        $queryUpdateRetour = "UPDATE historique_emprunts SET date_retour = CURDATE() WHERE id_livre = $idLivre AND id_utilisateur = $idUsager AND date_retour IS NULL";
        mysqli_query($conn, $queryUpdateRetour);
    }
}

// Afficher les livres
$query = "SELECT * FROM livre";
$res = mysqli_query($conn, $query);
$livres = mysqli_fetch_all($res, MYSQLI_ASSOC);

// Afficher les usagers
$query = "SELECT * FROM utilisateur";
$res = mysqli_query($conn, $query);
$usagers = mysqli_fetch_all($res, MYSQLI_ASSOC);

// Afficher liste des livres disponibles
$query = "SELECT * FROM livre WHERE nbExemplaire > 0";
$res = mysqli_query($conn, $query);
$livresDisponibles = mysqli_fetch_all($res, MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <meta charset="UTF-8">
    <title>Gestion des emprunts</title>
    <link rel="stylesheet" href="Style.css">
    <link rel="apple-touch-icon" sizes="180x180" href="imgs/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="imgs/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="imgs/favicon-16x16.png">
    <link rel="icon" type="image/x-icon" href="imgs/favicon.ico">
    <link rel="manifest" href="site.webmanifest">
</head>
<body>
    <div class="sucess">        
        <h2>Gestion des emprunts</h2>

        <!-- faire emprunt: -->
        
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" class="form">
        <fieldset><legend>Faire un emprunt</legend>
            
            <label for="livre">Livre :</label>
            <select name="livre" id="livre" class="box-input">
                <?php foreach ($livresDisponibles as $livre) : ?>
                    <option value="<?php echo $livre['id_livre']; ?>"><?php echo $livre['titre']; ?></option>
                <?php endforeach; ?>
            </select>
            <br>
            <label for="usager">Usager :</label>
            <select name="usager" id="usager" class="box-input">
                <?php foreach ($usagers as $usager) : ?>
                    <option value="<?php echo $usager['id_utilisateur']; ?>"><?php echo $usager['nomComplet']; ?></option>
                <?php endforeach; ?>
            </select>
            <br>
            <input type="submit" name="emprunter" value="Emprunter" class="box-button"><br>
            <br>
        </fieldset>
        </form>
        <br><br>

        <!-- rendre livre -->
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" class="form">
        <fieldset><legend>Rendre un livre</legend>
            <br>
            <label for="livre">Livre :</label>
            <select name="livre" id="livre_rendu" class="box-input">
                <?php foreach ($livres as $livre) : ?>
                    <option value="<?php echo $livre['id_livre']; ?>"><?php echo $livre['titre']; ?></option>
                <?php endforeach; ?>
            </select>
            <br>
            <label for="usager">Usager :</label>
            <select name="usager" id="usager_rendu" class="box-input">
                <?php foreach ($usagers as $usager) : ?>
                    <option value="<?php echo $usager['id_utilisateur']; ?>"><?php echo $usager['nomComplet']; ?></option>
                <?php endforeach; ?>
            </select>
            <br>
            <input type="submit" name="rendre" value="Rendre" class="box-button"><br>
            <br>
        </fieldset>
        </form>
        <br><br>

        <!-- afficher Livres disponibles: -->
        <fieldset>
            <legend>Livres disponibles</legend>
            <ul>
                <?php foreach ($livresDisponibles as $livre) : ?>
                    <li><?php echo $livre['titre']; ?></li>
                <?php endforeach; ?>
            </ul>
        </fieldset>
        <br><br>

        <!-- afficher Emprunts en cours -->
        <fieldset>
            <legend>Emprunts en cours</legend>
            <?php
            $query = "SELECT e.*, l.titre, u.nomComplet FROM emprunt e
                    JOIN livre l ON e.id_livre = l.id_livre
                    JOIN utilisateur u ON e.id_utilisateur = u.id_utilisateur";
            $res = mysqli_query($conn, $query);
            $emprunts = mysqli_fetch_all($res, MYSQLI_ASSOC);
            if (count($emprunts) > 0) {
                echo '<ul>';
                foreach ($emprunts as $emprunt) {
                    echo '<li>livre :  ' . $emprunt['id_livre'] . ' (' . $emprunt['titre'] . ')' . '&nbsp&nbsp&nbsp-&nbsp&nbsp&nbsp utilisateur :  ' . $emprunt['id_utilisateur'] . ' (' . $emprunt['nomComplet'] . ')' . '</li>';
                    echo '<br>';
                }
                echo '</ul>';
            } else {
                echo 'Aucun emprunt en cours.';
            }
            ?>
        </fieldset>
        <br><br>

        <!-- afficher Historique des emprunts -->
        <fieldset>
            <legend>Historique des emprunts</legend>
            <?php
            $query = "SELECT * FROM historique_emprunts";
            $res = mysqli_query($conn, $query);
            if ($res) {
                $historique = mysqli_fetch_all($res, MYSQLI_ASSOC);

                if (count($historique) > 0) {
                    echo '<ul>';
                    foreach ($historique as $historiqueEmprunt) {
                        echo '<li>Livre : ' . $historiqueEmprunt['id_livre'] . '&nbsp&nbsp&nbsp-&nbsp&nbsp&nbsp Utilisateur : ' . $historiqueEmprunt['id_utilisateur'] . '&nbsp&nbsp&nbsp-&nbsp&nbsp&nbsp Date emprunt : ' . $historiqueEmprunt['date_emprunt'] . '&nbsp&nbsp&nbsp-&nbsp&nbsp&nbsp Date retour : ' . $historiqueEmprunt['date_retour'] . '</li>';
                        echo '<br>';
                    }
                    echo '</ul>';
                } else {
                    echo 'Aucun emprunt dans l\'historique.';
                }
            } else {
                echo 'Erreur lors de la récupération de l\'historique des emprunts : ' . mysqli_error($conn);
            }
            ?>
        </fieldset>
        <br>
        <a class="retour" href="tabordAdmin.html">Retour au tableau de bord</a>
        <a class="sucess" href="logoutAdmin.php">Déconnexion</a>
        <br>
    </div>
</body>
</html>