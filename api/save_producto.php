<?php
require_once __DIR__ . '/../conexion.php';
header('Content-Type: application/json; charset=utf-8');

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) { http_response_code(400); echo json_encode(['ok'=>false,'message'=>'JSON inválido']); exit; }

$codigo = trim($input['codigo'] ?? '');
$nombre = trim($input['nombre'] ?? '');
$bodega_id = (int)($input['bodega_id'] ?? 0);
$sucursal_id = (int)($input['sucursal_id'] ?? 0);
$moneda_id = (int)($input['moneda_id'] ?? 0);
$precio = trim($input['precio'] ?? '');
$descripcion = trim($input['descripcion'] ?? '');
$materiales = $input['materiales'] ?? [];

try {
  $pdo = (new CConexion())->ConexionBD();
  $pdo->beginTransaction();

  // Revalidaciones server-side
  if ($codigo === '' || $nombre === '' || $bodega_id<=0 || $sucursal_id<=0 || $moneda_id<=0 || $descripcion==='' || !preg_match('/^\d+(\.\d{1,2})?$/', $precio) || count($materiales)<2) {
    throw new Exception('Datos inválidos.');
  }

  // Unicidad código
  $stmt = $pdo->prepare("SELECT 1 FROM producto WHERE codigo = :c LIMIT 1");
  $stmt->execute([':c' => $codigo]);
  if ($stmt->fetchColumn()) {
    throw new Exception('El código ya existe.');
  }

  // Insert producto
  $stmt = $pdo->prepare("INSERT INTO producto (codigo, nombre, bodega_id, sucursal_id, moneda_id, precio, descripcion)
                         VALUES (:codigo, :nombre, :bodega_id, :sucursal_id, :moneda_id, :precio::numeric(12,2), :descripcion)
                         RETURNING id");
  $stmt->execute([
    ':codigo' => $codigo,
    ':nombre' => $nombre,
    ':bodega_id' => $bodega_id,
    ':sucursal_id' => $sucursal_id,
    ':moneda_id' => $moneda_id,
    ':precio' => $precio,
    ':descripcion' => $descripcion
  ]);
  $prodId = (int)$stmt->fetchColumn();

  // Insert materiales
  $stmtMat = $pdo->prepare("INSERT INTO producto_material (producto_id, material_id) VALUES (:pid, :mid)");
  foreach ($materiales as $mid) {
    $mid = (int)$mid;
    if ($mid > 0) {
      $stmtMat->execute([':pid' => $prodId, ':mid' => $mid]);
    }
  }

  $pdo->commit();
  echo json_encode(['ok' => true, 'id' => $prodId]);
} catch (Exception $e) {
  if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
  http_response_code(400);
  echo json_encode(['ok' => false, 'message' => $e->getMessage()]);
}
