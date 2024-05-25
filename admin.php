<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: connexion.php");
    exit();
}

$stmt = $pdo->query("SELECT * FROM utilisateurs");
$users = $stmt->fetchAll();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $statut = $_POST['statut'];

    $stmt = $pdo->prepare("UPDATE utilisateurs SET statut = ? WHERE id = ?");
    $stmt->execute([$statut, $user_id]);
    header("Location: admin.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gestion des utilisateurs</title>
</head>
<body>
    <h2>Liste des utilisateurs</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Email</th>
            <th>Rôle</th>
            <th>Statut</th>
            <th>Action</th>
        </tr>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?= htmlspecialchars($user['id']) ?></td>
            <td><?= htmlspecialchars($user['nom']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td><?= htmlspecialchars($user['role']) ?></td>
            <td><?= htmlspecialchars($user['statut']) ?></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                    <select name="statut">
                        <option value="en attente" <?= $user['statut'] == 'en attente' ? 'selected' : '' ?>>En attente</option>
                        <option value="actif" <?= $user['statut'] == 'actif' ? 'selected' : '' ?>>Actif</option>
                        <option value="refusé" <?= $user['statut'] == 'refusé' ? 'selected' : '' ?>>Refusé</option>
                    </select>
                    <button type="submit">Mettre à jour</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
