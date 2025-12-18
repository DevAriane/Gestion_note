<?php
session_start();
    if(isset($_SESSION['niveau_securite'])){
    }else{
        echo "<center><font size=5 color=red> Veuillez decliner votre identite !!!</font></center>";
        return;
    }        
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>enregistrer les utilisateurs</title>
    <link rel="stylesheet" media="screen" type="text/css" href="formatage.css"/>
    <script src="menu_horizontal/SpryMenuBar.js" type="text/javascript"></script>
     <link href="menu_horizontal/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />



<?php

function liste_des_utilisateurs(){
    include("connexion_bd.php");

    $sql="select nom_utilisteur,mot_passe,niveau from utilisateur order by nom_utilisateur";
    $sql=$db->prepare($sql);
    $sql->execute();

    if($sql->rowcount()>0){
        echo"<table border=\"1px\">";
        echo "<caption>LISTE DES UTILISATEURS</caption>";
        echo"<tr><th>nom des utilisateurs</th><th>mot de passe</th><th>niveau</th></tr>";

        while($donnees = $sql->fetch(PDO::FETCH_ASSOC)){
            echo"<tr><td>".$donnees['nom_utilisateur']."</td><td>".$donnees['mot_passe']."</td><td>".$donnees['niveau']."</td></tr>";
        }
        echo "</table>";
    }
    $sql->closecursor();
}

function enregistrer_un_utilisateur(){
    
     include("connexion_bd.php");

    global $nom , $passeword ,  $niveau;

    $sql= "insert into utilisateur(NOM_UTILISATEUR,MOT_PASSE,NIVEAU)
                    values(:nom,:passeword,:niveau)";

    $sql=$db->prepare($sql); //pour preciser au code dans quelle base de donnees il faut excecuter l'insertion.
    
     $sql->bindValue(':nom', $nom);
     $sql->bindValue(':passeword', $passeword);
     $sql->bindValue(':niveau', $niveau);

    $sql->execute();

    if($sql){
        echo "<h4><font color=green>insertion reussite</font></h4>";
    }
    else{
        echo"<h4><font color=red>insertion echouer</font></h4>";
    }
    $sql->closecursor();
}

function modifier_un_utilisateur(){
    global $nom , $passeword ,  $niveau;

    include("connexion_bd.php");
    
    $sql="update utilisateur set MOT_PASSE=:passeword,NIVEAU=:niveau
    where NOM_UTILISATEUR=:nom";

    $sql=$db->prepare($sql);

    $sql->bindValue(':nom', $nom);
    $sql->bindValue(':passeword', $passeword);
    $sql->bindValue(':niveau', $niveau);
    $sql->execute();

    if($sql){
        echo "<h4><font color=green>modification reussite</font></h4>";
    }

    else{
        echo "<h4><font color = red> la modification a echoue</font></h4>";
    }
    $sql->closecursor();
}

function rechercher_un_utilisateur($code){

    include("connexion_bd.php");
    global $nom , $passeword ,  $niveau;

    $sql ="select MOT_PASSE,NIVEAU  from Utilisateur order by NOM_UTILISATEUR=:nom";

    $sql=$db->prepare($sql);
    $sql->bindValue(':nom',$nom);
    $sql->execute();

    while($donnees=$sql->fetch(PDO::FETCH_ASSOC)){
        $passeword=$donnees['MOT_PASSE'];
        $niveau=$donnees['NIVEAU'];
     }

     $sql->closecursor();

    }

    function supprimer_un_utilasateur(){
        global $nom;
        include("connexion_bd.php");

        $sql="delete from utilisateur where NOM_UTILISATEUR=:nom";

        $sql=$db->prepare($sql);
        $sql->bindValue(':nom',$nom);
        $sql->execute();

        
        if($sql){
            echo"<h4><font color = green size=4>suppresion reussite</font></h4>";
        }

        else{
            echo"<h4><font color =red size=4>Echec de la suppresion</font></h4>";
        }
        $sql->closecursor();

    }

    function verification_utilisateur($nom){
        include("connexion_bd.php");
        $n=0;
        $sql="select*from utilisateur where NOM_UTILISATEUR=:nom";
        $sql=$db->prepare($sql);
        $sql->bindValue(':nom',$nom);
        $sql->execute();
        $n=$sql->rowcount();
        $sql->closecursor();
        return $n;
        }
    

?>

</head>
<body>
     
    <form action="#" method="post">
        <?php
include("menu_horizontal/texte_menu_horizontal_simple.txt");

        $nom="";
        $passeword="";
        $niveau="";

        if(isset($_POST['nom'])){
            $nom=htmlspecialchars_decode($_POST['nom']);
        }
        
        if(isset($_POST['passeword'])){
            $passeword=htmlspecialchars_decode($_POST['passeword']);
        }
        
        if(isset($_POST['niveau'])){
            $niveau=htmlspecialchars_decode($_POST['niveau']);
        }
 
        
        if(isset($_POST['btnenregistrer_utilisateur'])){

            if(verification_utilisateur($nom)==0){
                enregistrer_un_utilisateur();
            }
            else{
                echo "<font color = red size = 4> cette matiere  existe deja</font>";
            }
           
        }

        if(isset($_POST['btnrechercher_utilisateur'])){
            rechercher_un_utilisateur($nom);
        }

        if(isset($_POST['btnmodifier_utilisateur'])){
            modifier_un_utilisateur();
             //partie dappelation
        }

        if(isset($_POST['btnsupprimer_utilisateur'])){
            supprimer_un_utilasateur();
            //partie dappelation
        }


        ?>

        <table border="0px">
            <caption>GESTION DES UTILISATEURS</caption>

            <tr><td>nom utilisateur</td><td>
            <input type ="text" size="20" name="nom" value="<?php echo $nom;?>"></td></tr>
            
            </td></tr>

            <tr><td>mot de passe</td><td>
            <input type ="text" size="20" name="passeword" value="<?php echo $passeword;?>"></td></tr>

            <tr><td>Niveau</td><td>
            <input type ="text" size="20" name="niveau" value="<?php echo $niveau;?>">
            

            <tr><td colspan="2" align-items="center">
                <input type="submit" name="btnenregistrer_utilisateur" value="Enregistrer">&nbsp;&nbsp;
                <input type="submit" name="btnmodifier_utilisateur" value="Modifier">&nbsp;&nbsp;
                <!--nbsp; permet de mettre l'espace entre les elements d'une cellule-->
                <input type="submit" name="btnsupprimer_utilisateur" value="Supprimer"></td></tr>
    </table>

    <?php
        liste_des_utilisateurs();
        ?>
    </form>
</body>
</html>