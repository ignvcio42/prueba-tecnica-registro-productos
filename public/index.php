<?php ?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <title>Formulario de Productos</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="./styles.css" />
</head>
<body>
  <main class="container">
    <h1>Formulario de Productos</h1>

    <form id="form-producto" novalidate>
      <div class="grid">
        <label>
          Código del Producto
          <input type="text" id="codigo" name="codigo" placeholder="Ej: PRD123" />
        </label>

        <label>
          Nombre del Producto
          <input type="text" id="nombre" name="nombre" placeholder="Ej: Teclado mecánico" />
        </label>

        <label>
          Bodega
          <select id="bodega" name="bodega">
            <option value="">Seleccione...</option>
          </select>
        </label>

        <label>
          Sucursal
          <select id="sucursal" name="sucursal" disabled>
            <option value="">Seleccione...</option>
          </select>
        </label>

        <label>
          Moneda
          <select id="moneda" name="moneda">
            <option value="">Seleccione...</option>
          </select>
        </label>

        <label>
          Precio
          <input type="text" id="precio" name="precio" placeholder="Ej: 19.99" />
        </label>
      </div>

      <fieldset class="materiales">
        <legend>Material del Producto (selecciona al menos 2)</legend>
        <div id="materiales-wrapper" class="materiales-wrapper">
        </div>
      </fieldset>

      <label class="descripcion">
        Descripción del Producto
        <textarea id="descripcion" name="descripcion" rows="5" placeholder="Describe el producto..."></textarea>
      </label>

      <div class="actions">
        <button id="btn-guardar" type="submit">Guardar Producto</button>
      </div>
    </form>

    <div id="resultado" class="resultado" aria-live="polite"></div>
  </main>

  <script src="./app.js"></script>
</body>
</html>
