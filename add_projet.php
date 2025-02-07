<?php
// Démarrer la session
session_start();

// Inclure la connexion à la base de données
include('connexion_db.php');

// Initialiser les messages d'erreur ou de succès
$message = "";



// TEMPORAIRE : Met un ID fixe pour tester sans connexion
$idDemandeur = $_SESSION['user_id']; 

// Récupérer idProfil du demandeur connecté
$stmt = $conn->prepare("SELECT idProfil FROM ProfilDemandeur WHERE idDemandeur = :idDemandeur");
$stmt->bindParam(':idDemandeur', $idDemandeur, PDO::PARAM_INT);
$stmt->execute();
$profil = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$profil) {
    die("Erreur : Aucun profil trouvé pour cet utilisateur.");
}

$idProfil = $profil['idprofil'];


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $typeProjet = $_POST['typeprojet'];
    $descriptionProjet = $_POST['descriptionprojet'];
    $dateDebut = $_POST['datedebut'];
    $dateFin = !empty($_POST['datefin']) ? $_POST['datefin'] : null;
    $lienProjet = !empty($_POST['lienprojet']) ? $_POST['lienprojet'] : null;

    $stmt = $conn->prepare("INSERT INTO ProjetDemandeur (idprofil, typeprojet, datedebut, datefin, descriptionprojet, lienprojet) 
                        VALUES (:idprofil, :typeprojet, :datedebut, :datefin, :descriptionprojet, :lienprojet)");

$stmt->bindParam(':idprofil', $idProfil, PDO::PARAM_INT);
$stmt->bindParam(':typeprojet', $typeProjet, PDO::PARAM_STR);
$stmt->bindParam(':datedebut', $dateDebut, PDO::PARAM_STR);
$stmt->bindParam(':datefin', $dateFin, PDO::PARAM_STR);
$stmt->bindParam(':descriptionprojet', $descriptionProjet, PDO::PARAM_STR);
$stmt->bindParam(':lienprojet', $lienProjet, PDO::PARAM_STR);

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
        <label id="affich">Type de Projet :</label>
        <input type="text" name="typeprojet" required>

        <label id="affich">Description :</label>
        <textarea name="descriptionprojet" required></textarea>

        <label id="affich">Date de Début :</label>
        <input type="date" name="datedebut" required>

        <label id="affich">Date de Fin :</label>
        <input type="date" name="datefin">

        <label id="affich">Lien du Projet :</label>
        <input type="url" name="lienprojet">

        <button type="submit">Ajouter</button>
        <a href="profil.php">Annuler</a>
    </form>
</body>
</html>
