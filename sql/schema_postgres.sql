-- sql/schema_postgres.sql
-- Ejecutar dentro de tu BD (ej: add_producto)

CREATE TABLE IF NOT EXISTS bodega (
  id SERIAL PRIMARY KEY,
  nombre VARCHAR(80) NOT NULL
);

CREATE TABLE IF NOT EXISTS sucursal (
  id SERIAL PRIMARY KEY,
  bodega_id INT NOT NULL REFERENCES bodega(id) ON DELETE CASCADE,
  nombre VARCHAR(80) NOT NULL
);

CREATE TABLE IF NOT EXISTS moneda (
  id SERIAL PRIMARY KEY,
  codigo VARCHAR(8) NOT NULL UNIQUE,
  nombre VARCHAR(80) NOT NULL
);

CREATE TABLE IF NOT EXISTS material (
  id SERIAL PRIMARY KEY,
  nombre VARCHAR(80) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS producto (
  id SERIAL PRIMARY KEY,
  codigo VARCHAR(15) NOT NULL UNIQUE,
  nombre VARCHAR(50) NOT NULL,
  bodega_id INT NOT NULL REFERENCES bodega(id),
  sucursal_id INT NOT NULL REFERENCES sucursal(id),
  moneda_id INT NOT NULL REFERENCES moneda(id),
  precio NUMERIC(12,2) NOT NULL CHECK (precio >= 0),
  descripcion TEXT NOT NULL,
  creado_en TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS producto_material (
  producto_id INT NOT NULL REFERENCES producto(id) ON DELETE CASCADE,
  material_id INT NOT NULL REFERENCES material(id),
  PRIMARY KEY (producto_id, material_id)
);

-- Datos iniciales
INSERT INTO bodega (nombre) VALUES ('Central'), ('Norte'), ('Sur')
ON CONFLICT DO NOTHING;

INSERT INTO sucursal (bodega_id, nombre) VALUES
  ((SELECT id FROM bodega WHERE nombre='Central'), 'Santiago Centro'),
  ((SELECT id FROM bodega WHERE nombre='Central'), 'Las Condes'),
  ((SELECT id FROM bodega WHERE nombre='Norte'), 'La Serena'),
  ((SELECT id FROM bodega WHERE nombre='Sur'), 'Concepción')
ON CONFLICT DO NOTHING;

INSERT INTO moneda (codigo, nombre) VALUES
  ('CLP', 'Peso Chileno'),
  ('USD', 'Dólar Estadounidense'),
  ('EUR', 'Euro')
ON CONFLICT DO NOTHING;

INSERT INTO material (nombre) VALUES
  ('Plástico'), ('Metal'), ('Madera'), ('Vidrio'), ('Cerámica'), ('Goma')
ON CONFLICT DO NOTHING;
