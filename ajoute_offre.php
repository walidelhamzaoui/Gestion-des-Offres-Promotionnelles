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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous">
    </script>
    <!-- Inline style to ensure margin is applied -->
    <style>
    body {
        background-color: #f8f9fa;
    }

    .card {
        border-radius: 1rem;
    }

    .btn-custom {
        background-color: #343a40;
        color: white;
    }

    .btn-custom:hover {
        background-color: #495057;
        color: white;
    }

    .form-control:focus {
        border-color: #495057;
        box-shadow: 0 0 0 0.25rem rgba(108, 117, 125, 0.25);
    }
    </style>
    </style>




    <!-- Bootstrap 5 JS Bundle (includes Popper) -->

 


</head>


<body>
    <div class=" ">
        <!-- Toggler -->
        <button class="navbar-toggler  collapsed d-lg-none py-5 px-3" type="button"
                data-bs-toggle="collapse" data-bs-target="#sidebarCollapse" aria-controls="sidebarCollapse"
                aria-expanded="false" aria-label="Toggle navigation">
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
                <li class="nav-item nav-itemdashboard"> <?php if ($user_role == 'admin'): ?>
                    <a href="tableau_de_bord.php" class="nav-link nav-linkdashboard "
                        class="btn btn-primary"><i class="bi bi-people-fill ps-3 me-3" style="font-size:20px"></i>
                        Gestion des utilisateurs</a>
                    <?php endif; ?>
                </li>

                <li class="nav-item nav-itemdashboard "><a class="nav-link nav-linkdashboard fs-6 "
                        href="categories.php"><i class="bi bi-grid ps-3 me-3" style="font-size:20px"></i>
                        Categories</a></li>

                <li>

                <li class="nav-item nav-itemdashboard "><a class="nav-link  nav-linkdashboard fs-6 active py-2  rounded-2 text-white "
                        href="ajoute_offre.php">
                        <i class="bi bi-gift-fill ps-3 me-3" style="font-size:20px"></i>
                        Offres</a></li>
                <li class="nav-item nav-itemdashboard"><a class="nav-link nav-linkdashboard" href="profil.php"><i
                            class="bi bi-person-circle ps-3 me-3" style="font-size:20px"></i> Mon
                        profil</a></li>

                <li>
                    <a class="nav-link nav-linkdashboard" id="logout-link" href="deconnexion.php"><i
                            class="bi bi-power ps-3 me-3" style="font-size:20px"></i> Déconnexion</a></a>
                </li>
            </ul>

        </div>

        <!-- Dashboard -->
        <div class="d-flex flex-column flex-lg-row h-lg-full bg-surface-secondary ">
            <!-- Vertical Navbar -->
            <nav class="navbar  d-none d-lg-block eshow navbar-vertical h-lg-screen navbar-expand-lg px-0 py-0 position-relative   border-bottom border-bottom-lg-0 border-end-lg"
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

                    <li class="nav-item nav-itemdashboard "><a class="nav-link nav-linkdashboard fs-6 "
                            href="categories.php"><i class="bi bi-grid" style="font-size:20px"></i>
                            Categories</a></li>

                    <li>

                    <li class="nav-item nav-itemdashboard "><a
                            class="nav-link text-white active  rounded-2 active  nav-linkdashboard fs-6 "
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
                    <div class="container d-flex justify-content-center align-items-center ">
                        <div class="card shadow col-12 col-lg-8 p-4">
                            <div class="card-body">
                                <div class="p-2 ajoute d-flex justify-content-center align-items-center">
                                    <h4 class="card-title mb-4 text-center text-white rounded py-lg-2 px-lg-5 p-3"
                                        style="background: rgb(45,131,209);
                           background: linear-gradient(90deg, rgba(45,131,209,1) 0%, rgba(83,148,204,1) 65%, rgba(0,212,255,1) 100%);">
                                        Ajouter une offre</h4>
                                </div>
                                <form action="traitement_ajout_offre.php" method="POST" enctype="multipart/form-data">

                                    <div>
                                        <div class="d-md-flex justify-content-around align-items-center gap-3">
                                            <div class="form-group my-3 col-lg-6 col-md-6 ">
                                                <label for="titre" class="form-label">Titre :</label>
                                                <input type="text" class="form-control" id="titre" name="titre"
                                                    required>
                                            </div>
                                            <div class="form-group my-3 col-lg-6 col-md-6">
                                                <label for="periode_validite" class="form-label">Période de validité
                                                    :</label>
                                                <input type="date" class="form-control" id="periode_validite"
                                                    name="periode_validite" required>
                                            </div>
                                        </div>
                                        <div class="d-md-flex justify-content-around align-items-center gap-3">
                                            <div class="form-group my-3 col-lg-6 col-md-6">
                                                <label for="image" class="form-label">Image :</label>
                                                <input type="file" class="form-control" id="image" name="image"
                                                    accept="image/*" required>
                                            </div>
                                            <div class="form-group my-3 col-lg-6 col-md-6">
                                                <label for="category_id" class="form-label">Catégorie :</label>
                                                <select class="form-select" id="category_id" name="category_id"
                                                    required>
                                                    <option value="" disabled selected>Sélectionnez une catégorie
                                                    </option>
                                                    <?php
                                          // Récupération des catégories depuis la base de données
                                          $stmt = $pdo->query("SELECT * FROM categories");
                                           while ($row = $stmt->fetch()) {
                                               echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                                        }
                                      ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group my-3">
                                            <label for="description" class="form-label">Description :</label>
                                            <textarea class="form-control" id="description" name="description" rows="3"
                                                required></textarea>
                                        </div>
                                      
                                        <div class="text-center my-4">
                                            <button type="submit" class="btn btn-dark col-lg-5 col-7">Ajouter</button>
                                        </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    </script>
                </main>


            </div>
        </div>

</body>