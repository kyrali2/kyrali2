<?php
// Démarrer la session
session_start();
// Inclure la connexion à la base de données
include('connexion_db.php');
// Initialiser les messages d'erreur ou de succès
$message = "";
$idDemandeur = $_SESSION['user_id'];
$niveauDemandeur = $_SESSION['niveau'];
// Requête pour récupérer la question
$query = "SELECT question, idquestion FROM question q,domaine o,demandeuremploi e WHERE q.domaine_id=o.iddomaine AND o.iddomaine=e.domaine_id AND e.iddemandeur = :iddemandeur AND q.niveau_id=:niveau ORDER BY RANDOM() LIMIT 1"; // Sélectionne une question aléatoire
$stmt = $conn->prepare($query);
$stmt->bindParam(':iddemandeur', $idDemandeur);
$stmt->bindParam(':niveau', $niveauDemandeur);
try {
    // Exécuter la requête
    $stmt->execute();
    // Vérifier si la requête a retourné une ligne
    if ($stmt->rowCount() > 0) {
        // Récupérer la question
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $question = $result['question'];
        $idquestion = $result['idquestion'];
    } else {
        // Si aucune question n'est trouvée, afficher un message par défaut
        $question = "Aucune question disponible.";
    }
} catch (PDOException $e) {
    // Gérer les erreurs si la requête échoue
    $question = "Erreur lors de la récupération de la question.";
}

// Requête pour récupérer les réponses associées à cette question
$query_answers = "SELECT r.reponse1, r.reponse2, r.reponse3, r.reponse4 FROM reponse r,question q WHERE q.idquestion = :idquestion AND q.choix=r.idreponse";
$stmt_answers = $conn->prepare($query_answers);
$stmt_answers->bindParam(':idquestion', $idquestion);
$stmt_answers->execute();

// Récupérer les réponses dans un tableau
$answers = $stmt_answers->fetch(PDO::FETCH_ASSOC);

$query_rep = "SELECT reponsecorrecte FROM question WHERE idquestion=:idquestion"; 
$stmt_rep = $conn->prepare($query_rep);
$stmt_rep->bindParam(':idquestion', $idquestion);
$stmt_rep->execute();
$rep = $stmt_rep->fetch(PDO::FETCH_ASSOC);
$r = $rep['reponsecorrecte'];
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
        <label for="question">Question : <?php echo htmlspecialchars($question); ?></label>
            <div id="answers">
                <?php if (!empty($answers)) : ?>
                    <button type="button" class="answer-btn" onclick="checkAnswer('<?php echo htmlspecialchars($answers['reponse1']); ?>', '<?php echo $r; ?>')">
                        <?php echo htmlspecialchars($answers['reponse1']); ?>
                    </button>
                    <button type="button" class="answer-btn" onclick="checkAnswer('<?php echo htmlspecialchars($answers['reponse2']); ?>', '<?php echo $r; ?>')">
                        <?php echo htmlspecialchars($answers['reponse2']); ?>
                    </button>
                    <button type="button" class="answer-btn" onclick="checkAnswer('<?php echo htmlspecialchars($answers['reponse3']); ?>', '<?php echo $r; ?>')">
                         <?php echo htmlspecialchars($answers['reponse3']); ?>
                    </button>
                    <button type="button" class="answer-btn" onclick="checkAnswer('<?php echo htmlspecialchars($answers['reponse4']); ?>', '<?php echo $r; ?>')">
                        <?php echo htmlspecialchars($answers['reponse4']); ?>
                    </button>
                <?php else : ?>
                    <p>Aucune réponse disponible.</p>
                <?php endif; ?>
            </div>
            <br><br>
            <button type="button" onclick="closeForm()">Fermer</button>        
        </form>
    </div>
    <div id="form-container-exp" style="display: none;">
        <form id="myForm">
            <label for="name">Allons visiter ton profil</label>
            <br><br>
            <button type="button" onclick="goToProfile()">Let's GO 🚀</button>
            <button type="button" onclick="closeForm()">Fermer</button>
        </form>
    </div>
    <script src="jeu.js"></script>
</body>
</html>