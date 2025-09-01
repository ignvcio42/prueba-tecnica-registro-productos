<?php
require_once __DIR__ . '/../conexion.php';
header('Content-Type: application/json; charset=utf-8');

$codigo = isset($_GET['codigo']) ? trim($_GET['codigo']) : '';
if ($codigo === '') { echo json_encode(['exists' => false]); exit; }

$pdo = (new CConexion())->ConexionBD();
$stmt = $pdo->prepare("SELECT 1 FROM producto WHERE codigo = :c LIMIT 1");
$stmt->execute([':c' => $codigo]);
$exists = (bool)$stmt->fetchColumn();
echo json_encode(['exists' => $exists]);
