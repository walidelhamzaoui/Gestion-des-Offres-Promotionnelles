<?php
session_start();
require 'db.php';

// Vérification de l'authentification de l'utilisateur
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] != 'admin' && $_SESSION['user_role'] != 'gestionnaire')) {
    header("Location: connexion.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $periode_validite = $_POST['periode_validite'];
    $image = $_FILES['image']['name'];
    $category_id = $_POST['category_id'];

    // Code pour télécharger l'image et enregistrer les informations dans la base de données
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
    $image = basename($_FILES["image"]["name"]);

    // Insertion des données dans la table des offres
    $stmt = $pdo->prepare("INSERT INTO offres (titre, description, periode_validite, image, category_id) VALUES (?, ?, ?, ?, ?)");
    if ($stmt->execute([$titre, $description, $periode_validite, $image, $category_id])) {
        echo "L'offre a été ajoutée avec succès.";
    } else {
        echo "Erreur lors de l'ajout de l'offre.";
    }
}
?>
