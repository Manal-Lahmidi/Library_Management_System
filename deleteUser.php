<?php
    require('config.php');
    if(isset($_POST['supp'])){

    // Récupération de l'identifiant du livre à supprimer

    $userName = $_POST['name'];

    // Requête de suppression

    $query = "DELETE FROM utilisateur WHERE nomComplet = '$userName'";
    $result=@mysqli_query($conn, $query);

    // Exécution de la requête

    if ($result) {
        echo "<script type=\"text/javascript\"> alert('Utilisateur supprimé avec succès.');
        window.location='userList.php';</script>";
    } else {
        echo "<script type=\"text/javascript\"> alert('Erreur lors de la suppression de l'utilisateur.');
        window.location='UserManagement.php';</script>";
    }
    mysqli_close($conn);
    }
?>