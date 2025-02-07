<?php

// Démarrer la session
session_start();

// Inclure la connexion à la base de données
include('connexion_db.php');

// Initialiser les messages d'erreur ou de succès
$message = "";

// TEMPORAIRE : Met un ID fixe pour tester sans connexion
$idEntreprise = $_SESSION['user_id']; // Remplace par un ID existant dans ta base de données

// Récupérer les infos du profil
$query = $conn->prepare("SELECT * FROM profilentreprise WHERE identreprise = :idEntreprise");
$query->bindParam(':idEntreprise', $idEntreprise);
$query->execute();
$profil = $query->fetch(PDO::FETCH_ASSOC) ?: ["descriptionentreprise" => ""];


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $description = $_POST['description'];
   
    $update = $conn->prepare("UPDATE profilentreprise SET
                              descriptionentreprise = ?
                              WHERE identreprise = ?");
    $update->execute([$description,  $idEntreprise]);

    if ($update->rowCount() == 0) {
        // Aucune ligne mise à jour, donc on insère une nouvelle ligne
        $insert = $conn->prepare("INSERT INTO profilentreprise (identreprise,descriprionentreprise) 
                                    VALUES (?, ?, ?, ?)");
        $insert->execute([$idDemandeur, $description, $experience_pro, $experience_perso]);
    }
    header("Location: profilEntreprise.php");
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
        <textarea name="description"><?php echo htmlspecialchars($profil['descriptionentreprise']); ?></textarea>
        <button type="submit">Enregistrer</button>
        <a href="profil.php">Annuler</a>
    </form>
</body>
</html>