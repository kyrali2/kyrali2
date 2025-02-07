// Sélection des éléments
let character = document.querySelector('.character'); // Joueur
let robot = document.querySelector('.character2'); // Robot
let dialogueBox = document.getElementById('dialogue-box');
let dialogueText = document.createElement('p'); // Texte du dialogue
dialogueBox.appendChild(dialogueText);
let dialogueStep = 0; // Étape du dialogue

// Création du bouton de retour
let backButton = document.createElement('button');
backButton.innerText = "Retour à l'accueil";
backButton.style.position = "absolute";
backButton.style.bottom = "10px";
backButton.style.left = "50%";
backButton.style.transform = "translateX(-50%)";
backButton.style.backgroundColor = "#007bff";
backButton.style.color = "white";
backButton.style.padding = "10px 20px";
backButton.style.fontSize = "18px";
backButton.style.border = "none";
backButton.style.borderRadius = "8px";
backButton.style.cursor = "pointer";
backButton.style.display = "none"; // Caché au début
document.body.appendChild(backButton);

// Action du bouton de retour
backButton.addEventListener('click', function() {
    window.location.reload(); // Recharge la page pour revenir à l'accueil
});

// Création du message d'interaction "Appuyez sur E"
let startInteractionMessage = document.createElement('div');
startInteractionMessage.id = "interaction-message";
startInteractionMessage.style.position = 'absolute';
startInteractionMessage.style.left = '50%';
startInteractionMessage.style.top = '55%';
startInteractionMessage.style.transform = 'translate(-50%, -50%)';
startInteractionMessage.style.color = 'white';
startInteractionMessage.style.fontSize = '20px';
startInteractionMessage.style.fontWeight = 'bold';
startInteractionMessage.style.backgroundColor = 'rgba(0, 0, 0, 0.7)';
startInteractionMessage.style.padding = '10px';
startInteractionMessage.style.borderRadius = '10px';
startInteractionMessage.style.display = 'none'; // Caché au départ
startInteractionMessage.innerText = "Appuyez sur E pour commencer l'entretien";
document.body.appendChild(startInteractionMessage);

// Liste des dialogues (sans réponses du joueur)
let dialogues = [
    "Bonjour et bienvenue ! 🎉 Vous avez décroché un entretien.",
    "Pouvez-vous me parler un peu de vous ?",
    "Pourquoi pensez-vous être le bon choix pour ce poste ?",
    "Quels sont vos points forts et vos points à améliorer ?",
    "Où vous voyez-vous dans 5 ans ?",
    "Merci pour votre temps, nous reviendrons vers vous bientôt !"
];

// Fonction pour afficher le dialogue
function showNextDialogue() {
    if (dialogueStep < dialogues.length) {
        dialogueText.innerText = dialogues[dialogueStep];
        dialogueStep++;

        // Supprimer l'ancien bouton et ajouter un nouveau
        const previousButtons = dialogueBox.getElementsByClassName('answer-btn');
        while (previousButtons.length > 0) {
            previousButtons[0].remove();
        }

        let responseButton = document.createElement('button');
        responseButton.classList.add('answer-btn');
        responseButton.innerText = "Répondre";
        responseButton.onclick = showNextDialogue;
        dialogueBox.appendChild(responseButton);
    } else {
        dialogueBox.style.display = 'none';
        backButton.style.display = 'block'; // Affiche le bouton retour après l'entretien
    }
}

// Fonction pour démarrer le dialogue
function startDialogue() {
    dialogueBox.style.display = 'block';
    startInteractionMessage.style.display = 'none'; // Cacher le message "Appuyez sur E"
    showNextDialogue();
}

// Vérifier si le joueur est proche du robot
function isNearRobot() {
    const playerX = character.offsetLeft + character.offsetWidth / 2;
    const playerY = character.offsetTop + character.offsetHeight / 2;
    const robotX = robot.offsetLeft + robot.offsetWidth / 2;
    const robotY = robot.offsetTop + robot.offsetHeight / 2;

    return (Math.abs(playerX - robotX) < 60 && Math.abs(playerY - robotY) < 60);
}

// Déplacement du personnage avec les flèches (aucun déplacement automatique au départ)
let playerPosition = { x: 100, y: window.innerHeight / 2 }; // Position initiale
character.style.left = `${playerPosition.x}px`;
character.style.top = `${playerPosition.y}px`;

document.addEventListener('keydown', function(event) {
    const speed = 10; // Vitesse de déplacement

    switch(event.key) {
        case 'ArrowUp':
            playerPosition.y -= speed;
            break;
        case 'ArrowDown':
            playerPosition.y += speed;
            break;
        case 'ArrowLeft':
            playerPosition.x -= speed;
            break;
        case 'ArrowRight':
            playerPosition.x += speed;
            break;
        case 'e': // Démarrer le dialogue si proche du robot
            if (isNearRobot()) {
                startDialogue();
            }
            break;
    }

    // Mise à jour de la position du personnage
    character.style.left = `${playerPosition.x}px`;
    character.style.top = `${playerPosition.y}px`;

    // Vérifier si on est près du robot et afficher le message d'interaction
    if (isNearRobot()) {
        startInteractionMessage.style.display = 'block';
    } else {
        startInteractionMessage.style.display = 'none';
    }
});
