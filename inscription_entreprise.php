  <?php
session_start();
include('connexion_db.php');

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomEntreprise = trim($_POST['companyName']);
    $siret = trim($_POST['siret']);
    $email = trim($_POST['emailEntreprise']);
    $telephone = trim($_POST['companyPhone']);
    $secteur = trim($_POST['sector']);
    $nombreSalaries = trim($_POST['employees']);
    $password = password_hash($_POST['passwordEntreprise'], PASSWORD_DEFAULT);

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

            // Insérer dans la table `Entreprise`
            $sqlEntreprise = "INSERT INTO Entreprise (nomEntreprise, identifiantSIRET, emailEntreprise, telephoneEntreprise, secteurActivite, nombreSalarie, motdepasse)
                              VALUES (:nomEntreprise, :siret, :email, :telephone, :secteur, :nombreSalaries, :motdepasse)";
            $stmtEntreprise = $conn->prepare($sqlEntreprise);
            $stmtEntreprise->bindParam(':nomEntreprise', $nomEntreprise, PDO::PARAM_STR);
            $stmtEntreprise->bindParam(':siret', $siret, PDO::PARAM_STR);
            $stmtEntreprise->bindParam(':email', $email, PDO::PARAM_STR);
            $stmtEntreprise->bindParam(':telephone', $telephone, PDO::PARAM_STR);
            $stmtEntreprise->bindParam(':secteur', $secteur, PDO::PARAM_STR);
            $stmtEntreprise->bindParam(':nombreSalaries', $nombreSalaries, PDO::PARAM_INT);
            $stmtEntreprise->bindParam(':motdepasse', $password, PDO::PARAM_STR);
            $stmtEntreprise->execute();
            $idEntreprise = $conn->lastInsertId();

            // Insérer dans la table `Connexion`
            $sqlConnexion = "INSERT INTO Connexion (email, motdepasse, typeutilisateur, idEntreprise) VALUES (:email, :motdepasse, 'entreprise', :idEntreprise)";
            $stmtConnexion = $conn->prepare($sqlConnexion);
            $stmtConnexion->bindParam(':email', $email, PDO::PARAM_STR);
            $stmtConnexion->bindParam(':motdepasse', $password, PDO::PARAM_STR);
            $stmtConnexion->bindParam(':idEntreprise', $idEntreprise, PDO::PARAM_INT);
            $stmtConnexion->execute();

            $conn->commit();
            $message = "Compte entreprise créé avec succès !";
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
    <title>Inscription - Entreprise</title>
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
            <h1>Inscription - Entreprise</h1>
            <?php if ($message): ?>
                <p class="message"><?= htmlspecialchars($message) ?></p>
            <?php endif; ?>
            <form class="signup-form" action="" method="POST">
                <label for="companyName">Nom de l'entreprise :</label>
                <input type="text" name="companyName" id="companyName" required>

                <label for="siret">Numéro de SIRET :</label>
                <input type="text" name="siret" id="siret" required>

                <label for="emailEntreprise">Email :</label>
                <input type="email" name="emailEntreprise" id="emailEntreprise" required>

                <label for="passwordEntreprise">Mot de passe :</label>
                <input type="password" name="passwordEntreprise" id="passwordEntreprise" required>

                <label for="companyPhone">Téléphone :</label>
                <input type="tel" name="companyPhone" id="companyPhone" required>

                <label for="sector">Secteur d'activité :</label>
                <input type="text" name="sector" id="sector" required>

                <label for="employees">Nombre de salariés :</label>
                <input type="number" name="employees" id="employees" required>

                <button type="submit">S'inscrire</button>
            </form>
            <div class="links">
                <a href="accueil.php">Retour à l'accueil</a>
            </div>
        </div>
    </main>
</body>
</html>