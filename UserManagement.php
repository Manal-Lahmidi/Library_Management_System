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
		<link rel="stylesheet" href="formStyle.css">
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
						<legend style="font-size:1em;">Affichage</legend>
						<br>
						<input type="text" name="username" id="username" placeholder="nom d'utilisateur" class="boxv2-input">
						<input style="font-size:0.6em" for="id" type="submit" value="chercher" class="box-button">

						<br><br>
						
						<label style="font-size:0.7em"for="id">Id utilisateur :</label><br>
						<input type="text" name="id" id="id" class="boxv2-input" value="<?php echo $iduser; ?>" readonly>
						
						<label style="font-size:0.7em" for="name">Nom complet :</label><br>
						<input type="text" name="name" id="name" class="boxv2-input" value="<?php echo $name; ?>" readonly>

						<label style="font-size:0.7em" for="statut" >Statut :</label><br>
						<input type="text" name="statut" id="statut" class="boxv2-input" value="<?php echo $stat; ?>" readonly>

						<label style="font-size:0.7em" for="mail">Email :</label><br>
						<input type="text" name="mail" id="mail" class="boxv2-input" value="<?php echo $mail; ?>" readonly>
						<br>
				</form>
				
				<form action="userList.php" method="post" class="form">		
					<input style="font-size:0.6em" type="submit" value="liste des utilisateurs" class="box-button">
					<br>
				</form>
				</fieldset>
				<br>

				<!-- insertion -->

				<form action="insertUser.php" method="post" class="form"> 
					<fieldset>
						<legend style="font-size:1em;">Ajouter utilisateur</legend>
						<br>
						<label style="font-size:0.7em" for="Nom" >Nom complet :</label>
						<input type="text" id="Nom" name="Nom" class="boxv2-input" placeholder="nom d'utilisateur">
						
						<label style="font-size:0.7em" for="statut" >Statut :</label>
						<input type="text" id="statut" name="statut" class="boxv2-input" placeholder="statut">
						
						<label style="font-size:0.7em" for="Mail" >Email :</label>
						<input type="email" id="Mail" name="Mail" class="boxv2-input" placeholder="email">
						
						<label style="font-size:0.7em" for="pswd" >Password :</label>
						<input type="password" id="pswd" name="pswd" class="boxv2-input" placeholder="password">
						
						<input type="reset" value="Effacer" style="border-radius: 5px;background: #AA4502;cursor: pointer;font-size: 19px;color: #fff;border: 0;outline: 0; height: 2em;padding: 0;width:5em">
						<input type="submit" value="enregistrer" style="border-radius: 5px;background: #58af61;cursor: pointer;font-size: 19px;color: #fff;border: 0;outline: 0; height: 2em;padding: 0;width:5em">
					</fieldset>
       			</form>
				<br>

            	<!-- modofication -->
	
				<form method="post" action="updateUser.php" class="form">
					<fieldset>
						<legend style="font-size:1em;">Modifier utilisateur</legend>
						<br>
						<label style="font-size:0.7em" for="statut" >nom complet :</label>
						<input type="text" id="nom" name="nom" class="boxv2-input" placeholder="nom d'utilisateur">
						
						<input type="reset" value="Effacer" name="suppr" style="border-radius: 5px;background: #AA4502;cursor: pointer;font-size: 19px;color: #fff;border: 0;outline: 0; height: 2em;padding: 0;width:5em">
						<input type="submit" value= "Modifier" name="modif"  style="border-radius: 5px;background: #58af61;cursor: pointer;font-size: 19px;color: #fff;border: 0;outline: 0; height: 2em;padding: 0;width:5em">
					</fieldset>
				</form>
				<br>

				<!-- suppression -->

				<form method="post" action="deleteUser.php" class="form">
					<fieldset>
						<legend style="font-size:1em;">supprimer utilisateur</legend>
						<br>
						<label style="font-size:0.7em" for="name" >nom complet :</label>
						<input type="text" id="name" name="name" class="boxv2-input" placeholder="nom d'utilisateur">
						
						<input type="reset" value="Effacer" name="eff" style="border-radius: 5px;background: #AA4502;cursor: pointer;font-size: 19px;color: #fff;border: 0;outline: 0; height: 2em;padding: 0;width:5em">
						<input type="submit" value= "Supprimer" name="supp" class="boxv2-button" style="border-radius: 5px;background: #58af61;cursor: pointer;font-size: 19px;color: #fff;border: 0;outline: 0; height: 2em;padding: 0;width:5em">
					</fieldset>
				</form>
				<br>
                <a id="retour3" href="tabordAdmin.html">Retour au tableau de bord</a>
				<br>
			<a href="logoutAdmin.php">Déconnexion</a>
			<br>
		</div>
	</body>
</html>