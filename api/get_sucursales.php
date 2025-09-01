<?php
require_once __DIR__ . '/../conexion.php';
header('Content-Type: application/json; charset=utf-8');

$bodega_id = isset($_GET['bodega_id']) ? (int)$_GET['bodega_id'] : 0;
if ($bodega_id <= 0) { echo json_encode([]); exit; }

$pdo = (new CConexion())->ConexionBD();
$stmt = $pdo->prepare("SELECT id, nombre FROM sucursal WHERE bodega_id = :bid ORDER BY nombre");
$stmt->execute([':bid' => $bodega_id]);
echo json_encode($stmt->fetchAll());
