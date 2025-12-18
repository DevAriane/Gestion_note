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
    <title>composition</title>
    <link rel="stylesheet" media="screen" type="text/css" href="formatage.css"/>
    <script src="menu_horizontal/SpryMenuBar.js" type="text/javascript"></script>
     <link href="menu_horizontal/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />

<?php

function recuperer_les_matricules(){
    global $matricule;

    include("connexion_bd.php");

    $sql="select MATRICULE from candidat order by MATRICULE";
    $sql=$db->prepare($sql);
    $sql->execute();

    echo"<select name=\"matricule\" onchange=\"submit()\">";
    $a="";

    echo'<option value="'.$a.'">'.$a.'</option>';
    while($donnees=$sql->fetch(PDO::FETCH_ASSOC)){
        $a=$donnees['MATRICULE'];
        if($a==$matricule){
            echo'<option value="'.$a.'"selected>'.$a.'</option>';
        }
        else{
            echo'<option value="'.$a.'">'.$a.'</option>';
        }//fin du if
    }//fin du else
    $sql->closecursor();
    echo"</select>";
    }//fin de la fonction

function recherche_nom_candidat(){
    global $matricule,$nom;

    include("connexion_bd.php");

    $sql="select NOM_CANDIDAT from candidat where MATRICULE=:m";
    $sql=$db->prepare($sql);
    $sql->bindvalue(':m',$matricule);
    $sql->execute();

    while($donnees=$sql->fetch(PDO::FETCH_ASSOC)){
        $nom=$donnees['NOM_CANDIDAT'];
    }
    $sql->closecursor();
}


function recherche_une_matiere(){
    global $code,$libelle_matiere;

    include("connexion_bd.php");

    $sql="select LIBELLE_MATIERE from matiere where CODE_MATIERE=:code";

    $sql=$db->prepare($sql);
    $sql->bindvalue(':code',$code);
    $sql->execute();

    while($donnees=$sql->fetch(PDO::FETCH_ASSOC)){
        $libelle_matiere=$donnees['LIBELLE_MATIERE'];
    }
    $sql->closecursor();
}


function recuperer_les_codes_matieres(){
        global $code;
    
        include("connexion_bd.php");
    
        $sql="select CODE_MATIERE from matiere order by CODE_MATIERE";
        $sql=$db->prepare($sql);
        $sql->execute();
    
        echo"<select name=\"code\" onchange=\"submit()\">";
        $a="";
    
        echo'<option value="'.$a.'">'.$a.'</option>';
        while($donnees=$sql->fetch(PDO::FETCH_ASSOC)){
            $a=$donnees['CODE_MATIERE'];
            if($a==$code){
                echo'<option value="'.$a.'"selected>'.$a.'</option>';
            }
            else{
                echo'<option value="'.$a.'">'.$a.'</option>';
            }//fin du if
        }//fin du else
        $sql->closecursor();
        echo"</select>";
        }//fin de la foction
    


 function liste_des_compositions(){
    include("connexion_bd.php");

    $sql="select candidat.MATRICULE ,matiere.CODE_MATIERE ,NOTE_OBTENUE
    from candidat,matiere,composer
    where candidat.MATRICULE=composer.MATRICULE and 
    composer.CODE_MATIERE=matiere.CODE_MATIERE
    order by MATRICULE";
    
    $sql=$db->prepare($sql);
    $sql->execute();

    if($sql->rowcount()>0){
        echo"<table border=\"1px\">";
        echo "<caption>LISTE DES COMPOSITIONS</caption>";
        echo"<tr><th>matricule</th><th>code</th><th>note</th></tr>";

        while($donnees = $sql->fetch(PDO::FETCH_ASSOC)){
            echo"<tr><td>".$donnees['MATRICULE']."</td><td>".
            $donnees['CODE_MATIERE']."</td><td>".
            $donnees['NOTE_OBTENUE']."</td></tr>";
        }
        echo "</table>";
    }
    $sql->closecursor();
}


function enregistrer_une_composition($note){
    
    include("connexion_bd.php");

   global $atricule,$code,$note;

   $sql= "insert into composer(MATRICULE,CODE_MATIERE,NOTE_OBTENUE)
                   values(:matricule,:code,:note)";

   $sql=$db->prepare($sql); //pour preciser au code dans quelle base de donnees il faut excecuter l'insertion.
   
    $sql->bindValue(':matricule', $matricule);
    $sql->bindValue(':code', $code);
    $sql->bindValue(':note', $note);

    $sql->execute();

   if($sql){
       echo "<h4><font color=green>insertion reussite</font></h4>";
   }
   else{
       echo"<h4><font color=red>insertion echouer</font></h4>";
   }
   $sql->closecursor();
}
function modifier_une_composition(){
    global $matricule,$code,$note;

    include("connexion_bd.php");
    
    $sql="update composer set NOTE_OBTENUE=:note
           where MATRICULE=:matricule and CODE_MATIERE=:code";

    $sql=$db->prepare($sql);

    $sql->bindValue(':matricule', $matricule);
    $sql->bindValue(':code', $code);
    $sql->bindValue(':note', $note);
    $sql->execute();

    if($sql){
        echo "<h4><font color=green>modification reussite</font></h4>";
    }

    else{
        echo "<h4><font color = red> la modification a echoue</font></h4>";
    }
    $sql->closecursor();
} 

function rechercher_une_composition($matricule,$code){

    include("connexion_bd.php");
    global $matricule , $code ,  $note;

    $sql ="select NOTE_OBTENUE 
    from composer
    where CODE_MATIERE=:code and MATRICULE=:matricule";

    $sql=$db->prepare($sql);
    $sql->bindValue(':matricule',$matricule);
    $sql->bindValue(':code',$code);
    $sql->execute();

    while($donnees=$sql->fetch(PDO::FETCH_ASSOC)){
        $note=$donnees['NOTE_OBTENUE'];
     }
     $sql->closecursor();

    }
    
function supprimer_une_composition(){
        global $matricule,$code;
        include("connexion_bd.php");

        $sql="delete from composer
          where MATRICULE=:matricule and CODE_MATIERE=:code"; 

        $sql=$db->prepare($sql);
        $sql->bindValue(':matricule',$matricule);
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

function verification_composition($code,$matricule){
        include("connexion_bd.php");
        $n=0;
        $sql="select NOTE_OBTENUE 
        from composer 
        where CODE_MATIERE=:code and MATRICULE=:matricule";
        $sql=$db->prepare($sql);
        $sql->bindValue(':code',$code);
        $sql->bindValue(':matricule',$matricule);
        $sql->execute();
        $n=$sql->rowcount();
        $sql->closecursor();
        return $n;
        }  

        function enregistrer_note(){
            global $matricule,$code_matiere,$note_obtenue;
            include("connexion_bd.php");
            $sql = "insert into copmoser(matricule,code_matiere,note_obtenue) values(:matricule,:code,:note)";
            $sql=$db->prepare($sql);
            $sql->bindvalue(':matricule',$matricule);
            $sql->bindValue(':code',$code_matiere);
            $sql->bindValue(':note',$note_obtenue);
            $sql->execute();

            if($sql){
                echo"<H4><font color blue> insertion reussie </font><H4>";
            }
            $sql->closecursor();
        }

?>

</head>
<body>

    <div class="form">
    <form action="#" method="POST">

    <?php

include("menu_horizontal/texte_menu_horizontal_simple.txt");
        $matricule="";
        $nom="";
        $code="";
        $libelle_matiere="";
        $note="";

        if(isset($_POST['matricule'])){
            $matricule=htmlspecialchars_decode($_POST['matricule']);
            recherche_nom_candidat();
        }

        if(isset($_POST['nom'])){
            $nom=htmlspecialchars_decode($_POST['nom']);
        }
        
        if(isset($_POST['code'])){
            $code=htmlspecialchars_decode($_POST['code']);
            recherche_une_matiere();
        }

        if(isset($_POST['libelle_matiere'])){
            $libelle_matiere=htmlspecialchars_decode($_POST['libelle_matiere']);
        }
        
        if(isset($_POST['note'])){
            $note=floatval($_POST['note']);
        }
 
        
         if(isset($_POST['btnenregistrer_composition'])){

            if(verification_composition($code,$matricule)==0){
                enregistrer_une_composition($note);
            }
            else{
                echo "<font color = red size = 4> cette matiere  existe deja</font>";
            }
        }

        
         if(isset($_POST['btnmodifier_composition'])){
            modifier_une_composition();
             //partie dappelation
        } 

        if(isset($_POST['btnrechercher_composition'])){
            rechercher_une_composition($matricule,$code);
        }

           
        if(isset($_POST['btnsupprimer_composition'])){
            supprimer_une_composition();
            //partie dappelation
        }

        
        ?>

        <table border="0px">
            <caption>GESTION DES NOTES</caption>

            <tr><td>Matricule</td><td><?php recuperer_les_matricules();?>
            <input type ="submit" name="btnrechercher_composition" value ="rechercher">
            </td></tr>
            <tr><td>Nom</td><td><?php echo $nom;?></td></tr>

            <tr><td>Code de la matiere</td><td><?php recuperer_les_codes_matieres();?>
            </td></tr>
            <tr><td>Libelle_matiere</td><td><?php echo $libelle_matiere;?></td></tr>

            <tr><td>Note obtenue</td><td>
             <input type="text" size="20" name="note" value="<?php echo $note;?>"></td></tr>

             <tr><td colspan="2" align-items="center">
                <input type="submit" name="btnenregistrer_composition" value="Enregistrer">&nbsp;&nbsp;
                <input type="submit" name="btnmodifier_composition" value="Modifier">&nbsp;&nbsp;
                <!--nbsp; permet de mettre l'espace entre les elements d'une cellule-->
                <input type="submit" name="btnsupprimer_composition" value="Supprimer"></td></tr>
    </table>
    <?php
        liste_des_compositions();
    ?>
    </form>
    </div>
</body>
</html>