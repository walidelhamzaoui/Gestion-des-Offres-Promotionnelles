<?php
// Activation des erreurs PHP
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Début de la session
session_start();

// Vérification de l'authentification de l'utilisateur
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] != 'admin') && ($_SESSION['user_role'] !='gestionnaire')) {
    header("Location: connexion.php");
    exit();
}

$user_role = $_SESSION['user_role'];

// Connexion à la base de données
require 'db.php';
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Les Offres</title>
    <link rel="stylesheet" type="text/css" href="./css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <style>
        /* Votre CSS personnalisé ici */
        /* CSS pour la barre de navigation verticale fixe */


        
    </style>
</head>

<body>
    <div class="">
        <!-- Toggler -->
        <button class="navbar-toggler collapsed d-lg-none py-5 px-3" type="button" data-bs-toggle="collapse"
            data-bs-target="#sidebarCollapse" aria-controls="sidebarCollapse" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="toggler-icon top-bar"></span>
            <span class="toggler-icon middle-bar"></span>
            <span class="toggler-icon bottom-bar"></span>
        </button>
        <div class="collapse navbar-collapse" id="sidebarCollapse">
            <ul class="navbar-navdashboard navbar-nav px-3">
                <div class="container-fluid text-center mt-lg-5">
                    <!-- <a href="../index.php" class="navbar-brand pt-5"><img src="../img/image2fsac4.jpg" alt="FSAC Logo" class="logo"></a> -->
                </div>
                <li class="nav-item nav-itemdashboard">
                    <?php if ($user_role == 'admin'): ?>
                        <a href="tableau_de_bord.php" class="nav-link nav-linkdashboard"><i class="bi bi-people-fill ps-3 me-3" style="font-size:20px"></i> Gestion des utilisateurs</a>
                    <?php endif; ?>
                </li>
                <li class="nav-item nav-itemdashboard"><a class="nav-link nav-linkdashboard fs-6" href="categories.php"><i class="bi bi-grid ps-3 me-3" style="font-size:20px"></i> Categories</a></li>
                <li class="nav-item nav-itemdashboard"><a class="nav-link nav-linkdashboard fs-6 active py-2 rounded-2 text-white" href="ajoute_offre.php"><i class="bi bi-gift-fill ps-3 me-3" style="font-size:20px"></i> Offres</a></li>
                <li class="nav-item nav-itemdashboard"><a class="nav-link nav-linkdashboard" href="profil.php"><i class="bi bi-person-circle ps-3 me-3" style="font-size:20px"></i> Mon profil</a></li>
                <li><a class="nav-link nav-linkdashboard" id="logout-link" href="deconnexion.php"><i class="bi bi-power ps-3 me-3" style="font-size:20px"></i> Déconnexion</a></li>
            </ul>
        </div>
        <div class="d-flex flex-column flex-lg-row h-lg-full bg-surface-secondary" >
            <nav class="navbar d-none d-lg-block eshow navbar-vertical h-lg-screen navbar-expand-lg px-0 py-0 position-relative border-bottom border-bottom-lg-0 border-end-lg" id="navbarVertical"  >
                <ul class="navbar-navdashboard navbar-nav px-2 text-center" >
                    <div class="pt-2">
                        <h6 class="fs-6">Gestion des Offres Promotionnelles</h6>
                    </div>
                    <hr>
                    <?php if ($user_role == 'admin'): ?>
                    <li class="nav-item nav-itemdashboard pt-2">
                        <a href="tableau_de_bord.php" class="nav-link nav-linkdashboard "><i class="bi bi-people-fill" style="font-size:20px"></i> Gestion des utilisateurs</a>
                    </li>
                    <?php endif; ?>
                    <li class="nav-item nav-itemdashboard"><a class="nav-link nav-linkdashboard fs-6" href="categories.php"><i class="bi bi-grid" style="font-size:20px"></i> Categories</a></li>
                    <li class="nav-item nav-itemdashboard"><a class="nav-link text-white active rounded-2 nav-linkdashboard" style="width:250px" href="ajoute_offre.php"><i class="bi bi-gift-fill" style="font-size:20px"></i> Offres</a></li>
                    <li class="nav-item nav-itemdashboard"><a class="nav-link nav-linkdashboard fs-6" href="profil.php"><i class="bi bi-person-circle" style="font-size:20px"></i> Mon Profil</a></li>
                    <li><a class="nav-link nav-linkdashboard fs-6" id="logout-link" href="deconnexion.php"><i class="bi bi-power" style="font-size:20px"></i> Déconnexion</a></li>
                </ul>
            </nav>

            <div class="h-screen flex-grow-1 main overflow-y-lg-auto" style="height: 690px !important; overflow-y: auto;">

                <main class="">
                    <div class="d-flex justify-content-center align-items-center">
                        <div class="card shadow col-12 col-lg-12 p-4">
                            <div class="card-body">
                                <div class="p-2 d-flex justify-content-between align-items-center">
                                    <h4 class="col-lg-4 card-title text-center p-3 px-lg-5 rounded px-3 py-2 text-white" style="background: rgb(45,131,209); background: linear-gradient(90deg, rgba(45,131,209,1) 0%, rgba(83,148,204,1) 65%, rgba(0,212,255,1) 100%)">Liste des Offres</h4>
                                    <button class="btn btn-dark text-end py-3 px-4" data-bs-toggle="modal" data-bs-target="#addOfferModal">Ajouter une offre</button>
                                </div>
                                <div class="table-responsive mt-4">
                                    <table class="table table-striped table-hover">
                                        <thead class="table-dark">
                                            <tr class="text-center">
                                                <th scope="col" class="text-white bg-dark" style="width: 100px;">ID</th>
                                                <th scope="col" class="text-white bg-dark" style="width: 200px;">Titre</th>
                                                <th scope="col" class="text-white bg-dark" style="width: 150px;">Période de validité</th>
                                                <th scope="col" class="text-white bg-dark" style="width: 150px;">Catégorie</th>
                                                <th scope="col" class="text-white bg-dark" style="width: 300px;">Description</th>
                                                <th scope="col" class="text-white bg-dark" style="width: 100px;">Image</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $stmt = $pdo->query("SELECT offres.*, categories.name as category_name FROM offres JOIN categories ON offres.category_id = categories.id");
                                            while ($row = $stmt->fetch()) {
                                                echo "<tr class='text-center'>";
                                                echo "<td style='width: 100px;'><div style='overflow-x: auto; max-width: 100px;'>" . htmlspecialchars($row['id']) . "</div></td>";
                                                echo "<td style='width: 200px;'><div style='overflow-x: auto; max-width: 200px;'>" . htmlspecialchars($row['titre']) . "</div></td>";
                                                echo "<td style='width: 150px;'><div style='overflow-x: auto; max-width: 150px;'>" . htmlspecialchars($row['periode_validite']) . "</div></td>";
                                                echo "<td style='width: 150px;'><div style='overflow-x: auto; max-width: 150px;'>" . htmlspecialchars($row['category_name']) . "</div></td>";
                                                echo "<td style='width: 300px; overflow-x: auto;'><div style=' max-width: 300px;'>" . htmlspecialchars($row['description']) . "</div></td>";
                                                echo "<td style='width: 100px;'><img src='uploads/" . $row['image'] . "' alt='" . $row['titre'] . "' style='max-width: 100px; max-height: 100px;'></td>";
                                                echo "</tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="addOfferModal" tabindex="-1" aria-labelledby="addOfferModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title text-dark" id="addOfferModalLabel">Ajouter une offre</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="traitement_ajout_offre.php" method="POST" enctype="multipart/form-data">
                                        <div class="form-group my-3">
                                            <label for="titre" class="form-label">Titre :</label>
                                            <input type="text" class="form-control" id="titre" name="titre" required>
                                        </div>
                                        <div class="form-group my-3">
                                            <label for="periode_validite" class="form-label">Période de validité :</label>
                                            <input type="date" class="form-control" id="periode_validite" name="periode_validite" required>
                                        </div>
                                        <div class="form-group my-3">
                                            <label for="image" class="form-label">Image :</label>
                                            <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                                        </div>
                                        <div class="form-group my-3">
                                            <label for="category_id" class="form-label">Catégorie :</label>
                                            <select class="form-select" id="category_id" name="category_id" required>
                                                <option value="" disabled selected>Sélectionnez une catégorie</option>
                                                <?php
                                                    $stmt = $pdo->query("SELECT * FROM categories");
                                                    while ($row = $stmt->fetch()) {
                                                        echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group my-3">
                                            <label for="description" class="form-label">Description :</label>
                                            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                                        </div>
                                        <div class="text-center my-4">
                                            <button type="submit" class="btn btn-dark col-lg-5 col-7">Ajouter</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </main>
            </div>
        </div>
    </div>
</body>

</html>
