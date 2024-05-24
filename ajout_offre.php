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

// Connexion à la base de données
require 'db.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ajout d'offre</title>
</head>
<body>
    <h2>Ajouter une offre</h2>
    <form action="traitement_ajout_offre.php" method="POST" enctype="multipart/form-data">
        <label>Titre:</label>
        <input type="text" name="titre" required>
        <br>
        <label>Description:</label>
        <textarea name="description" required></textarea>
        <br>
        <label>Période de validité:</label>
        <input type="date" name="periode_validite" required>
        <br>
        <label>Image:</label>
        <input type="file" name="image" accept="image/*" required>
        <br>
        <label>Catégorie:</label>
        <select name="category_id" required>
            <option value="">Sélectionnez une catégorie</option>
            <?php
            // Récupération des catégories depuis la base de données
            $stmt = $pdo->query("SELECT * FROM categories");
            while ($row = $stmt->fetch()) {
                echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
            }
            ?>
        </select>
        <button type="submit">Ajouter</button>
    </form>
</body>
</html>
