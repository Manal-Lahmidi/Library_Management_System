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
		<link rel="stylesheet" href="formStyle.css">
		<link rel="apple-touch-icon" sizes="180x180" href="imgs/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="imgs/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="imgs/favicon-16x16.png">
		<link rel="icon" type="image/x-icon" href="imgs/favicon.ico">
		<link rel="manifest" href="site.webmanifest">
		<style>
			.boxv2-input {
				font-size: 0.5em;
				background: #fff;
				border: 1px solid #ddd;
				margin-bottom: 1.8em;
				padding-left: 1em;
				border-radius: 5px;
				width: 95%;
				height: 50px;
			}
		</style>
	</head>
	<body>
		<div class="sucess">
			<h3 >Bienvenue <?php echo $_SESSION['adminame']; ?> dans votre espace de de gestion des livres</h3>
            
			<!-- gestion des livres -->
				<!-- affichage -->

					<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" class="form">
						<fieldset>
							<legend style="font-size:1em;">Affichage</legend>
							<br>
							<input type="text" name="libelelivre" id="libelelivre" placeholder="nom du livre" class="boxv2-input">
							<input style="font-size:0.6em" type="submit" value="chercher" class="box-button">
							
							<br><br>
							
							<label style="font-size:0.7em" class="labell" for="name">Titre :</label>
							<input type="text" name="titre" id="titre" class="boxv2-input" value="<?php echo $bookname; ?>" readonly>

							<label style="font-size:0.7em" class="labell" for="auteur">Auteur :</label>
							<input type="text" name="auteur" id="auteur" class="boxv2-input" value="<?php echo $auteur; ?>" readonly>

							<label style="font-size:0.7em" class="labell" for="maisonédition" >Maison d'édition :</label>
							<input type="text" name="maisonédition" id="maisonédition" class="boxv2-input" value="<?php echo $med; ?>" readonly>

							<label style="font-size:0.7em" class="labell" for="nbrpage">Nombre de page :</label>
							<input type="text" name="nbrpage" id="nbrpage" class="boxv2-input" value="<?php echo $np; ?>" readonly>

							<br>

							<label style="font-size:0.7em" class="labell" for="nbrexemp">Nombre d'exemplaires :</label>
							<input type="text" name="nbrexemp" id="nbrexemp" class="boxv2-input" value="<?php echo $nex; ?>" readonly>
							<br>
					</form>	

					<form action="listBook.php" method="post" class="form">		
						<input style="font-size:0.6em" type="submit" value="liste des livres" class="box-button">
						<br>
					</form>	

					</fieldset>
					<br>

				<!-- insertion -->

				<form action="insertBook.php" method="post" class="form"> 
					<fieldset>
						<legend style="font-size:1em;">Ajouter livre</legend>
						<br>
						<label style="font-size:0.7em" class="labell" for="titre">Titre :</label>
						<br><input type="text" id="titre" name="titre" class="boxv2-input" placeholder="titre du livre">
						
						<label style="font-size:0.7em" class="labell" for="auteur">Auteur :</label>
						<input type="text" id="auteur" name="auteur" class="boxv2-input" placeholder="auteur">
						
						<label style="font-size:0.7em" class="labell" for="med">maison d'édition :</label>
						<input type="text" id="med" name="med" class="boxv2-input" placeholder="maison d'édition">
						
						<label style="font-size:0.7em" class="labell" for="nbp">Nombre de page :</label>
						<input type="text" id="nbp" name="nbp" class="boxv2-input" placeholder="nombre de pages">
						
						<label style="font-size:0.7em" class="labell" for="nbe">Nombre d'exemplaires :</label>
						<input type="text" id="nbe" name="nbe" class="boxv2-input" placeholder="nombre d'exemplaires">
						
						<input class="labell" type="reset" value="Effacer" style="border-radius: 5px;background: #AA4502;cursor: pointer;font-size: 19px;color: #fff;border: 0;outline: 0; height: 2em;padding: 0;width:5em">
						<input type="submit" value="enregistrer" style="border-radius: 5px;background: #58af61;cursor: pointer;font-size: 19px;color: #fff;border: 0;outline: 0; height: 2em;padding: 0;width:5em">
					</fieldset>
       			</form>
				<br>

				<!-- modification --> 

				<form method="post" action="updateBook.php" class="form">
					<fieldset>
                		<legend style="font-size:1em;">Modifier livre</legend>
						<br>
						<label style="font-size:0.7em" class="labell" for="id">id livre :</label>
						<input type="text" name="id" class="boxv2-input" size="20" maxlength="10" placeholder="id livre"> 
						
						<input type="reset" value="Effacer" name="suppr" style="border-radius: 5px;background: #AA4502;cursor: pointer;font-size: 19px;color: #fff;border: 0;outline: 0; height: 2em;padding: 0;width:5em">
						<input type="submit" value= "Modifier" name="modif"  style="border-radius: 5px;background: #58af61;cursor: pointer;font-size: 19px;color: #fff;border: 0;outline: 0; height: 2em;padding: 0;width:5em">
					</fieldset>
				</form>
				<br>

				<!-- suppresssion --> 

				<form action="deleteBook.php" method="post" class="form">
					<fieldset>
						<legend style="font-size:1em;">Supprimer livre</legend>
						<br>
						<label style="font-size:0.7em" class="labell" for="identi">id livre :</label>
						<input type="text" name="identi" class="boxv2-input" size="20" maxlength="10" placeholder="id livre"> 
						
						<input type="reset" value="Effacer" name="eff" style="border-radius: 5px;background: #AA4502;cursor: pointer;font-size: 19px;color: #fff;border: 0;outline: 0; height: 2em;padding: 0;width:5em">
						<input type="submit" value= "Supprimer" name="supp"  style="border-radius: 5px;background: #58af61;cursor: pointer;font-size: 19px;color: #fff;border: 0;outline: 0; height: 2em;padding: 0;width:5em">
					</fieldset>
				</form>
				<br>
			<a id="retour4" href="tabordAdmin.html">Retour au tableau de bord</a>
			<br>
			<a href="logoutAdmin.php">Déconnexion</a>
			<br>
		</div>
	</body>
</html>