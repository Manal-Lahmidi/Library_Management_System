<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <title>liste des livres</title>
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
            $requete="SELECT * FROM livre";
            $result=mysqli_query($conn, $requete);

            if ($result->num_rows > 0) 
            {
                $nbart=mysqli_num_rows($result);
                echo "<h3> Tous nos livres</h3>";
                echo "<h4> Il y a $nbart livres </h4>";

                // Affichage des détails du livre

                echo "<table>";
                    echo "  <tr>
                                <th><b>id &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp</b></th>

                                <th>Titre &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp 
                                    &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp</th>
                                
                                <th>Auteur &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp
                                    &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp</th>
                                
                                <th>Maison d'édition &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp</th>
                                
                                <th>Nbr de page &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp</th>
                                
                                <th>Nbr d'éxemplaire &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp</th>
                            </tr>";
                    while ($row = $result->fetch_assoc()) {  
                        echo "<tr>";
                        echo "<td>" . $row["id_livre"] . "</td>";
                        echo "<td>" . $row["titre"] . "</td>";
                        echo "<td>" . $row["auteur"] . "</td>";
                        echo "<td>" . $row["maisonEdition"] . "</td>";
                        echo "<td>" . $row["nbPage"] . "</td>";
                        echo "<td>" . $row["nbExemplaire"] . "</td>";              
                        echo "</tr>";
                            }
                echo "</table>";   
            }
                
            else {
                echo "<script type=\"text/javascript\"> alert('Aucun livre trouvé');</script>";
            }
        ?>
        <br><br>
        <a class="retour" href="tabord.php">Retour au tableau de bord</a>
    </body>
</html>