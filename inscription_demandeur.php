<?php
session_start();
include('connexion_db.php');

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['lastname']);
    $prenom = trim($_POST['firstname']);
    $email = trim($_POST['emailCandidat']);
    $telephone = trim($_POST['phone']);
    $education = trim($_POST['education']);
    $domaine = trim($_POST['domaine']);
    $contractType = trim($_POST['contractType']);
    $password = password_hash($_POST['passwordCandidat'], PASSWORD_DEFAULT);

    try {
        // Vérifier si l'email est unique
        $sqlCheck = "SELECT COUNT(*) FROM Connexion WHERE email = :email";
        $stmtCheck = $conn->prepare($sqlCheck);
        $stmtCheck->bindParam(':email', $email, PDO::PARAM_STR);
        $stmtCheck->execute();
        $emailExists = $stmtCheck->fetchColumn();

        if ($emailExists > 0) {
            $message = "Erreur : L'adresse e-mail est déjà utilisée.";
        } else {
            $conn->beginTransaction();

            // Insérer dans la table `Connexion`
            $sqlConnexion = "INSERT INTO Connexion (email, motdepasse, typeutilisateur) VALUES (:email, :motdepasse, 'candidat')";
            $stmtConnexion = $conn->prepare($sqlConnexion);
            $stmtConnexion->bindParam(':email', $email, PDO::PARAM_STR);
            $stmtConnexion->bindParam(':motdepasse', $password, PDO::PARAM_STR);
            $stmtConnexion->execute();
            $idConnexion = $conn->lastInsertId();

            // Insérer dans la table `DemandeurEmploi`
            $sqlDemandeur = "INSERT INTO DemandeurEmploi (nom, prenom, emailDemandeur, telephoneDemandeur, domaineetude, niveauEtude, typeContrat)
                             VALUES (:nom, :prenom, :email, :telephone, :domaine, :education, :contractType)";
            $stmtDemandeur = $conn->prepare($sqlDemandeur);
            $stmtDemandeur->bindParam(':nom', $nom, PDO::PARAM_STR);
            $stmtDemandeur->bindParam(':prenom', $prenom, PDO::PARAM_STR);
            $stmtDemandeur->bindParam(':email', $email, PDO::PARAM_STR);
            $stmtDemandeur->bindParam(':telephone', $telephone, PDO::PARAM_STR);
            $stmtDemandeur->bindParam(':domaine', $domaine, PDO::PARAM_STR);
            $stmtDemandeur->bindParam(':education', $education, PDO::PARAM_STR);
            $stmtDemandeur->bindParam(':contractType', $contractType, PDO::PARAM_STR);
            $stmtDemandeur->execute();
            $idDemandeur = $conn->lastInsertId();

            // Mettre à jour la table `Connexion` avec l'id du candidat
            $sqlUpdateConnexion = "UPDATE Connexion SET idDemandeur = :idDemandeur WHERE idconnexion = :idConnexion";
            $stmtUpdate = $conn->prepare($sqlUpdateConnexion);
            $stmtUpdate->bindParam(':idDemandeur', $idDemandeur, PDO::PARAM_INT);
            $stmtUpdate->bindParam(':idConnexion', $idConnexion, PDO::PARAM_INT);
            $stmtUpdate->execute();

            $conn->commit();
            $message = "Compte utilisateur créé avec succès !";
            header("Location: connexion.php");
            exit;
        }
    } catch (PDOException $e) {
        $conn->rollBack();
        $message = "Erreur : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Candidat</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="header">
        <div class="logo">JobQuest</div>
        <nav>
            <a href="accueil.php">Accueil</a>
            <a href="#">À propos</a>
            <a href="#">Contact</a>
        </nav>
    </header>
    <main class="main-content">
        <div class="form-container">
            <h1>Inscription - Candidat</h1>
            <?php if ($message): ?>
                <p class="message"><?= htmlspecialchars($message) ?></p>
            <?php endif; ?>
            <form class="signup-form" action="" method="POST">
                <label for="firstname">Prénom :</label>
                <input type="text" name="firstname" id="firstname" required>

                <label for="lastname">Nom :</label>
                <input type="text" name="lastname" id="lastname" required>

                <label for="emailCandidat">Email :</label>
                <input type="email" name="emailCandidat" id="emailCandidat" required>

                <label for="passwordCandidat">Mot de passe :</label>
                <input type="password" name="passwordCandidat" id="passwordCandidat" required>

                <label for="phone">Téléphone :</label>
                <input type="tel" name="phone" id="phone" required>

                <label for="education">Niveau d'étude :</label>
                <input type="text" name="education" id="education" required>

                <label for="domaine">Domaine d'étude :</label>
                <select name="domaine" id="domaine" required>
                    <option value="medecine">Médecine</option>
                    <option value="informatique">Informatique</option>
                    <option value="droit">Droit</option>
                </select>

                <label for="contractType">Type de contrat :</label>
                <select name="contractType" id="contractType" required>
                    <option value="CDI">CDI</option>
                    <option value="CDD">CDD</option>
                    <option value="Stage">Stage</option>
                    <option value="Alternance">Alternance</option>
                    <option value="Bénévolat">Bénévolat</option>
                </select>

                <button type="submit">S'inscrire</button>
            </form>
            <div class="links">
                <a href="accueil.php">Retour à l'accueil</a>
            </div>
        </div>
    </main>
</body>
</html>