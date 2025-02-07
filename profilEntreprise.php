<?php

// Démarrer la session
session_start();

// Inclure la connexion à la base de données
include('connexion_db.php');

// Initialiser les messages d'erreur ou de succès
$message = "";

// TEMPORAIRE : Met un ID fixe pour tester sans connexion
$idEntreprise = $_SESSION['user_id']; // Remplace par un ID existant dans ta base de données

// Appeler la fonction pour récupérer les projets
$stmProjets = $conn->prepare("SELECT * FROM GetprojetsEntreprise(:idEntreprise)");
$stmProjets->bindParam(':idEntreprise', $idEntreprise, PDO::PARAM_INT);
$stmProjets->execute();


// Récupérer les résultats
$projets = $stmProjets->fetchAll(PDO::FETCH_ASSOC);

// Vérifier que l'ID existe en base de données
$query = $conn->prepare("SELECT * FROM Entreprise WHERE idEntreprise = ?");
$query->execute([$idEntreprise]);
$entreprise = $query->fetch(PDO::FETCH_ASSOC);


if (!$entreprise) {
    die("⚠️ Erreur : L'utilisateur n'existe pas !");
}


// Récupérer le profil
$query = $conn->prepare("SELECT * FROM ProfilEntreprise WHERE idEntreprise = ?");
$query->execute([$idEntreprise]);
$profil = $query->fetch(PDO::FETCH_ASSOC) ?: ["descriptionentreprise" => ""];


// Récupérer le nombre de projets du demandeur
$stmCountProjets = $conn->prepare("SELECT CountProjetsEntreprise(:idEntreprise) AS total_projets");
$stmCountProjets->bindParam(':idEntreprise', $idEntreprise, PDO::PARAM_INT);
$stmCountProjets->execute();
$nbProjets = $stmCountProjets->fetch(PDO::FETCH_ASSOC)['total_projets'] ?? 0;


// Récupérer le niveau du demandeur
$stmNiveau = $conn->prepare("SELECT GetNiveauEntreprise(:idEntreprise) AS niveau");
$stmNiveau->bindParam(':idEntreprise', $idEntreprise, PDO::PARAM_INT);
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
    <h1>👤 Bienvenue, <?php echo htmlspecialchars($entreprise['nomentreprise']); ?> !</h1>


    <div class="badge">
    <div class="badge gold">
        ⭐ <span><?php echo htmlspecialchars($nbProjets); ?> -- Quêtes accomplies ! Continue ton aventure !</span>
    </div>
    <div class="badge silver">
        🌟 <span><?php echo htmlspecialchars($niveau); ?> -- Niveau du héros ! Deviens une légende !</span>
    </div>
</div>


        <p><strong>l'entreprise :</strong> <?php echo htmlspecialchars($entreprise['nomentreprise']); ?></p>
        <p><strong> Identifiant SIRET :</strong> <?php echo htmlspecialchars($entreprise['identifiantsiret']); ?></p>
        <p><strong>Email :</strong> <?php echo htmlspecialchars($entreprise['emailentreprise']); ?></p>
        <p><strong>Téléphone :</strong> <?php echo htmlspecialchars($entreprise['telephoneentreprise']); ?></p>


       
    </div>
    <div class="projects-section">
    <h2>🏆 Ta Quête Épique ! (Ton parcours, tes exploits...)</h2>




<div class="profile-section">
    <h3>Description :</h3>
    <p><?php echo nl2br(htmlspecialchars($profil['descriptionentreprise'])); ?></p>
</div>


<a class="monbutton" href="update_profil_entreprise.php?idEntreprise=<?php echo $idEntreprise; ?>" class="edit-button">✏️ Modifier mon profil</a>


</div>


<div class="projects-section">
    <h2>🚀 Mes Projets</h2>
    <?php if (!empty($projets)): ?>
        <table class="projects-table">
            <thead>
                <tr>
                    <th>Réalisation</th>
                    <th>Description</th>
                    <th>Date de Début</th>
                    <th>Date de Fin</th>
                    <th>Voir le Projet</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($projets as $projet): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($projet['typerealisation']); ?></td>
                        <td><?php echo htmlspecialchars($projet['descriptionrealisation']); ?></td>
                        <td><?php echo htmlspecialchars($projet['datedebut']); ?></td>
                        <td><?php echo htmlspecialchars($projet['datefin']); ?></td>
                        <td><a href="<?php echo htmlspecialchars($projet['source']); ?>" target="_blank">Voir</a></td>
                    </tr>
                   
                <?php endforeach; ?>
               
            </tbody>
        </table>
    <?php else: ?>
        <p>Aucun projet ajouté.</p>
    <?php endif; ?>
       


   
<a class="monbutton" href="add_projet_entreprise.php?idDemandeur=<?php echo $idDemandeur; ?>" class="add-project-button">➕ Ajouter un projet</a>


   
</div>


</div>


</body>
</html>
