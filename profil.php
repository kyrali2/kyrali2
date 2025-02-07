<?php

// Démarrer la session
session_start();

// Inclure la connexion à la base de données
include('connexion_db.php');

// Initialiser les messages d'erreur ou de succès
$message = "";



// TEMPORAIRE : Met un ID fixe pour tester sans connexion
$idDemandeur = 1; // Remplace par un ID existant dans ta base de données

// Appeler la fonction pour récupérer les projets


// Appeler la fonction pour récupérer les projets
$stmProjets = $conn->prepare("SELECT * FROM GetProjetParDemandeur(:idDemandeur)");
$stmProjets->bindParam(':idDemandeur', $idDemandeur, PDO::PARAM_INT);
$stmProjets->execute();

// Récupérer les résultats
$projets = $stmProjets->fetchAll(PDO::FETCH_ASSOC);




// Vérifier que l'ID existe en base de données
$query = $conn->prepare("SELECT * FROM DemandeurEmploi WHERE idDemandeur = ?");
$query->execute([$idDemandeur]);
$demandeur = $query->fetch(PDO::FETCH_ASSOC);

if (!$demandeur) {
    die("⚠️ Erreur : L'utilisateur n'existe pas !");
}

// Récupérer le profil
$query = $conn->prepare("SELECT * FROM ProfilDemandeur WHERE idDemandeur = ?");
$query->execute([$idDemandeur]);
$profil = $query->fetch(PDO::FETCH_ASSOC) ?: ["DescriptionDemandeur" => "", "ExperienceProfessionnelle" => "", "ExperiencePersonnelle" => ""];

// Récupérer le nombre de projets du demandeur
$stmCountProjets = $conn->prepare("SELECT CountProjetsDemandeur(:idDemandeur) AS total_projets");
$stmCountProjets->bindParam(':idDemandeur', $idDemandeur, PDO::PARAM_INT);
$stmCountProjets->execute();
$nbProjets = $stmCountProjets->fetch(PDO::FETCH_ASSOC)['total_projets'] ?? 0;

// Récupérer le niveau du demandeur
$stmNiveau = $conn->prepare("SELECT GetNiveauDemandeur(:idDemandeur) AS niveau");
$stmNiveau->bindParam(':idDemandeur', $idDemandeur, PDO::PARAM_INT);
$stmNiveau->execute();
$niveau = $stmNiveau->fetch(PDO::FETCH_ASSOC)['niveau'] ?? 0;



?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil</title>
    <link rel="stylesheet" href="profil.css">
</head>
<body>

<div class="profile-container">
    <div class="profile-card">
    <h1>👤 Bienvenue, <?php echo htmlspecialchars($demandeur['prenom']); ?> !</h1>

    <div class="badges">
    <div class="badge gold">
        ⭐ <span><?php echo htmlspecialchars($nbProjets); ?> -- Quêtes accomplies ! Continue ton aventure !</span> 
    </div>
    <div class="badge silver">
        🌟 <span><?php echo htmlspecialchars($niveau); ?> -- Niveau du héros ! Deviens une légende !</span> 
    </div>
</div>

        <p><strong>Nom :</strong> <?php echo htmlspecialchars($demandeur['nom']); ?></p>
        <p><strong>Prénom :</strong> <?php echo htmlspecialchars($demandeur['prenom']); ?></p>
        <p><strong>Email :</strong> <?php echo htmlspecialchars($demandeur['emaildemandeur']); ?></p>
        <p><strong>Téléphone :</strong> <?php echo htmlspecialchars($demandeur['telephonedemandeur']); ?></p>

        
    </div>
    <div class="projects-section">
    <h2>🏆 Ta Quête Épique ! (Ton parcours, tes exploits...)</h2>


<div class="profile-section">
    <h3>Description :</h3>
    <p><?php echo nl2br(htmlspecialchars($profil['descriptiondemandeur'])); ?></p>
</div>

<div class="profile-section">
    <h3>Expérience Professionnelle :</h3>
    <p><?php echo nl2br(htmlspecialchars($profil['experienceprofessionnelle'])); ?></p>
</div>

<div class="profile-section">
    <h3>Expérience Personnelle :</h3>
    <p><?php echo nl2br(htmlspecialchars($profil['experiencepersonnelle'])); ?></p>
</div>
<a class="monbutton" href="update_profil.php?idDemandeur=<?php echo $idDemandeur; ?>" class="edit-button">✏️ Modifier mon profil</a>

</div>

<div class="projects-section">
    <h2>🚀 Mes Projets</h2>
    <?php if (!empty($projets)): ?>
        <table class="projects-table">
            <thead>
                <tr>
                    <th>Type de Projet</th>
                    <th>Description</th>
                    <th>Date de Début</th>
                    <th>Date de Fin</th>
                    <th>Voir le Projet</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($projets as $projet): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($projet['typeprojet']); ?></td>
                        <td><?php echo htmlspecialchars($projet['descriptionprojet']); ?></td>
                        <td><?php echo htmlspecialchars($projet['datedebut']); ?></td>
                        <td><?php echo htmlspecialchars($projet['datefin']); ?></td>
                        <td><a href="<?php echo htmlspecialchars($projet['lienprojet']); ?>" target="_blank">Voir</a></td>
                    </tr>
                    
                <?php endforeach; ?>
                
            </tbody>
        </table>
    <?php else: ?>
        <p>Aucun projet ajouté.</p>
    <?php endif; ?>
        

   
<a class="monbutton" href="add_projet.php?idDemandeur=<?php echo $idDemandeur; ?>" class="add-project-button">➕ Ajouter un projet</a>

    
</div>

</div>

</body>
</html>