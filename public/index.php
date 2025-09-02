<?php ?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <title>Formulario de Producto</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="./styles.css" />
</head>
<body>
  <main class="container">
    <h1>Formulario de Producto</h1>

    <form id="form-producto" novalidate>
      <div class="grid">
        <label>
          Código
          <input type="text" id="codigo" name="codigo" placeholder="" />
        </label>

        <label>
          Nombre
          <input type="text" id="nombre" name="nombre" placeholder="" />
        </label>

        <label>
          Bodega
          <select id="bodega" name="bodega">
            <option value=""></option>
          </select>
        </label>

        <label>
          Sucursal
          <select id="sucursal" name="sucursal" disabled>
            <option value=""></option>
          </select>
        </label>

        <label>
          Moneda
          <select id="moneda" name="moneda">
            <option value=""></option>
          </select>
        </label>

        <label>
          Precio
          <input type="text" id="precio" name="precio" placeholder="" />
        </label>
      </div>

      <fieldset class="materiales">
        <legend>Material del Producto</legend>
        <div id="materiales-wrapper" class="materiales-wrapper">
        </div>
      </fieldset>

      <label class="descripcion">
        Descripción
        <textarea id="descripcion" name="descripcion" rows="5" placeholder=""></textarea>
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
