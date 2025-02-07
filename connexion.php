<?php 
session_start();
include('connexion_db.php'); // Connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']); 
    $password = $_POST['password'];

    error_log("Tentative de connexion avec l'email : " . $email);

    try {
        // Vérifier si l'email existe dans la table connexion
        $query = $conn->prepare("SELECT * FROM connexion WHERE email = :email");
        $query->execute(['email' => $email]);
        $user = $query->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            error_log("Utilisateur trouvé avec ID: " . $user['idconnexion']);

            // Affichage des valeurs pour détecter les erreurs
            var_dump("Mot de passe entré: " . $password);
            var_dump("Mot de passe en base: " . $user['motdepasse']);
            var_dump("Mot de passe entré en hex: " . bin2hex($password));
            var_dump("Mot de passe en base en hex: " . bin2hex($user['motdepasse']));

            // Vérification du mot de passe
            if (password_verify($password, $user['motdepasse'])) {
              error_log("Mot de passe valide.");
          
                // Vérifier si c'est un demandeur
                if ($user['typeutilisateur'] === 'candidat') {
                    $queryDemandeur = $conn->prepare("SELECT * FROM demandeuremploi WHERE iddemandeur = :iddemandeur");
                    $queryDemandeur->execute(['iddemandeur' => $user['iddemandeur']]);
                    $demandeur = $queryDemandeur->fetch(PDO::FETCH_ASSOC);

                    if ($demandeur) {
                        $_SESSION['user_id'] = $demandeur['iddemandeur'];
                        $_SESSION['email'] = $demandeur['emaildemandeur'];
                        $_SESSION['role'] = 'demandeur';
                        $_SESSION['username'] = $demandeur['prenom'] . " " . $demandeur['nom'];
                        $_SESSION['niveau'] = $demandeur['niveau'];
                        error_log("Redirection vers jeu.html");
                        header("Location: jeu.php");
                        exit();
                    } else {
                        error_log("Aucun demandeur trouvé pour ID: " . $user['iddemandeur']);
                    }
                } elseif ($user['typeutilisateur'] === 'entreprise') {
                  $queryEntreprise = $conn->prepare("SELECT * FROM entreprise WHERE identreprise = :identreprise");
                  $queryEntreprise->execute(['identreprise' => $user['identreprise']]);
                  $entreprise = $queryEntreprise->fetch(PDO::FETCH_ASSOC);

                  if ($entreprise) {
                      $_SESSION['user_id'] = $entreprise['identreprise'];
                      $_SESSION['email'] = $entreprise['emailentreprise'];
                      $_SESSION['role'] = 'entreprise';
                      $_SESSION['niveau'] = $entreprise['niveau'];
                      error_log("Redirection vers accueil_entreprise.php");
                      header("Location: accueil_entreprise.php");
                      exit();
                  } else {
                      error_log("Aucune entreprise trouvée pour ID: " . $user['identreprise']);
                  }
                } else {
                    error_log("Type d'utilisateur inconnu: " . $user['typeutilisateur']);
                }
            } else {
                error_log("Mot de passe incorrect.");
            }
        } else {
            error_log("Utilisateur non trouvé avec email: " . $email);
        }
    } catch (Exception $e) {
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