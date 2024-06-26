<?php
// Inclure la configuration de la base de données
require 'db.php';
session_start();

// Vérification des autorisations
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin', 'gestionnaire'])) {
    header("Location: connexion.php");
    exit();
}

// Gestion des catégories
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['category_name'])) {
    $category_name = $_POST['category_name'];

    $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (?)");
    $stmt->execute([$category_name]);
    header("Location: categories.php");
}

$stmt = $pdo->query("SELECT * FROM categories");
$categories = $stmt->fetchAll();

$user_role = $_SESSION['user_role'];
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Categories</title>
    <link rel="stylesheet" type="text/css" href="./css/style.css">
    <style>
        body {
            background-color: #f4f4f4;
            
        }

        .nav-linkdashboard {
            color: #555;
            transition: color 0.3s;
        }

        .nav-linkdashboard:hover {
            color: #007bff;
        }

        .table-responsive {
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .table thead {
            background-color: #343a40;
            color: #fff;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            transition: background-color 0.3s, border-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
        .logout-link{
            position: fixed;bottom:0
        }
    </style>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous">
    </script>
</head>

<body>
    <div class="">
        <button class="navbar-toggler bg-white collapsed d-lg-none py-5 px-3" type="button" data-bs-toggle="collapse"
            data-bs-target="#sidebarCollapse" aria-controls="sidebarCollapse" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="toggler-icon top-bar"></span>
            <span class="toggler-icon middle-bar"></span>
            <span class="toggler-icon bottom-bar"></span>
        </button>

        <div class="collapse navbar-collapse" id="sidebarCollapse">
            <ul class="navbar-navdashboard navbar-nav px-2">
                <div class="container-fluid text-center mt-lg-5">
                    <a href="../index.php" class="navbar-brand pt-5"><img src="../img/image2fsac4.jpg" alt="FSAC Logo"
                            class="logo"></a>
                </div>
                <li class="nav-item nav-itemdashboard">
                    <?php if (in_array($user_role, ['admin', 'gestionnaire'])): ?>
                        <a href="tableau_de_bord.php" class="nav-link nav-linkdashboard"><i class="bi bi-people-fill ps-3 me-3" style="font-size:20px"></i>
                            Gestion des utilisateurs</a>
                    <?php endif; ?>
                </li>
                <li class="nav-item nav-itemdashboard">
                    <a class="nav-link nav-linkdashboard active py-2 rounded-2 text-white fs-6"
                        href="categories.php"><i class="bi bi-grid ps-3 me-3" style="font-size:20px"></i>
                        Categories</a>
                </li>
                <li class="nav-item nav-itemdashboard">
                    <a class="nav-link nav-linkdashboard fs-6" href="ajoute_offre.php">
                        <i class="bi bi-gift-fill ps-3 me-3" style="font-size:20px"></i>
                        Offres</a>
                </li>
                <li class="nav-item nav-itemdashboard">
                    <a class="nav-link nav-linkdashboard" href="profil.php"><i class="bi bi-person-circle ps-3 me-3" style="font-size:20px"></i> Mon
                        profil</a>
                </li>
                <li>
                    <a class="nav-link nav-linkdashboard" id="logout-link" href="deconnexion.php"><i
                            class="bi bi-power ps-3 me-5" style="font-size:20px"></i> Déconnexion</a>
                </li>
            </ul>
        </div>

        <div class="d-flex flex-column flex-lg-row h-lg-full bg-surface-secondary" >
            <nav style="height:600px" class="navbar d-none d-md-block eshow navbar-vertical h-lg-screen navbar-expand-lg px-0 py-0 position-relative border-bottom border-bottom-lg-0 border-end-lg"
                id="navbarVertical">
                <ul class="navbar-navdashboard navbar-nav px-2 text-center">
                    <div class="pt-2">
                        <h6 class="fs-6">Gestion des Offres Promotionnelles</h6>
                    </div>
                    <hr>
                    <li class="nav-item nav-itemdashboard pt-2">
                        <?php if (in_array($user_role, ['admin', ])): ?>
                            <a href="tableau_de_bord.php" class="nav-link nav-linkdashboard my-1"><i class="bi bi-people-fill" style="font-size:20px"></i>
                                Gestion des utilisateurs</a>
                        <?php endif; ?>
                    </li>
                    <li class="nav-item nav-itemdashboard">
                        <a class="nav-link nav-linkdashboard fs-6 text-white active my-1 rounded-2"
                            href="categories.php"><i class="bi bi-grid" style="font-size:20px"></i>
                            Categories</a>
                    </li>
                    <li class="nav-item nav-itemdashboard">
                        <a class="nav-link my-1 nav-linkdashboard fs-6" href="ajoute_offre.php">
                            <i class="bi bi-gift-fill" style="font-size:20px"></i>
                            Offres</a>
                    </li>
                    <li class="nav-item nav-itemdashboard">
                        <a class="nav-link nav-linkdashboard fs-6 my-1" href="profil.php"><i class="bi bi-person-circle" style="font-size:20px"></i> Mon
                            Profil</a>
                    </li>
                    <li>
                        <a class="nav-link nav-linkdashboard fs-6 my-4 " id="logout-link" href="deconnexion.php"><i
                                class="bi bi-power" style="font-size:20px"></i> Déconnexion</a>
                    </li>
                </ul>
            </nav>

            <div class="h-screen flex-grow-1 main overflow-y-lg-auto" style="height: 690px !important; overflow-y: auto;">
                <main>
                    <div class="d-flex justify-content-center align-items-center">
                        <div class="card shadow-sm col-lg-12 col-12">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between p-3">
                                    <h4 class="card-title p-2 px-lg-5 rounded px-3 py-2 text-white"
                                        style="background: rgb(45,131,209); background: linear-gradient(90deg, rgba(45,131,209,1) 0%, rgba(83,148,204,1) 65%, rgba(0,212,255,1) 100%)">
                                        Liste des catégories
                                    </h4>
                                    <button type="button" class="btn btn-dark" data-bs-toggle="modal"
                                        data-bs-target="#addCategoryModal">
                                        Ajouter une catégorie
                                    </button>
                                </div>
                                <div class="table-responsive mt-3">
                                    <table class="table table-striped table-hover">
                                        <thead class="table-dark">
                                            <tr>
                                                <th scope="col" class="text-center text-white">ID</th>
                                                <th scope="col" class="text-center text-white">Nom</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($categories as $category): ?>
                                            <tr>
                                                <td class="text-center"><?= htmlspecialchars($category['id']) ?></td>
                                                <td class="text-center"><?= htmlspecialchars($category['name']) ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addCategoryModalLabel">Ajouter une nouvelle catégorie</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="categories.php">
                                <div class="mb-3">
                                    <label for="category_name" class="form-label">Nom de la catégorie</label>
                                    <input type="text" class="form-control" id="category_name" name="category_name"
                                        required>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-dark">Ajouter</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
