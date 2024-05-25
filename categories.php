<?php
// Inclure la configuration de la base de données
require 'db.php';
session_start();

// Vérification des autorisations
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
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


if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

$user_role = $_SESSION['user_role'];
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: connexion.php");
    exit();
}

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
<!-- bytewebster.com -->
<!-- bytewebster.com -->
<!-- bytewebster.com -->

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
                            class="nav-link nav-linkdashboard fs-6 "
                            href="categories.php"><i class="bi bi-grid" style="font-size:20px"></i> 
                            Categories</a></li>

                    <li>

                    <li class="nav-item nav-itemdashboard "><a class="nav-link text-white active  rounded-2  nav-linkdashboard fs-6 "
                            href="ajoute_offre.php">
                            <i class="bi bi-gift-fill" style="font-size:20px"></i>
                            Offres</a></li>
                    <li class="nav-item nav-itemdashboard "><a class="nav-link nav-linkdashboard fs-6 "
                            href="profil.php"><i class="bi bi-person-circle me-2" style="font-size:20px"></i> Mon
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
                            class="nav-link nav-linkdashboard fs-6 text-white active  rounded-2 "
                            href="categories.php"><i class="bi bi-grid" style="font-size:20px"></i> 
                            Categories</a></li>

                    <li>

                    <li class="nav-item nav-itemdashboard "><a class="nav-link   nav-linkdashboard fs-6 "
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
                <main class="py-6">
                    <div class="d-flex justify-content-center align-items-center ">
                        <div class="card shadow-sm col-lg-5 col-11 ">
                            <div class="card-body">
                                <h4 class="card-title text-center mb-4">Ajouter une nouvelle catégorie</h4>
                                <form method="POST"
                                    class=" d-flex justify-content-center align-items-center flex-column">
                                    <div class="col-md-12 col-12">
                                        <label for="category_name" class="form-label">Nom de la catégorie</label>
                                        <input type="text" class="form-control" id="category_name" name="category_name"
                                            required>
                                    </div>
                                    <div class="col-8 text-center my-3">
                                        <button type="submit" class="btn btn-dark col-8 ">Ajouter</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>

                    <h2 class="text-center my-4">Liste des catégories</h2>

                    <div class="d-flex justify-content-center align-items-center ">
                        <div class="table-responsive  col-lg-9 col-12">
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
            </main>

        </div>
    </div>

</body>




</HTML>