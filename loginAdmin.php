<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
		<title>login</title>
		<link rel="stylesheet" href="Styl.css">
		<link rel="apple-touch-icon" sizes="180x180" href="imgs/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="imgs/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="imgs/favicon-16x16.png">
		<link rel="icon" type="image/x-icon" href="imgs/favicon.ico">
		<link rel="manifest" href="site.webmanifest">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
	</head>
	<body>
		<?php
		require('config.php');
		session_start();

		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$adminame = stripslashes($_REQUEST['adminame']);
			$adminame = mysqli_real_escape_string($conn, $adminame);
			$password = stripslashes($_REQUEST['password']);
			$password = mysqli_real_escape_string($conn, $password);
			$query = "SELECT * FROM `admin` WHERE nomcomplet='$adminame' AND password='$password'";
			$result = mysqli_query($conn,$query) or die(mysqli_error($conn));
			$rows = mysqli_num_rows($result);
			if ($result->num_rows == 1) 
			{
				$_SESSION['adminame'] = $adminame;
				header("Location: tabordAdmin.html");
				
			}
			else{
				
				$message = "Le nom ou le mot de passe est incorrect.";
			}
		}
		?>
		<form class="box" action="" method="post" name="login">
			<h1 class="box-logo box-title"><a href="library.html">WikiBook</a></h1>
			<h1 class="box-title">Connexion</h1>
			<input type="text" class="box-input" name="adminame" placeholder="Nom complet de l'admin">
			<input type="password" class="box-input" id="id_password" name="password" placeholder="Mot de passe">
			<i class="far fa-eye" id="togglePassword" style="margin-left: -30px; cursor: pointer"></i>
			<input type="submit" value="Connexion " name="submit" class="box-button">
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