<?php
require 'db.php';

// Vérifie si des mots-clés ont été soumis
if(isset($_GET['keywords'])) {
    // Récupère les mots-clés de recherche depuis le formulaire
    $keywords = $_GET['keywords'];

    // Prépare la partie de la requête SQL pour rechercher les offres par mots-clés
    $searchQuery = " WHERE titre LIKE '%$keywords%' OR description LIKE '%$keywords%'";

    // Si une catégorie est sélectionnée, ajoutez un filtre à la requête SQL
    if (isset($_GET['category']) && !empty($_GET['category'])) {
        $category_id = $_GET['category'];
        $searchQuery .= " AND category_id = $category_id";
    }

    // Requête SQL pour récupérer les offres avec les catégories correspondantes
    $sql = "SELECT offres.*, categories.name AS category_name 
            FROM offres 
            INNER JOIN categories ON offres.category_id = categories.id
            $searchQuery";

    $stmt = $pdo->query($sql);

    // Vérifie si des offres ont été trouvées
    if ($stmt->rowCount() > 0) {
        // Affiche les résultats de la recherche
        while ($row = $stmt->fetch()) {
            echo "<div>";
            echo "<h3>" . htmlspecialchars($row['titre']) . "</h3>";
            echo "<p>" . htmlspecialchars($row['description']) . "</p>";
            echo "<p>Catégorie: " . htmlspecialchars($row['category_name']) . "</p>";
            // Affichage de l'image (remplacez le chemin par votre propre chemin d'image)
            echo "<img src='uploads/" . htmlspecialchars($row['image']) . "' alt='" . htmlspecialchars($row['titre']) . "' class='img-fluid'>";
            echo "</div>";
        }
    } else {
        // Aucune offre trouvée
        echo "<p>Aucune offre ne correspond à votre recherche.</p>";
    }
} else {
    // Redirection vers la page d'accueil si aucun mot-clé n'est spécifié
    header("Location: index.php");
    exit();
}
?>
