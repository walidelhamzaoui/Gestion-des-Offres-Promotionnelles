
<?php

session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

$user_role = $_SESSION['user_role'];
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] != 'client' && $_SESSION['user_role'] != 'admin' && $_SESSION['user_role'] != 'gestionnaire')) {
    header("Location: connexion.php");
    exit();
}

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

            // Mettre à jour les données de l'utilisateur après la mise à jour
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
<html>

<head>
    <title>Profil</title>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Profil</title>
        <!-- Bootstrap CSS -->
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <!-- Custom CSS -->
        <link rel="stylesheet" type="text/css" href="./css/style.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous">
        </script>
        <style>
        body {
            /* background-color: #f8f9fa; */
        }

        .profile-container {
            max-width:1000px;
            margin: 50px auto;
            padding: 20px;
            background: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .profile-header {
            text-align: center;
            
        }

      

        

       
        </style>


        <!-- bytewebster.com -->
        <!-- bytewebster.com -->
        <!-- bytewebster.com -->

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

                <li class="nav-item nav-itemdashboard "><a class="nav-link  nav-linkdashboard fs-6 "
                        href="ajoute_offre.php">
                        <i class="bi bi-gift-fill ps-3 me-3" style="font-size:20px"></i>
                        Offres</a></li>
                <li class="nav-item nav-itemdashboard"><a class="nav-link nav-linkdashboard active py-2  rounded-2 text-white" href="profil.php"><i
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
                <nav class="navbar  d-none d-lg-block eshow navbar-vertical h-lg-screen navbar-expand-lg px-0 py-0 position-relative   "
                    id="navbarVertical">

                    <ul class="navbar-navdashboard navbar-nav px-2 text-center">
                    <div class="pt-2">
                        <h6 class="fs-6">Gestion des Offres Promotionnelles</h6>
                    </div>
                    <hr>
                    <li class="nav-item nav-itemdashboard pt-2">
                        <?php if ($user_role == 'admin'): ?>
                        <a href="tableau_de_bord.php" class="nav-link nav-linkdashboard "><i class="bi bi-people-fill"
                                style="font-size:20px"></i> Gestion des utilisateurs</a>
                        <?php endif; ?>
                    </li>
                    <li class="nav-item nav-itemdashboard"><a class="nav-link nav-linkdashboard fs-6"
                            href="categories.php"><i class="bi bi-grid" style="font-size:20px"></i> Categories</a></li>
                    <li class="nav-item nav-itemdashboard"><a
                            class="nav-link nav-linkdashboard fs-6"
                            href="ajoute_offre.php"><i class="bi bi-gift-fill" style="font-size:20px"></i> Offres</a>
                    </li>
                    <li class="nav-item nav-itemdashboard"><a class="nav-link nav-linkdashboard text-white active rounded-2  fs-6"
                            href="profil.php"><i class="bi bi-person-circle" style="font-size:20px"></i> Mon
                            Profil</a></li>
                    <li>
                        <a class="nav-link nav-linkdashboard fs-6" id="logout-link" href="deconnexion.php"><i
                                class="bi bi-power" style="font-size:20px"></i> Déconnexion</a>
                    </li>
                </ul>
                </nav>
                <!-- Main content -->
                <div class="h-screen flex-grow-1 bg-white  main overflow-y-lg-auto">
                    <main class="py-6">
                        <div class="container profile-container">
                            <div class="profile-header p-4  d-flex justify-content-center align-items-center">
                            <h4 class="col-lg-4 col-12 p-2 px-lg-5 rounded px-3 py-2 text-white"
                                    style="background: rgb(45,131,209);
                           background: linear-gradient(90deg, rgba(45,131,209,1) 0%, rgba(83,148,204,1) 65%, rgba(0,212,255,1) 100%)">Mon Profil</h4>
                            </div>
                            <?php if (!empty($message)): ?>
                            <div class="alert alert-<?= $message_type ?> mt-4">
                                <?= htmlspecialchars($message) ?>
                            </div>
                            <?php endif; ?>
                            <form method="POST">
                                <div class="form-group">
                                    <label for="nom">Nom:</label>
                                    <input type="text" class="form-control" id="nom" name="nom"
                                        value="<?= htmlspecialchars($user['nom']) ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email:</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="<?= htmlspecialchars($user['email']) ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">Nouveau mot de passe:</label>
                                    <input type="password" id="password" class="form-control" name="password">
                                </div>
                                <div class="form-group">
                                    <label for="confirm_password">Confirmer le mot de passe:</label>
                                    <input type="password" id="confirm_password" class="form-control"
                                        name="confirm_password">
                                </div>
                                <div class="text-center my-5  col-12 ">
                                    <button type="submit" class="btn btn-dark btn-block col-lg-3 col-12">Mettre à jour</button>
                                </div>
                            </form>
                        </div>
                    </main>
                </div>
           
        </div>
    </body>




</HTML>