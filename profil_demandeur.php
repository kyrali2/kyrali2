<?php
// Connexion à la base de données
include('connexion_db.php');


// Vérifier si l'ID est présent dans l'URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("❌ Demandeur introuvable !");
}


$idDemandeur = $_GET['id'];


// Récupérer les informations du demandeur
$query = $conn->prepare("SELECT * FROM GetProfilDemandeur(:idDemandeur)");
$query->execute(['idDemandeur' => $idDemandeur]);
$profil = $query->fetch(PDO::FETCH_ASSOC);


if (!$profil) {
    die("⚠️ Profil non trouvé !");
}


// Récupérer les projets du demandeur
$queryProjets = $conn->prepare("SELECT * FROM ProjetDemandeur WHERE idProfil IN (SELECT idProfil FROM ProfilDemandeur WHERE idDemandeur = :idDemandeur)");
$queryProjets->execute(['idDemandeur' => $idDemandeur]);


$projets = $queryProjets->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>👤 Profil de <?php echo htmlspecialchars($profil['prenom'] . ' ' . $profil['nom']); ?></title>
    <link rel="stylesheet" href="profil_demandeur.css">
</head>
<body>


<div class="profil-container">
    <h1>👤 Profil de <?php echo htmlspecialchars($profil['prenom'] . ' ' . $profil['nom']); ?></h1>
   
    <div class="profil-info">
        <p>📧 Email : <?php echo htmlspecialchars($profil['email']); ?></p>
        <p>📞 Téléphone : <?php echo htmlspecialchars($profil['telephone']); ?></p>
        <p>🔥 Niveau : ⭐ <?php echo htmlspecialchars($profil['niveau']); ?> (Étoile d'argent)</p>
        <p>🏆 Nombre de projets : 🏅 <?php echo htmlspecialchars($profil['nombreprojets']); ?> (Étoile d'or)</p>
        <p>📝 Description : <?php echo nl2br(htmlspecialchars($profil['description'])); ?></p>
        <p>💼 Expérience Pro : <?php echo nl2br(htmlspecialchars($profil['experiencePro'] ?? "Aucune expérience professionnelle")); ?></p>
        <p>🎭 Expérience Perso : <?php echo nl2br(htmlspecialchars($profil['experiencePerso'] ?? "Aucune expérience personnelle")); ?></p>
    </div>


    <h2>📌 Projets réalisés</h2>
    <ul class="projets-list">
        <?php foreach ($projets as $projet): ?>
            <li>
                <strong>🎯 <?php echo htmlspecialchars($projet['typeprojet']); ?></strong> <br>
                🗓 Début : <?php echo htmlspecialchars($projet['datedebut']); ?> -
                <?php echo ($projet['datefin']) ? "Fin : " . htmlspecialchars($projet['datefin']) : "En cours"; ?>
                <p>📖 <?php echo nl2br(htmlspecialchars($projet['descriptionprojet'])); ?></p>
                <?php if (!empty($projet['lienprojet'])): ?>
                    <p>🔗 <a href="<?php echo htmlspecialchars($projet['lienprojet']); ?>" target="_blank">Voir le projet</a></p>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>


    <a href="liste_demandeur.php" class="btn-back">🔙 Retour</a>
    <a href="liste_demandeur.php" class="btn-back">🔙 Proposition d'entretien</a>
</div>


</body>
</html>
