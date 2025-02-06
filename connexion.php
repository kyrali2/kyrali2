<?php
session_start();
include('connexion_db.php'); // Connexion à la base de données

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        // Rechercher l'utilisateur dans la base de données
        $query = "SELECT * FROM utilisateurs WHERE email = :email";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérification du mot de passe
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id']; // Stocker l'ID utilisateur en session
            $_SESSION['email'] = $user['email'];
            header("Location: accueil.php"); // Redirection après connexion
            exit();
        } else {
            $error = "Email ou mot de passe incorrect.";
        }
    } catch (PDOException $e) {
        $error = "Erreur lors de la connexion : " . $e->getMessage();
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
