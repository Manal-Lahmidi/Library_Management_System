<?php
	// Initialiser la session

	require('config.php');
	session_start();

	// Vérifiez si l'utilisateur est connecté, sinon redirigez-le vers la page de connexion

	if(!isset($_SESSION["username"]))
	{
		header("Location: login.php");
		exit(); 
	}

	$auteur = "";   
	$med = "";   
	$np = "";   
	$nex = "";
	
	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
        $id_sel = $_POST["libelelivre"];
        $sql = "SELECT * FROM livre WHERE titre ='$id_sel'";
        $res = mysqli_query($conn,$sql);
    
        if ($res->num_rows > 0)
		{
        // Affichage des détails du livre
            while ($row = $res->fetch_assoc()) {             
				$auteur = $row["auteur"];   
				$med = $row["maisonEdition"];   
				$np = $row["nbPage"];   
				$nex = $row["nbExemplaire"];     
            }
        }
        else
		{
            echo "<script type=\"text/javascript\"> alert('Aucun livre trouvé pour ce nom.');</script>";
        }
    }
?>

<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
		<title>tableau de bord</title>
		<link rel="stylesheet" href="Style.css">
		<link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
		<link rel="manifest" href="/favicon/site.webmanifest">
		<link rel="icon" type="image/x-icon" href="favicon.ico">
	</head>
	<body>
		<div class="sucess">
			<h2>Bienvenue <?php echo $_SESSION['username']; ?>!</h2>
			<p>dans votre tableau de bord</p>

			<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" class="form">
				<fieldset>
					<legend>affichage livres</legend>
					<br>
					<input type="text" name="libelelivre" id="libelelivre" placeholder="nom du livre" class="box-input" >
					<input type="submit" value="chercher" class="box-button"><br><br>

					<label for="auteur">Auteur :</label>
					<input type="text" name="auteur" id="auteur" class="box-input" value="<?php echo $auteur; ?>" readonly>

					<label for="maisonédition" >Maison d'édition :</label>
					<input type="text" name="maisonédition" id="maisonédition" class="box-input" value="<?php echo $med; ?>" readonly>

					<label for="nbrpage">Nombre de page :</label>
					<input type="text" name="nbrpage" id="nbrpage" class="box-input" value="<?php echo $np; ?>" readonly>

					<label for="nbrexemp">Nombre d'exemplaires :</label>
					<input type="text" name="nbrexemp" id="nbrexemp" class="box-input" value="<?php echo $nex; ?>" readonly>
				
			</form>

			<form action="listBookUser.php" method="post" class="form">		
				<input type="submit" value="liste des livres" class="box-button">
				<br>
			</form>
			</fieldset>
			<br>

			<!-- <h1>Gestion des emprunts</h1> -->

			<!-- faire emprunt : -->
			<form action="Emprunt.php" method="post" class="form">
				<fieldset>
					<legend>emprunter livre</legend>
					<br>
					<label for="user">Votre id:</label>
					<input type="text" name="user" id="user1" class="box-input" value="<?php echo $_SESSION['id_utilisateur'];?>">
					<br>
					<?php 
						// Afficher liste des livres disponibles
						$query = "SELECT * FROM livre WHERE nbExemplaire > 0";
						$res = mysqli_query($conn, $query);
						$livresDisponibles = mysqli_fetch_all($res, MYSQLI_ASSOC);
					?>
					<label for="livre">Livre :</label>
					<select name="livre" id="livreEmprunt" class="box-input">
						<?php foreach ($livresDisponibles as $livre) : ?>
							<option value="<?php echo $livre['id_livre']; ?>"><?php echo $livre['titre']; ?></option>
						<?php endforeach; ?>
					</select>
					<br>
        			<input type="submit" name="emprunter" value="Emprunter" class="box-button">
				</fieldset>
			</form>
			<br>
			<!-- rendre livre : -->
			<form action="RendreEmprunt.php" method="post" class="form">
				<fieldset>
					<legend>rendre livre</legend>
					<br>
					<label for="user">Votre id:</label>
					<input type="text" name="user" id="user2" class="box-input" value="<?php echo $_SESSION['id_utilisateur']; ?>">
					<br>
					<?php
						// Afficher les livres
						$query = "SELECT * FROM livre";
						$res = mysqli_query($conn, $query);
						$livres = mysqli_fetch_all($res, MYSQLI_ASSOC);
					?>
					<label for="livre">Livre :</label>
					<select name="livre" id="livreRendu" class="box-input">
						<?php foreach ($livres as $livre) : ?>
							<option value="<?php echo $livre['id_livre']; ?>"><?php echo $livre['titre']; ?></option>
						<?php endforeach; ?>
					</select>
					<br>
        			<input type="submit" name="rendre" value="Rendre" class="box-button">
				</fieldset>
			</form>
			<br>
			<!-- livres dispo: -->
			<fieldset>
				<legend>Livres disponibles</legend>
				<?php
					// Afficher liste des livres disponibles
					$query = "SELECT * FROM livre WHERE nbExemplaire > 0";
					$res = mysqli_query($conn, $query);
					$livresDisponibles = mysqli_fetch_all($res, MYSQLI_ASSOC);
					?>
				<ul class="tabord">
					<?php foreach ($livresDisponibles as $livre) : ?>
						<li><?php echo $livre['titre']; ?></li>
					<?php endforeach; ?>
				</ul>
			</fieldset>
			<br>
			<a href="logout.php">Déconnexion</a>
			<br>
		</div>
	</body>
</html>