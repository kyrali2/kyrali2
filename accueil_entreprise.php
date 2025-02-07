<?php
session_start();
include('connexion_db.php'); // Connexion à la base de données

// Vérifier si l'utilisateur est connecté
if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
    $user_id = $_SESSION['user_id'];
    $role = $_SESSION['role'];
    $username = "";

    try {
        if ($role === 'entreprise') {
            // Récupérer le nom de l'entreprise
            $query = $conn->prepare("SELECT nomEntreprise FROM Entreprise WHERE idEntreprise = :idEntreprise");
            $query->execute(['idEntreprise' => $user_id]);
            $entreprise = $query->fetch(PDO::FETCH_ASSOC);
            if ($entreprise) {
                $username = $entreprise['nomEntreprise'];
            }
        } elseif ($role === 'demandeur') {
            // Si nécessaire, récupérer le nom du demandeur
            // Pour cet exemple, nous supposons que le nom du demandeur est déjà stocké dans la session
            $username = $_SESSION['username'];
        }
    } catch (Exception $e) {
        error_log("Erreur lors de la récupération du nom de l'utilisateur : " . $e->getMessage());
    }
} else {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'Accueil</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="entreprise.css">
</head>
<body>
    <header class="header">
        <div class="logo">MonApp</div>
        <a href="acceueil.php" style="color: white; font-size: 40px; text-decoration: none; padding: 10px;" class="btn-return">
    <i class="fas fa-user"></i>
   </a>
    </header>
    <main class="main-content">
        <div class="welcome-container">
            <h1>Bienvenue, <?php echo htmlspecialchars($username); ?>!</h1>
            <div class="button-container">
    <button class="btn" onclick="window.location.href='interface_entreprise.php'">
     Jouez
    </button>
    <button class="btn" onclick="window.location.href='liste.php'">
    Liste candidats
    </button>
            </div>
        </div>
    </main>

    <footer class="footer">
        <p>© JamR 2025 MonApp. Tous droits réservés.</p>
    </footer>
</body>
</html>
