<?php
$host = 'plg-broker.ad.univ-lorraine.fr';
//$host='localhost';
$bdd='gamejame';
$user='m1user1_21';
$mdp='m1user1_21';
//$port =5432; 
try{
$conn=new PDO("pgsql:host=$host;dbname=$bdd",$user,$mdp);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//echo"connexion établie";
} catch(PDOException $e){
echo"Impossible de se connecter à la base de données!!";

}
?>
/***** POUR CONNECTER VOTRE PAGE à la  base  */
// Démarrer la session
session_start();

// Inclure la connexion à la base de données
include('connexion_db.php');

// Initialiser les messages
$message = "";