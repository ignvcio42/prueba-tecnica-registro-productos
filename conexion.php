<?php
class CConexion
{
    function ConexionBD()
    {
        $host = "localhost";
        $port = "5432";
        $dbname = "add_producto";
        $username = "postgres";
        $password = "postgres";

        try {
            $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // <- clave
        } catch (PDOException $exp) {
            http_response_code(500);
            die("No se pudo conectar a la base de datos: " . $exp->getMessage());
        }

        return $conn;
    }
}
