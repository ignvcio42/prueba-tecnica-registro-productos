Registro de Productos (PHP + Postgres + JS nativo + CSS nativo)

Requisitos del enunciado: HTML/CSS nativo, JS nativo con AJAX, PHP sin framework, BD (preferencia PostgreSQL). Validaciones por JS con mensajes personalizados, unicidad de código, selects dinámicos, y guardado vía AJAX→PHP→BD.

Estructura:
- public/ -> index.php, styles.css, app.js
- api/ -> endpoints PHP (get_bodegas, get_sucursales, get_monedas, get_materiales, check_codigo, save_producto)
- sql/ -> schema_postgres.sql
- conexion.php
- README.txt

Instalación local (XAMPP + PostgreSQL):
1) Habilitar en php.ini:
   extension=pdo_pgsql
   extension=pgsql
   Reiniciar Apache.
2) Crear BD (ej: add_producto) y usuario/permiso (pgAdmin).
3) Importar sql/schema_postgres.sql en la BD.
4) Ajustar credenciales en conexion.php si es necesario.
5) Servir: http://localhost/entrevista_desis/public/

Pruebas:
- Endpoints JSON:
  /api/get_bodegas.php
  /api/get_monedas.php
  /api/get_materiales.php
  /api/get_sucursales.php?bodega_id=1
  /api/check_codigo.php?codigo=PRD123
- Flujo completo desde /public/

Versiones:
- PHP: PHP 8.2.12 (cli) (built: Oct 24 2023 21:15:15) (ZTS Visual C++ 2019 x64)
Copyright (c) The PHP Group
Zend Engine v4.2.12, Copyright (c) Zend Technologies
- PostgreSQL: 17.6.1
