<?php
session_start();
if (!isset($_SESSION["admin_login"])) {
    header("Location: login.php");
    exit();
}

try {
    $pdo = new PDO("mysql:host=localhost;dbname=sae23;charset=utf8", "mzerrouki", "passroot");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST["nom"];
    $type = $_POST["type"];
    $capacite = $_POST["capacite"];
    $id_batiment = $_POST["id_batiment"];

    $stmt = $pdo->prepare("INSERT INTO Salle (nom, type, capacite, id_batiment) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nom, $type, $capacite, $id_batiment]);

    header("Location: admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Ajouter Salle</title>
</head>
<body>
    <h1>Ajouter une nouvelle Salle</h1>
    <form method="post">
        <label>Nom :</label><input type="text" name="nom" required><br>
        <label>Type :</label><input type="text" name="type" required><br>
        <label>Capacité :</label><input type="number" name="capacite" required><br>
        <label>ID Bâtiment :</label><input type="number" name="id_batiment" required><br>
        <button type="submit">Ajouter</button>
    </form>
    <p><a href="admin.php">Retour à l'administration</a></p>
</body>
</html>


