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
    <title>enregistrer des candidats</title>
    <link rel="stylesheet" media="screen" type="text/css" href="formatage.css"/>
    <script src="menu_horizontal/SpryMenuBar.js" type="text/javascript"></script>
     <link href="menu_horizontal/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />


    <?php

function convertir_date($ma_date){
    $tabdate = explode("/",$ma_date); //explode decoupe une chaine en segment
    return $tabdate[2]."-".$tabdate[1]."-".$tabdate[0]; 
}

    function enregistrer_un_candidat(){

        global $matricule , $nom , $date_naissance , $lieu_naissance , $filiere , $annee_academique;
         //global permet d'etendre la portee d'une variable
    
        include("connexion_bd.php");
    
        $sql="insert into candidat(MATRICULE,NOM_CANDIDAT,DATE_NAISSANCE, 
                                     LIEU_NAISSANCE,FILIERE,ANNEE_ACADEMIQUE)
                            values(:matricule,:nom,:date ,:lieu_naissance,:filiere,:annee_academique)";
    
        $sql=$db->prepare($sql); //pour preciser au code dans quelle base de donnees il faut excecuter l'insertion.
    
        $sql->bindvalue(':matricule', $matricule);
        $sql->bindvalue(':nom', $nom);
        $sql->bindvalue(':date', convertir_date($date_naissance));
        $sql->bindvalue(':lieu_naissance', $lieu_naissance);
        $sql->bindvalue(':filiere', $filiere);
        $sql->bindvalue(':annee_academique',$annee_academique);
    
        $sql->execute();
    
        if($sql){
            echo "<h4><font color=green>insertion reussite</font></h4>";
        }
        else{
            echo"<h4><font color=red>insertion echouer</font></h4>";
        }
        $sql->closecursor();

    }

    function verification_matricule($mat){
        include ("connexion_bd.php");
        $n=0;
        $sql="select * from candidat where matricule=:m";
        $sql=$db-> prepare ($sql);
        $sql->bindvalue(':m',$mat);
        $sql->execute();
        $n=$sql->rowcount();
        $sql->closecursor();
        return $n;

    }
    
    function recherche_candidat ($mat){
        global $nom, $date_naissance, $lieu_naissance, $filiere, $annee_academique;
        include ("connexion_bd.php");
        $sql="select NOM_CANDIDAT, date_format(DATE_NAISSANCE, '%d/%m/%Y') as
        DATE_NAISSANCE, LIEU_NAISSANCE, FILIERE, ANNEE_ACADEMIQUE
        from candidat where MATRICULE=:m";

        $sql=$db->prepare($sql);
        $sql->bindvalue(':m',$mat);
        $sql->execute();

        while($donnees=$sql->fetch(PDO::FETCH_ASSOC)){
            $nom=$donnees['NOM_CANDIDAT'];
            $date_naissance=$donnees['DATE_NAISSANCE'];
            $lieu_naissance=$donnees['LIEU_NAISSANCE'];
            $filiere=$donnees['FILIERE'];
            $annee_academique=$donnees['ANNEE_ACADEMIQUE'];
        }
        $sql->closecursor();
    }

    function modifier_un_candidat(){

        global $matricule , $nom , $date_naissance , $lieu_naissance , $filiere , $annee_academique;

        include("connexion_bd.php");
        
        $sql="update candidat set NOM_CANDIDAT=:nom,DATE_NAISSANCE=:date,LIEU_NAISSANCE=:lieu_naissance,
        FILIERE=:filiere,ANNEE_ACADEMIQUE=:annee_academique where MATRICULE=:matricule";

        $sql=$db->prepare($sql); //pour preciser au code dans quelle base de donnees il faut excecuter l'insertion.
    
        $sql->bindValue(':nom', $nom);
        $sql->bindValue(':date', convertir_date($date_naissance));
        $sql->bindValue(':lieu_naissance', $lieu_naissance);
        $sql->bindValue(':filiere', $filiere);
        $sql->bindValue(':annee_academique',$annee_academique);
        $sql->bindValue(':matricule', $matricule); 
        $sql->execute();

        if($sql){
            echo"<font color = green size=4>Modifiaction reussite</font>";
        }

        else{
            echo"<font color =red size=4>Echec de la modification</font>";
        }
        $sql->closecursor();
        }

    function supprimer_un_candidat(){
        //partie de creation de la fonction

        global $matricule;
        include("connexion_bd.php");

        $sql="delete from candidat where MATRICULE=:matricule";

        $sql=$db->prepare($sql);
        $sql->bindValue(':matricule',$matricule);
        $sql->execute();

        
        if($sql){
            echo"<font color = green size=4>suppresion reussite</font>";
        }

        else{
            echo"<font color =red size=4>Echec de la suppresion</font>";
        }
        $sql->closecursor();

       }
        ?>

</head>
<body>
     
    <form action="#" method="post">
        <?php

       include("menu_horizontal/texte_menu_horizontal_simple.txt");
        $matricule="";
        $nom="";
        $date_naissance="";
        $lieu_naissance="";
        $filiere="";
        $annee_academique="";

        if(isset($_POST['matricule'])){
            $matricule=htmlspecialchars_decode($_POST['matricule']);
        }
        
        if(isset($_POST['nom'])){
            $nom=htmlspecialchars_decode($_POST['nom']);
        }
        
        if(isset($_POST['date_naissance'])){
            $date_naissance=htmlspecialchars_decode($_POST['date_naissance']);
        }
        
        if(isset($_POST['lieu_naissance'])){
            $lieu_naissance=htmlspecialchars_decode($_POST['lieu_naissance']);
        }
        
        if(isset($_POST['filiere'])){
            $filiere=htmlspecialchars_decode($_POST['filiere']);
        }
        
        if(isset($_POST['annee_academique'])){
            $annee_academique=htmlspecialchars_decode($_POST['annee_academique']);
        }

        
        if (isset($_POST['btnenregistrer_candidat'])){
            if(verification_matricule($matricule)==0){
                enregistrer_un_candidat();
            } else{
                echo"<font color=red size=4> Le matricule attribué au nouveau candidat existe déja </font>";
            }
        }

        if(isset($_POST['btnrechercher_candidat'])){
            recherche_candidat($matricule);
        }

        if(isset($_POST['btnmodifier_candidat'])){
            modifier_un_candidat();
       }
              
if(isset($_POST['btnsupprimer_candidat'])){
    supprimer_un_candidat();
       }
        ?>

        <table border="0px">
            <caption>GESTION DES CANDIDATS</caption>

            <tr><td>Matricule</td><td>
            <input type ="text" size="20" name="matricule" value="<?php echo $matricule;?>">
            <input type ="submit" name="btnrechercher_candidat" value="Rechercher">
        
        </td></tr>

            <tr><td>Nom</td><td>
            <input type ="text" size="20" name="nom" value="<?php echo $nom;?>"></td></tr>

            <tr><td>Date de naissance</td><td>
            <input type ="text" size="20" name="date_naissance" value="<?php echo $date_naissance;?>"></td></tr>

            <tr><td>Lieu de naissance</td><td>
            <input type ="text" size="20" name="lieu_naissance" value="<?php echo $lieu_naissance;?>"></td></tr>

            <tr><td>Filiere</td><td>
            <input type ="text" size="20" name="filiere" value="<?php echo $filiere;?>"></td></tr>

            <tr><td>Annee academique</td><td>
            <input type ="text" size="20" name="annee_academique" value="<?php echo $annee_academique;?>"></td></tr>

            <tr><td colspan="2" align-items="center">
                <input type="submit" name="btnenregistrer_candidat" value="Enregistrer">&nbsp;&nbsp;
                <input type="submit" name="btnemodifier_candidat" value="Modifier">&nbsp;&nbsp;
                <!--nbsp; permet de mettre l'espace entre les elements d'une cellule-->
                <input type="submit" name="btnsupprimer_candidat" value="Supprimer"></td></tr>
    </table>
    </form>
</body>
</html>