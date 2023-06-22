
<?php 
require('config.php');
if (isset($_POST['Nom'], $_POST['statut'], $_POST['Mail'], $_POST['pswd'])){

	// récupérer le prenom d'utilisateur et supprimer les antislashes ajoutés par le formulaire
	
	$username = stripslashes($_POST['Nom']);
	$username = mysqli_real_escape_string($conn, $username);
	
	// récupérer le nom d'utilisateur et supprimer les antislashes ajoutés par le formulaire
	$userstat = stripslashes($_POST['statut']);
	$userstat = mysqli_real_escape_string($conn, $userstat);
	
	// récupérer l'email et supprimer les antislashes ajoutés par le formulaire
	$email = stripslashes($_POST['Mail']);
	$email = mysqli_real_escape_string($conn, $email);
	
	// récupérer le mot de passe et supprimer les antislashes ajoutés par le formulaire
	$password = stripslashes($_POST['pswd']);
	$password = mysqli_real_escape_string($conn, $password);
	
	//requéte SQL + mot de passe crypté
    $query = "INSERT into `utilisateur` (nomComplet, statut, mail, password)
              VALUES ('$username','$userstat','$email', '".hash('sha256', $password)."')";
	
	// Exécute la requête sur la base de données
    $res = mysqli_query($conn, $query);
    if($res){
       echo "<script type=\"text/javascript\"> alert('utilisateur ajouté avec succès.');
	   		window.location='userList.php';</script>";
             
    }
    else{
        echo "<script type=\"text/javascript\"> alert('erreur d'insertion.');
			window.location='UserManagement.php';</script>" ;
    }
}
else{
    echo "<script type=\"text/javascript\"> alert('complétez le formulaire.');
		window.location='UserManagement.php';</script>" ;
    
}