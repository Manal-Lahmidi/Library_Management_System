<?php
require('config.php');

if(isset($_POST['supp'])){

    // Récupération de l'identifiant du livre à supprimer

    $idBook = $_POST['identi'];

    // Requête de suppression

    $query = "DELETE FROM livre WHERE id_livre = '$idBook'";
    $result=@mysqli_query($conn, $query);

    // Exécution de la requête

    if ($result) {
        echo "<script type=\"text/javascript\"> alert('Le livre a été supprimé avec succès.');
        window.location='listBook.php';</script>";
    } else {
        echo "<script type=\"text/javascript\"> alert('Erreur lors de la suppression de livre');
        window.location='BookManagement.php';</script>";
    }
    mysqli_close($conn);
}
?>