<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=sae23;charset=utf8", "mzerrouki", "passroot");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Dernière mesure de chaque capteur
    $sql = "SELECT c.nom AS capteur_nom, c.type, c.unite, m.valeur, m.date, m.heure
            FROM Capteur c
            LEFT JOIN Mesure m ON m.id_capteur = c.id_capteur
            WHERE (m.date, m.heure) IN (
                SELECT MAX(date), MAX(heure)
                FROM Mesure m2
                WHERE m2.id_capteur = c.id_capteur
            )
            GROUP BY c.id_capteur
            ORDER BY c.nom";
    $stmt = $pdo->query($sql);
    $dernieres_mesures = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Consultation Publique</title>
<style>
table, th, td { border: 1px solid black; border-collapse: collapse; padding: 5px; }
</style>
</head>
<body>
    <h1>Consultation des dernières mesures des capteurs</h1>

    <form method="get">
        <button type="submit">Rafraîchir</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Capteur</th>
                <th>Type</th>
                <th>Unité</th>
                <th>Dernière Valeur</th>
                <th>Date</th>
                <th>Heure</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dernieres_mesures as $mesure) : ?>
            <tr>
                <td><?php echo htmlspecialchars($mesure["capteur_nom"]); ?></td>
                <td><?php echo htmlspecialchars($mesure["type"]); ?></td>
                <td><?php echo htmlspecialchars($mesure["unite"]); ?></td>
                <td><?php echo htmlspecialchars($mesure["valeur"]); ?></td>
                <td><?php echo htmlspecialchars($mesure["date"]); ?></td>
                <td><?php echo htmlspecialchars($mesure["heure"]); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>


