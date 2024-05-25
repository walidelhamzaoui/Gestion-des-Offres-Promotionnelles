<?php
// Activation des erreurs PHP
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Début de la session
session_start();

// Vérification de l'authentification de l'utilisateur
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] != 'admin' && $_SESSION['user_role'] != 'gestionnaire')) {
    header("Location: connexion.php");
    exit();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

$user_role = $_SESSION['user_role'];
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: connexion.php");
    exit();
}

// Connexion à la base de données
require 'db.php';
?>

<head>
    <meta charset="utf-8">
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="./css/style.css">
    <!-- Bootstrap 5 CSS -->
    <!-- Inline style to ensure margin is applied -->
    <style>
    body {
        background-color: #f4f4f4;
    }

    * .nav-linkdashboard {
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
    </style>




    <!-- Bootstrap 5 JS Bundle (includes Popper) -->

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous">
    </script>


</head>


<body>
    <div class=" ">
        <!-- Toggler -->
        <button class="navbar-toggler bg-white collapsed d-lg-none py-5 px-3" type="button" data-bs-toggle="collapse"
            data-bs-target="#sidebarCollapse" aria-controls="sidebarCollapse" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="toggler-icon top-bar"></span>
            <span class="toggler-icon middle-bar"></span>
            <span class="toggler-icon bottom-bar"></span>
        </button>
        <!-- Brand -->

        <!-- <hr> -->
        <!-- User menu (mobile) -->
        <div class="navbar-user d-lg-none">
            <!-- Dropdown -->
            <div class="dropdown">
                <!-- Toggle -->
                <!-- <a href="#" id="sidebarAvatar" role="button" data-bs-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            <div class="avatar-parent-child">
                                  <a href="../index.php" class="navbar-brand"><img src="../img/image2fsac4.jpg" alt="FSAC Logo"></a>
                                <span class="avatar-child avatar-badge bg-success"></span>
                            </div>
                        </a> -->

            </div>
        </div>
        <!-- Collapse -->
        <div class="collapse navbar-collapse " id="sidebarCollapse">
            <!-- Navigation -->

            <ul class="navbar-navdashboard navbar-nav px-3 ">
                <div class="container-fluid text-center mt-lg-5">
                    <a href="../index.php" class="navbar-brand pt-5  "><img src="../img/image2fsac4.jpg" alt="FSAC Logo"
                            class="logo"></a>
                </div>
                <li class="nav-item nav-itemdashboard pt-2"> <?php if ($user_role == 'admin'): ?>
                    <a href="tableau_de_bord.php" class=" nav-link nav-linkdashboard  " class="btn btn-primary"><i
                            class="bi bi-people-fill me-2" style="font-size:20px"></i> Gestion des
                        utilisateurs</a>
                    <?php endif; ?>
                </li>
                <li class="nav-item nav-itemdashboard "><a
                        class="nav-link nav-linkdashboard fs-6  text-white" href="categories.php"><i
                            class="bi bi-grid me-2" style="font-size:20px"></i>
                        Categories</a></li>

                <li>

                <li class="nav-item nav-itemdashboard "><a class="nav-link nav-linkdashboard fs-6  "
                        href="ajoute_offre.php"><i class="bi bi-person-circle me-2" style="font-size:20px"></i>
                        Offre</a></li>

                <li>
                <li class="nav-item nav-itemdashboard "><a class="nav-link nav-linkdashboard fs-6 " href="profil.php"><i
                            class="bi bi-person-circle me-2" style="font-size:20px"></i> Mon
                        Profil</a></li>

                <li>
                    <a class="nav-link nav-linkdashboard fs-6" id="logout-link" href="deconnexion.php"><i
                            class="bi bi-power me-2" style="font-size:20px"></i>Déconnexion</a></a>
                </li>
            </ul>

        </div>

        <!-- Dashboard -->
        <div class="d-flex flex-column flex-lg-row h-lg-full bg-surface-secondary ">
            <!-- Vertical Navbar -->
            <nav class="navbar  d-none d-md-block eshow navbar-vertical h-lg-screen navbar-expand-lg px-0 py-0 position-relative   border-bottom border-bottom-lg-0 border-end-lg"
                id="navbarVertical">

                <ul class="navbar-navdashboard navbar-nav px-2 text-center">
                    <div class="pt-2">
                        <h6 class="fs-6 "> Gestion des Offres Promotionnelles </h6>
                    </div>
                    <hr>
                    <li class="nav-item nav-itemdashboard pt-2"> <?php if ($user_role == 'admin'): ?>
                        <a href="tableau_de_bord.php" class=" nav-link nav-linkdashboard  " class="btn btn-primary"><i
                                class="bi bi-people-fill" style="font-size:20px"></i> Gestion des
                            utilisateurs</a>
                        <?php endif; ?>
                    </li>

                    <li class="nav-item nav-itemdashboard "><a
                            class="nav-link nav-linkdashboard fs-6 "
                            href="categories.php"><i class="bi bi-grid" style="font-size:20px"></i> 
                            Categories</a></li>

                    <li>

                    <li class="nav-item nav-itemdashboard "><a class="nav-link text-white active  rounded-2  nav-linkdashboard fs-6 "
                            href="ajoute_offre.php">
                            <i class="bi bi-gift-fill" style="font-size:20px"></i>
                            Offres</a></li>

                    
                    <li class="nav-item nav-itemdashboard "><a class="nav-link nav-linkdashboard fs-6 "
                            href="profil.php"><i class="bi bi-person-circle" style="font-size:20px"></i> Mon
                            Profil</a></li>

                    <li>
                        <a class="nav-link nav-linkdashboard fs-6" id="logout-link" href="deconnexion.php"><i
                                class="bi bi-power" style="font-size:20px"></i> Déconnexion</a></a>
                    </li>


                </ul>
            </nav>
            <!-- Main content -->
            <div class="h-screen flex-grow-1  main overflow-y-lg-auto">
            <main class="py-6" style="background-color: #f8f9fa;">
    <div class="container d-flex justify-content-center align-items-center">
        <div class="card shadow col-8 p-4">
            <div class="card-body">
                <h2 class="card-title mb-4 text-center">Ajouter une offre</h2>
                <form action="traitement_ajout_offre.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group my-3">
                        <label for="titre">Titre :</label>
                        <input type="text" class="form-control" id="titre" name="titre" required>
                    </div>
                    <div class="form-group  my-3">
                        <label for="description">Description :</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                    </div>
                    <div class="form-group  my-3">
                        <label for="periode_validite">Période de validité :</label>
                        <input type="date" class="form-control" id="periode_validite" name="periode_validite" required>
                    </div>
                    <div class="form-group  my-3">
                        <label for="image ">Image :</label><br>
                        <input type="file" class="form-control-file pt-2" id="image" name="image" accept="image/*" required>
                    </div>
                    <div class="form-group  my-3">
                        <label for="category_id">Catégorie :</label>
                        <select class="form-control" id="category_id" name="category_id" required>
                            <option value="">Sélectionnez une catégorie</option>
                            <?php
                                // Récupération des catégories depuis la base de données
                                $stmt = $pdo->query("SELECT * FROM categories");
                                while ($row = $stmt->fetch()) {
                                    echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <div class="my-3 text-center">
                    <button type="submit" class="btn btn-dark col-lg-5">Ajouter</button>
                            </div>
                </form>
            </div>
        </div>
    </div>
</main>


            </div>
        </div>

</body>