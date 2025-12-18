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
    <title>enregistrer les admis</title>
    <link rel="stylesheet" media="screen" type="text/css" href="formatage.css"/>
    


<?php
function afficher_les_admis(){
    global $MOYENNE_MINIMALE,$ANNEE_ACADEMIQUE;
    include("connexion_bd.php");

    $sql="Select candidat.MATRICULE,nom_candidat ,sum(note_obtenue*coefficient)/sum(coefficient)
    as MOYENNE from candidat,matiere,composer where
    candidat.MATRICULE=composer.MATRICULE and composer.code_matiere=matiere.code_matiere
     and candidat.ANNEE_ACADEMIQUE=:ANNEE group by MATRICULE,nom_candidat order by nom_candidat";

    $sql=$db->prepare($sql);
    $sql->bindvalue(':ANNEE',$ANNEE_ACADEMIQUE);
    $sql->execute();

    echo"<table border=\"1px\">";
    echo"<caption>Liste des admis</caption>";
    echo"<tr><th>Numero</th><th>MATRICULE</th><th>Moyenne </th></tr>";
    $I=0;

    while ($donnees=$sql-> fetch(PDO::FETCH_ASSOC)){
        if ($donnees['MOYENNE']>=$MOYENNE_MINIMALE){
            $I=$I+1;

        echo"<tr><td>$I</td><td>".$donnees['MATRICULE'].
            "</td><td>".$donnees['MOYENNE']."</td></tr>";
        }
    }
   echo"</table>";
    $sql->closecursor();
}
?>
</head>
<body>
    <form action="#" method="POST">
    <?php
    $MOYENNE_MINIMALE=0;
    $ANNEE_ACADEMIQUE="";
    
    if(isset($_POST['ANNEE_ACADEMIQUE'])){
        $ANNEE_ACADEMIQUE=$_POST['ANNEE_ACADEMIQUE'];
    }
    if(isset($_POST['MOYENNE_MINIMALE'])){
        $MOYENNE_MINIMALE=floatval($_POST['MOYENNE_MINIMALE']);
    }
    ?>
    <table border="opx">
    <caption>Definir les parametres</caption>
    <tr><td>ANNEE_ACADEMIQUE</td><td>
        <input type="text" name="ANNEE_ACADEMIQUE" size="20" value="<?php echo $ANNEE_ACADEMIQUE;?>"></td></tr>
        <tr><td>MOYENNE MINIMALE</td><td>
            <input type="text" name="MOYENNE_MINIMALE" size="20" value="<?php echo $MOYENNE_MINIMALE;?>"></td></tr>
            <tr><td colspan="2" align="center"> 
                <input type="submit" name="btnok" value="ok"></td></tr>
</table>
<?php
    afficher_les_admis();
    ?>
    </form>
</body>
</html>