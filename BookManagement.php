<?php
	// Initialiser la session

	require('config.php');
	session_start();

	// Vérifiez si l'utilisateur est connecté, sinon redirigez-le vers la page de connexion
	
	if(!isset($_SESSION["adminame"])){
		header("Location: loginAdmin.php");
		exit();
	}

	// recherche livres:

	$bookname = "";
	$auteur = "";
	$med = "";
	$np = "";
	$nex = "";
	if ($_SERVER["REQUEST_METHOD"] == "POST"){	
		$id_book = $_POST["libelelivre"];
		$sql2 = "SELECT * FROM livre WHERE titre ='$id_book'";
		$res2 = mysqli_query($conn,$sql2);

		if ($res2->num_rows > 0) {

		// Affichage des détails du livre

			while ($row = $res2->fetch_assoc()) { 
				$bookname=$row["titre"];          
				$auteur = $row["auteur"];   
				$med = $row["maisonEdition"];   
				$np = $row["nbPage"];   
				$nex = $row["nbExemplaire"];     
			}
		}
		else {
			echo "<script type=\"text/javascript\"> alert('Aucun livre trouvé pour ce nom.');</script>";
		}
	}

?>
<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
		<title>book management</title>
		<link rel="stylesheet" href="Style.css">
		<link rel="apple-touch-icon" sizes="180x180" href="imgs/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="imgs/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="imgs/favicon-16x16.png">
		<link rel="icon" type="image/x-icon" href="imgs/favicon.ico">
		<link rel="manifest" href="site.webmanifest">
	</head>
	<body>
		<div class="sucess">
			<h3>Bienvenue <?php echo $_SESSION['adminame']; ?> dans votre espace de de gestion des livres</h3>
            
			<!-- gestion des livres -->
				<!-- affichage -->

					<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" class="form">
						<fieldset>
							<legend>Affichage</legend>
							
							<input type="text" name="libelelivre" id="libelelivre" placeholder="nom du livre" class="box-input">
							<input type="submit" value="chercher" class="box-button">
							
							<label class="labell" for="name">Titre :</label>
							<input type="text" name="titre" id="titre" class="box-input" value="<?php echo $bookname; ?>" readonly>

							<label class="labell" for="auteur">Auteur :</label>
							<input type="text" name="auteur" id="auteur" class="box-input" value="<?php echo $auteur; ?>" readonly>

							<label class="labell" for="maisonédition" >Maison d'édition :</label>
							<input type="text" name="maisonédition" id="maisonédition" class="box-input" value="<?php echo $med; ?>" readonly>

							<label class="labell" for="nbrpage">Nombre de page :</label>
							<input type="text" name="nbrpage" id="nbrpage" class="box-input" value="<?php echo $np; ?>" readonly>

							<label class="labell" for="nbrexemp">Nombre d'exemplaires :</label>
							<input type="text" name="nbrexemp" id="nbrexemp" class="box-input" value="<?php echo $nex; ?>" readonly>
							<br>
					</form>	

					<form action="listBook.php" method="post" class="form">		
						<input type="submit" value="liste des livres" class="box-button">
						<br>
					</form>	

					</fieldset>
					<br>

				<!-- insertion -->

				<form action="insertBook.php" method="post" class="form"> 
					<fieldset>
						<legend>Ajouter livre</legend>
						
						<label class="labell" for="titre">Titre :</label>
						<input type="text" id="titre" name="titre" class="box-input" placeholder="titre du livre">
						
						<label class="labell" for="auteur">Auteur :</label>
						<input type="text" id="auteur" name="auteur" class="box-input" placeholder="auteur">
						
						<label class="labell" for="med">maison d'édition :</label>
						<input type="text" id="med" name="med" class="box-input" placeholder="maison d'édition">
						
						<label class="labell" for="nbp">Nombre de page :</label>
						<input type="text" id="nbp" name="nbp" class="box-input" placeholder="nombre de pages">
						
						<label class="labell" for="nbe">Nombre d'exemplaires :</label>
						<input type="text" id="nbe" name="nbe" class="box-input" placeholder="nombre d'exemplaires">
						
						<input class="resett" type="reset" value="Effacer">
						<input class="submitt" type="submit" value="enregistrer">
					</fieldset>
       			</form>
				<br>

				<!-- modification --> 

				<form method="post" action="updateBook.php" class="form">
					<fieldset>
                		<legend>Modifier livre</legend>
						
						<label class="labell" for="id">id livre :</label>
						<input type="text" name="id" class="box-input" size="20" maxlength="10" placeholder="id livre"> 
						
						<input class="resett" type="reset" value="Effacer" name="suppr">
						<input class="submitt" type="submit" value= "Modifier" name="modif" >
					</fieldset>
				</form>
				<br>

				<!-- suppresssion --> 

				<form action="deleteBook.php" method="post" class="form">
					<fieldset>
						<legend>Supprimer livre</legend>
						
						<label class="labell" for="identi">id livre :</label>
						<input type="text" name="identi" class="box-input" size="20" maxlength="10" placeholder="id livre"> 
						
						<input class="resett" type="reset" value="Effacer" name="eff">
						<input class="submitt" type="submit" value= "Supprimer" name="supp" >
					</fieldset>
				</form>
				<br>
			<a class="retour" href="tabordAdmin.html">Retour au tableau de bord</a>
			<a href="logoutAdmin.php">Déconnexion</a>
			<br>
		</div>
	</body>
</html>