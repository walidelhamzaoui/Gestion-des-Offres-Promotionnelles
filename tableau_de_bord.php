<?php
session_start();

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

$stmt = $pdo->query("SELECT * FROM utilisateurs");
$users = $stmt->fetchAll();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $statut = $_POST['statut'];

    $stmt = $pdo->prepare("UPDATE utilisateurs SET statut = ? WHERE id = ?");
    $stmt->execute([$statut, $user_id]);
    header("Location: tableau_de_bord.php");
}
?>



<head>
    <meta charset="utf-8">
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="./css/style.css">
    <!-- Bootstrap 5 CSS -->
    
    <!-- Bootstrap 5 JS Bundle (includes Popper) -->

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous">
    </script>
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






</head>
<!-- bytewebster.com -->
<!-- bytewebster.com -->
<!-- bytewebster.com -->

<body>
    <div class=" ">
        <!-- Toggler -->
        <button class="navbar-toggler  collapsed d-lg-none py-5 px-3" type="button" data-bs-toggle="collapse"
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
                <li class="nav-item nav-itemdashboard "> <?php if ($user_role == 'admin'): ?>
                    <a href="tableau_de_bord.php" class="nav-link nav-linkdashboard active py-2  my-3 pt-5 rounded-2 text-white"
                        class="btn btn-primary"><i class="bi bi-people-fill ps-3 me-3" style="font-size:20px"></i>
                        Gestion des utilisateurs</a>
                    <?php endif; ?>
                </li>

                <li class="nav-item nav-itemdashboard "><a class="nav-link nav-linkdashboard fs-6  my-3"
                        href="categories.php"><i class="bi bi-grid ps-3 me-3" style="font-size:20px"></i>
                        Categories</a></li>

                <li>

                <li class="nav-item nav-itemdashboard "><a class="nav-link  nav-linkdashboard fs-6  my-3 "
                        href="ajoute_offre.php">
                        <i class="bi bi-gift-fill ps-3 me-3" style="font-size:20px"></i>
                        Offres</a></li>
                <li class="nav-item nav-itemdashboard"><a class="nav-link nav-linkdashboard fs-6  my-3 " href="profil.php"><i
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
                        <a href="admin.php" class=" nav-link nav-linkdashboard active  rounded-2 text-white"
                            class="btn btn-primary"><i class="bi bi-people-fill " style="font-size:20px"></i> Gestion
                            des utilisateurs</a>
                        <?php endif; ?>
                    </li>


                    <li class="nav-item nav-itemdashboard "><a class="nav-link nav-linkdashboard fs-6 "
                            href="categories.php"><i class="bi bi-grid" style="font-size:20px"></i>
                            Categories</a></li>

                    <li>

                    <li class="nav-item nav-itemdashboard "><a class="nav-link   nav-linkdashboard  "
                            href="ajoute_offre.php">
                            <i class="bi bi-gift-fill" style="font-size:20px"></i>
                            Offres</a></li>
                    <li class="nav-item nav-itemdashboard "><a class="nav-link nav-linkdashboard "
                            href="profil.php"><i class="bi bi-person-circle" style="font-size:20px"></i> Mon
                            Profil</a></li>

                    <li>
                        <a class="nav-link nav-linkdashboard fs-6" id="logout-link" href="deconnexion.php"><i
                                class="bi bi-power" style="font-size:20px"></i>Déconnexion</a></a>
                    </li>


                </ul>
            </nav>
            <!-- Main content -->
            <div class="h-screen flex-grow-1  main overflow-y-lg-auto">
                <main class="">
                    <div class="container-fluid">
                        <div class="row">
                           
                            <div class="table-responsive">
                            <div class="p-5 m-4 pb-0 pt-0 d-flex justify-content-center align-items-center ">
                                <h4 class="col-12 p-3 px-lg-5 rounded px-3 py-2 text-white"
                                    style="background: rgb(45,131,209);
                           background: linear-gradient(90deg, rgba(45,131,209,1) 0%, rgba(83,148,204,1) 65%, rgba(0,212,255,1) 100%); width:fit-content">Liste des
                                    Utilisateurs</h4>
                            </div>
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr class='text-center'>
                                            <th scope="col" class="text-white">ID</th>
                                            <th scope="col" class="text-white">Nom</th>
                                            <th scope="col" class="text-white">Email</th>
                                            <th scope="col" class="text-white">Rôle</th>
                                            <th scope="col" class="text-white text-center">Statut</th>
                                            <th scope="col" class="text-white text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($users as $user): ?>
                                        <tr class='text-center'>
                                            <td><?= htmlspecialchars($user['id']) ?></td>
                                            <td><?= htmlspecialchars($user['nom']) ?></td>
                                            <td><?= htmlspecialchars($user['email']) ?></td>
                                            <td><?= htmlspecialchars($user['role']) ?></td>
                                            <td class="text-center pt-4">
                                                <span
                                                    class=" text-white rounded  text-center p-2 px-2 <?= $user['statut'] == 'actif' ? 'bg-success' : ($user['statut'] == 'refusé' ? 'bg-danger' : 'bg-warning') ?>">
                                                    <?= ucfirst(htmlspecialchars($user['statut'])) ?>
                                                </span>
                                            </td>
                                            <td class="table-action ">
                                                <form method="POST" class="d-flex justify-content-center">
                                                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                                    <select name="statut" class="form-select me-2"
                                                        style="width: 150px;">
                                                        <option value="en attente"
                                                            <?= $user['statut'] == 'en attente' ? 'selected' : '' ?>>En
                                                            attente</option>
                                                        <option value="actif"
                                                            <?= $user['statut'] == 'actif' ? 'selected' : '' ?>>Actif
                                                        </option>
                                                        <option value="refusé"
                                                            <?= $user['statut'] == 'refusé' ? 'selected' : '' ?>>Refusé
                                                        </option>
                                                    </select>
                                                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                                                </form>
                                            </td>
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
    </div>
</body>




</HTML>