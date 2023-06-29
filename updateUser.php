<?php 
require('config.php');
if(isset($_POST['modif']))
{
    $access=@mysqli_real_escape_string($conn,$_POST['nom']);
    $requete1="SELECT * FROM utilisateur WHERE nomComplet='$access' ";
    $result1=@mysqli_query($conn, $requete1);
    $coord=mysqli_fetch_row($result1);
?>

<!-- Création du formulaire -->
<!DOCTYPE html>
<html>
	<head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
		<title>users management</title>
	    <link rel="stylesheet" href="Style.css">
        <link rel="apple-touch-icon" sizes="180x180" href="imgs/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="imgs/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="imgs/favicon-16x16.png">
		<link rel="icon" type="image/x-icon" href="imgs/favicon.ico">
		<link rel="manifest" href="site.webmanifest">
	</head>
    <body>
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" class="form">
            <br><br>
            <fieldset>	
                <br>
                <legend style="text-align:center;">Modifier vos coordonnées</legend>	
                <br>
                <label for="nom">Nom Complet:</label>
                <input type="text" name="nom" id="nom" class="box-input" value="<?php echo $coord[1]; ?>" required>

                <label for="statut">Statut:</label>
                <input type="text" name="statut" id="statut" class="box-input" value="<?php echo $coord[2]; ?>" required>

                <label for="email">email:</label>
                <input type="email" name="email" id="email" class="box-input" value="<?php echo $coord[3]; ?>" required>
  
                <input type="reset" value=" Effacer" style="border-radius: 5px;background: #AA4502;cursor: pointer;font-size: 19px;color: #fff;border: 0;outline: 0; height: 2em;padding: 0;width:5em">
                <input type="submit" name="modif" value="Enregistrer" style="border-radius: 5px;background: #58af61;cursor: pointer;font-size: 19px;color: #fff;border: 0;outline: 0; height: 2em;padding: 0;width:5em">
            </fieldset>
            <br>
            <a href="UserManagement.php">retour au tableau de bord</a>
        </form>
    </body>
</html>

<?php } ?>

<?php                
    if(isset($_POST['nom'])&& isset($_POST['statut'])&& isset($_POST['email']))
    {
        //ENREGISTREMENT
        
        $nomC=$_POST['nom'];
        $statut=$_POST['statut'];
        $mail=$_POST['email'];

        //Requête SQL

        $requete="UPDATE utilisateur SET nomComplet='$nomC', statut='$statut', mail='$mail' WHERE nomComplet='$access'";
        $result=@mysqli_query($conn,$requete);
        mysqli_close($conn);
        if(!$result)
        {   
            echo "<script type=\"text/javascript\"> alert('erreur de modification');
            window.location='updateUser.php';</script>";
        }
        else
        {   
            echo "<script type=\"text/javascript\"> alert('Vos modifications sont enregistrées');
            window.location='userList.php';</script>";
        }
    }
    // else 
    // { 
    //     echo "<script type=\"text/javascript\"> alert('remplissez tous les champs!');</script>"; 
    // }
?>