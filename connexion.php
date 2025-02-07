<?php 
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('connexion_db.php'); // Connexion à la base de données

// Vérifier la connexion à la base
if (!$conn) {
    die("Erreur de connexion à la base de données");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "Le script est bien exécuté <br>";

    var_dump($_POST); // Vérifie les données envoyées par le formulaire

    $email = trim($_POST['email']); 
    $password = $_POST['password'];

    error_log("Tentative de connexion avec l'email : " . $email);
    echo "Tentative de connexion avec l'email : " . htmlspecialchars($email) . "<br>";

    try {
        // Vérifier si l'email existe dans la table connexion
        $query = $conn->prepare("SELECT * FROM connexion WHERE email = :email");
        $query->execute(['email' => $email]);
        $user = $query->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            echo "Utilisateur trouvé : " . htmlspecialchars($user['idconnexion']) . "<br>";
            error_log("Utilisateur trouvé avec ID: " . $user['idconnexion']);

            if ($password === $user['motdepasse']) {
                echo "Mot de passe valide.<br>";
                error_log("Mot de passe valide.");

                // Vérifier si c'est un demandeur
                if ($user['typeutilisateur'] === 'candidat') {
                    echo "L'utilisateur est un candidat.<br>";

                    $queryDemandeur = $conn->prepare("SELECT * FROM demandeuremploi WHERE iddemandeur = :iddemandeur");
                    $queryDemandeur->execute(['iddemandeur' => $user['iddemandeur']]);
                    $demandeur = $queryDemandeur->fetch(PDO::FETCH_ASSOC);

                    if ($demandeur) {
                        echo "Demandeur trouvé : " . htmlspecialchars($demandeur['iddemandeur']) . "<br>";
                        $_SESSION['user_id'] = $demandeur['iddemandeur'];
                        $_SESSION['email'] = $demandeur['emaildemandeur'];
                        $_SESSION['role'] = 'demandeur';
                        $_SESSION['username'] = $demandeur['prenom'] . " " . $demandeur['nom'];

                        error_log("Redirection vers jeu.html");
                        header("Location: jeu.html");
                        exit();
                    } else {
                        echo "Aucun demandeur trouvé pour ID: " . htmlspecialchars($user['iddemandeur']) . "<br>";
                        error_log("Aucun demandeur trouvé pour ID: " . $user['iddemandeur']);
                    }
                } else {
                    echo "Type d'utilisateur inconnu : " . htmlspecialchars($user['typeutilisateur']) . "<br>";
                    error_log("Type d'utilisateur inconnu: " . $user['typeutilisateur']);
                }
            } else {
                echo "Mot de passe incorrect.<br>";
                error_log("Mot de passe incorrect.");
            }
        } else {
            echo "Utilisateur non trouvé avec cet email.<br>";
            error_log("Utilisateur non trouvé avec email: " . $email);
        }
    } catch (Exception $e) {
        echo "Erreur SQL : " . $e->getMessage() . "<br>";
        error_log("Erreur SQL : " . $e->getMessage());
    }
}
?>






<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>JobQuest - Connexion</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <!-- Header -->
  <header class="header">
    <div class="logo">JobQuest</div>
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

      
    </div>
  </main>

  <footer class="footer">
    <p>© JamR 2025 JobQuest. Tous droits réservés.</p>
  </footer>

</body>
</html>