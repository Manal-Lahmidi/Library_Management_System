<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <title>user list</title>
        <link rel="stylesheet" href="Style.css">
        <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
        <link rel="manifest" href="/favicon/site.webmanifest">
        <link rel="icon" type="image/x-icon" href="favicon.ico">
    </head>
    <body>
        <?php
            require('config.php');
            $requete="SELECT * FROM utilisateur";
            $result=mysqli_query($conn, $requete);

            if ($result->num_rows > 0)
            {
                $nbart=mysqli_num_rows($result);
                echo "<h3> Liste des utilisateurs</h3>";
                echo "<h4> Il y a $nbart utilisateur </h4>";
                // Affichage des détails du livre
                    echo "<table>";
                        echo "<tr>
                                <th>Id_utilisateur &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp</th>
                                <th>Nom complet &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp</th>
                                <th>Statut &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp</th>
                                <th>Email &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp</th>
                            </tr>";
                            while ($row = $result->fetch_assoc()) {  
                                echo "<tr>";
                                echo "<td>" . $row["id_utilisateur"] . "</td>";
                                echo "<td>" . $row["nomComplet"] . "</td>";
                                echo "<td>" . $row["statut"] . "</td>";
                                echo "<td>" . $row["mail"] . "</td>";             
                                echo "</tr>";
                            }
                    echo "</table>";   
                }
                
            else {
                echo "<script type=\"text/javascript\"> alert('Aucun utilisateur trouvé.');</script>";
            }
        ?>
        <br><br>
        <a class="retour" href="UserManagement.php">Retour au tableau de bord</a>
    </body>
</html>