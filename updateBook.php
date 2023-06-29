<?php 
require('config.php');
    if(isset($_POST['modif']))
    { 
    $idBook=@mysqli_real_escape_string($conn,$_POST['id']);
    $requete="SELECT * FROM livre WHERE id_livre='$idBook' ";
    $result=@mysqli_query($conn, $requete);
    $coord=mysqli_fetch_row($result);
    
?>
<!-- Création du formulaire -->
<!DOCTYPE html>
<html>
	<head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
		<title>book management</title>
	    <link rel="stylesheet" href="Style.css">
        <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
        <link rel="manifest" href="/favicon/site.webmanifest">
        <link rel="icon" type="image/x-icon" href="favicon.ico">
	</head>
    <body>
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" class="form">
            <br><br>
            <fieldset>	
            <br>
            <legend style="text-align:center;">Modifier les coordonnées du livre</legend>	
            <br>
            <label for="titre">Titre :</label>
            <input type="text" name="titre" id="titre" class="box-input" value="<?php echo $coord[1]; ?>" required>
            
            <label for="auteur">Auteur :</label>
            <input type="text" name="auteur" id="auteur" class="box-input" value="<?php echo $coord[2]; ?>" required>
            
            <label for="med" >Maison d'édition :</label>
            <input type="text" name="med" id="med" class="box-input" value="<?php echo $coord[3]; ?>" required>

            <label for="nbp">Nombre de page :</label>
            <input type="text" name="nbp" id="nbp" class="box-input" value="<?php echo $coord[4]; ?>" required>

            <label for="nbrexemp">Nombre d'exemplaires :</label>
            <input type="text" name="nbe" id="nbe" class="box-input" value="<?php echo $coord[5]; ?>" required>

            <input type="reset" class="resett" value=" Effacer">
            <input type="submit" class="submitt" name="modif" value="Enregistrer">
            </fieldset>
            <br>
            <input type="hidden" name="id" value="<?php echo $idBook;?>">
            <a href="BookManagement.php">retour au tableau de bord</a>    
        </form>
    </body>
 </html>
<?php 
}   
else{
    echo "<script type=\"text/javascript\"> alert('erreur de traitement du formulaire')</script>";
}
if(isset($_POST['titre'])&& isset($_POST['auteur'])&& isset($_POST['med'])&& isset($_POST['nbp'])&& isset($_POST['nbe']))
    {
        //ENREGISTREMENT

        $title=$_POST['titre'];
        $auteur=$_POST['auteur'];
        $med=$_POST['med'];
        $nbp=$_POST['nbp'];
        $nbe=$_POST['nbe'];
        $idBook=$_POST['id'];

        //Requête SQL

        $requete="UPDATE livre SET titre='$title', auteur='$auteur', maisonEdition='$med', nbPage='$nbp', nbExemplaire='$nbe' WHERE id_livre='$idBook'";
        $result=@mysqli_query($conn,$requete);
        if(!$result)
        {   
            echo "<script type=\"text/javascript\"> alert('Erreur : ".mysqli_error($conn)."')</script>";
        }
        else
        { 
            echo "<script type=\"text/javascript\"> alert('Vos modifications sont enregistrées');
            window.location='listBook.php';</script>"; 
        }
        mysqli_close($conn);
    }
?>

