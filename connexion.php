Page : connexion.php                                                                                                                                                                                <?php
session_start();
global $conn;
include('connexion_db.php'); // Connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']); // Éviter les espaces
    $password = $_POST['password']; // Mot de passe saisi

    // Log de l'email
    error_log("Tentative de connexion avec l'email : " . $email);

    try {
        // Vérifier si l'email existe dans la table "connexion"
        $query = $conn->prepare("SELECT * FROM connexion WHERE emailconnexion = :email");
        $query->execute(['email' => $email]);
        $user = $query->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Log si l'utilisateur existe
            error_log("Utilisateur trouvé dans la table connexion.");

            // Vérification du mot de passe
            if (password_verify($password, $user['motdepasse'])) {
                // Log pour vérifier le mot de passe
                error_log("Mot de passe vérifié avec succès pour l'email : " . $email);

                // Vérification du rôle et recherche dans la table correspondante
                if ($user['iddemandeur']) {
                    // L'utilisateur est un demandeur
                    $_SESSION['user_id'] = $user['iddemandeur'];
                    $_SESSION['email'] = $user['emailconnexion'];
                    $_SESSION['role'] = 'demandeur';
                    $_SESSION['username'] = $user['iddemandeur']; // À adapter si nécessaire

                    // Redirection vers l'interface demandeur
                    error_log("Redirection vers l'interface demandeur.");
                    header("Location: jeu.php");
                    exit();
                } elseif ($user['identreprise']) {
                    // L'utilisateur est une entreprise
                    $_SESSION['user_id'] = $user['identreprise'];
                    $_SESSION['email'] = $user['emailconnexion'];
                    $_SESSION['role'] = 'entreprise';
                    $_SESSION['username'] = $user['identreprise']; // À adapter si nécessaire

                    // Redirection vers l'interface entreprise
                    error_log("Redirection vers l'interface entreprise.");
                    header("Location: interface_entreprise.php");
                    exit();
                } else {
                    // Aucun rôle trouvé pour cet utilisateur
                    error_log("Aucun rôle trouvé pour cet utilisateur.");
                    echo "<script>alert('Aucun rôle trouvé pour cet email.');</script>";
                }
            } else {
                // Mot de passe incorrect
                error_log("Mot de passe incorrect pour l'email : " . $email);
                echo "<script>alert('Mot de passe incorrect.');</script>";
            }
        } else {
            // Email non trouvé
            error_log("Email non trouvé dans la base de données : " . $email);
            echo "<script>alert('Email non trouvé.');</script>";
        }
    } catch (Exception $e) {
        // Log d'erreur pour attraper les exceptions
        error_log("Erreur rencontrée : " . $e->getMessage());
        echo "<script>alert('Erreur : " . $e->getMessage() . "');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MonApp - Connexion</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <!-- Header -->
  <header class="header">
    <div class="logo">MonApp</div>
    <nav>
      <a href="accueil.php">Accueil</a>
      <a href="#">À propos</a>
      <a href="#">Contact</a>
    </nav>
  </header>

  <!-- Formulaire de connexion -->
  <main class="container">
    <div class="glass-form-container">
      <h1>Connexion</h1>
      
      <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

      <form class="glass-form" method="POST">
        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Se connecter</button>
      </form>

      <div class="glass-links">
        <a href="accueil.php">Retour à l'accueil</a>
      </div>
    </div>
  </main>

  <footer class="footer">
    <p>© JamR 2025 MonApp. Tous droits réservés.</p>
  </footer>

</body>
</html>