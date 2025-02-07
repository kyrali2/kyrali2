<?php
session_start();
include('connexion_db.php');

// Vérification si l'entreprise est connectée
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'entreprise') {
    header("Location: connexion.php");
    exit();
}

// Récupérer le niveau de l'entreprise depuis la session
$niveau = $_SESSION['niveau'];

// Requête pour récupérer la question et ses réponses
$query = $conn->prepare("
    SELECT q.idquestionentreprise, q.question, q.reponsecorrecte,
           r.reponse1, r.reponse2, r.reponse3, r.reponse4
    FROM questionentreprise q
    JOIN reponseentreprise r ON q.idquestionentreprise = r.idquestion
    WHERE q.niveau_id = :niveau
    LIMIT 1
");
$query->execute(['niveau' => $niveau]);
$question = $query->fetch(PDO::FETCH_ASSOC);

// Vérification de l'existence de la question
if (!$question) {
    // Rediriger vers la page d'accueil si aucune question n'est disponible pour ce niveau
    header("Location: accueil_entreprise.php");
    exit();
}

// Gestion de la soumission de la réponse
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['choix'])) {
    $reponseChoisie = $_POST['choix'];  // Valeur du bouton radio sélectionné
    $reponseCorrecte = $question['reponsecorrecte'];  // Bonne réponse depuis la BDD

    // Vérifier si la réponse est correcte
    if ($reponseChoisie == $reponseCorrecte) {
        // Augmenter le niveau de l'entreprise
        $newLevel = $niveau + 1;
        $updateQuery = "UPDATE entreprise SET niveau = :newLevel WHERE identreprise = :identreprise";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bindParam(':newLevel', $newLevel, PDO::PARAM_INT);
        $updateStmt->bindParam(':identreprise', $_SESSION['user_id'], PDO::PARAM_INT);
        $updateStmt->execute();

        // Mettre à jour la session
        $_SESSION['niveau'] = $newLevel;

        $message = "<p style='color: green; text-align: center;'>Bonne réponse ! Votre niveau a été mis à jour.</p>";
        // Recharger la page pour voir la nouvelle question
        header("Refresh:2");
    } else {
        $message = "<p style='color: red; text-align: center;'>Mauvaise réponse, réessayez.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Question Entreprise</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        p {
            font-size: 1.1em;
            line-height: 1.6;
            color: #555;
        }
        label {
            display: block;
            margin: 10px 0;
            font-size: 1.1em;
            color: #333;
        }
        input[type="radio"] {
            margin-right: 10px;
        }
        button {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            font-size: 1.2em;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }
        button:hover {
            background-color: #45a049;
        }
        .message {
            font-size: 1.2em;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Question du niveau <?= htmlspecialchars($niveau) ?></h1>

        <p><?= htmlspecialchars($question['question']) ?></p>

        <form method="POST">
            <label><input type="radio" name="choix" value="<?= htmlspecialchars($question['reponse1']) ?>" required> <?= htmlspecialchars($question['reponse1']) ?></label>
            <label><input type="radio" name="choix" value="<?= htmlspecialchars($question['reponse2']) ?>"> <?= htmlspecialchars($question['reponse2']) ?></label>
            <label><input type="radio" name="choix" value="<?= htmlspecialchars($question['reponse3']) ?>"> <?= htmlspecialchars($question['reponse3']) ?></label>
            <label><input type="radio" name="choix" value="<?= htmlspecialchars($question['reponse4']) ?>"> <?= htmlspecialchars($question['reponse4']) ?></label>
            <button type="submit">Répondre</button>
        </form>

        <?php if (isset($message)) { echo "<div class='message'>$message</div>"; } ?>
    </div>
</body>
</html>