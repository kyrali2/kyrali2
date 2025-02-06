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
      <a href="accueil.html">Accueil</a>
      <a href="#">À propos</a>
      <a href="#">Contact</a>
    </nav>
  </header>

  <!-- Formulaire de connexion -->
  <main class="container">
    <div class="glass-form-container">
      <h1>Connexion</h1>
      
      <form class="glass-form">
        <label for="email">Email :</label>
        <input type="email" id="email" required>

        <label for="password">Mot de passe :</label>
        <input type="password" id="password" required>

        <button type="submit">Se connecter</button>
      </form>

      <div class="glass-links">
        <a href="accueil.html">Retour à l'accueil</a>
      </div>
    </div>
  </main>

  <footer class="footer">
    <p>© JamR 2025 MonApp. Tous droits réservés.</p>
  </footer>

</body>
</html>
