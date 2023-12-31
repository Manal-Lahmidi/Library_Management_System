<?php
require('config.php');

// ********************* Faire un emprunt *****************************************************************************

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

//********************* Rendre un livre ******************************************************************************

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
    <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
    <link rel="manifest" href="/favicon/site.webmanifest">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
</head>
<body>
    <div class="sucess">        

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
                        echo '<li>Livre : ' . $historiqueEmprunt['id_livre'] . '&nbsp&nbsp&nbsp-&nbsp&nbsp&nbsp Utilisateur : ' . $historiqueEmprunt['id_utilisateur'] . '<br>Date emprunt : ' . $historiqueEmprunt['date_emprunt'] ;
                        if ($historiqueEmprunt['date_retour']) {
                            echo '<br>Date retour : ' . $historiqueEmprunt['date_retour'];
                        }
                        echo '</li>';
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
        <a class="retour" href="EmpruntManagement.php">Retour au tableau de bord</a>
        <a class="sucess" href="logoutAdmin.php">Déconnexion</a>
        <br>
    </div>
</body>
</html>
