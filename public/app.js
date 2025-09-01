// --- Helpers AJAX (XMLHttpRequest nativo) ---
function xhrGet(url, cb) {
  const xhr = new XMLHttpRequest();
  xhr.open('GET', url, true);
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      if (xhr.status >= 200 && xhr.status < 300) cb(null, JSON.parse(xhr.responseText));
      else cb(new Error(xhr.responseText || 'Error de red'));
    }
  };
  xhr.send();
}

function xhrPost(url, dataObj, cb) {
  const xhr = new XMLHttpRequest();
  xhr.open('POST', url, true);
  xhr.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      if (xhr.status >= 200 && xhr.status < 300) cb(null, JSON.parse(xhr.responseText));
      else cb(new Error(xhr.responseText || 'Error de red'));
    }
  };
  xhr.send(JSON.stringify(dataObj));
}

// --- Cargas iniciales ---
document.addEventListener('DOMContentLoaded', () => {
  cargarBodegas();
  cargarMonedas();
  cargarMateriales();
  document.getElementById('bodega').addEventListener('change', onBodegaChange);
  document.getElementById('form-producto').addEventListener('submit', onSubmit);
});

function cargarBodegas() {
  const sel = document.getElementById('bodega');
  xhrGet('../api/get_bodegas.php', (err, data) => {
    if (err) return console.error(err);
    (data || []).forEach(b => {
      const opt = document.createElement('option');
      opt.value = b.id;
      opt.textContent = b.nombre;
      sel.appendChild(opt);
    });
  });
}

function onBodegaChange(e) {
  const bodegaId = e.target.value;
  const selSucursal = document.getElementById('sucursal');
  selSucursal.innerHTML = '<option value="">Seleccione...</option>';
  selSucursal.disabled = !bodegaId;

  if (!bodegaId) return;

  xhrGet(`../api/get_sucursales.php?bodega_id=${encodeURIComponent(bodegaId)}`, (err, data) => {
    if (err) return console.error(err);
    (data || []).forEach(s => {
      const opt = document.createElement('option');
      opt.value = s.id;
      opt.textContent = s.nombre;
      selSucursal.appendChild(opt);
    });
  });
}

function cargarMonedas() {
  const sel = document.getElementById('moneda');
  xhrGet('../api/get_monedas.php', (err, data) => {
    if (err) return console.error(err);
    (data || []).forEach(m => {
      const opt = document.createElement('option');
      opt.value = m.id;
      opt.textContent = `${m.codigo} - ${m.nombre}`;
      sel.appendChild(opt);
    });
  });
}

function cargarMateriales() {
  const wrap = document.getElementById('materiales-wrapper');
  xhrGet('../api/get_materiales.php', (err, data) => {
    if (err) return console.error(err);
    (data || []).forEach(mat => {
      const lbl = document.createElement('label');
      const chk = document.createElement('input');
      chk.type = 'checkbox';
      chk.name = 'materiales';
      chk.value = mat.id;
      lbl.appendChild(chk);
      const span = document.createElement('span');
      span.textContent = mat.nombre;
      lbl.appendChild(span);
      wrap.appendChild(lbl);
    });
  });
}

// --- VALIDACIONES ---
// Código: alfanumérico con al menos una letra y un número, 5-15 chars
function validarCodigo(codigo) {
  if (!codigo || codigo.trim() === '') { alert('El código del producto no puede estar en blanco.'); return false; }
  const trimmed = codigo.trim();
  if (trimmed.length < 5 || trimmed.length > 15) { alert('El código del producto debe tener entre 5 y 15 caracteres.'); return false; }
  const soloAlfaNum = /^[A-Za-z0-9]+$/.test(trimmed);
  const tieneLetra = /[A-Za-z]/.test(trimmed);
  const tieneNumero = /[0-9]/.test(trimmed);
  if (!soloAlfaNum || !tieneLetra || !tieneNumero) { alert('El código del producto debe contener letras y números'); return false; }
  return true;
}

// Nombre: 2-50
function validarNombre(nombre) {
  if (!nombre || nombre.trim() === '') { alert('El nombre del producto no puede estar en blanco.'); return false; }
  const len = nombre.trim().length;
  if (len < 2 || len > 50) { alert('El nombre del producto debe tener entre 2 y 50 caracteres.'); return false; }
  return true;
}

// Precio: número positivo con hasta dos decimales
function validarPrecio(precio) {
  if (!precio || precio.trim() === '') { alert('El precio del producto no puede estar en blanco.'); return false; }
  const re = /^\d+(\.\d{1,2})?$/;
  if (!re.test(precio.trim())) { alert('El precio del producto debe ser un número positivo con hasta dos decimales.'); return false; }
  return true;
}

// Materiales: al menos 2
function validarMateriales() {
  const marcados = Array.from(document.querySelectorAll('input[name="materiales"]:checked'));
  if (marcados.length < 2) { alert('Debe seleccionar al menos dos materiales para el producto.'); return false; }
  return true;
}

// Selects obligatorios
function validarBodega(id) { if (!id) { alert('Debe seleccionar una bodega.'); return false; } return true; }
function validarSucursal(id) { if (!id) { alert('Debe seleccionar una sucursal para la bodega seleccionada.'); return false; } return true; }
function validarMoneda(id) { if (!id) { alert('Debe seleccionar una moneda para el producto.'); return false; } return true; }

// Descripción: 10-1000
function validarDescripcion(texto) {
  if (!texto || texto.trim() === '') { alert('La descripción del producto no puede estar en blanco.'); return false; }
  const len = texto.trim().length;
  if (len < 10 || len > 1000) { alert('La descripción del producto debe tener entre 10 y 1000 caracteres.'); return false; }
  return true;
}

// Verificar unicidad del código (AJAX)
function verificarUnicidadCodigo(codigo) {
  return new Promise((resolve, reject) => {
    xhrGet(`../api/check_codigo.php?codigo=${encodeURIComponent(codigo.trim())}`, (err, data) => {
      if (err) return reject(err);
      resolve(Boolean(data && data.exists === true));
    });
  });
}

// --- Envió del formulario ---
async function onSubmit(e) {
  e.preventDefault();
  const codigo = document.getElementById('codigo').value;
  const nombre = document.getElementById('nombre').value;
  const bodega = document.getElementById('bodega').value;
  const sucursal = document.getElementById('sucursal').value;
  const moneda = document.getElementById('moneda').value;
  const precio = document.getElementById('precio').value;
  const descripcion = document.getElementById('descripcion').value;
  const materiales = Array.from(document.querySelectorAll('input[name="materiales"]:checked')).map(c => c.value);

  if (!validarCodigo(codigo)) return;
  if (!validarNombre(nombre)) return;
  if (!validarBodega(bodega)) return;
  if (!validarSucursal(sucursal)) return;
  if (!validarMoneda(moneda)) return;
  if (!validarPrecio(precio)) return;
  if (!validarMateriales()) return;
  if (!validarDescripcion(descripcion)) return;

  try {
    const existe = await verificarUnicidadCodigo(codigo);
    if (existe) { alert('El código del producto ya está registrado.'); return; }
  } catch (err) { console.error(err); alert('No se pudo verificar el código del producto. Intenta nuevamente.'); return; }

  const payload = {
    codigo, nombre,
    bodega_id: Number(bodega),
    sucursal_id: Number(sucursal),
    moneda_id: Number(moneda),
    precio: precio.trim(),
    materiales: materiales.map(Number),
    descripcion
  };

  xhrPost('../api/save_producto.php', payload, (err, resp) => {
    const out = document.getElementById('resultado');
    if (err) {
      alert('Error al guardar el producto.');
      out.style.display = 'block';
      out.textContent = 'Error al guardar.';
      return;
    }
    if (resp && resp.ok) {
      out.style.display = 'block';
      out.textContent = 'Producto guardado con éxito.';
      document.getElementById('form-producto').reset();
      document.getElementById('sucursal').innerHTML = '<option value="">Seleccione...</option>';
      document.getElementById('sucursal').disabled = true;
    } else {
      alert(resp && resp.message ? resp.message : 'No fue posible guardar.');
    }
  });
}
