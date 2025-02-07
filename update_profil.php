<?php

// Démarrer la session
session_start();

// Inclure la connexion à la base de données
include('connexion_db.php');

// Initialiser les messages d'erreur ou de succès
$message = "";



// TEMPORAIRE : Met un ID fixe pour tester sans connexion
$idDemandeur = 1; // Remplace par un ID existant dans ta base de données


// Récupérer les infos du profil
$query = $conn->prepare("SELECT * FROM Profildemandeur WHERE iddemandeur = ?");
$query->execute([$idDemandeur]);
$profil = $query->fetch(PDO::FETCH_ASSOC) ?: ["descriptiondemandeur" => "", "experienceprofessionnelle" => "", "experiencepersonnelle" => ""];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $description = $_POST['description'];
    $experience_pro = $_POST['experience_pro'];
    $experience_perso = $_POST['experience_perso'];

    $update = $conn->prepare("UPDATE Profildemandeur SET 
                              descriptiondemandeur = ?, 
                              experienceprofessionnelle = ?, 
                              experiencepersonnelle = ?
                              WHERE iddemandeur = ?");
    $update->execute([$description, $experience_pro, $experience_perso, $idDemandeur]);

    header("Location: profil.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier mon profil</title>
    <link rel="stylesheet" href="updateprfil.css">

</head>
<body>
    <h1>Modifier mon profil</h1>
    <form method="POST">
        <label>Description :</label>
        <textarea name="description"><?php echo htmlspecialchars($profil['descriptiondemandeur']); ?></textarea>

        <label>Expérience Professionnelle :</label>
        <textarea name="experience_pro"><?php echo htmlspecialchars($profil['experienceprofessionnelle']); ?></textarea>

        <label>Expérience Personnelle :</label>
        <textarea name="experience_perso"><?php echo htmlspecialchars($profil['experiencepersonnelle']); ?></textarea>

        <button type="submit">Enregistrer</button>
        <a href="profil.php">Annuler</a>
    </form>
</body>
</html>
