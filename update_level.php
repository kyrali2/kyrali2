<?php
session_start();
include('connexion_db.php');

// Vérification de l'action de mise à jour du niveau
if (isset($_POST['action']) && $_POST['action'] === 'updateLevel') {
    // Identifiant du joueur (par exemple, récupéré depuis la session)
    $idDemandeur = $_SESSION['user_id'];
    // Requête pour récupérer le niveau actuel du joueur
    $query = "SELECT niveau FROM demandeuremploi WHERE iddemandeur = :iddemandeur";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':iddemandeur', $idDemandeur);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $currentLevel = $result['niveau'];
        
        // Incrémentation du niveau si la réponse est correcte
        $newLevel = $currentLevel + 1;

        // Requête pour mettre à jour le niveau du joueur dans la base de données
        $updateQuery = "UPDATE demandeuremploi SET niveau = :newLevel WHERE iddemandeur = :iddemandeur";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bindParam(':newLevel', $newLevel);
        $updateStmt->bindParam(':iddemandeur', $idDemandeur);
        
        if ($updateStmt->execute()) {
            echo "Niveau mis à jour avec succès!";
        } else {
            echo "Erreur lors de la mise à jour du niveau.";
        }
    } else {
        echo "Utilisateur non trouvé.";
    }
} else {
    echo "Action non autorisée.";
}
?>
