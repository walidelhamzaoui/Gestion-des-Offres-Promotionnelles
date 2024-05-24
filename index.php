<?php
require 'db.php';

// Initialisation de la requête SQL de base
$sql = "SELECT offres.*, categories.name AS category_name 
        FROM offres 
        INNER JOIN categories ON offres.category_id = categories.id";

// Si une catégorie est sélectionnée, ajouter un filtre à la requête SQL
if (isset($_GET['category']) && !empty($_GET['category'])) {
    $category_id = $_GET['category'];
    $sql .= " WHERE offres.category_id = $category_id";
}

// Si des mots-clés de recherche sont fournis, ajouter un filtre à la requête SQL
if (isset($_GET['keywords']) && !empty($_GET['keywords'])) {
    $keywords = $_GET['keywords'];
    // Ajouter le filtre pour les titres ou descriptions qui contiennent les mots-clés
    $sql .= " AND (offres.titre LIKE '%$keywords%' OR offres.description LIKE '%$keywords%')";
}

$stmt = $pdo->query($sql);

// Vérifier si aucune offre n'est trouvée
if ($stmt->rowCount() === 0) {
    $no_offers_message = "Aucune offre ne correspond à votre recherche.";
} else {
    $no_offers_message = "";
}
$stmt = $pdo->query($sql);

// Récupération des offres depuis la base de données
$offres = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votre Titre</title>
    <!-- Stylesheet Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h2>Offres</h2>
        <!-- Formulaire de recherche -->
        <form action="index.php" method="GET" class="mb-3" id="searchForm">
  
    <div>
    <label for="category-filter">Filtrer par catégorie:</label>
    <select id="category-filter" onchange="filterByCategory(this)">
        <option value="">Toutes les catégories</option>
        <?php
        // Requête SQL pour récupérer les catégories
        $categories = $pdo->query("SELECT * FROM categories");
        while ($category = $categories->fetch()) {
            echo "<option value='" . $category['id'] . "'>" . $category['name'] . "</option>";
        }
        ?>
    </select>
</div>
<div class="input-group">
        <input type="text" class="form-control" name="keywords" id="searchKeywords" placeholder="Recherche d'offres" aria-label="Recherche d'offres" value="<?php echo isset($_GET['keywords']) ? htmlspecialchars($_GET['keywords']) : ''; ?>">
        <button class="btn btn-outline-primary" type="submit">Rechercher</button>
        <button class="btn btn-outline-secondary" type="button" id="clearSearch">Effacer</button>
    </div>
</form>


        <!-- Affichage des offres avec filtres de catégorie -->
        <?php if (!empty($no_offers_message)) : ?>
            <p class='no-offers-message'><?php echo $no_offers_message; ?></p>
        <?php else : ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Image</th>
                            <th scope="col">Titre</th>
                            <th scope="col">Description</th>
                            <th scope="col">Catégorie</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($offres as $offre) : ?>
                            <tr>
                                <!-- Affichage de l'image avec une colonne dédiée -->
                                <td><img src='uploads/<?php echo htmlspecialchars($offre['image']); ?>' alt='<?php echo htmlspecialchars($offre['titre']); ?>' class='img-fluid w-25'></td>
                                <td><?php echo htmlspecialchars($offre['titre']); ?></td>
                                <td><?php echo htmlspecialchars($offre['description']); ?></td>
                                <td><?php echo htmlspecialchars($offre['category_name']); ?></td>
                                <td>
                            <?php
                            // URL de l'offre
                            $url_offre = "https://votre_site.com/detail_offre.php?offre_id=" . $offre['id'];
                            // Bouton de partage sur Facebook
                            echo "<a href='https://www.facebook.com/sharer/sharer.php?u=" . urlencode($url_offre) . "' target='_blank' class='btn btn-primary btn-sm'>Partager sur Facebook</a>";
                            // Ajoutez d'autres boutons de partage si nécessaire
                            ?>
                        </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
    <div class="mt-4">
            <h3>Inscription à la Newsletter</h3>
            <form action="index.php" method="POST" id="newsletterForm">
                <div class="mb-3">
                    <label for="email" class="form-label">Adresse e-mail</label>
                    <input type="email" class="form-control" name="email" id="email" required>
                </div>
                <button type="submit" class="btn btn-primary">S'inscrire</button>
            </form>
            <?php
            if (isset($_POST['email']) && !empty($_POST['email'])) {
                $email = $_POST['email'];
                $stmt = $pdo->prepare("INSERT INTO newsletters (email) VALUES (?)");
                if ($stmt->execute([$email])) {
                    echo "<p class='mt-3 text-success'>Merci de vous être inscrit à notre newsletter!</p>";
                } else {
                    echo "<p class='mt-3 text-danger'>Une erreur s'est produite. Veuillez réessayer.</p>";
                }
            }
            ?>
        </div>
    </div>

    <!-- Fichiers JavaScript Bootstrap (optionnels, mais nécessaires pour certaines fonctionnalités) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Récupérer le champ de recherche
        const searchKeywordsInput = document.getElementById('searchKeywords');
        // Récupérer le bouton pour effacer le contenu du champ
        const clearSearchButton = document.getElementById('clearSearch');

        // Ajouter un écouteur d'événement au bouton "Effacer"
        clearSearchButton.addEventListener('click', function() {
            // Effacer le contenu du champ de recherche
            searchKeywordsInput.value = '';
            // Soumettre le formulaire
            document.getElementById('searchForm').submit();
        });
    });
    
</script>
<script>
    // Fonction pour filtrer les offres par catégorie
    function filterByCategory(select) {
        var category_id = select.value;
        // Rediriger vers la même page avec le paramètre de filtre de catégorie
        window.location.href = "index.php?category=" + category_id;
    }
</script>
</body>

</html>
