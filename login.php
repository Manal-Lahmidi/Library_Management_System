﻿<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
		<title>login</title>
		<link rel="stylesheet" href="Style.css">
		<link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
		<link rel="manifest" href="/favicon/site.webmanifest">
		<link rel="icon" type="image/x-icon" href="favicon.ico">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
	</head>
	<body>
		<?php
		require('config.php');
		session_start();

		if (isset($_POST['username']))
		{
			$username = stripslashes($_REQUEST['username']);
			$userlname = mysqli_real_escape_string($conn, $username);
			$password = stripslashes($_REQUEST['password']);
			$password = mysqli_real_escape_string($conn, $password);
			$query = "SELECT * FROM `utilisateur` WHERE nomComplet='$username' and password='".hash('sha256', $password)."'";
			$result = mysqli_query($conn,$query) or die(mysqli_error($conn));
			$rows = mysqli_num_rows($result);
			if($rows==1){
				$_SESSION['username'] = $username;
				// Récupérer l'ID de l'utilisateur depuis la base de données
				$query = "SELECT id_utilisateur FROM utilisateur WHERE nomComplet='$username'";
				$result = mysqli_query($conn, $query);
				$row = mysqli_fetch_assoc($result);
				$id_utilisateur = $row['id_utilisateur'];
			
				$_SESSION['id_utilisateur'] = $id_utilisateur;
				
				header("Location: tabord.php");	
			}
			else{
				$message = "Le nom d'utilisateur ou le mot de passe est incorrect.";
			}
		}
		?>
		<form class="box" action="" method="post" name="login">
			<h1 class="box-logo box-title"><a href="library.html">WikiBook</a></h1>
			<h1 class="box-title">Connexion</h1>
			<input type="text" class="box-input" name="username" placeholder="Nom complet d'utilisateur">
			<input type="password" class="box-input" name="password" id="id_password" placeholder="Mot de passe">
			<i class="far fa-eye" id="togglePassword" style="margin-left: -60px; cursor: pointer"></i>
			<input type="submit" value="Connexion " name="submit" class="box-button">
			<p class="box-register">Vous êtes nouveau ici? <a href="register.php"> S'inscrire</a></p>
			<?php if (! empty($message)) { ?>
				<p class="errorMessage"><?php echo $message; ?></p>
			<?php } ?>
		</form>
		<script>
			const togglePassword = document.querySelector('#togglePassword');
			const password = document.querySelector('#id_password');

			togglePassword.addEventListener('click', function (e) {
				// toggle the type attribute
				const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
				password.setAttribute('type', type);
				// toggle the eye slash icon
				this.classList.toggle('fa-eye-slash');
			});
		</script>
	</body>
</html>