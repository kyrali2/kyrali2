<?php
// Inclure la connexion à la base de données
include('connexion_db.php');


// Exécuter la requête pour récupérer les 5 premiers demandeurs
$query = $conn->prepare("SELECT * FROM GetTop5Demandeur()");
$query->execute();
$demandeurs = $query->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🏆 Top 5 Demandeurs</title>
    <link rel="stylesheet" href="liste_demandeur.css">
</head>
<body>


<h1>🏆 Hall of Fame : Top 5 Demandeurs</h1>


<table class="demandeurs-table">
    <thead>
        <tr>
            <th>👤 Nom</th>
            <th>🎭 Prénom</th>
            <th>📧 Email</th>
            <th>📞 Téléphone</th>
            <th>🔥 Niveau</th>
            <th>🏆 Projets</th>
            <th>🔍 Profil</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($demandeurs as $demandeur): ?>
        <tr>
            <td><?php echo htmlspecialchars($demandeur['nom']); ?></td>
            <td><?php echo htmlspecialchars($demandeur['prenom']); ?></td>
            <td><?php echo htmlspecialchars($demandeur['email']); ?></td>
            <td><?php echo htmlspecialchars($demandeur['telephone']); ?></td>
            <td>⭐ <?php echo htmlspecialchars($demandeur['niveau']); ?></td>
            <td>🏅 <?php echo htmlspecialchars($demandeur['nombreprojets']); ?></td>
            <td>
            <a href="profil_demandeur.php?id=<?php echo htmlspecialchars($demandeur['iddemandeur']); ?>" class="visit-button">👁️ Visiter</a>


            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>


</body>
</html>
