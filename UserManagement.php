<?php
	// Initialiser la session

	require('config.php');
	session_start();

	// Vérifiez si l'utilisateur est connecté, sinon redirigez-le vers la page de connexion

	if(!isset($_SESSION["adminame"])){
		header("Location: loginAdmin.php");
		exit();
	}

	// recherche des users:

	$iduser = "" ;   
	$name = "";   
	$stat = "";   
	$mail = "";

	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
        $id_user = $_POST["username"];
        $sql1 = "SELECT * FROM utilisateur WHERE nomComplet ='$id_user'";
        $res1 = mysqli_query($conn,$sql1);
    
        if ($res1->num_rows > 0)
		{
        // Affichage des détails de l'utilisatreur

            while ($row = $res1->fetch_assoc()) {             
				$iduser = $row["id_utilisateur"];   
				$name = $row["nomComplet"];   
				$stat = $row["statut"];   
				$mail = $row["mail"];     
            }
		}
		else 
		{
			echo "<script type=\"text/javascript\"> alert('Aucun utilisateur trouvé pour ce nom.');</script>";
		}
    }
?>

<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
		<title>user management</title>
		<link rel="stylesheet" href="Styl.css">
		<link rel="apple-touch-icon" sizes="180x180" href="imgs/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="imgs/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="imgs/favicon-16x16.png">
		<link rel="icon" type="image/x-icon" href="imgs/favicon.ico">
		<link rel="manifest" href="site.webmanifest">
	</head>
	<body>
		<div class="sucess">
			<h3>Bienvenue <?php echo $_SESSION['adminame']; ?> dans votre espace de gestion des utilisateurs</h3>
			<!-- gestion des users -->
				<!-- affichage  -->

				<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" class="form">
					<fieldset>
						<legend>Affichage</legend>

						<input type="text" name="username" id="username" placeholder="nom d'utilisateur" class="box-input">
						<br>
						<input type="submit" value="chercher" class="box-button">
						
						<label for="id">Id utilisateur :</label><br>
						<input type="text" name="id" id="id" class="box-input" value="<?php echo $iduser; ?>" readonly>
						
						<label for="name">Nom complet :</label><br>
						<input type="text" name="name" id="name" class="box-input" value="<?php echo $name; ?>" readonly>

						<label for="statut" >Statut :</label><br>
						<input type="text" name="statut" id="statut" class="box-input" value="<?php echo $stat; ?>" readonly>

						<label for="mail">Email :</label><br>
						<input type="text" name="mail" id="mail" class="box-input" value="<?php echo $mail; ?>" readonly>
						<br>
				</form>
				<br>
				<form action="userList.php" method="post" class="form">		
					<input type="submit" value="liste des utilisateurs" class="box-button">
					<br>
				</form>
				</fieldset>
				<br><br>

				<!-- insertion -->

				<form action="insertUser.php" method="post" class="form"> 
					<fieldset>
						<legend>Ajouter utilisateur</legend>
						<br>
						<label for="Nom" >Nom complet :</label>
						<input type="text" id="Nom" name="Nom" class="box-input" placeholder="nom d'utilisateur">
						
						<label for="statut" >Statut :</label>
						<input type="text" id="statut" name="statut" class="box-input" placeholder="statut">
						
						<label for="Mail" >Email :</label>
						<input type="email" id="Mail" name="Mail" class="box-input" placeholder="email">
						
						<label for="pswd" >Password :</label>
						<input type="password" id="pswd" name="pswd" class="box-input" placeholder="password">
						
						<input class="resett" type="reset" value="Effacer">
						<input class="submitt" type="submit" value="enregistrer">
					</fieldset>
       			</form>
				<br><br>

            	<!-- modofication -->
	
				<form method="post" action="updateUser.php" class="form">
					<fieldset>
						<legend>Modifier utilisateur</legend>
						<br>
						<label for="nom" >nom complet :</label>
						<input type="text" id="nom" name="nom" class="box-input" placeholder="nom d'utilisateur">
						
						<input class="resett" type="reset" value="Effacer" name="suppr">
						<input class="submitt" type="submit" value= "Modifier" name="modif">
					</fieldset>
				</form>
				<br><br>

				<!-- suppression -->

				<form method="post" action="deleteUser.php" class="form">
					<fieldset>
						<legend>supprimer utilisateur</legend>
						<br>
						<label for="name" >nom complet :</label>
						<input type="text" id="name" name="name" class="box-input" placeholder="nom d'utilisateur">
						
						<input class="resett" type="reset" value="Effacer" name="eff">
						<input class="submitt" type="submit" value= "Supprimer" name="supp" class="boxv2-button">
					</fieldset>
				</form>
				<br>
                <a class="retour" href="tabordAdmin.html">Retour au tableau de bord</a>
				<a href="logoutAdmin.php">Déconnexion</a>
			<br>
		</div>
	</body>
</html>