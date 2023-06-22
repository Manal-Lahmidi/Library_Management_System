<?php

require('config.php');

// Rendre un livre
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rendre'])) {
    $idLivre = $_POST['livre'];
    $idUsager = $_POST['user'];

    // Vérifier si le livre a été emprunté par l'utilisateur
    $query = "SELECT COUNT(*) AS total FROM emprunt WHERE id_livre = $idLivre AND id_utilisateur = $idUsager";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $totalEmprunts = $row['total'];

    if ($totalEmprunts <= 0) {
        echo "<script type=\"text/javascript\"> alert('Le livre n\'a pas été emprunté par cet utilisateur.');
        window.location='tabord.php';</script>";
    } else {
        // Supprimer l'emprunt de la table des emprunts
        $query = "DELETE FROM emprunt WHERE id_livre = $idLivre AND id_utilisateur = $idUsager AND date_retour >= CURDATE()";
        $result = mysqli_query($conn, $query);

        if ($result) {
            echo "<script type=\"text/javascript\"> alert('Livre rendu avec succès.');
            window.location='tabord.php';</script>";
        } else {
            echo "<script type=\"text/javascript\"> alert('Erreur lors du rendu du livre.');
            window.location='tabord.php';</script>";
        }

        // Mettre à jour le nombre d'exemplaires disponibles
        $query = "UPDATE livre SET nbExemplaire = nbExemplaire + 1 WHERE id_livre = $idLivre";
        $result = mysqli_query($conn, $query);

        // Enregistrer la date de retour dans l'historique des emprunts
        $queryUpdateRetour = "UPDATE historique_emprunts SET date_retour = CURDATE() WHERE id_livre = $idLivre AND id_utilisateur = $idUsager AND date_retour IS NULL";
        mysqli_query($conn, $queryUpdateRetour);
    }
}

?>
