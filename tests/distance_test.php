<?php
// Week 1 task: test ST_Distance_Sphere with SRID 4326 points (latitude FIRST in MySQL 8).
// Run:  php tests/distance_test.php
declare(strict_types=1);

$cfg = (require __DIR__ . '/../config/config.php')['db'];
$pdo = new PDO("mysql:host={$cfg['host']};port={$cfg['port']};charset=utf8mb4", $cfg['user'], $cfg['pass'],
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

echo "Server: " . $pdo->query('SELECT VERSION()')->fetchColumn() . "\n";
echo "(Must say 8.x and NOT 'MariaDB'. XAMPP's built-in database is MariaDB.)\n\n";

$pdo->exec('CREATE DATABASE IF NOT EXISTS rg_geo_test CHARACTER SET utf8mb4');
$pdo->exec('USE rg_geo_test');
$pdo->exec('DROP TABLE IF EXISTS geo_test');
$pdo->exec('CREATE TABLE geo_test (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(50),
            location POINT NOT NULL SRID 4326, SPATIAL INDEX(location))');

// POINT(latitude longitude) for SRID 4326 in MySQL 8
$points = [['Nablus', 32.2211, 35.2544], ['Ramallah', 31.9038, 35.2034], ['Jenin', 32.4610, 35.3000]];
$ins = $pdo->prepare("INSERT INTO geo_test (name, location) VALUES (?, ST_GeomFromText(?, 4326))");
foreach ($points as [$n, $lat, $lng]) {
    $ins->execute([$n, "POINT($lat $lng)"]);          // latitude first
}

// Distance from the "user" (Nablus centre) to each point, nearest first
$st = $pdo->prepare("SELECT name,
        ROUND(ST_Distance_Sphere(location, ST_GeomFromText(:me, 4326)) / 1000, 1) AS km
        FROM geo_test ORDER BY km");
$st->execute([':me' => 'POINT(32.2211 35.2544)']);
$rows = $st->fetchAll(PDO::FETCH_KEY_PAIR);
foreach ($rows as $n => $km) echo str_pad($n, 10) . "$km km\n";

$ok = abs($rows['Ramallah'] - 35.5) < 2 && abs($rows['Jenin'] - 27.3) < 2;
echo $ok ? "\nPASS: distances are right (Nablus-Ramallah is about 35 km)\n"
         : "\nFAIL: distances look wrong. Check the latitude/longitude order and the MySQL version.\n";
$pdo->exec('DROP DATABASE rg_geo_test');
exit($ok ? 0 : 1);
