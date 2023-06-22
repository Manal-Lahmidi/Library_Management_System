<?php 
require('config.php');
if (isset($_POST['titre'], $_POST['auteur'], $_POST['med'], $_POST['nbp'], $_POST['nbe'])){
	
	// récupérer le prenom d'utilisateur et supprimer les antislashes ajoutés par le formulaire
	$bookname = stripslashes($_POST['titre']);
	$bookname = mysqli_real_escape_string($conn, $bookname);
	
	// récupérer le nom d'utilisateur et supprimer les antislashes ajoutés par le formulaire
	$author = stripslashes($_POST['auteur']);
	$author = mysqli_real_escape_string($conn, $author);
	
	// récupérer l'email et supprimer les antislashes ajoutés par le formulaire
	$med = stripslashes($_POST['med']);
	$med = mysqli_real_escape_string($conn, $med);
	
	// récupérer le mot de passe et supprimer les antislashes ajoutés par le formulaire
	$nbp = stripslashes($_POST['nbp']);
	$nbp = mysqli_real_escape_string($conn, $nbp);
	
	// récupérer le mot de passe et supprimer les antislashes ajoutés par le formulaire
	$nbe = stripslashes($_POST['nbe']);
	$nbe = mysqli_real_escape_string($conn, $nbe);
    
	//requéte SQL + mot de passe crypté
    $query = "INSERT into `livre` (titre, auteur, maisonEdition, nbPage, nbExemplaire)
              VALUES ('$bookname','$author','$med', '$nbp', '$nbe')";
	
	// Exécute la requête sur la base de données
    $res = mysqli_query($conn, $query);
    if($res){
       echo "<script type=\"text/javascript\"> alert('livre ajouté avec succès');
	   window.location='listBook.php';</script>";
             
    }
    else{
        echo "<script type=\"text/javascript\"> alert('erreur d'insertion');
		window.location='BookManagement.php';</script>" ;
    }
}
else{
    echo "<script type=\"text/javascript\"> alert('complétez le formulaire');</script>" ;
    
}