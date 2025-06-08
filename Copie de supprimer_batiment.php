<?php
session_start();

// Vérifie si l'admin est connecté
if (!isset($_SESSION["admin_login"])) {
    header("Location: login.php");
    exit();
}

// Connexion à la base de données
try {
    $pdo = new PDO("mysql:host=localhost;dbname=sae23;charset=utf8", "mzerrouki", "passroot");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Vérifie si un id de bâtiment est fourni en GET
if (!isset($_GET["id"])) {
    die("ID de bâtiment manquant.");
}

$id_batiment = $_GET["id"];

// Supprime le bâtiment de la BDD
$stmt = $pdo->prepare("DELETE FROM Batiment WHERE id_batiment = ?");
$stmt->execute([$id_batiment]);

// Redirige vers admin.php
header("Location: admin.php");
exit();
?>


