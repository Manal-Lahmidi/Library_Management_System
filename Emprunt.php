<?php
require('config.php');

// Faire un emprunt

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['emprunter'])) {
    $idUsager = $_POST['user'];
    $idLivre = $_POST['livre'];
    $dateEmprunt = date('Y-m-d');
    $dateRetour = date('Y-m-d', strtotime('+30 days'));

    // Vérifier si l'utilisateur a déjà emprunté cinq livres
    $query = "SELECT COUNT(*) AS total FROM emprunt WHERE id_utilisateur = $idUsager AND date_retour >= CURDATE()";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $totalEmprunts = $row['total'];
    if ($totalEmprunts >= 5) {
        echo "<script type=\"text/javascript\"> alert('Vous avez atteint la limite d\'emprunts. Vous ne pouvez pas emprunter plus de cinq livres.');
        window.location='tabord.php';</script>";
    }else {
        // Vérifier si l'utilisateur a déjà emprunté le livre dans les 30 derniers jours
        $dateLimite = date('Y-m-d', strtotime('-30 days'));
        $queryCheckEmprunt = "SELECT COUNT(*) FROM emprunt WHERE id_livre = $idLivre AND id_utilisateur = $idUsager AND date_emprunt >= '$dateLimite'";
        $resCheckEmprunt = mysqli_query($conn, $queryCheckEmprunt);
        $empruntExist = mysqli_fetch_row($resCheckEmprunt)[0];
        if ($empruntExist) {
            echo "<script type=\"text/javascript\"> alert('Vous avez déjà emprunté un exemplaire de ce livre dans ces derniers 30 jours.');   
            window.location='tabord.php';</script>";
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
                window.location='tabord.php';</script>";
                // Arrêter l'exécution du reste du code ou rediriger vers une autre page si nécessaire
            } else {
            // Insérer l'emprunt dans la table des emprunts
            $query = "INSERT INTO emprunt (id_livre, id_utilisateur, date_emprunt, date_retour) VALUES ('$idLivre', '$idUsager', '$dateEmprunt', '$dateRetour')";
            $res = mysqli_query($conn, $query);
            if ($res) {
                echo "<script type=\"text/javascript\"> alert('Emprunt ajouté avec succès.');
                window.location='tabord.php';</script>";
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
?>