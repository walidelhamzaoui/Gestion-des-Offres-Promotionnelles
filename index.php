<?php
require 'db.php';
session_start();

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
    $keywords = '%' . $keywords . '%';
    if (strpos($sql, 'WHERE') !== false) {
        $sql .= " AND (offres.titre LIKE :keywords OR offres.description LIKE :keywords)";
    } else {
        $sql .= " WHERE (offres.titre LIKE :keywords OR offres.description LIKE :keywords)";
    }
}

$stmt = $pdo->prepare($sql);
if (isset($keywords)) {
    $stmt->bindParam(':keywords', $keywords);
}
$stmt->execute();

// Vérifier si aucune offre n'est trouvée
if ($stmt->rowCount() === 0) {
    $no_offers_message = "Aucune offre ne correspond à votre recherche.";
} else {
    $no_offers_message = "";
}

// Récupération des offres depuis la base de données
$offres = $stmt->fetchAll();
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

$message = '';
$message_type = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if (!empty($password) && $password !== $confirm_password) {
        $message = "Les mots de passe ne correspondent pas.";
        $message_type = "danger";
    } else {
        // Update the user profile
        if (!empty($password)) {
            // Hash the new password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE utilisateurs SET nom = ?, email = ?, mot_de_passe = ? WHERE id = ?");
            $params = [$nom, $email, $hashed_password, $user_id];
        } else {
            $stmt = $pdo->prepare("UPDATE utilisateurs SET nom = ?, email = ? WHERE id = ?");
            $params = [$nom, $email, $user_id];
        }

        if ($stmt->execute($params)) {
            $message = "Profil mis à jour avec succès.";
            $message_type = "success";
        } else {
            $message = "Erreur lors de la mise à jour du profil.";
            $message_type = "danger";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Offres Promotionnelles</title>
    <!-- Stylesheet Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-l82ZNoE1pBdGWoqUC5thKaChFNC6FHOrpYAh2R5e5l6WXsLw+VjwhBn8s9/Ky2TC4khJxBjwd2Pd+Hs8HcjOcw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="./css/style.css">
    
    <style>
        .navbar-brand {
            padding-left: 20px;
        }

        .card {
            margin-bottom: 20px;
            box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 12px;
        }

        .card img {
            height: 200px;
            object-fit: cover;
        }

        .newsletter-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: fit-content;
            flex-direction: column;
            background-color: black;
            border-radius: 10px;
            margin-bottom: 10px;
        }

        .newsletter-form {
            max-width: 500px;
            width: 100%;
            padding: 15px;
        }

        input {
            border: 2px solid black;
        }

        .search-input-group {
            position: relative;
        }

        .search-input-group input {
            padding-right: 45px;
        }

        .search-button {
            position: absolute;
            right: 0;
            top: 0;
            bottom: 0;
            border: none;
            background: transparent;
            color: #007bff;
            padding: 0 15px;
            cursor: pointer;
        }

        .share-btn {
            display: inline-flex;
            align-items: center;
            transition: width 0.3s ease-in-out;
            overflow: hidden;
            width: 40px; /* Width of icon only */
            white-space: nowrap;
            border-radius: 5px;
        }

        .share-btn .share-text {
            margin-left: 10px;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }

        .share-btn:hover {
            width: auto; /* Adjust width to fit text */
            padding: 0 10px;
            color: black;
            padding: 10px !important;
        }

        .share-btn:hover .share-text {
            opacity: 1;
        }

        /* Specific styles for each button */
        .facebook-share {
            background-color: #3b5998 !important;
        }

        .whatsapp-share {
            background-color: #25D366 !important;
        }

        .navbar {
            backdrop-filter: blur(80px);
            box-shadow: 1px 1px 4px 8px rgba(0, 0, 0, 0.1);
            background-color: black; /* Slightly transparent background to see the blur effect */
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Gestion des Offres Promotionnelles</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav m-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link py-1 mt-3 border-bottom rounded active" style="font-size:18px" aria-current="page" href="#">Les Offres</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" style="font-size:18px" data-bs-toggle="modal" data-bs-target="#profileModal">Profil</a>
                    </li>
                </ul>
                <form action="index.php" method="GET" class="d-lg-flex gap-4 position-relative" id="searchForm">
                    <div class="search-input-group">
                        <input class="form-control" type="search" name="keywords" id="searchKeywords" placeholder="Recherche d'offres" aria-label="Recherche d'offres" value="<?php echo isset($_GET['keywords']) ? htmlspecialchars($_GET['keywords']) : ''; ?>">
                        <button class="search-button" type="submit">
                            <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                            </svg>
                        </button>
                    </div>
                    <button class="btn btn-danger mt-2 px-5 py-2" type="button" id="clearSearch">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        
        <div class="d-flex justify-content-center align-items-center col-12">
            <div class="col-lg-4 my-2">
                <label for="category-filter">Filtrer par catégorie:</label>
                <select id="category-filter" class="form-select mb-4 bg-second" onchange="filterByCategory(this)">
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
        </div>
        <!-- Affichage des offres avec filtres de catégorie -->
        <?php if (!empty($no_offers_message)) : ?>
        <p class='no-offers-message'><?php echo $no_offers_message; ?></p>
        <?php else : ?>
        <div class="row">
            <?php foreach ($offres as $offre) : ?>
            <div class="col-md-4">
                <div class="card">
                    <img src='uploads/<?php echo htmlspecialchars($offre['image']); ?>' alt='<?php echo htmlspecialchars($offre['titre']); ?>' class='card-img-top'>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($offre['titre']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($offre['description']); ?></p>
                        <p class="card-text"><small class="text-muted"><?php echo htmlspecialchars($offre['category_name']); ?></small></p>
                        <a href='https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode("https://votre_site.com/detail_offre.php?offre_id=" . $offre['id']); ?>' target='_blank' class='btn p-2 rounded-2 facebook-share text-white btn-sm mt-2 share-btn'>
                            <i class="bi bi-facebook" style="font-size:20px"></i> <span class="share-text">Partager sur Facebook</span>
                        </a>
                        <a href='whatsapp://send?text=<?php echo urlencode("Check out this offer: https://votre_site.com/detail_offre.php?offre_id=" . $offre['id']); ?>' target='_blank' class='btn whatsapp-share text-white p-2 rounded-2 mt-2 btn-sm share-btn'>
                            <i class="bi bi-whatsapp" style="font-size:20px"></i> <span class="share-text">Partager sur WhatsApp</span>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <div class="container newsletter-container p-3 mt-4">
        <h3 class="text-white">Inscription à la Newsletter</h3>
        <form action="index.php" method="POST" id="newsletterForm" class="newsletter-form">
            <div class="mb-3">
                <label for="email" class="form-label text-white">Adresse e-mail</label>
                <input type="email" class="form-control" name="email" id="email" required>
            </div>
            <div class="text-end">
                <button type="submit" class="btn btn-warning">S'inscrire</button>
            </div>
        </form>
        <?php
        if (isset($_POST['email']) && !empty($_POST['email'])) {
            $email = $_POST['email'];
            $stmt = $pdo->prepare("INSERT INTO newsletters (email) VALUES (?)");
            if ($stmt->execute([$email])) {
                echo "<p class='mt-3 text-success text-center'>Merci de vous être inscrit à notre newsletter!</p>";
            } else {
                echo "<p class='mt-3 text-danger text-center'>Une erreur s'est produite. Veuillez réessayer.</p>";
            }
        }
        ?>
    </div>

    <!-- Modal Profil -->
    <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="profileModalLabel">Mon Profil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php if (!empty($message)): ?>
                    <div class="alert alert-<?= $message_type ?> mt-4">
                        <?= htmlspecialchars($message) ?>
                    </div>
                    <?php endif; ?>
                    <form method="POST" id="profileForm">
                        <div class="form-group">
                            <label for="nom">Nom:</label>
                            <input type="text" class="form-control" id="nom" name="nom" value="<?= htmlspecialchars($user['nom']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Nouveau mot de passe:</label>
                            <input type="password" id="password" class="form-control" name="password">
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Confirmer le mot de passe:</label>
                            <input type="password" id="confirm_password" class="form-control" name="confirm_password">
                        </div>
                        <div class="text-center my-5 col-12">
                            <button type="submit" class="btn btn-dark btn-block col-lg-6 col-12">Mettre à jour</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchKeywordsInput = document.getElementById('searchKeywords');
        const clearSearchButton = document.getElementById('clearSearch');

        clearSearchButton.addEventListener('click', function() {
            searchKeywordsInput.value = '';
            document.getElementById('searchForm').submit();
        });
    });

    function filterByCategory(select) {
        var category_id = select.value;
        window.location.href = "index.php?category=" + category_id;
    }
    </script>
</body>
</html>
