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
    <title>enregistrer les matieres</title>
    <link rel="stylesheet" media="screen" type="text/css" href="formatage.css"/>
    <script src="menu_horizontal/SpryMenuBar.js" type="text/javascript"></script>
     <link href="menu_horizontal/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />



<?php

function liste_des_matieres(){
    include("connexion_bd.php");

    $sql="select CODE_MATIERE,LIBELLE_MATIERE,COEFFICIENT from matiere order by LIBELLE_MATIERE";
    $sql=$db->prepare($sql);
    $sql->execute();

    if($sql->rowcount()>0){
        echo"<table border=\"1px\">";
        echo "<caption>LISTE DES MATIERES</caption>";
        echo"<tr><th>code</th><th>Libelle</th><th>coefficient</th></tr>";
    
        while($donnees = $sql->fetch(PDO::FETCH_ASSOC)){
            echo"<tr><td>".$donnees['CODE_MATIERE']."</td><td>".$donnees['LIBELLE_MATIERE']."</td><td>".$donnees['COEFFICIENT']."</td></tr>";
        }
        echo "</table>";
    }
    $sql->closecursor();
}

function enregistrer_une_matiere(){
    
     include("connexion_bd.php");

    global $code , $libelle_matiere ,  $coefficient;

    $sql= "insert into matiere(CODE_MATIERE,LIBELLE_MATIERE,COEFFICIENT)
                    values(:code,:libelle_matiere,:coefficient)";

    $sql=$db->prepare($sql); //pour preciser au code dans quelle base de donnees il faut excecuter l'insertion.
    
     $sql->bindValue(':code', $code);
     $sql->bindValue(':libelle_matiere', $libelle_matiere);
     $sql->bindValue(':coefficient', $coefficient);

    $sql->execute();

    if($sql){
        echo "<h4><font color=green>insertion reussite</font></h4>";
    }
    else{
        echo"<h4><font color=red>insertion echouer</font></h4>";
    }
    $sql->closecursor();
}

function modifier_une_note(){
    global $code , $libelle_matiere , $coefficient;

    include("connexion_bd.php");
    
    $sql="update matiere set LIBELLE_MATIERE=:libelle_matiere,COEFFICIENT=:coefficient
           where CODE_MATIERE=:code";

    $sql=$db->prepare($sql);

    $sql->bindValue(':libelle_matiere', $libelle_matiere);
    $sql->bindValue(':coefficient', $coefficient);
    $sql->bindValue(':code', $code);
    $sql->execute();

    if($sql){
        echo "<h4><font color=green>modification reussite</font></h4>";
    }

    else{
        echo "<h4><font color = red> la modification a echoue</font></h4>";
    }
    $sql->closecursor();
}

function rechercher_une_matiere($code){

    include("connexion_bd.php");
    global $code , $libelle_matiere , $coefficient;

    $sql ="select LIBELLE_MATIERE, COEFFICIENT 
    from matiere
    where CODE_MATIERE=:code";

    $sql=$db->prepare($sql);
    $sql->bindValue(':code',$code);
    $sql->execute();

    while($donnees=$sql->fetch(PDO::FETCH_ASSOC)){
        $libelle_matiere=$donnees['LIBELLE_MATIERE'];
        $coefficient=$donnees['COEFFICIENT'];
     }

     $sql->closecursor();

    }

    function supprimer_une_matiere(){
        global $code;
        include("connexion_bd.php");

        $sql="delete from matiere where CODE_MATIERE=:code";

        $sql=$db->prepare($sql);
        $sql->bindValue(':code',$code);
        $sql->execute();

        
        if($sql){
            echo"<h4><font color = green size=4>suppresion reussite</font></h4>";
        }

        else{
            echo"<h4><font color =red size=4>Echec de la suppresion</font></h4>";
        }
        $sql->closecursor();

    }

    function verification_matiere($code){
        include("connexion_bd.php");
        $n=0;
        $sql="select*from matiere where CODE_MATIERE=:code";
        $sql=$db->prepare($sql);
        $sql->bindValue('code',$code);
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

        $code="";
        $libelle_matiere="";
        $coefficient="";

        if(isset($_POST['code'])){
            $code=htmlspecialchars_decode($_POST['code']);
        }
        
        if(isset($_POST['libelle_matiere'])){
            $libelle_matiere=htmlspecialchars_decode($_POST['libelle_matiere']);
        }
        
        if(isset($_POST['coefficient'])){
            $coefficient=htmlspecialchars_decode($_POST['coefficient']);
        }
 
        
        if(isset($_POST['btnenregistrer_matiere'])){

            if(verification_matiere($code)==0){
                enregistrer_une_matiere();
            }
            else{
                echo "<font color = red size = 4> cette matiere  existe deja</font>";
            }
           
        }

        if(isset($_POST['btnrechercher_matiere'])){
            rechercher_une_matiere($code);
        }

        if(isset($_POST['btnmodifier_matiere'])){
            modifier_une_note();
             //partie dappelation
        }

        if(isset($_POST['btnsupprimer_matiere'])){
            supprimer_une_matiere();
            //partie dappelation
        }


        ?>

        <table border="0px">
            <caption>GESTION DES MATIERES</caption>

            <tr><td>Code de la matiere</td><td>
            <input type ="text" size="20" name="code" value="<?php echo $code;?>">
            <input type ="submit" name="btnrechercher_matiere" value ="rechercher">
            </td></tr>

            <tr><td>libelle de la matiere</td><td>
            <input type ="text" size="20" name="libelle_matiere" value="<?php echo $libelle_matiere;?>"></td></tr>

            <tr><td>coefficient</td><td>
            <input type ="text" size="20" name="coefficient" value="<?php echo $coefficient;?>"></td></tr>

            <tr><td colspan="2" align-items="center">
                <input type="submit" name="btnenregistrer_matiere" value="Enregistrer">&nbsp;&nbsp;
                <input type="submit" name="btnmodifier_matiere" value="Modifier">&nbsp;&nbsp;
                <!--nbsp; permet de mettre l'espace entre les elements d'une cellule-->
                <input type="submit" name="btnsupprimer_matiere" value="Supprimer"></td></tr>
    </table>

    <?php
        liste_des_matieres();
        ?>
    </form>
</body>
</html>