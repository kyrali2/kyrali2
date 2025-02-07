<?php

// Démarrer la session
session_start();

// Inclure la connexion à la base de données
include('connexion_db.php');

// Initialiser les messages d'erreur ou de succès
$message = "";



// TEMPORAIRE : Met un ID fixe pour tester sans connexion
$idDemandeur = $_SESSION['user_id']; // Remplace par un ID existant dans ta base de données


// Récupérer les infos du profil
$query = $conn->prepare("SELECT * FROM Profildemandeur WHERE iddemandeur = ?");
$query->execute([$idDemandeur]);
$profil = $query->fetch(PDO::FETCH_ASSOC) ?: ["descriptiondemandeur" => "", "experienceprofessionnelle" => "", "experiencepersonnelle" => ""];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $description = $_POST['description'];
    $experience_pro = $_POST['experience_pro'];
    $experience_perso = $_POST['experience_perso'];

    $update = $conn->prepare("UPDATE profildemandeur SET 
                              descriptiondemandeur = ?, 
                              experienceprofessionnelle = ?, 
                              experiencepersonnelle = ?
                              WHERE iddemandeur = ?");
    $update->execute([$description, $experience_pro, $experience_perso, $idDemandeur]);
    if ($update->rowCount() == 0) {
        // Aucune ligne mise à jour, donc on insère une nouvelle ligne
        $insert = $conn->prepare("INSERT INTO profildemandeur (iddemandeur, descriptiondemandeur, experienceprofessionnelle, experiencepersonnelle) 
                                    VALUES (?, ?, ?, ?)");
        $insert->execute([$idDemandeur, $description, $experience_pro, $experience_perso]);
    }
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
        <label id="affich">Description :</label>
        <textarea name="description"><?php echo htmlspecialchars($profil['descriptiondemandeur']); ?></textarea>

        <label id="affich">Expérience Professionnelle :</label>
        <textarea name="experience_pro"><?php echo htmlspecialchars($profil['experienceprofessionnelle']); ?></textarea>

        <label id="affich">Expérience Personnelle :</label>
        <textarea name="experience_perso"><?php echo htmlspecialchars($profil['experiencepersonnelle']); ?></textarea>

        <button type="submit">Enregistrer</button>
        <a href="profil.php">Annuler</a>
    </form>
</body>
</html>
