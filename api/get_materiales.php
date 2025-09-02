<?php
require_once __DIR__ . '/../conexion.php';
header('Content-Type: application/json; charset=utf-8');

$pdo = (new CConexion())->ConexionBD();
$rows = $pdo->query("SELECT id, nombre FROM material ORDER BY id")->fetchAll();
echo json_encode($rows);
