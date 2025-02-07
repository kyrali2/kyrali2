<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entretien - Simulation</title>
    <link rel="stylesheet" href="entretien.css">
</head>
<body>
    <div id="game-container">
        <!-- Message de bienvenue -->
        <div id="welcome-message">
            🎉 Super, vous avez décroché un entretien ! 🤝
        </div>

        <!-- Zone de la maison (Entretien) -->
        <div id="house">
            <div id="company">Entreprise</div>
        </div>

        <!-- Personnage mobile -->
        <div id="character1" class="character" style="left: 10%;"></div>

        <!-- Personnage immobile représentant l'entreprise -->
        <div id="company-robot" class="character2"></div>

        <!-- Bulle d'interaction -->
        <div id="interaction-bubble-ent">Appuyez sur E pour commencer l'entretien !</div>

        <!-- Dialogue entre les personnages -->
        <div id="dialogue-box">
            <div id="character1-dialogue">Personnage 1 : Bonjour !</div>
            <div id="character2-dialogue">Personnage 2 : Bonjour, bienvenue à l'entretien ! 😊</div>
        </div>
    </div>

    <script src="entretien.js"></script>
</body>
</html>
