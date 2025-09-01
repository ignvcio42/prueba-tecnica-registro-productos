<?php
require_once __DIR__ . '/../conexion.php';
header('Content-Type: application/json; charset=utf-8');

$pdo = (new CConexion())->ConexionBD();
$rows = $pdo->query("SELECT id, nombre FROM bodega ORDER BY nombre")->fetchAll();

echo json_encode($rows);
exit;
?>