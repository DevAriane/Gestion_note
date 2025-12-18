<?php

$pdo_options[PDO::ATTR_ERRMODE]=PDO::ERRMODE_EXCEPTION;
$db =new PDO('mysql:host=localhost;dbname=gestion_concours', 'root' ,'',$pdo_options);
$db->exec("SET NAMES 'utf8'");
//permettra de connecter notre site a la base de donnees

?>