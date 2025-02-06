<?php
// Démarrer la session
session_start();

// Inclure la connexion à la base de données
include('connexion_db.php');

// Initialiser les messages d'erreur ou de succès
$message = "";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monde Interactif</title>
    <link rel="stylesheet" href="jeu.css">
</head>
<body>
    <div id="game-container">
        <div id="character"></div>
        <div class="transition-zone" id="zone1"></div>
        <div class="transition-zone" id="zone2"></div>
        
    </div>
    <div id="question-zone"></div>
    <div id="text-question">Questions</div>
    <div id="exp-zone"></div>
    <div id="text-exp">Experience</div>
    <div id="interaction-bubble-ent" style="display: none;">
        <p>Appuyez sur I pour interagir avec l'entreprise</p>
    </div>
    <div id="interaction-bubble-exp" style="display: none;">
        <p>Appuyez sur I pour ajouter une expérience</p>
    </div>
    <div id="interaction-bubble-question" style="display: none;">
        <p>Appuyez sur I pour répondre à une question</p>
    </div>

    <div id="form-container" style="display: none;">
        <form id="myForm">
            <label for="name">Nom de poste</label>
            <input type="text" id="name" name="name" required>
            <br><br>
            <label for="email">CV</label>
            <input type="email" id="email" name="email" required>
            <br><br>
            <label>Lettre de motivation</label>
            <input type ="text">
            <button type="submit">Envoyer</button>
            <button type="button" onclick="closeForm()">Fermer</button>
        </form>
    </div>
    <div id="form-container-question" style="display: none;">
        <form id="myForm">
            <label for="name">Question</label>
            <input type="text" required>
            <br><br>
            <button type="submit">Envoyer</button>
            <button type="button" onclick="closeForm()">Fermer</button>
        </form>
    </div>
    <div id="form-container-exp" style="display: none;">
        <form id="myForm">
            <label for="name">Nom</label>
            <input type="text" required>
            <br><br>
            <label for="email">Description</label>
            <input type="email" required>
            <br><br>
            <button type="submit">Envoyer</button>
            <button type="button" onclick="closeForm()">Fermer</button>
        </form>
    </div>
    <script src="jeu.js"></script>
</body>
</html>