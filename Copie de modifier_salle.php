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

if (!isset($_GET["nom"])) {
    die("Nom de salle manquant.");
}

$nom = $_GET["nom"];
$stmt = $pdo->prepare("SELECT * FROM Salle WHERE nom = ?");
$stmt->execute([$nom]);
$salle = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$salle) {
    die("Salle non trouvée.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $type = $_POST["type"];
    $capacite = $_POST["capacite"];
    $id_batiment = $_POST["id_batiment"];

    $stmt = $pdo->prepare("UPDATE Salle SET type = ?, capacite = ?, id_batiment = ? WHERE nom = ?");
    $stmt->execute([$type, $capacite, $id_batiment, $nom]);

    header("Location: admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Modifier Salle</title>
</head>
<body>
    <h1>Modifier la Salle</h1>
    <form method="post">
        <label>Nom :</label>
        <input type="text" name="nom" value="<?php echo htmlspecialchars($salle["nom"]); ?>" readonly><br>
        <label>Type :</label>
        <input type="text" name="type" value="<?php echo htmlspecialchars($salle["type"]); ?>" required><br>
        <label>Capacité :</label>
        <input type="number" name="capacite" value="<?php echo htmlspecialchars($salle["capacite"]); ?>" required><br>
        <label>ID Bâtiment :</label>
        <input type="number" name="id_batiment" value="<?php echo htmlspecialchars($salle["id_batiment"]); ?>" required><br>
        <button type="submit">Modifier</button>
    </form>
    <p><a href="admin.php">Retour à l'administration</a></p>
</body>
</html>


