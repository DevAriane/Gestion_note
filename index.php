<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Connecter les utilisateurs</title>
    <link rel="stylesheet" media="screen" type="text/css" href="formatage.css"/>
    


<?php

function identification_utilisateur(){
    global $nom_utilisateur, $mot_passe, $niveau;
    
    if(($nom_utilisateur=="")or ($mot_passe=="")){
        return "mauvais";
    }

    include ("connexion_bd.php");
    $sql="Select nom_utilisateur,mot_passe,niveau from utilisateur where nom_utilisateur=:nom and mot_passe=:mot";

    $sql=$db->prepare($sql); //pour preciser au code dans quelle base de donnees il faut excecuter l'insertion.
    
    $sql->bindValue(':nom', $nom_utilisateur);
    $sql->bindValue(':mot', $mot_passe);

    $sql->execute();
    $nom="";
    $mot="";

    while($donnees=$sql->fetch(PDO::FETCH_ASSOC)){
        
        $nom=$donnees['nom_utilisateur'];
        $mot=$donnees['mot_passe'];
        $niveau=$donnees['niveau'];
    }

    $sql->closecursor();

    $_SESSION['niveau_securite']=$niveau;

    if ((strcmp($nom,$nom_utilisateur)==0) and (strcmp($mot,$mot_passe)==0)){
        return "ok";
    }else{
        return "mauvais";
    }
}

?>

</head>
<body>

<form action="#" method="POST">
        <?php
       

        $nom_utilisateur="";
        $mot_passe="";
        $niveau="";


        if(isset($_POST['nom'])){
            $nom_utilisateur=($_POST['nom']);
        }
        
        if(isset($_POST['mot'])){
            $mot_passe=($_POST['mot']);
        }

        if (isset($_POST['btnok'])){
            if(identification_utilisateur()=="ok"){
                
                echo "<script type=\"text/javascript\">";
                echo "parent.document.location.href=\"gestion_candidat.php\"";
                echo "</script>";
            }else{
                echo "<font color=red size=4> Mot de passe non valide </font>";
            }
        }

        ?>

        <table border="0px">
            <caption> CONNEXION AU PROGRAMME</caption>

            <tr><td>Nom utilisateur</td><td>
            <input type ="text" size="20" name="nom" value="<?php echo $nom_utilisateur;?>"></td></tr>
            
            </td></tr>

            <tr><td>Mot de passe</td><td>
            <input type ="text" size="20" name="mot" value="<?php echo $mot_passe;?>"></td></tr>
        
        </td></tr>

            <tr><td colspan="2" align-items="center">
                <input type="submit" name="btnok" value="ok">
               
            
            </table>

    </form>
    </body>
</html>