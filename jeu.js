const character = document.getElementById("character");
const gameContainer = document.getElementById("game-container");
const questionContainer = document.getElementById("question-zone");
const expContainer = document.getElementById("exp-zone")
const zones = document.querySelectorAll(".transition-zone");
const interactionBubbleEnt = document.getElementById("interaction-bubble-ent");
const interactionBubbleQuestion = document.getElementById("interaction-bubble-question");
const interactionBubbleExp = document.getElementById("interaction-bubble-exp");

let posX = window.innerWidth / 2 - 25;
let posY = window.innerHeight / 2 - 25;
const speed = 10;
let isBlocked = false; // Indicateur de blocage du personnage
let canInteract = false; // Indicateur si on peut interagir avec la zone
let canInteract2 = false;
let canInteract3 = false;
// Gestion des déplacements
let intervalId = null; // Variable pour garder la référence de l'intervalle
let isMoving = false; // Indicateur pour savoir si on est en mouvement

document.addEventListener("keydown", (event) => {
    console.log(`Position initiale: X: ${posX}, Y: ${posY}`);
    
    if (event.key === "i" && canInteract) {
        toggleForm(); // Ouvre/ferme le formulaire si on peut interagir
        return; // Ne pas traiter d'autres événements si on interagit
    }
    if (event.key === "i" && canInteract2) {
        toggleFormQuestion(); // Ouvre/ferme le formulaire si on peut interagir
        return; // Ne pas traiter d'autres événements si on interagit
    }
    if (event.key === "i" && canInteract3) {
        toggleFormExp(); // Ouvre/ferme le formulaire si on peut interagir
        return; // Ne pas traiter d'autres événements si on interagit
    }

    if (event.repeat) return; // Evite de traiter les touches répétées (inutile ici)

    // Démarrer le mouvement quand une flèche est pressée
    switch (event.key) {
        case "ArrowUp":
            if (!isMoving) {
                character.style.backgroundPosition = `200px 100px`;
                intervalId = setInterval(() => {
                    posY -= speed;
                    character.style.top = `${posY}px`;
                    checkTransition();
                }, 100); // Déplace tous les 100ms
                isMoving = true;
            }
            break;
        case "ArrowDown":
            if (!isMoving) {
                character.style.backgroundPosition = `200px 0px`;
                character.className = 'walk-down';
                intervalId = setInterval(() => {
                    posY += speed;
                    character.style.top = `${posY}px`;
                    checkTransition();
                }, 100);
                isMoving = true;
            }
            break;
        case "ArrowLeft":
            if (!isMoving) {
                character.style.backgroundPosition = `200px 300px`;
                character.className = 'walk-left';
                intervalId = setInterval(() => {
                    posX -= speed;
                    character.style.left = `${posX}px`;
                    checkTransition();
                }, 100);
                isMoving = true;
            }
            break;
        case "ArrowRight":
            if (!isMoving) {
                character.style.backgroundPosition = `200px 200px`;
                intervalId = setInterval(() => {
                    posX += speed;
                    character.style.left = `${posX}px`;
                    checkTransition();
                }, 100);
                isMoving = true;
            }
            break;
    }
});

document.addEventListener("keyup", (event) => {
    // Arrêter le mouvement quand la touche est relâchée
    if (event.key === "ArrowUp" || event.key === "ArrowDown" || event.key === "ArrowLeft" || event.key === "ArrowRight") {
        clearInterval(intervalId); // Arrêter l'intervalle
        isMoving = false; // Réinitialiser l'indicateur de mouvement
    }
});

// Changement de scène
function checkTransition() {
    let collisionDetected = false;
    zones.forEach(zone => {
        const rect = zone.getBoundingClientRect();
        if (
            posX + 50 > rect.left &&
            posX < rect.right &&
            posY + 50 > rect.top &&
            posY < rect.bottom
        ) {
            changeBackground(zone.id);
            collisionDetected = true;
            isBlocked = true; // Bloquer le personnage dans la zone
            canInteract = true; // Permet d'afficher le message d'interaction
            interactionBubbleEnt.style.display = "block"; // Afficher la bulle d'interaction
        }
    })
    const re = questionContainer.getBoundingClientRect();
    if (
        posX + 50 > re.left &&
        posX < re.right &&
        posY + 50 > re.top &&
        posY < re.bottom
    ) {
        collisionDetected = true;
        isBlocked = true; // Bloquer le personnage dans la zone
        canInteract2 = true; // Permet d'afficher le message d'interaction
        interactionBubbleQuestion.style.display = "block"; // Afficher la bulle d'interaction
    }
    const rexp = expContainer.getBoundingClientRect();
    if (
        posX + 50 > rexp.left &&
        posX < rexp.right &&
        posY + 50 > rexp.top &&
        posY < rexp.bottom
    ) {
        collisionDetected = true;
        isBlocked = true; // Bloquer le personnage dans la zone
        canInteract3 = true; // Permet d'afficher le message d'interaction
        interactionBubbleExp.style.display = "block"; // Afficher la bulle d'interaction
    }
    // Si aucune collision n'est détectée, débloquer le personnage
    if (!collisionDetected) {
        isBlocked = false;
        canInteract = false;
        canInteract2 = false;
        canInteract3 = false; // Désactiver l'interaction
        interactionBubbleQuestion.style.display = "none";
        interactionBubbleEnt.style.display = "none";
        interactionBubbleExp.style.display = "none"; // Cacher la bulle d'interaction
    }
}

function changeBackground(zoneId) {
    if (zoneId === "zone1") {
        gameContainer.style.background = "url('background2.png') no-repeat center center/cover";
    } else if (zoneId === "zone2") {
        gameContainer.style.background = "url('background3.png') no-repeat center center/cover";
    }
}

// Ouvrir/fermer le formulaire avec la touche "i"
function toggleForm() {
    const formContainer = document.getElementById("form-container");
    if (formContainer.style.display === "none" || formContainer.style.display === "") {
        formContainer.style.display = "block";
    }
}

function toggleFormQuestion() {
    const formContainer = document.getElementById("form-container-question");
    if (formContainer.style.display === "none" || formContainer.style.display === "") {
        formContainer.style.display = "block";
    }
}
function toggleFormExp() {
    const formContainer = document.getElementById("form-container-exp");
    if (formContainer.style.display === "none" || formContainer.style.display === "") {
        formContainer.style.display = "block";
    }
}
// Fonction pour fermer le formulaire manuellement
function closeForm() {
    document.getElementById("form-container").style.display = "none";
    document.getElementById("form-container-exp").style.display = "none";
    document.getElementById("form-container-question").style.display = "none";
}

window.onload = function() {
  const rect = character.getBoundingClientRect();
  posX = rect.left;
  posY = rect.top;

  // Mise à jour de la position initiale
  character.style.left = `${posX}px`;
  character.style.top = `${posY}px`;
};
function fetchRandomQuestion() {
    fetch('main.php') // Remplace par le chemin de ton fichier PHP
        .then(response => response.json())
        .then(data => {
            const question = data.question; // Récupère la question depuis le JSON
        })
        .catch(error => {
            console.error("Erreur lors de la récupération de la question:", error);
        });
}
