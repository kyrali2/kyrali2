<?php
// Démarrer la session
session_start();


// Inclure la connexion à la base de données
include('connexion_db.php');


// Initialiser les messages d'erreur ou de succès
$message = "";

// TEMPORAIRE : Met un ID fixe pour tester sans connexion
$idEntreprise = $_SESSION['user_id'];


// Récupérer idProfil du demandeur connecté
$stmt = $conn->prepare("SELECT idProfil FROM ProfilEntreprsie WHERE idEntreprise = :idEntreprsie");
$stmt->bindParam(':idEntreprise', $idEntreprise, PDO::PARAM_INT);
$stmt->execute();
$profil = $stmt->fetch(PDO::FETCH_ASSOC);


if (!$profil) {
    die("Erreur : Aucun profil trouvé pour cet utilisateur.");
}


$idProfil = $profil['idprofil'];


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $typeRealisation = $_POST['typerealisation'];
    $descriptionRealisation = $_POST['descriptionrealisation'];
    $dateDebut = $_POST['datedebut'];
    $dateFin = !empty($_POST['datefin']) ? $_POST['datefin'] : null;
    $Source = !empty($_POST['source']) ? $_POST['source'] : null;


    $stmt = $conn->prepare("INSERT INTO ProjetDemandeur (idprofil, typerealisation, datedebut, datefin, descriptionrealisation, source)
                        VALUES (:idprofil, :typerealisation, :datedebut, :datefin, :descriptionrealisation, :source)");


$stmt->bindParam(':idprofil', $idProfil, PDO::PARAM_INT);
$stmt->bindParam(':typerealisation', $typeRealisation, PDO::PARAM_STR);
$stmt->bindParam(':datedebut', $dateDebut, PDO::PARAM_STR);
$stmt->bindParam(':datefin', $dateFin, PDO::PARAM_STR);
$stmt->bindParam(':descriptionrealisation', $descriptionRealisation, PDO::PARAM_STR);
$stmt->bindParam(':source', $Source, PDO::PARAM_STR);


$stmt->execute();


    header("Location: profil.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un projet</title>
    <link rel="stylesheet" href="updateprfil.css">


</head>
<body>
    <h1>Ajouter un projet</h1>
    <form method="POST">
        <label>Type de Projet :</label>
        <input type="text" name="typeprojet" required>


        <label>Description :</label>
        <textarea name="descriptionprojet" required></textarea>


        <label>Date de Début :</label>
        <input type="date" name="datedebut" required>


        <label>Date de Fin :</label>
        <input type="date" name="datefin">


        <label>Lien du Projet :</label>
        <input type="url" name="lienprojet">


        <button type="submit">Ajouter</button>
        <a href="profil.php">Annuler</a>
    </form>
</body>
</html>


