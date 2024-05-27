<?php
require 'db.php';
session_start();

// Vérifier si l'utilisateur est authentifié
if (!isset($_SESSION['user_id'])) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas authentifié
    header("Location: connexion.php");
    exit();
}

// Récupérer l'ID de l'utilisateur authentifié
$user_id = $_SESSION['user_id'];

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

// Récupérer les informations de l'utilisateur authentifié
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

            // Réaffectez les nouvelles données à $user après la mise à jour
            $user['nom'] = $nom;
            $user['email'] = $email;
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

    
        /* Custom CSS for modern cards */
.card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: transform 0.3s, box-shadow 0.3s;
}

.card:hover {
    transform: translateY(-10px);
    box-shadow: 0 16px 32px rgba(0, 0, 0, 0.2);
}

.card-img-top {
    height: 200px;
    object-fit: cover;
}

.card-body {
    padding: 15px;
    background-color: #f8f9fa;
}

.card-title {
    font-size: 1.25rem;
    font-weight: bold;
    margin-bottom: 10px;
}

.card-text {
    font-size: 1rem;
    color: #333;
    margin-bottom: 15px;
}







.text-white {
    color: #fff !important;
}

.text-muted {
    color: #6c757d !important;
}


        .newsletter-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: fit-content;
            flex-direction: column;
            background: rgb(12,48,187);
background: linear-gradient(90deg, rgba(12,48,187,1) 0%, rgba(11,20,180,0.7792366946778712) 2%, rgba(19,81,203,1) 30%, rgba(19,203,201,1) 99%);
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
    /* position: fixed; */
    top: 0;
    width: 100%;
    z-index: 1000; /* Ensures the navbar stays above other content */
    backdrop-filter: blur(5px);
    box-shadow: 1px 1px 4px 8px rgba(0, 0, 0, 0.1);
    background: rgb(12,27,187);
background: linear-gradient(90deg, rgba(12,27,187,1) 0%, rgba(4,0,6,0.7792366946778712) 100%, rgba(0,1,10,1) 100%);
    padding: 10px !important;
    
}
.filter-container {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 50px;
    }

    .filter-form {
        background: rgb(12,48,187);
background: linear-gradient(90deg, rgba(12,48,187,1) 0%, rgba(11,20,180,0.7792366946778712) 2%, rgba(19,81,203,1) 30%, rgba(19,203,201,1) 99%);
        padding: 20px;
        border-radius: 10px;
    }

    .filter-form label {
        display: block;
        margin-bottom: 10px;
        
    }

    .filter-form select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ced4da;
        border-radius: 5px;
        background-color: white;
    }

    .filter-form select:focus {
        outline: none;
        border-color: #495057;
        box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25);
    }

    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            
            <a class="navbar-brand" href="#"><div style="width:50px;height:50px;border-radius:50px;background-color:background: rgb(17,173,218);
background: linear-gradient(90deg, rgba(17,173,218,1) 0%, rgba(30,104,180,1) 13%, rgba(0,237,255,1) 100%);"></div></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav m-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link py-1 mt-3 border-bottom  rounded bg-info text-white p-3 px-5" style="font-size:18px;width:fit-content" aria-current="page" href="#">Les Offres</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" style="font-size:18px" data-bs-toggle="modal" data-bs-target="#profileModal">Mon Profil</a>
                    </li>
                </ul>
                <form action="index.php" method="GET" class="d-flex gap-4 position-relative" id="searchForm">
                    <div class="search-input-group">
                        <input class="form-control" type="search" name="keywords" id="searchKeywords" placeholder="Recherche d'offres" aria-label="Recherche d'offres" value="<?php echo isset($_GET['keywords']) ? htmlspecialchars($_GET['keywords']) : ''; ?>">
                        <button class="search-button" type="submit">
                            <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" fill="currentColor" class="bi bi-search mb-2" viewBox="0 0 16 16">
                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                            </svg>
                        </button>
                    </div>
                    <button class="btn btn-danger mt-1 px-5 py-2 mb-3" type="button" id="clearSearch">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        
    <div class="container mt-5">
    <div class="filter-container">
    <div class="filter-form col-lg-4">
        <label for="category-filter " class="text-white">Filtrer par catégorie :</label>
        <select id="category-filter" class="form-select mb-4" onchange="filterByCategory(this)">
    <option value="">Toutes les catégories</option>
    <?php
    // Requête SQL pour récupérer les catégories
    $categories = $pdo->query("SELECT * FROM categories");
    while ($category = $categories->fetch()) {
        // Vérifier si la catégorie est sélectionnée
        $selected = '';
        if (isset($_GET['category']) && $_GET['category'] == $category['id']) {
            $selected = 'selected';
        }
        echo "<option value='" . $category['id'] . "' $selected>" . $category['name'] . "</option>";
    }
    ?>
</select>

    </div>
</div>

        
        <!-- Affichage des offres avec filtres de catégorie -->
        <?php if (!empty($no_offers_message)) : ?>
        <p class='no-offers-message bg-danger p-2  text-white text-center' ><?php echo $no_offers_message; ?></p>
        <?php else : ?>
        <div class="row">
            <?php foreach ($offres as $offre) : ?>
            <div class="col-lg-4 col-md-6 mt-5 col-12">
                <div class="card my-5">
                    <img src='uploads/<?php echo htmlspecialchars($offre['image']); ?>' alt='<?php echo htmlspecialchars($offre['titre']); ?>' class='card-img-top'>
                    <div class="card-body ">
                        <h5 class="card-title bg-dark text-white p-2 text-center"><?php echo htmlspecialchars($offre['titre']); ?></h5>
                        <hr>
                        <h5 class="card-text text-justify text-white p-2"><small class="text-muted"><?php echo htmlspecialchars($offre['category_name']); ?></small></h5>
                        <hr>
                        <p class="card-text" style="text-align:justify; height:150px; overflow-y:auto;">
    <?php echo htmlspecialchars($offre['description']); ?>
</p>
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
    <script>
        
    </script>
</body>
</html>
